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
	node [fillcolor=floralwhite, fontcolor=brown4, setlinewidth=bold, tooltip="Here we go"];	
EOD;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
unlink("../../data/graph1.svg");
unlink("../../data/graph1.gv");






$packages1 = array();
$packages2 = array();

GetAncestor($conn, $packages1, $pid);
// GetPredecessor($conn, $packages2, $pid);

$packages = array_merge($packages1, $packages2);

$file = fopen("../../data/graph1.gv", "w");
fwrite($file, $txt);

$sql3 = 'select name from Packages where id=';
if(!$result = $conn->query($sql3.$pid))
	error_log('Error in line '.__LINE__.': '.$conn->error);
if ($row = $result->fetch_assoc())
	$p_name = $row['name'];
fwrite($file, '"'.$p_name.'" [shape=ellipse color=red];'."\n");

GetAncestor($conn, $file, $pid);
GetPredecessor($conn, $file, $pid);


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
function GetAncestor($conn, $file, $pid) {
	if ($pid == '')
		return;

	$sql = "SELECT name FROM Packages where id=".$pid;
	$p_name = FetchData($sql, $conn)[0]['name'];

	$sql = "select distinct f.dataframe_id,d.name as df_name from QualityMetrics q,Features f,DataFrames d where f.dataframe_id=d.id and q.feature_id=f.id and q.package_id=".$pid;
	$the_list = FetchData($sql, $conn);


	foreach ($the_list as $item) {
		error_log("a=====================================================".$item['dataframe_id']."===".$item['df_name']);
		$sql = "select distinct package_id, p.name as p_out from InputDataFrames i, Packages p where p.id=i.package_id and dataframe_id=".$item['dataframe_id'];
		$dataframes = FetchData($sql, $conn);
		if (count ($dataframes) == 0) {
			fwrite($file, "\t" . $p_name . " -> " . '" "' .'[ label="'.$item['df_name'].'" ]'.";\n");
		}
		else {
			fwrite($file, "\t" . $p_name . " -> " . $dataframes[0]['p_out'] .'[ label="'.$item['df_name'].'" ]'.";\n");
			if ($pid == $dataframes[0]['package_id'])
				continue;
			GetAncestor($conn, $file, $dataframes[0]['package_id']);	
		}
	}
}


function GetPredecessor($conn, $file, $pid) {
	if ($pid == '')
		return $packages;

	$sql = "SELECT name FROM Packages where id=".$pid;
	$p_name = FetchData($sql, $conn)[0]['name'];

	$sql = "select distinct i.dataframe_id as df_id,d.name as df_name from InputDataFrames i,DataFrames d where d.id=i.dataframe_id and i.package_id=".$pid;
	$dataframes = FetchData($sql, $conn);
	foreach ($dataframes as $df) {
		$sql="select q.package_id as source_pid,p.name as source_name from Features f,QualityMetrics q,Packages p where p.id=q.package_id and f.id=q.feature_id and f.dataframe_id=".$df['df_id'];
		$pkges = FetchData($sql, $conn);
		error_log("b=====================================================".$df['df_id']."===".$df['df_name']);
		if (count ($pkges) == 0) {
			fwrite($file, "\t" . '"  "' . " -> " . $p_name .'[ label="'.$df['df_name'].'" ]'.";\n");
			continue;
		}
		if ($pid == $pkges[0]['source_pid'])
			continue;
		fwrite($file, "\t" . $pkges[0]['source_name'] . " -> " . $p_name .'[ label="'.$df['df_name'].'" ]'.";\n");
		GetPredecessor($conn, $file, $pkges[0]['source_pid']);
	}
}

?>