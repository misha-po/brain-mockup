<?php
require 'DB_utils.php';

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$pid = $_GET['package_id'];


$package_data = json_decode(urldecode($_GET['package_data']));
error_log(urldecode($_GET['package_data']));


$sql = "update Packages set "
		." algorithm=".$package_data->algo.","
		." serving_type=".$package_data->serving->serving_type.","
		." rt_serving_type=".$package_data->serving->rt_serving_type.","
		." output_file='".$package_data->serving->file_name."'"
	." where id=".$pid;

if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	echo "{'error:'.$conn->error}";
}
	
foreach($package_data->input_data as $df) {
	$sql = "insert into InputDataFrames (package_id, dataframe_id)"
		." select * from (select ".$pid.", ".$df.") as tmp"
		." where not exists ("
		."   select package_id, dataframe_id from InputDataFrames where package_id=".$pid." and dataframe_id=".$df
		." ) limit 1;";
	if(!$result = $conn->query($sql)) {
		error_log('Error: '.$conn->error);
		echo "{'error:'.$conn->error}";
	}
		
	error_log($sql);
}

	
echo(json_encode((object) array('error' => "OK")));
return;


/*
insert into table_listnames (name, address, tele)
select * from (select 'Rupert', 'Somewhere', '022') as tmp
where not exists (
    select name from table_listnames where name = 'rupert'
) limit 1;


	$sql = "SELECT "
				."Algorithms.description "
				."from Packages p,Algorithms a,AlgoTypes as at,AlgoUniverse as u,AlgoInputEntities as e "
				."where p.algorithm=a.id and a.algo_type=at.id and p.universe=u.id and p.input_entity=e.id and p.id=".$pid;

*/
$input_df_list = json_decode(urldecode($_GET['input_df_list']));
$output_features = json_decode(urldecode($_GET['output_features']));

for ($i = 0; $i < count($input_df_list); $i++) {
	$sql1 = "select i.dataframe_id from InputDataFrames i,DataFrames as d where d.name ='".$input_df_list[$i]."' and i.dataframe_id=d.id and i.package_id=".$pid;
	if(!$result1 = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
	if($result1->fetch_assoc())
		continue;
	$sql2 = "insert into InputDataFrames (package_id,dataframe_id) select ".$pid." as package_id,id as dataframe_id from DataFrames where name='".$input_df_list[$i]."'";
	if(!$result2 = $conn->query($sql2)) {
		echo(json_encode((object) array('error' => $conn->error)));
		return;
	}
}

///////////////////////////////////////////////////////////////////////////////
$input_df_ids = array();
for ($i = 0; $i < count($input_df_list); $i++) {
	$sql1 = "select id from DataFrames where name='".$input_df_list[$i]."'";
	if(!$result = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
	while($row = $result->fetch_assoc()) {
		array_push($input_df_ids, $row['id']);
	}
}

$sql1 = "select dataframe_id from InputDataFrames where package_id=".$pid;
if(!$result = $conn->query($sql1)) {
	echo(json_encode((object) array('error' => $conn->error)));
	error_log('Error: '.$conn->error);
	return;
}
$existing_input_dfs = array();
while($row = $result->fetch_assoc()) {
	error_log($row['dataframe_id']);
	array_push($existing_input_dfs, $row['dataframe_id']);
}

$diff = array_diff($existing_input_dfs, $input_df_ids);

foreach($diff as $i) {
	$sql1 = "delete from InputDataFrames where package_id=".$pid." and dataframe_id=".$i;
	error_log($sql1);
	if(!$result = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}
///////////////////////////////////////////////////////////////////////////////
$sql1 = "select feature_id from QualityMetrics where package_id=".$pid;
if(!$result = $conn->query($sql1)) {
	echo(json_encode((object) array('error' => $conn->error)));
	error_log('Error: '.$conn->error);
	return;
}

$features = array();
while($row = $result->fetch_assoc()) {
	array_push($features, $row['feature_id']);
}
$diff = array_diff($features, $output_features);

foreach($diff as $i) {
	$sql1 = "delete from QualityMetrics where package_id=".$pid." and feature_id=".$i;
	if(!$result = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}

///////////////////////////////////////////////////////////
echo(json_encode((object) array('error' => "OK")));


?>
