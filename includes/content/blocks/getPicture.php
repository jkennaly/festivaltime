<?php

include("../../../variables/variables.php");

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");

$band=$_GET['band'];
$sql = "SELECT `pic`, `type`, `descrip` FROM `pics` WHERE `band` = '$band' order by rand() limit 1";
$res = mysql_query($sql, $main);
$pic = mysql_fetch_array($res);
$picData = $pic['pic'];
//echo "Content-type: ".$pic['type'];
//echo "Content-descrip: ".$pic['descrip'];
header("Content-type: ".$pic['type']);
echo $picData;

mysql_close($main);

?>