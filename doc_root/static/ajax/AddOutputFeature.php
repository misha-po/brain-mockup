<?php
$package_id = $_GET['package_id'];
$df_id = $_GET['df_id'];
$feature_name = $_GET['feature_name'];
$data_type_id = $_GET['data_type_id'];
$data_constraint_id = $_GET['data_constraint_id'];
$is_target = $_GET['is_target'];
$quality_metrics = urldecode($_GET['quality_metrics']);
$dataframe_name = $_GET['dataframe_name'];

$servername = "localhost";
$username = "root";
$password = "1q2w3e4r";
$dbname = "HDP_PoalimBrainDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql1 = "select * from Features where dataframe_id=".$df_id." and name='".$feature_name."'";
$sql2 = "insert into Features (dataframe_id, name, data_type, value_constraint, is_target) values ("
			.$df_id.","
			."'".$feature_name."',"
			.$data_type_id.","
			.$data_constraint_id.","
			.$is_target.")";
$sql3 = "select last_insert_id()as last_id";
// if(!$result1 = $conn->query($sql1)) {
	// echo(json_encode((object) array('error' => $conn->error)));
	// error_log('Error: '.$conn->error);
	// return;
// }
// if($row1 = $result1->fetch_assoc()) {
	// $json_data = (object) array(
				// 'error' => "Feature '".$feature_name."' already exists"
			// );
	// echo(json_encode($json_data));
	// return;
// }

if(!$conn->query($sql2)) {
	echo(json_encode((object) array('error' => $conn->error)));
	error_log('Error: '.$conn->error);
	return;
}

if(!$result3 = $conn->query($sql3)) {
	echo(json_encode((object) array('error' => $conn->error)));
	error_log('Error: '.$conn->error);
	return;
}
$feature_id = $result3->fetch_assoc()['last_id'];

$sql4 = "insert into QualityMetrics (feature_id, package_id, quality_metrics) values ("
			.$feature_id.","
			.$package_id.","
			."'".$quality_metrics."')";
if(!$conn->query($sql4)) {
	echo(json_encode((object) array('error' => $conn->error)));
	error_log('Error: '.$conn->error);
	return;
}

///////////////////////////////////////////////////////////
echo(json_encode((object) array('error' => "OK")));
?>
