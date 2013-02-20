<?php
include('variables/variables.php');
include('includes/check_rights.php');
session_start(); 

$right_required = "FollowLink";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

$link = htmlspecialchars($_GET["linkid"]);

	mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");


	$query="SELECT * FROM links WHERE id='$link'";
	$query_link = mysql_query($query);
	$link_row = mysql_fetch_assoc($query_link);
	$destination = $link_row["link"];

	$clicker = "UPDATE links SET clicks=clicks+1 WHERE id='$link'";
	$query = mysql_query($clicker);

	header("Location: $destination");

mysql_close();
}

?>
