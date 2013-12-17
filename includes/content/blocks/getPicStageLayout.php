<?php
/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/ 

$festivaltimeContext = 1;

include("../../../variables/variables.php");
$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

$layout=mysql_real_escape_string( $_GET['layout'] );

$sql = "SELECT `image`, `type` FROM `stage_layouts` WHERE `id` = '$layout'";
$res = mysql_query($sql, $master);
if(mysql_num_rows($res) > 0){
	$pic = mysql_fetch_array($res);
} else {
	$sql = "SELECT `image`, `type` FROM `stage_layouts` WHERE `default` = '1' LIMIT 1";
	$res = mysql_query($sql, $master);
	$pic = mysql_fetch_array($res);
	
}
$picData = $pic['image'];

header("Content-type: ".$pic['type']);
echo $picData;

mysql_close($master);

?>
