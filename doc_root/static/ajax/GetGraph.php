<?php
$pid = $_GET['id'];
$servername = "localhost";
$username = "root";
$password = "1q2w3e4r";
$dbname = "HDP_PoalimBrainDB";
$txt = <<<EOD
digraph unix {
	size="7,7";
	bgcolor="#f2f2f2";
	node [shape=box, color=blue, style="filled",URL="../static/img/matrixBi.png"];
	node [fillcolor=floralwhite, fontcolor=brown4, setlinewidth=bold, tooltip="This is a tooltip"];
	
EOD;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
unlink("../../data/graph1.svg");

$packages = array();
$sql1 = "select distinct q.package_id as p_start,i.package_id as p_end,d.name as df_name from QualityMetrics as q, Features as f, DataFrames d,InputDataFrames i where i.dataframe_id=f.dataframe_id and d.id=f.dataframe_id and f.id=q.feature_id and q.package_id=";
$sql2 = "select distinct i.package_id p_end,q.package_id p_start,d.name as df_name from InputDataFrames i,Features f,QualityMetrics q,DataFrames d where d.id=f.dataframe_id and q.feature_id=f.id and f.dataframe_id=i.dataframe_id and i.package_id=";
$sql3 = 'select name from Packages where id=';
//*
$packages = GetSiblings($conn, $packages, $sql1, $pid, false);
$packages = GetSiblings($conn, $packages, $sql2, $pid, true);

/*/
if(!$result = $conn->query($sql1)) {
	error_log('Error: '.$conn->error);
}
while($row = $result->fetch_assoc()) {
	$node = (object) array(
		'p_in' => $row['p_start'],
		'p_out' => $row['p_end'],
		'df_name' => $row['df_name'],
		'visited' => False
	);
	array_push($packages , $node);
}

$sql = "select distinct i.package_id p_end,q.package_id p_start,d.name as df_name from InputDataFrames i,Features f,QualityMetrics q,DataFrames d where d.id=f.dataframe_id and q.feature_id=f.id and f.dataframe_id=i.dataframe_id and i.package_id=".$pid;
if(!$result = $conn->query($sql2)) {
	error_log('Error: '.$conn->error);
}
while($row = $result->fetch_assoc()) {
	$node = (object) array(
		'p_in' => $row['p_start'],
		'p_out' => $row['p_end'],
		'df_name' => $row['df_name'],
		'visited' => False
	);
	array_push($packages , $node);
}
//*/


$file = fopen("../../data/graph1.gv", "w");
fwrite($file, $txt);

if(!$result = $conn->query($sql3.$pid))
	error_log('Error: '.$conn->error);
if ($row = $result->fetch_assoc())
	$p_name = $row['name'];
fwrite($file, '"'.$p_name.'" [shape=ellipse color=red];'."\n");


//$n = FindSiblings($file, $array, $root);
if ( count($packages) == 0){
	fwrite($file, "\t" . $pid . ";\n");
}
else {
	for ($i = 0; $i < count($packages); $i++) {
		if($packages[$i]->visited == True)
			return 0;
		$packages[$i]->visited = True;
		$p_in_name = '';
		$p_out_name = '';
		$sql = $sql3.$packages[$i]->p_in;
		if(!$result = $conn->query($sql)) {
			error_log('Error: '.$conn->error);
			continue;
		}
		if ($row = $result->fetch_assoc())
			$p_in_name = $row['name'];
		$sql = $sql3.$packages[$i]->p_out;
		if(!$result = $conn->query($sql)) {
			error_log('Error: '.$conn->error);
			continue;
		}
		if ($row = $result->fetch_assoc())
			$p_out_name = $row['name'];
		fwrite($file, "\t" . $p_in_name . " -> " . $p_out_name .'[ label="'.$packages[$i]->df_name.'" ]'.";\n");
	}
}

fwrite($file, "}");
fclose($file);
$conn->close();


system ("dot -Tsvg ../../data/graph1.gv -o ../../data/graph1.svg");
echo(json_encode((object) array('error' => "OK")));
return;



///////////////////////////////////////////////////
///////////////////////////////////////////////////
///////////////////////////////////////////////////

function GetSiblings($conn, $packages, $sql, $pid, $go_back) {
	if ($pid == '')
		return $packages;
	//error_log(($go_back?'back':'for').'  '.count($packages).'   pid='.$pid);
	if(!$result = $conn->query($sql.$pid)) {
		error_log($sql);
		error_log('Error: '.$conn->error);
	}
	while($row = $result->fetch_assoc()) {
		$node = (object) array(
			'p_in' => $row['p_start'],
			'p_out' => $row['p_end'],
			'df_name' => $row['df_name'],
			'visited' => False
		);
		if ($node->p_in == $node->p_out)
			continue;
//		error_log('pin='.$node->p_in.' pout='.$node->p_out);
		$packages = GetSiblings($conn, $packages, $sql, ($go_back)?$node->p_in:$node->p_out, $go_back);
		array_push($packages , $node);
	}
	return $packages;
}
?>