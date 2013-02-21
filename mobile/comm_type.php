<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php include('../variables/variables.php'); ?>
<?php include('../includes/check_rights.php'); ?>
<?php session_start(); ?>

<title>Gametime Comms Confirmation</title>

</head>

	<body>

<div id="content">

<?php
$right_required = "SendComms";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname) or die( "Unable to select database");

/*
$band = $_GET['band'];


$sql = "select bands.start as stime, bands.end as etime, stages.name as stage, bands.id as id, bands.name as name from bands, stages where bands.id='$band'";
$res = mysql_query($sql);
$band_row = mysql_fetch_array($res);
$name=$band_row['name'];
$band=$band_row['id'];
$stage=$band_row['stage'];
*/
$query="SELECT id, username FROM Users WHERE username='".$_SESSION['user']."'";
$query_user = mysql_query($query);
$user_row = mysql_fetch_array($query_user);
$user = $user_row['id'];
$uname = $user_row['username'];
$basetime_s=mysql_real_escape_string($_GET['time']);

/*
$stime= substr($band_row['stime'], 11, 5);
$etime= substr($band_row['etime'], 11, 5);
*/
$ctime= strftime("%H:%M");

echo "<div id=\"upcoming\">";

for($i=1;$i<=6;$i++) {
switch ($i)
{
case "1":
//will be at
  $commtype=$i;
  $moreinfo = 0;
  $commstring = "";
  $displaystring = "empty"; 
  break;
case "2":
//is at
  $commtype=$i;
  $moreinfo = 0;
  $commstring = "";
  $displaystring = "empty";
  break;
case "3":
//rated requires more info
  $commtype=$i;
  $moreinfo = 3;
  $commstring = "$ctime $uname rated ";
  $displaystring = "rate a band";
  break;
case "4":
//leaving
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
  $displaystring = "I am at";
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

If($moreinfo == 0) echo "<a href=\"comm_confirm.php?commtype=$commtype&commstring=$commstring&fromuser=$user&band=0\">";
If($moreinfo == 1) echo "<a href=\"more_info.php?commtype=$commtype&commstring=$commstring&fromuser=$user&band=0\">";
If($moreinfo == 2) echo "<a href=\"custom_comm.php?commtype=$commtype&commstring=$commstring&fromuser=$user&band=0\">";
If($moreinfo == 3) echo "<a href=\"mobile_rate.php?commtype=$commtype&commstring=$commstring&time=$basetime_s&fromuser=$user&band=0\">";
echo "<div class=\"band$i band\">

<p class=\"bandname\">".$displaystring."</p>";

echo "</div></a>";
} //Closes for($i=1;$i<=6;$i++)


?>
</div> <!--End of #upcoming -->

<div id="comms">
<?php
//Get current comms data
$sql = "select commstring from comms order by id desc limit 0,6";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
	echo "<p>".$row['commstring']."</p>";
}
?>

<p><?php echo $user." is logged on."; ?><p>

</div> <!--end #comms -->
</div> <!--end #content -->

<?php 
mysql_close();
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
