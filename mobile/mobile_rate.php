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


$query="SELECT id, username FROM Users WHERE username='".$_SESSION['user']."'";
$query_user = mysql_query($query, $master);
$user_row = mysql_fetch_array($query_user);
$user = $user_row['id'];
$uname = $user_row['username'];
$basetime_s=mysql_real_escape_string($_GET['time']);
$commstring_inc=mysql_real_escape_string($_GET['commstring']);


$ctime= strftime("%H:%M");

echo "<div id=\"upcoming\">";

$sql = "select * from bands where sec_start < '$basetime_s' and sec_end > '0' order by sec_end desc limit 0,6";
$res = mysql_query($sql, $main);
$i=1;
while($row = mysql_fetch_array($res)) {
	$commstring = $commstring_inc." ".$row['name']." with a ";
	echo "<a href=\"more_info.php?band=".$row['id']."&time=$basetime_s&commtype=3&commstring=$commstring&fromuser=$user\"><div class=\"band$i band\"><p class=\"bandname\">".$row['name']."</p></div></a>";
	$i++;
}


?>
</div> <!--End of #upcoming -->

<div id="comms">
<h1>Choose a band to rate</h1>

</div> <!--end #comms -->
</div> <!--end #content -->

<?php 
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
