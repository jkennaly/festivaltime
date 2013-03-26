<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="styles/style.css" media="screen" />
<?php

session_start(); 

include('variables/variables.php');

$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

function isInteger($input){
    return(ctype_digit(strval($input)));
}

If(isset($_GET['fest']) && isInteger($_GET['fest'])) {
	$_SESSION['fest'] = $_GET['fest'];
} 
 include('includes/check_rights.php');
 include('includes/content/blocks/database_functions.php'); 
include('includes/content/blocks/other_functions.php'); 


If(!empty($_SESSION['fest'])){

include('variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");



 include('variables/page_variables.php'); 

}
?>

<title><?php echo $sitename ?></title>

<script type="text/javascript" src="includes/js/docflow.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

</head>

	<body>

		<div id="wrapper">
<?php // var_dump($_SESSION); 

 include('includes/header.php'); 

 include('includes/nav.php'); 

If(!empty($_SESSION['fest']) && $_SESSION['fest']>0){

	include('includes/content.php'); 

} else include('includes/unselected.php');

 include('includes/footer.php'); ?>

		</div> <!-- End #wrapper -->

	</body>
<?php
If(!empty($main)) mysql_close($main);
If(!empty($master)) mysql_close($master);
?>
</html>

