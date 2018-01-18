<?php
$servername = "localhost";
$username = "root";
$password = "1q2w3e4r";
$dbname = "HDP_PoalimBrainDB";
$conn = new mysqli($servername, $username, $password, $dbname);

$txt = <<<EOD
{
	"headers" : [
		"id",
		"Name",
		"Owner",
		"Email",
		"Status"
		]
EOD;

		
// Create connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "select UNIX_TIMESTAMP(max(last_modified)) as timestamp from Packages;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo($row['timestamp'].'|||');


$sql = "SELECT p.id as pid, name,  a.owner_name, a.owner_email, run_status FROM Packages as p, Algorithms as a WHERE a.id=p.algorithm;";
$result = $conn->query($sql);

if ($result->num_rows == 0)
	return;

$rows = array();
while($r = $result->fetch_assoc()) {
  array_push($rows,$r);
}

$txt = $txt.
	",".
	'"rows": '.
	json_encode($rows).
	"}";
// error_log($txt);
echo $txt;
?>