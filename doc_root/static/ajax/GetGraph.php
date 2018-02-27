<?php
require 'DB_utils.php';

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

$packages1 = array();
$packages2 = array();

$packages1 = GetAncestor($conn, $packages1, $pid);
$packages2 = GetPredecessor($conn, $packages2, $pid);

$packages = array_merge($packages1, $packages2);

$file = fopen("../../data/graph1.gv", "w");
fwrite($file, $txt);

$sql3 = 'select name from Packages where id=';
if(!$result = $conn->query($sql3.$pid))
	error_log('Error in line '.__LINE__.': '.$conn->error);
if ($row = $result->fetch_assoc())
	$p_name = $row['name'];
fwrite($file, '"'.$p_name.'" [shape=ellipse color=red];'."\n");


//$n = FindSiblings($file, $array, $root);
for ($i = 0; $i < count($packages); $i++) {
	fwrite($file, "\t" . $packages[$i]->p_in . " -> " . $packages[$i]->p_out .'[ label="'.$packages[$i]->df_name.'" ]'.";\n");
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
function GetAncestor($conn, $packages, $pid) {
	if ($pid == '')
		return $packages;

	$sql = "SELECT name FROM Packages where id=".$pid;
	$p_name = FetchData($sql, $conn)[0]['name'];

	foreach ($packages as $pkg) {
		if ($pkg->p_in == $p_name) {
			return $packages;
		}
	}

	$sql = "SELECT distinct d.name as df_name, p.name p_end, p.id as pid"
			." FROM QualityMetrics q, Features as f, InputDataFrames as i,Packages as p, DataFrames d "
			." where f.dataframe_id=d.id and i.package_id=p.id and i.dataframe_id=f.dataframe_id and q.feature_id=f.id and q.package_id=".$pid;
	
	$the_list = FetchData($sql, $conn);
	foreach ($the_list as $item) {
		$node = (object) array(
			'p_in' => $p_name,
			'p_out' => $item['p_end'],
			'df_name' => $item['df_name'],
			'visited' => False
		);
		$packages = GetAncestor($conn, $packages, $item['pid']);
		array_push($packages , $node);
	}
	return $packages;
}

function GetPredecessor($conn, $packages, $pid) {
	error_log("Here we go, pid=".$pid);

	if ($pid == '')
		return $packages;

	$sql = "SELECT name FROM Packages where id=".$pid;
	$p_name = FetchData($sql, $conn)[0]['name'];

	foreach ($packages as $pkg) {
		if ($pkg->p_in == $p_name) {
			return $packages;
		}
	}

	$sql = "SELECT i.package_id,i.dataframe_id,f.id f_id,d.name as df_name, q.package_id pid, p.name p_in"
			." FROM DataFrames d,InputDataFrames i, Features f, QualityMetrics q, Packages p"
			." where q.package_id=p.id and q.feature_id=f.id and i.dataframe_id=f.dataframe_id and d.id=i.dataframe_id and i.package_id=".$pid;
	$features = FetchData($sql, $conn);

	$the_list = FetchData($sql, $conn);
	foreach ($the_list as $item) {
		$node = (object) array(
			'p_in' => $item['p_in'],
			'p_out' => $p_name,
			'df_name' => $item['df_name'],
			'visited' => False
		);
		$packages = GetAncestor($conn, $packages, $item['pid']);
		array_push($packages , $node);
	}
	return $packages;
}

?>