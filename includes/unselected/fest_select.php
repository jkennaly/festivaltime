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

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

?>
<div id="content">

<?php

//Find all festivals registered in the master database

$sql = "SHOW TABLES LIKE 'info_%'";
$result = mysql_query($sql, $master);

while($row = mysql_fetch_array($result)) {
	$sql = "select * from ".$row['0'];
	$res = mysql_query($sql, $master);
	while($row1 = mysql_fetch_array($res)) {
		switch($row1['item']) {
			case "Festival id":
				$fest=$row1['value'];
				break;
			case "Festival Name":
				$festname=$row1['value'];
				break;
			case "Festival Year":
				$festyear=$row1['value'];
				break;
            case "festtype":
                $festtype=$row1['value'];
                break;
		}
	}

$linetext = "<li><a href=\"".$basepage."?disp=home&fest=".$fest."\">".$festname." ".$festyear."</a></li>";
$lineitem[] = array(
                        "text" => $linetext,
                        "type" => $festtype,
);
}

echo "<h3>Select a multiple-day festival</h3>";
echo "<ul id=\"festlist\">";
foreach ($lineitem as $v){
    If($v['type'] == "1")     echo $v['text'];
}
echo "</ul>";

echo "<h3>Select a SimFest</h3>";
echo "<ul id=\"simfestlist\">";
foreach ($lineitem as $v){
    If($v['type'] == "2" || $v['type'] == "3" || $v['type'] == "4")     echo $v['text'];
}
echo "</ul>";

$right_required = "SimFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>
<h3>The following link will allow you to add a new simulated festival.</h3>

<a href="<?php echo $basepage; ?>?disp=add_simfest">Add new SimFest</a>

<?php
}

$right_required = "AddFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>
<h3>The following link will allow you to add a new show.</h3>

<a href="<?php echo $basepage; ?>?disp=add_fest">Add new festival or concert</a>

<?php
}

$right_required = "SiteAdmin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>
<h3>The following link will allow you to delete a show.</h3>

<a href="<?php echo $basepage; ?>?disp=delete_fest">Delete festival or concert</a>

<?php
}
} else {
    echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}
?>
</div> <!-- end #content -->
