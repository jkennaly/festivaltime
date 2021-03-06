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


function MatchFilter($target, $field, $table){
	foreach($target as $check) {
		If (!isset($first_pass_complete)) $temp = "(".$table.".".$field."='".$check."'";
		If (isset($first_pass_complete)) $temp .= " OR ".$table.".".$field."='".$check."'";
		$first_pass_complete = 1;	
    	}
$passes = $temp.")";
return $passes;
}

function ExternalIncludeFilter($field, $table, $ext_target, $ext_table, $ext_key, $ext_value, $mysql_link){
	$query="select $ext_target from $ext_table where $ext_key='$ext_value'";
	$result = mysql_query($query, $mysql_link);
If(mysql_num_rows($result)>0){
	while($row = mysql_fetch_array($result)) {
		If (!isset($first_pass_complete)) $temp = "(".$table.".".$field."='".$row[$ext_target]."'";
		If (isset($first_pass_complete)) $temp .= " OR ".$table.".".$field."='".$row[$ext_target]."'";
		$first_pass_complete = 1;
	}
$passes = $temp.")";
} else $passes = "";
return $passes;
}

function ExternalExcludeFilter($field, $table, $ext_target, $ext_table, $ext_key, $ext_value, $mysql_link){
	$query="select $ext_target from $ext_table where $ext_key='$ext_value'";
	$result = mysql_query($query, $mysql_link);
If(mysql_num_rows($result)>0){
	while($row = mysql_fetch_array($result)) {
		If (!isset($first_pass_complete)) $temp = "(".$table.".".$field."!='".$row[$ext_target]."'";
		If (isset($first_pass_complete)) $temp .= " AND ".$table.".".$field."!='".$row[$ext_target]."'";
		$first_pass_complete = 1;
	}
$passes = $temp.")";
} else $passes = "";
return $passes;
}

function ExternalMinimumFilter($field, $table, $ext_target, $ext_table, $ext_key, $ext_value, $mysql_link){
	$query="select $ext_target from $ext_table group by $ext_target having $ext_key>$ext_value";
	$result = mysql_query($query, $mysql_link);
If(mysql_num_rows($result)>0){
	while($row = mysql_fetch_array($result)) {
		If (!isset($first_pass_complete)) $temp = "(".$table.".".$field."='".$row[$ext_target]."'";
		If (isset($first_pass_complete)) $temp .= " OR ".$table.".".$field."='".$row[$ext_target]."'";
		$first_pass_complete = 1;
	}
$passes = $temp.")";
} else $passes = "";
return $passes;
}

function ExternalMaximumFilter($field, $table, $ext_target, $ext_table, $ext_key, $ext_value, $mysql_link){
	$query="select $ext_target from $ext_table group by $ext_target having $ext_key<$ext_value";
	$result = mysql_query($query, $mysql_link);
If(mysql_num_rows($result)>0){
	while($row = mysql_fetch_array($result)) {
		If (!isset($first_pass_complete)) $temp = "(".$table.".".$field."='".$row[$ext_target]."'";
		If (isset($first_pass_complete)) $temp .= " OR ".$table.".".$field."='".$row[$ext_target]."'";
		$first_pass_complete = 1;
	}
$passes = $temp.")";
} else $passes = "";
return $passes;
}

?>
