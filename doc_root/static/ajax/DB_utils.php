<?php
if (php_uname ('s') == 'Linux')
	$servername = "localhost";
else
	$servername = "192.168.56.101";

$username = "root";
$password = "1q2w3e4r";
$dbname = "HDP_PoalimBrainDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


function FetchData($sql, $conn) {
	if(!$result = $conn->query($sql)) {
		die('Error: '.$conn->error);
	}
	$out_data = array();
	while($row = $result->fetch_assoc()) {
		array_push($out_data, $row);
	}
	return $out_data;
}

?>
