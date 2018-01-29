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

$type = $_GET['type'];
if($type == 'tag') {
	$sql = "select UNIX_TIMESTAMP(max(last_modified)) as timestamp from Packages;";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$timestamp = $row['timestamp'];

	$sql = "SELECT id, name 'Name',description Description FROM VisibilityTags";
}
if($type == 'algo') {
	$sql = "select UNIX_TIMESTAMP(max(last_modified)) as timestamp from Packages;";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$timestamp = $row['timestamp'];

	$sql = "SELECT a.id id, a.algo_code 'Algo name', t.name 'Algo type',a.owner_name Owner, a.owner_email Email FROM Algorithms as a,AlgoTypes t WHERE a.algo_type=t.id";
}
if($type == 'pckg') {
	$sql = "select UNIX_TIMESTAMP(max(last_modified)) as timestamp from Packages;";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$timestamp = $row['timestamp'];
	$sql = "SELECT p.id, name Name, a.owner_name Owner, a.owner_email Email, run_status Status FROM Packages as p, Algorithms as a WHERE a.id=p.algorithm";
}
if($type == 'dataframe') {
	$timestamp = -1;
	$sql = "select id,name Name from DataFrames";
}
if($type == 'feature') {
	$df_id = $_GET['df'];
	$timestamp = -1;
	$sql = "select f.id,f.name Name, d.name Type, v.name as 'Constraint', f.description Description from Features f,Data_types d,Value_constraints v where f.value_constraint=v.id and f.data_type=d.id and dataframe_id=".$df_id;
}
$result = $conn->query($sql);
$fields = mysqli_fetch_fields($result);
$headers = array();
for ($i = 0; $i < count($fields); $i++) {
	array_push($headers, $fields[$i]->name);
}
$rows = array();
while($row = $result->fetch_assoc()) {
	array_push($rows,$row);
}

$json_data = (object) array(
				'error' => 'OK',
				'timestamp' => $timestamp,
				'headers' => $headers,
				'rows' => $rows
			);

//error_log(json_encode($json_data));
echo json_encode($json_data);
?>