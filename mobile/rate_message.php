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

If(!empty($_SESSION['fest'])){

include('../variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


 include('../variables/page_variables.php'); 
 include('../includes/content/blocks/database_functions.php'); 
}

 ?>

<title>Gametime Comms Confirmation</title>

</head>

	<body>

<div id="content">

<?php
$right_required = "SendComms";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
If(!empty($_GET['commstring']) && ($_GET['commtype'] == 3)) {


$commstring = mysql_real_escape_string($_GET['commstring']);
$commtype = mysql_real_escape_string($_GET['commtype']);
$fromuser = mysql_real_escape_string($_GET['fromuser']);
$band = mysql_real_escape_string($_GET['band']);
$rating = mysql_real_escape_string($_GET['rating']);


echo "<div id=\"messageconfirm\">";
echo "<br>Message will display as shown on the following line:<br>";
echo "<p>".$commstring."<p>";
echo "</div>";

?>
<form id="custom" action="comm_confirm.php" autofocus method="get">
<textarea name="rate_comment" rows="5" style="width:100%;"></textarea>
<input type="hidden" name="commstring" value="<?php echo $commstring; ?>">
<input type="hidden" name="commtype" value="<?php echo $commtype; ?>">
<input type="hidden" name="fromuser" value="<?php echo $fromuser; ?>">
<input type="hidden" name="band" value="<?php echo $band; ?>">
<input type="hidden" name="rating" value="<?php echo $rating; ?>">
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
