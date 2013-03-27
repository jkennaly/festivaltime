<?php


include("../../../variables/variables.php");
$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");
$fest=$_GET['fest'];

include('../../../variables/fest_variables.php');


//echo "dbhost = $dbhost,dbuser = $dbuser,dbpw = $dbpw, dbname=$dbname";
$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


$picid=$_GET['picid'];
$sql = "SELECT `pic`, `type`, `descrip` FROM `pics` WHERE `id` = '$picid'";
$res = mysql_query($sql, $main);
$pic = mysql_fetch_array($res);
$picData = $pic['pic'];
//echo "Content-type: ".$pic['type'];
//echo "Content-descrip: ".$pic['descrip'];

header("Content-type: ".$pic['type']);
echo $picData;

mysql_close($main);
mysql_close($master);

?>
