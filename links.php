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


session_start(); 
 include('includes/check_rights.php');  
 
include('variables/variables.php');

$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

$right_required = "FollowLink";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){



function isInteger($input){
    return(ctype_digit(strval($input)));
}

If(isset($_GET['fest']) && isInteger($_GET['fest'])) {
	$_SESSION['fest'] = $_GET['fest'];
} 
// echo $_SESSION['fest']."<br />"; 
 

If(!empty($_SESSION['fest'])){

include('variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


 include('variables/page_variables.php'); 

}
 include('includes/content/blocks/database_functions.php'); 
 include('includes/content/blocks/other_functions.php'); 

$link=$_GET['linkid'];

	$query="SELECT * FROM links WHERE id='$link'";
	$query_link = mysql_query($query, $main);
	$link_row = mysql_fetch_assoc($query_link);
	$destination = $link_row["link"];

	$clicker = "UPDATE links SET clicks=clicks+1 WHERE id='$link'";
	$query = mysql_query($clicker, $main);
	
//	echo $destination;

	header("Location: $destination");

}

?>
