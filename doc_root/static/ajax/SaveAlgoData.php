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
$algo_data = json_decode(urldecode($_GET['algo_data']));

if ($algo_id != -1) {
	$sql = "update Algorithms set algo_type=".$algo_data->algo_type.", "
						."algo_code='".$algo_data->algo_code."', "
						."owner_name='".$algo_data->owner_name."', "
						."owner_email='".$algo_data->owner_email."', "
						."description='".$algo_data->description."'"
						." where id=".$algo_id;
}
else {
	$sql = "insert into Algorithms (algo_type, algo_code, owner_name, owner_email, description, egg_path) values ("
						."'".$algo_data->algo_code."', "
						.$algo_data->algo_type.", "
						."'".$algo_data->owner_name."', "
						."'".$algo_data->owner_email."', "
						."'".$algo_data->description."')";
}
error_log($sql);
		
if(!$result = $conn->query($sql)) {
	echo(json_encode((object) array('error' => $conn->error)));
	error_log('Error: '.$conn->error);
	return;
}
///////////////////////////////////////////////////////////
echo(json_encode((object) array('error' => "OK")));


?>
