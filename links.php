<?php
include('variables/variables.php');
include('includes/check_rights.php');
session_start(); 

$right_required = "FollowLink";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

$link = htmlspecialchars($_GET["linkid"]);
	If(!empty($_SESSION['fest'])){

include('variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


 include('variables/page_variables.php'); 

}



	$query="SELECT * FROM links WHERE id='$link'";
	$query_link = mysql_query($query, $main);
	$link_row = mysql_fetch_assoc($query_link);
	$destination = $link_row["link"];

	$clicker = "UPDATE links SET clicks=clicks+1 WHERE id='$link'";
	$query = mysql_query($clicker, $main);

	header("Location: $destination");

}

?>
