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
$master = mysql_connect($dbhost, $master_dbuser, $master_dbpw);
@mysql_select_db($master_db, $master) or die("Unable to select master database");

$picGet = $_GET['pic'];

$sql = "SELECT `pic`, `type`, `descrip` FROM `pics` WHERE `id` = '$picGet'";
$res = mysql_query($sql, $master);
$pic = mysql_fetch_array($res);
$picData = $pic['pic'];

header("Content-type: " . $pic['type']);
echo $picData;

mysql_close($master);
