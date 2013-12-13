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


include("../../../variables/variables.php");
$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

$band=mysql_real_escape_string( $_GET['band'] );


$sql = "SELECT `scaled_pic`, `type`, `descrip` FROM `pics` WHERE `band` = '$band'  ORDER BY RAND() LIMIT 1";
$res = mysql_query($sql, $master);
$pic = mysql_fetch_array($res);
$picData = $pic['scaled_pic'];

header("Content-type: ".$pic['type']);
echo $picData;

mysql_close($master);

?>
