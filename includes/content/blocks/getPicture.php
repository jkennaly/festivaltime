#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<?php


include("../../../variables/variables.php");
$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");
$fest=$_GET['fest'];

include('../../../variables/fest_variables.php');


//echo "dbhost = $dbhost,dbuser = $dbuser,dbpw = $dbpw, dbname=$dbname";
$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


$band=$_GET['band'];

$bandsql="select master_id from bands where id='$band'";
$bandres=mysql_query($bandsql, $main);
$row=mysql_fetch_array($bandres);
$mband=$row['master_id'];
$sql = "SELECT `pic`, `type`, `descrip` FROM `pics` WHERE `mas_id` = '$mband' order by rand() limit 1";
$res = mysql_query($sql, $master);
$pic = mysql_fetch_array($res);
$picData = $pic['pic'];
//echo "Content-type: ".$pic['type'];
//echo "Content-descrip: ".$pic['descrip'];

header("Content-type: ".$pic['type']);
echo $picData;

mysql_close($main);
mysql_close($master);

?>
