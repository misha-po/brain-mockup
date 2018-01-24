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

$id = $_GET['feature_id'];
$sql1 = "select f.name Feature, d.name Dataframe from Features f, DataFrames d where f.dataframe_id=d.id and f.id=".$id;
$sql5 = "SELECT * from Data_types";
$sql6 = "SELECT * from Value_constraints";

if(!$result1 = $conn->query($sql1)) {
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
$tags = array();
while($row = $result->fetch_assoc()) {
	array_push($tags, $row);
}



$json_data = (object) array(
				'error' => 'OK',
				'feature_data' => $row1,
				'data_types' => $data_types,
				'value_constraints' => $value_constraints,
				'tags' => $tags
			);

error_log(json_encode($json_data));
echo(json_encode($json_data));
?>
