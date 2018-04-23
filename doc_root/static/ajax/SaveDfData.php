<?php
require 'DB_utils.php';


$df_id = $_GET['df_id'];

$df_data = json_decode(urldecode($_GET['df_data']));

if ($df_data->df_id == -1) {
	$sql = "insert into DataFrames (name, description) values ("
			."'".$df_data->name."',"
			."'".$df_data->description."')";
	error_log($sql);
	if(!$result = $conn->query($sql)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
}
else {
	$sql = "update VisibilityTags set description='".$df_data->description."' where id=".$df_id;
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
