<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php 

include("../variables/variables.php");

$main = mysql_connect($dbhost,$dbuser,$dbpw);
$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");
@mysql_select_db($master_db, $master) or die( "Unable to select master database");


 session_start(); 
 include('../variables/page_variables.php');  
 include('../includes/check_rights.php');

?>


<title>Gametime Comms Confirmation</title>

</head>

	<body>


<div id="upcoming">

<?php
$right_required = "SendComms";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){




$commstring_inc = mysql_real_escape_string($_GET['commstring']);
$commtype = mysql_real_escape_string($_GET['commtype']);
If(!empty($_GET['band'])) {$band = mysql_real_escape_string($_GET['band']);}
	else $band = 0;
If(!empty($_GET['fromuser'])) {$fromuser = mysql_real_escape_string($_GET['fromuser']);}
	else $fromuser = 0;

for($i=1;$i<=6;$i++) {

switch ($commtype)
{
case "1":
/*
//will be at
  $commtype=$i;
  $moreinfo = 0;
  $commstring = "$ctime $uname will be at $name $stage $stime $etime";
  $displaystring = "will be at"; 
*/
  break;
case "2":
//is at
/*
  $commtype=$i;
  $moreinfo = 0;
  $commstring = "$ctime $uname is at $name $stage $stime $etime";
  $displaystring = "is at";
*/
  break;
case "3":
//rated requires more info
If( $i <6){
  $commstring = $commstring_inc." $i";
  $displaystring = "$i";
  $rating = $i;
} else {
  $commstring = $commstring_inc."";
  $displaystring = "Unrated";
  $rating = 0;
}
  break;
case "4":
//leaving
/*
  $commtype=$i;
  $moreinfo = 0;
  $commstring = "$ctime $uname leaving $name $stage $stime $etime";
  $displaystring = "leaving";
*/
  break;
case "5":
//other location more info
  switch ($i)
{
case "1":
  $commstring = $commstring_inc." Sahara Beer Tent"; 
  $displaystring = "Sahara Beer Tent";
  break;
case "2":
  $commstring = $commstring_inc." Main/Outdoor Beer Tent"; 
  $displaystring = "Main/Outdoor Beer Tent";
  break;
case "3":
  $commstring = $commstring_inc." Main VIP"; 
  $displaystring = "Main VIP";
  break;
case "4":
  $commstring = $commstring_inc." Sahara VIP"; 
  $displaystring = "Sahara VIP";
  break;
case "5":
  $commstring = $commstring_inc." lockers"; 
  $displaystring = "lockers";
  break;
case "6":
  $commstring = $commstring_inc." Shuttle Pickup"; 
  $displaystring = "Shuttle Pickup";
  break;

} //Closes switch ($i)
  break;
default:

} //Closes switch ($i)

If($i != 3) echo "<a href=\"comm_confirm.php?commtype=$commtype&commstring=$commstring&fromuser=$fromuser\">";
If($commtype == 3) echo "<a href=\"rate_message.php?commtype=$commtype&commstring=$commstring&fromuser=$fromuser&band=$band&rating=$rating\">";

echo "<div class=\"band$i band\">

<p class=\"bandname\">".$displaystring."</p>";

echo "</div></a>";
} //Closes for($i=1;$i<=6;$i++)
?>
</div> <!-- end #upcoming -->

<?php



echo "<div id=\"comms\">";
echo "<br>Message so far:<br>";
echo "<p>".$_GET['commstring']."<p>";
echo "</div> <!--end #comms -->";





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
