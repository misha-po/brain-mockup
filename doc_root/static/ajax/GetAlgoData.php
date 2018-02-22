<?php
require 'DB_utils.php';

$algo_id = $_GET['algo_id'];
$sql = "SELECT "
			."a.id, "
			."a.algo_code, "
			."at.name as algo_type, "
			."a.owner_name, "
			."a.owner_email, "
			."a.description "
			."from Algorithms a, AlgoTypes as at  "
			."where a.algo_type=at.id and a.id=".$algo_id;

$algo_data = FetchData($sql, $conn);

$sql = "select * from AlgoTypes";
$algo_types = FetchData($sql, $conn);

$sql = "select i.id,d.name from InputDataFrames i, DataFrames d where i.dataframe_id=d.id and i.algorithm_id=".$algo_id;
$input_dataframes = FetchData($sql, $conn);

$sql = "select * from DataFrames";
$all_data_frames = FetchData($sql, $conn);

$json_data = (object) array(
				'error' => 'OK',
				'algo_data' => $algo_data,
				'input_dataframes' => $input_dataframes,
				'all_data_frames' => $all_data_frames,
				'algo_types' => $algo_types
			);

error_log(json_encode($json_data));
echo(json_encode($json_data));
?>
