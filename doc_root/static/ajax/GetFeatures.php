<?php
require 'DB_utils.php';

$df_id = $_GET['df_id'];

$sql1 = "select * from Features where dataframe_id=".$df_id;

if(!$result1 = $conn->query($sql1)) {
	error_log('Error: '.$conn->error);
	return;
}
$available_features = array();
while($row = $result1->fetch_assoc()) {
	array_push($available_features, $row);
}

$json_data = (object) array(
				'available_features' => $available_features
			);

//error_log("Here we go");
echo(json_encode($json_data));
?>
