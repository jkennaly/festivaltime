<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php include('../variables/variables.php'); ?>
<?php include('../includes/check_rights.php'); ?>
<?php session_start(); 
If(!empty($_REQUEST['s']) {
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

mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname) or die( "Unable to select database");
If(!empty($_GET['commstring_pre'])) $commstring_pre = mysql_real_escape_string($_GET['commstring_pre']);
$commstring = mysql_real_escape_string($_GET['commstring']);
$commtype = mysql_real_escape_string($_GET['commtype']);
If(!empty($_GET['fromuser'])) $fromuser = mysql_real_escape_string($_GET['fromuser']);
If(!empty($_GET['band'])) $band = mysql_real_escape_string($_GET['band']);
If($commtype == 6) $commstring = $commstring_pre." ".$commstring;

/*
echo "Current user is ".$_SESSION['user'];
echo "<br>Band you are announcing is ".$band_row['name'];
echo "<br>Stage you are announcing is ".$band_row['stage'];
echo "<br>Start time you are announcing is ".$stime;
echo "<br>End time you are announcing is ".$etime;
echo "<br>Current time is ".$ctime;
*/
echo "<div id=\"messageconfirm\">";
echo "<br>Message will display as shown on the following line:<br>";
echo "<p>".$commstring."<p>";
echo "</div>";

?>
<form id="confirmcommform" action="mobile.php" method="post">
<input type="hidden" name="commstring" value="<?php echo $commstring; ?>">
<input type="hidden" name="fromuser" value="<?php echo $fromuser; ?>">
<input type="hidden" name="band" value="<?php echo $band; ?>">
<input type="submit" name="s" class="mobilebutton" value="Confirm">
<input type="submit" name="s" class="mobilebutton" value="Cancel">

</form>
<?php


mysql_close();
}
}
else{
?>
<p>

You do not have sufficient access rights to view this page.

</p>

<?php 
}

?>
</div> <!-- end #content -->
</html>
