/*
*Copyright (c) 2013 Jason Kennaly.
*All rights reserved. This program and the accompanying materials
*are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
*http://www.gnu.org/licenses/agpl.html
*
*Contributors:
*    Jason Kennaly - initial API and implementation
*/


<?php

/* This block updates the database if a rating star was clicked
*  This block requires the following variables: none
*/

$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_POST['new_link'])){
	
	$query="SELECT link FROM links WHERE band='$band' AND user='$user'";
	$query_link = mysql_query($query, $main);
	$link_row = mysql_fetch_assoc($query_link);

	If ( !isset($link_row['link']) ) {
	$link = mysql_real_escape_string($_POST["new_link"]);
	$descrip = mysql_real_escape_string($_POST["new_descrip"]);
	$sql = "INSERT INTO links (band, user, link, descrip) VALUES ('$band', '$user', '$link', '$descrip')";
	$sql_run = mysql_query($sql, $main);	
	} else {
	$link = mysql_real_escape_string($_POST["new_link"]);
	$descrip = mysql_real_escape_string($_POST["new_descrip"]);
	$sql = "UPDATE links SET link='$link', descrip='$descrip', clicks='0' WHERE band='$band' AND user='$user'";
	$sql_run = mysql_query($sql, $main);	
	}
	
}
}
?>
