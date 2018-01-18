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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "select UNIX_TIMESTAMP(max(last_modified)) as timestamp from Algorithms;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
echo($row['timestamp'].'|||');


$sql = "SELECT a.id id, a.algo_code 'Algo name', t.name 'Algo type',a.owner_name Owner, a.owner_email Email FROM Algorithms as a,AlgoTypes t WHERE a.algo_type=t.id";
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