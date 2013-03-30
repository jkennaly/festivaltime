<?php
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
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

/*
$stime= substr($band_row['stime'], 11, 5);
$etime= substr($band_row['etime'], 11, 5);
*/
$ctime= strftime("%H:%M");

echo "<div id=\"upcomingsmall\">";

for($i=1;$i<=2;$i++) {
switch ($i)
{
/*
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
*/
case "1":
//other location near more info
  $commtype=5;
  $moreinfo = 1;
  $commstring = "$ctime $uname is at ";
  $displaystring = "I am at";
  break;
case "2":
//custom requires more info
  $commtype=6;
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
</div> <!--End of #upcomingsmall -->

<div id="commslarge">
<?php
//Get current comms data
$sql = "select commstring from comms where commtype!='2' AND commtype!='5' AND commtype!='0' order by id desc";
$result = mysql_query($sql, $main);
while($row = mysql_fetch_array($result)) {
	echo "<p>".$row['commstring']."</p>";
}
?>


</div> <!--end #commslarge -->
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
