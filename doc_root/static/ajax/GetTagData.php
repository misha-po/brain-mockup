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
$id = $_GET['tag_id'];

$sql = "SELECT name,description from VisibilityTags where id=".$id;

error_log($sql);
if(!$result1 = $conn->query($sql)) {
	error_log('Error: '.$conn->error);
	return;
}
$row1 = $result1->fetch_assoc();

$json_data = (object) array(
				'error' => 'OK',
				'tag_data' => $row1
			);

error_log(json_encode($json_data));
echo(json_encode($json_data));
?>
