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


?>

<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php 
session_start(); 

include('../variables/variables.php');

$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

function isInteger($input){
    return(ctype_digit(strval($input)));
}

If(!empty($_GET['fest']) && isInteger($_GET['fest'])) {
	$_SESSION['fest'] = $_GET['fest'];
} 
 include('../includes/check_rights.php');
 include('../includes/content/blocks/database_functions.php'); 
include('../includes/content/blocks/other_functions.php'); 

If(!empty($_SESSION['fest'])){

include('../variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


 include('../variables/page_variables.php'); 
}
 
If(!empty($_REQUEST['s'])) {
If($_REQUEST['s'] == "Cancel" ) header("Location: mobile.php");
} //Closes If(!empty($_REQUEST['s'])
?>

<title>Gametime Comms Confirmation</title>

</head>

	<body>

<div id="content">

<?php
$right_required = "SendComms";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
If(!empty($_GET['commstring']) && !empty($_GET['commtype'])) {


If(!empty($_GET['commstring_pre'])) $commstring_pre = mysql_real_escape_string($_GET['commstring_pre']);
$commstring = mysql_real_escape_string($_GET['commstring']);
$commtype = mysql_real_escape_string($_GET['commtype']);
If(!empty($_GET['fromuser'])) $fromuser = mysql_real_escape_string($_GET['fromuser']);
If(empty($_GET['fromuser'])) $fromuser = $user;
If(!empty($_GET['band'])) $band = mysql_real_escape_string($_GET['band']);
If(empty($_GET['band'])) $band = 0;
If(!empty($_GET['location'])) $location = mysql_real_escape_string($_GET['location']);
If(empty($_GET['location'])) $location = 0;
If($commtype == 6) $commstring = $commstring_pre." ".$commstring;


echo "<div id=\"messageconfirm\">";
echo "<br>Message will display as shown on the following line:<br>";
echo "<p>".$commstring."<p>";

If($commtype == 3) {
	$rate_comment = mysql_real_escape_string($_GET['rate_comment']);
	$rating = mysql_real_escape_string($_GET['rating']);
	echo "<br>The following comment will be recorded in the database but not broadcast:<br>";
	echo "<p>".$rate_comment."<p>";
} else {$rate_comment = 0; $rating=0;}
echo "</div>";

?>
<form id="confirmcommform" action="mobile.php" method="post">
<input type="hidden" name="commstring" value="<?php echo $commstring; ?>">
<input type="hidden" name="rate_comment" value="<?php echo $rate_comment; ?>">
<input type="hidden" name="commtype" value="<?php echo $commtype; ?>">
<input type="hidden" name="fromuser" value="<?php echo $fromuser; ?>">
<input type="hidden" name="band" value="<?php echo $band; ?>">
<input type="hidden" name="rating" value="<?php echo $rating; ?>">
<input type="hidden" name="location" value="<?php echo $location; ?>">
<input type="submit" name="s" class="mobilebutton" value="Confirm">
<input type="submit" name="s" class="mobilebutton" value="Cancel">

</form>
<?php


}
}
else{
?>
<p>

You do not have sufficient access rights to view this page.

</p>

<?php 
}
If(!empty($main)) mysql_close($main);
If(!empty($master)) mysql_close($master);

?>
</div> <!-- end #content -->
</html>
