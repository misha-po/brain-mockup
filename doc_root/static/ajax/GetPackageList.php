<?php
$text = <<<EOD
{
	"root" : [
		{
			"name":"retail"
		},
		{
			"name":"business"
		},
		{
			"name":"international"
		},
		{
			"name":"dealing_room"
		}
	]
}
EOD;

echo($text);
return;


$myfile = fopen("../data/basic_algo.xml", "r") or die("Unable to open file!");
$xml = fread($myfile, filesize("../data/basic_algo.xml"));
echo($xml);
return;

?>