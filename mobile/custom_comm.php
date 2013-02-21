<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php include('../variables/variables.php'); ?>
<?php include('../variables/page_variables.php'); ?>
<?php include('../includes/check_rights.php'); ?>
<?php session_start(); ?>

<title>Gametime Comms Confirmation</title>

</head>

	<body>

<div id="content">

<?php
$right_required = "SendComms";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
If(!empty($_GET['commstring']) && ($_GET['commtype'] == 6)) {

mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname) or die( "Unable to select database");

$commstring = mysql_real_escape_string($_GET['commstring']);
$commtype = mysql_real_escape_string($_GET['commtype']);
$fromuser = mysql_real_escape_string($_GET['fromuser']);


echo "<div id=\"messageconfirm\">";
echo "<br>Message will display as shown on the following line:<br>";
echo "<p>".$commstring."<p>";
echo "</div>";

?>
<form id="custom" action="comm_confirm.php" autofocus method="get">
<textarea name="commstring" rows="5" style="width:100%;"></textarea>
<input type="hidden" name="commstring_pre" value="<?php echo $commstring; ?>">
<input type="hidden" name="commtype" value="6">
<input type="hidden" name="fromuser" value="<?php echo $fromuser; ?>">
<input type="hidden" name="band" value="0">
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
