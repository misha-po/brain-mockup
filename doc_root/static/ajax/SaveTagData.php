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

$tag_id = $_GET['tag_id'];
$tag_data = json_decode(urldecode($_GET['tag_data']));

if ($tag_data->tag_id == -1) {
	$sql = "insert into VisibilityTags (name,description) values ("
			."'".$tag_data->name."',"
			."'".$tag_data->description."')";
	error_log($sql);
	if(!$result = $conn->query($sql)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}
else {
	$sql = "update VisibilityTags set description='".$tag_data->description."' where id=".$tag_id;
	error_log($sql);

	if(!$result = $conn->query($sql)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}
///////////////////////////////////////////////////////////
echo(json_encode((object) array('error' => "OK")));


?>
