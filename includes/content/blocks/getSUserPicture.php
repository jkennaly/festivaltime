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
include('SimpleImage.php');


$user = $_GET['user'];


$sql = "SELECT `scaled_pic`, `type`, `description` FROM `pics_users` WHERE `user` = '$user' OR `user` = '0' order by `id` DESC limit 1";
$res = mysql_query($sql, $master);
$pic = mysql_fetch_array($res);
$picData = $pic['scaled_pic'];
//echo "Content-type: ".$pic['type'];
//echo "test";
//echo $sql;
//echo "Content-descrip: ".$pic['descrip'];

/*
$image = new SimpleImage();
$image->create($picData);
$image->resize(205,205);
*/

header("Content-type: " . $pic['type']);
echo $picData;
// $image->image;

mysql_close($master);

