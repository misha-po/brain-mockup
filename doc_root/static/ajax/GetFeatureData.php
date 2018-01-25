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
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$id = $_GET['feature_id'];
$dataframe_id = $_GET['df_id'];

$sql5 = "SELECT * from Data_types";
$sql6 = "SELECT * from Value_constraints";

$sql = "select f.name Feature, d.name Dataframe, f.is_target, f.description from Features f, DataFrames d where f.dataframe_id=d.id and f.id=".$id;
error_log($sql);
if(!$result1 = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
$row1 = $result1->fetch_assoc();

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


$sql = "SELECT * from VisibilityTags";
if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
$available_tags = array();
while($row = $result->fetch_assoc()) {
	array_push($available_tags, $row);
}

$sql = "select v.id,v.name from FeatureTags f, VisibilityTags v where f.tag_id=v.id and feature_id=".$id;
if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
$used_tags = array();
while($row = $result->fetch_assoc()) {
	array_push($used_tags, $row);
}

$sql = " select * from DataFrames where id=".$dataframe_id;
if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
$dataframe = $result->fetch_assoc();

$json_data = (object) array(
				'error' => 'OK',
				'feature_data' => $row1,
				'data_types' => $data_types,
				'value_constraints' => $value_constraints,
				'available_tags' => $available_tags,
				'dataframe' => $dataframe,
				'used_tags' => $used_tags
			);

error_log(json_encode($json_data));
echo(json_encode($json_data));
?>
