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

$feature_id = $_GET['feature_id'];
$feature_data = json_decode(urldecode($_GET['feature_data']));

if ($feature_data->feature_id == -1) {
	$sql = "select count(*) as count from Features where dataframe_id=".$feature_data->dataframe_id." and name='".$feature_data->name."'";
	if(!$result = $conn->query($sql)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
	$count = $result->fetch_assoc();
	if($count['count'] != 0 ) {
		echo(json_encode((object) array('error' => 'Feature '.$feature_data->name.' already defined for this dataframe')));
		return;
	}
	$sql = "insert into Features (dataframe_id,name,is_target,description,data_type,value_constraint, universe, key_enity) values (".
			$feature_data->dataframe_id.","
			."'".$feature_data->name."',"
			.$feature_data->is_target.","
			."'".$feature_data->description."',"
			.$feature_data->data_type.","
			.$feature_data->data_constraint.","
			.$feature_data->universe.","
			.$feature_data->key_enity.")";
	error_log($sql);
	if(!$result = $conn->query($sql)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
	if (count($feature_data->used_tags) > 0) {
		$sql = "select last_insert_id()as last_id";
		if(!$result = $conn->query($sql)) {
			echo(json_encode((object) array('error' => $conn->error)));
			error_log('Error: '.$conn->error);
			return;
		}
		$feature_id = $result->fetch_assoc()['last_id'];
		$sql = "insert into FeatureTags (feature_id,tag_id) values ";
		for($i = 0; $i < count($feature_data->used_tags); $i++) {
			$sql = $sql."(".$feature_id.",".$feature_data->used_tags[$i].')';
			if ($i < (count($feature_data->used_tags)-1)) 
				$sql = $sql.",";
		}
		if(!$result = $conn->query($sql)) {
			echo(json_encode((object) array('error' => $conn->error)));
			error_log('Error: '.$conn->error);
			return;
		}
	}
}
else {
	$sql = "update Features set data_type=".$feature_data->data_type.","
			."value_constraint=".$feature_data->data_constraint.", "
			."is_target=".$feature_data->is_target.", "
			."universe=".$feature_data->universe.", "
			."key_enity=".$feature_data->key_enity.", "
			."description='".$feature_data->description."'"
			." where id=".$feature_id;
	error_log($sql);

	if(!$result = $conn->query($sql)) {
		echo(json_encode((object) array('error' => $conn->error)));
		error_log('Error: '.$conn->error);
		return;
	}
	for($i = 0; $i < count($feature_data->used_tags); $i++) {
		$sql = "select count(*) count from FeatureTags where  feature_id=".$feature_id." and tag_id=".$feature_data->used_tags[$i];
		error_log($sql);
		if(!$result = $conn->query($sql)) {
			echo(json_encode((object) array('error' => $conn->error)));
			error_log('Error: '.$conn->error);
			return;
		}
		$count = $result->fetch_assoc();
		if($count['count'] != 0 )
			continue;

		$sql = "insert into FeatureTags set feature_id=".$feature_id.", tag_id=".$feature_data->used_tags[$i];
		if(!$result = $conn->query($sql)) {
			echo(json_encode((object) array('error' => $conn->error)));
			error_log('Error: '.$conn->error);
			return;
		}
	}
}
///////////////////////////////////////////////////////////
echo(json_encode((object) array('error' => "OK")));


?>
