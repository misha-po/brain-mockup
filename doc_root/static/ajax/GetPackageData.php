<?php
require 'DB_utils.php';

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$pid = $_GET['id'];

$sql1 = "SELECT "
			."p.id as pid, "
			."a.id as aid, "
			."a.algo_code, "
			."at.name as algo_type, "
			."a.owner_name, "
			."a.owner_email, "
			."p.egg_path, "
			."u.name as universe, "
			."e.name as input_entity, "
			."a.description "
			."from Packages p,Algorithms a,AlgoTypes as at,AlgoUniverse as u,AlgoInputEntities as e "
			."where p.algorithm=a.id and a.algo_type=at.id and p.universe=u.id and p.input_entity=e.id and p.id=".$pid;

$sql2 = "select * from InputDataFrames as f, DataFrames as df where df.id=f.dataframe_id and package_id=".$pid;

$sql5 = "SELECT * from Data_types";

$sql6 = "SELECT * from Value_constraints";

if(!$result1 = $conn->query($sql1)) {
	error_log('Error: '.$conn->error);
	return;
}
$row1 = $result1->fetch_assoc();

if(!$result2 = $conn->query($sql2)) {
	error_log('Error: '.$conn->error);
	return;
}
$dfs = array();
while($row2 = $result2->fetch_assoc()) {
	array_push($dfs, $row2);
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

if(!$result5 = $conn->query($sql5)) {
	error_log('Error: '.$conn->error);
	return;
}
$data_types = array();
while($row5 = $result5->fetch_assoc()) {
	array_push($data_types, $row5);
}


if(!$result6 = $conn->query($sql6)) {
	error_log('Error: '.$conn->error);
	return;
}
$value_constraints = array();
while($row6 = $result6->fetch_assoc()) {
	array_push($value_constraints, $row6);
}

$sql = "select * from DataFrames";
$all_data_frames = FetchData($sql, $conn);
// if(!$result7 = $conn->query($sql7)) {
	// error_log('Error: '.$conn->error);
	// return;
// }
// $all_data_frames = array();
// while($row7 = $result7->fetch_assoc()) {
	// array_push($all_data_frames, $row7);
// }

/*
$algo_types = array();
$sql = "select * from AlgoTypes";
if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
while($row = $result->fetch_assoc()) {
	array_push($algo_types, $row);
}
*/
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

$json_data = (object) array(
				'error' => 'OK',
				'pckg_data' => $row1,
				'data_frames' => $dfs,
				'output_df' => $output_df,
				'features' => $features,
				'data_types' => $data_types,
				'value_constraints' => $value_constraints,
				'all_data_frames' => $all_data_frames,
//				'algo_types' => $algo_types,
				'algorithms' => $algorithms,
				'available_features' => $available_features
			);

error_log(json_encode($json_data));
echo(json_encode($json_data));
?>
