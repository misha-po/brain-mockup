<?php
$servername = "localhost";
$username = "root";
$password = "1q2w3e4r";
$dbname = "HDP_PoalimBrainDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$package_id = $_GET['package_id'];
$input_df_list = json_decode(urldecode($_GET['input_df_list']));
$output_features = json_decode(urldecode($_GET['output_features']));

for ($i = 0; $i < count($input_df_list); $i++) {
	$sql1 = "select i.dataframe_id from InputDataFrames i,DataFrames as d where d.name ='".$input_df_list[$i]."' and i.dataframe_id=d.id and i.package_id=".$package_id;
	if(!$result1 = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
	if($result1->fetch_assoc())
		continue;
	$sql2 = "insert into InputDataFrames (package_id,dataframe_id) select ".$package_id." as package_id,id as dataframe_id from DataFrames where name='".$input_df_list[$i]."'";
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

$sql1 = "select dataframe_id from InputDataFrames where package_id=".$package_id;
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
	$sql1 = "delete from InputDataFrames where package_id=".$package_id." and dataframe_id=".$i;
	error_log($sql1);
	if(!$result = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}
///////////////////////////////////////////////////////////////////////////////
$sql1 = "select feature_id from QualityMetrics where package_id=".$package_id;
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
	$sql1 = "delete from QualityMetrics where package_id=".$package_id." and feature_id=".$i;
	if(!$result = $conn->query($sql1)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}

///////////////////////////////////////////////////////////
echo(json_encode((object) array('error' => "OK")));


?>
