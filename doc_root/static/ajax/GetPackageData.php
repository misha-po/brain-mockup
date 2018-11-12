<?php
require 'DB_utils.php';

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$pid = $_GET['id'];


$sql5 = "SELECT * from Data_types";
if(!$result5 = $conn->query($sql5)) {
	error_log('Error: '.$conn->error);
	return;
}
$data_types = array();
while($row5 = $result5->fetch_assoc()) {
	array_push($data_types, $row5);
}

$sql6 = "SELECT * from Value_constraints";
if(!$result6 = $conn->query($sql6)) {
	error_log('Error: '.$conn->error);
	return;
}
$value_constraints = array();
while($row6 = $result6->fetch_assoc()) {
	array_push($value_constraints, $row6);
}



$sql = "SELECT distinct f.dataframe_id id, d.name name"
		." FROM QualityMetrics q,Features f,DataFrames d "
		." where d.id=f.dataframe_id and f.id=q.feature_id and q.package_id=".$pid;

$output_df = FetchData($sql, $conn);
if (count($output_df) != 0)
	$output_df = $output_df[0];
else {
	$output_df['id'] = -1;
	$output_df['name'] = '--none--';
}

$sql4 = "select f.id,f.name,q.quality_metrics,dt.name as data_type, v.name as value_constraint, f.is_target "
		." from QualityMetrics q, Features f, DataFrames d, Data_types dt, Value_constraints v "
		." where f.dataframe_id=d.id and q.feature_id=f.id and f.data_type=dt.id and f.value_constraint=v.id and q.package_id=".$pid;

$features = FetchData($sql4, $conn);



$sql = "select * from DataFrames";
$all_data_frames = FetchData($sql, $conn);


$algorithms = array();
$sql = "select id,algo_code name from Algorithms";
if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
while($row = $result->fetch_assoc()) {
	array_push($algorithms, $row);
}

$available_features = array();
if ($output_df['id'] != -1) {
	$sql = "select * from Features where dataframe_id=".$output_df['id'];
	if(!$result = $conn->query($sql)) {
		error_log('Error: '.$conn->error);
		return;
	}
	while($row = $result->fetch_assoc()) {
		array_push($available_features, $row);
	}
}

$sql = "SELECT * FROM ServingType";
$all_serving_types = FetchData($sql, $conn);

$sql = "SELECT * from VisibilityTags";
$available_tags = FetchData($sql, $conn);


if ($pid != -1) {
	$sql = "SELECT "
				."p.id as pid, "
				."a.id as aid, "
				."a.algo_code, "
				."at.name as algo_type_name, "
				."a.algo_type, "
				."at.id as type_id, "
				."a.owner_name, "
				."a.owner_email, "
				."p.egg_path, "
				."u.name as universe, "
				."e.name as input_entity, "
				."a.description, "
				."from Packages p,Algorithms a,AlgoTypes as at,AlgoUniverse as u,AlgoInputEntities as e "
				."where p.algorithm=a.id and a.algo_type=at.id and p.universe=u.id and p.input_entity=e.id and p.id=".$pid;
	$pckg_data = FetchData($sql, $conn)[0];
	$sql = "select * from InputDataFrames as f, DataFrames as df where df.id=f.dataframe_id and package_id=".$pid;
	$dfs = FetchData($sql, $conn);

	$sql = "SELECT p.tag_id id, v.name FROM PackageTagList p, VisibilityTags v where p.tag_id=v.id and package_id=".$pid;
	$prohibited_tags = FetchData($sql, $conn);
	
	$sql = "select s.id,s.name from Packages p, ServingType s where p.serving_type = s.id and p.id=".$pid;
	$serving_type = FetchData($sql, $conn)[0]['id'];
	// $sql = "select rt_serving_type from Packages where id=".$pid;
	// $rt_serving_type = FetchData($sql, $conn)[0]['rt_serving_type'];
}
else {
	$sql = "SELECT a.id as aid,a.algo_type,a.algo_code,a.owner_name,a.owner_email from Algorithms as a limit 1;";
	$algo_data = FetchData($sql, $conn)[0];

	$pckg_data = [];
	$pckg_data['aid'] = $algo_data['aid'];
	$pckg_data['algo_type'] = $algo_data['algo_type'];
	$pckg_data['algo_code'] = $algo_data['algo_code'];
	$pckg_data['owner_name'] = $algo_data['owner_name'];
	$pckg_data['owner_email'] = $algo_data['owner_email'];

	$dfs = [];
	$serving_type = 1;
	$prohibited_tags = [];
	$copy_to_serving = [];
}
$sql = "SELECT * from CopyToServing where id=".$pckg_data['algo_type'];
$copy_to_serving = FetchData($sql, $conn)[0];


$json_data = (object) array(
				'error' => 'OK',
				'pckg_data' => $pckg_data,
				'data_frames' => $dfs,
				'output_df' => $output_df,
				'features' => $features,
				'data_types' => $data_types,
				'value_constraints' => $value_constraints,
				'all_data_frames' => $all_data_frames,
//				'algo_types' => $algo_types,
				'available_tags' => $available_tags,
				'algorithms' => $algorithms,
				'available_features' => $available_features,
				'prohibited_tags' => $prohibited_tags,
				'all_serving_types' => $all_serving_types,
				'serving_type' => $serving_type,
				'copy_to_serving' => $copy_to_serving
			);

echo(json_encode($json_data));
?>
