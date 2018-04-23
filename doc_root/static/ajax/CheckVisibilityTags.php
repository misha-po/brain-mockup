<?php
require 'DB_utils.php';

$data = json_decode(urldecode($_GET['data']));

$tag_list = $data->tag_list;
$df_list = $data->df_list;

error_log('df_list '.json_encode($df_list));
error_log('tag_list '.json_encode($tag_list));

$list = array();
foreach ($df_list as $dataframe) {
	$sql = "select id,name,description from Features where dataframe_id=".$dataframe->df_id;
	$feature_set = FetchData($sql, $conn);
	foreach ($feature_set as $feature) {
		error_log($feature['name'].' id='.$feature['id']);
		$sql = "select tag_id,name from FeatureTags,VisibilityTags where tag_id=VisibilityTags.id and feature_id=".$feature['id'];
		$tag_set = FetchData($sql, $conn);
		foreach ($tag_set as $tag_id) {
			$tags = array();
			if (in_array(intval($tag_id["tag_id"]), $tag_list)) {
				$blocked_feature = (object) array(
					'dataframe' => $dataframe,
					'feature' => $feature,
					'block_tag' => $tag_id
				);
				array_push($list, $blocked_feature);
			}
		}
	}
}

$result = (object) array(
	'error' => "OK",
	'list' => $list
);
echo(json_encode((object)$result));
?>