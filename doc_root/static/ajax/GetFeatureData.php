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

$algo_id = $_GET['algo_id'];
$sql1 = "SELECT "
			."a.id, "
			."a.algo_code, "
			."at.name as algo_type, "
			."a.owner_name, "
			."a.owner_email, "
			."a.description "
			."from Algorithms a, AlgoTypes as at  "
			."where a.algo_type=at.id and a.id=".$algo_id;

if(!$result1 = $conn->query($sql1)) {
	error_log('Error: '.$conn->error);
	return;
}
$row1 = $result1->fetch_assoc();

$sql = "select * from AlgoTypes";
if(!$result = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
$algo_types = array();
while($row = $result->fetch_assoc()) {
	array_push($algo_types, $row);
}

$json_data = (object) array(
				'error' => 'OK',
				'algo_data' => $row1,
				'algo_types' => $algo_types
			);

error_log(json_encode($json_data));
echo(json_encode($json_data));
?>
