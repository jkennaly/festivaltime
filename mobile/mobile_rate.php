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


/*
$band = $_GET['band'];


$sql = "select bands.start as stime, bands.end as etime, stages.name as stage, bands.id as id, bands.name as name from bands, stages where bands.id='$band'";
$res = mysql_query($sql, $main);
$band_row = mysql_fetch_array($res);
$name=$band_row['name'];
$band=$band_row['id'];
$stage=$band_row['stage'];
*/
$query="SELECT id, username FROM Users WHERE username='".$_SESSION['user']."'";
$query_user = mysql_query($query, $master);
$user_row = mysql_fetch_array($query_user);
$user = $user_row['id'];
$uname = $user_row['username'];
$basetime_s=mysql_real_escape_string($_GET['time']);
$commstring_inc=mysql_real_escape_string($_GET['commstring']);

/*
$stime= substr($band_row['stime'], 11, 5);
$etime= substr($band_row['etime'], 11, 5);
*/
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

/*
for($i=1;$i<=6;$i++) {
switch ($i)
{
case "1":
//will be at
  $commtype=$i;
  $moreinfo = 0;
//  $commstring = "$ctime $uname will be at $name $stage $stime $etime";
  $displaystring = "empty"; 
  break;
case "2":
//is at
  $commtype=$i;
  $moreinfo = 0;
//  $commstring = "$ctime $uname is at $name $stage $stime $etime";
  $displaystring = "empty";
  break;
case "3":
//rated requires more info
  $commtype=$i;
  $moreinfo = 3;
  $commstring = "$ctime $uname rated ";
  $displaystring = "Rate a band";
  break;
case "4":
//leaving
  $commtype=$i;
  $moreinfo = 0;
//  $commstring = "$ctime $uname leaving $name $stage $stime $etime";
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

If($moreinfo == 0) echo "<a href=\"comm_confirm.php?commtype=$commtype&commstring=$commstring\">";
If($moreinfo == 1) echo "<a href=\"more_info.php?commtype=$commtype&commstring=$commstring\">";
If($moreinfo == 2) echo "<a href=\"custom_comm.php?commtype=$commtype&commstring=$commstring\">";
If($moreinfo == 3) echo "<a href=\"mobile_rate.php?commtype=$commtype&commstring=$commstring\">";
echo "<div class=\"band$i band\">

<p class=\"bandname\">".$displaystring."</p>";

echo "</div></a>";
} //Closes for($i=1;$i<=6;$i++)

*/
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
