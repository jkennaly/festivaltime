<?php
session_start();



$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

include("../../../variables/variables.php");

include('../../../variables/fest_variables.php');

//echo "dbhost = $dbhost,dbuser = $dbuser,dbpw = $dbpw, dbname=$dbname";
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
