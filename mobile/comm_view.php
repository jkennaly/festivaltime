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

echo "<div id=\"upcomingsmall\">";

for($i=1;$i<=4;$i++) {
switch ($i)
{
case "1":
//send a message
  $link = "comm_type.php?time=$basetime_s";
  $displaystring = "Send a message"; 
  break;
case "2":
//return to main
  $link = "mobile.php?time=$basetime_s";
  $displaystring = "Back to Main Screen";
  break;
case "3":
//rated requires more info
  $commtype=0;
  $link = "location_tracker.php?time=$basetime_s";
  $commstring = "";
  $displaystring = "Location Tracker";
  break;
case "4":
//empty
  $commtype=$i;
  $moreinfo = 0;
  $commstring = "";
  $displaystring = "empty";
  break;
case "5":
//other location near more info
  $commtype=$i;
  $moreinfo = 1;
  $commstring = "$ctime $uname is at ";
  $displaystring = "other location";
  break;
case "6":
//custom requires more info
  $commtype=$i;
  $moreinfo = 2;
  $commstring = "$ctime $uname "; 
  $displaystring = "custom message";
  break;
default:

} //Closes switch ($i)

echo "<a href=\"$link\">";
echo "<div class=\"band$i band\">

<p class=\"bandname\">".$displaystring."</p>";

echo "</div></a>";
} //Closes for($i=1;$i<=6;$i++)


?>
</div> <!--End of #upcomingsmall -->

<div id="commslarge">
<?php
//Get current comms data
$sql = "select commstring from comms order by id desc";
$result = mysql_query($sql, $main);
while($row = mysql_fetch_array($result)) {
	echo "<p>".$row['commstring']."</p>";
}
?>

<p><?php echo $user." is logged on."; ?><p>

</div> <!--end #comms -->

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
