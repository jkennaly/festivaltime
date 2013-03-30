#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile2.css" media="screen" />

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

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//If there is an incoming Submitted commstring, process it, if it is not cancelled
If(!empty($_POST)) {
If($_POST['s'] == "Confirm") {
	$commstring = $_POST['commstring'];
	$commtype = $_POST['commtype'];
	$fromuser = $_POST['fromuser'];
	$band = $_POST['band'];
	$location = $_POST['location'];
	//Verify string is not already in db
	$query = "select * from comms where commstring='$commstring'";
	$check = mysql_query($query, $main);
	If(mysql_num_rows($check) == 0) {
		$query = "insert into comms (commstring, displayed, fromuser, band, commtype, location) values ( '$commstring', '0', '$fromuser', '$band', '$commtype', '$location' ); ";
		$upd = mysql_query($query, $main);
	} //Closes If(mysql_num_rows($check) == 
	If($commtype == 3) {
	$rate_comment = $_POST['rate_comment'];
	$rating = $_POST['rating'];
	//If rating is not 6 automatically send a message that the user at the show
	$loc_query = "select * from comms where commtype='2' and fromuser='$fromuser' and band='$band'";
	$check = mysql_query($loc_query, $main);
	If(mysql_num_rows($check) == 0) {
		$query = "insert into comms (displayed, fromuser, band, commtype) values ( '0', '$fromuser', '$band', '2' ); ";
		$upd = mysql_query($query, $main);
	} //Closes If(mysql_num_rows($check) == 
		$query = "insert into live_rating (comment, rating, user, band) values ( '$rate_comment', '$rating', '$fromuser', '$band' ); ";
		$upd = mysql_query($query, $main);
		$query = "insert into live_rating (comment, rating, user, band, festival) values ( '$rate_comment', '$rating', '$fromuser', '$band_master_id', '$fest_id' ); ";
		$upd = mysql_query($query, $master);
	} // Closes If($commtype == 3)
} // Closes If($_POST['s'] == "Confirm")
}// Closes If(!empty($_POST) 
//Finished processing commstring


/***************************************************************
*
*		Begin Time-Decision between sim and real
*
*****************************************************************/

//Determine whether to use real time or fake time. If within one day plus or minus of any festival day, use real time. Otherwise create a fake date to use

//Find the max and min php time asscosated with the dates in the days table

$sql = "select date from days";
$res = mysql_query($sql, $main);
$fest_date_min = 0;
$fest_date_max=0;
while($row=mysql_fetch_array($res)) {
	$fest_date_temp=strtotime($row['date']);
	If($fest_date_temp>$fest_date_max || $fest_date_max==0 ) $fest_date_max=$fest_date_temp;
	If($fest_date_temp<$fest_date_min ||  $fest_date_min==0 ) $fest_date_min=$fest_date_temp;
}

//Push the fest date one day forward for max to encompass that entire day
$fest_date_max = $fest_date_max + 3600*24;

//Find the timespan covered by the fest
$fest_span = $fest_date_max - $fest_date_min;

//Find one day before to one day after the fest
$fest_pre = $fest_date_min -3600*24;
$fest_post = $fest_date_max +3600*24;

//If within a day plus or minus of the fest, use real time

If(time()>$fest_pre && time()<$fest_post) {
	$basetime = strftime("%Y-%m-%d %H:%M");
	$basetime_s = time();
	$time_is_simmed = 0;
} else {
	//Calculate a simulated elapsed time since fest started, plus correction to make simulated times convenient
	$correction = 67*3600; //+67 hours
	$basetime_s = (time()+ $correction) % ($fest_span) + $fest_date_min ;
	//Comment the following line to unfreeze time
//	$basetime_s = 1365979398;
	$basetime = strftime("%a %Y-%m-%d %H:%M", $basetime_s);
	$time_is_simmed = 1;
} //Closes If($time()>$fest_pre && $time()<$fest_post)

/***************************************************************
*
*		End Time-Decision between sim and real
*
*****************************************************************/

//End determination of fake or real time

$min_percent = 50;
$max_min = 30;
$hours_out = 2;
$time_to_reach_stage = 10;




$t_10min = $basetime_s+60*$time_to_reach_stage;
$t_2hr = $basetime_s+60*60*$hours_out;

$sql = "select * from bands where (sec_end> '$t_10min' and sec_end < '$t_2hr') OR (sec_start > '$t_10min' and sec_start < '$t_2hr')";



$res=mysql_query($sql, $main);
while($row=mysql_fetch_array($res)) {
	$active_band[] = $row;
}//Closes while($row=mysql_fetch_array($res)
If(!empty($active_band)) {
$i=0;
foreach($active_band as $v) {
	$uscore = uscoref($v['id'], $user, $avg_rating, $main);
//	echo "Band id is ".$v['id']."<br>";
//	echo "Band name is ".$v['name']."<br>";
//	echo "Band start is ".$v['start']."<br>";
//	echo "Static score for this band is $uscore<br>";
//	echo "Band end is ".$v['end']."<br>";
	$total_set = $v['sec_end'] - $v['sec_start'];
//If the set time is calculated at 0, simulate a set time of 45 min.
	If($total_set == 0) $total_set = 45*60;
	$time_to_end = $v['sec_end'] - $t_10min;
	If($time_to_end>$total_set) $time_to_end = $total_set;
	$time_to_start = $v['sec_start'] - $t_10min;
	$time_remaining = $time_to_end/$total_set;
	$time_remaining_percent = $time_remaining*100;
//	echo "$time_remaining_percent% of the set is left to play.<br>";
	If ($time_to_start<0) $time_to_start = 0;
	$time_to_start_min=$time_to_start/60;
//	echo "Band will start in ".$time_to_start_min." min.<br>";

/***************************************************************
*
*		Begin Scoring Calculation
*
*****************************************************************/


	If( $time_to_start_min < $max_min && $time_remaining_percent > 50){ $scoring_factor = 1; } else {
	If( $time_to_start_min >= $max_min ) $scoring_factor = 1/($max_min - 60*$hours_out)*$time_to_start_min - ($max_min/($max_min - 60*$hours_out))+1;
	If(  $time_remaining_percent <= 50) $scoring_factor = ($time_remaining_percent*2)/100;
	If( $scoring_factor > 1 ) $scoring_factor =1;
}
	$adjusted_score = $uscore*$scoring_factor;

//	echo "Adjusted score for this band is $adjusted_score.<br>";


/***************************************************************
*
*		End Scoring Calculation
*
*****************************************************************/


	$scored_band[$i]['id']=$v['id'];
	$scored_band[$i]['score']=$adjusted_score;
	$scored_band[$i]['name']=$v['name'];
	$scored_band[$i]['scoring_factor']=$scoring_factor;
	$scored_band[$i]['basescore']=$uscore;
	$scored_band[$i]['tts']=$time_to_start_min;
	$scored_band[$i]['mm']=$max_min;
	$scored_band[$i]['trp']=$time_remaining_percent;
	
//	echo "Scored band is ".$scored_band[$i]."<br><br>";
	$i++;
	
} //Closes foreach($active_band as $v)
} //Closes If(!empty($active_band)





$sql="select max(id) as rows from bands";
$res = mysql_query($sql, $main);
$num = mysql_fetch_assoc($res);

If(!empty($_POST['user'])) $scoreuser = $_POST['user'];
If(empty($_POST['user'])) $scoreuser = $user;

$sql = "select avg(rating) as average from ratings where ratings.user='$scoreuser'";

$res = mysql_query($sql, $main);
$arr = mysql_fetch_assoc($res);

$useravgrating = $arr['average'];

$sql = "select username from Users where id='$scoreuser'";
$res = mysql_query($sql, $master);
$user_row = mysql_fetch_array($res);
$scoreusername = $user_row['username'];

//echo "Showing band scores for user ".$scoreusername."<br>";
//echo "The average rating for this user is ".$useravgrating."<br>";
//echo "The average rating for all bands by all users is ".$avg_rating."<br>";
//echo "Your average rating for all bands is ".$uavg_rating."<br>";
/*
foreach($scored_band as $v){
	echo "ID is ".$v['id']." and score is ".$v['score']." for ".$v['name']."<br>";
}
*/
/*****************************************************************
* 
*                        Begin processing of band info
*
******************************************************************/



//Prep data. This only occurs if $i is defined, which only happens if ther eis data to display

If(!empty($i)) {
for ($index=1; $index<=$i; $index++)
  {
	$temp=$index-1;
//	$sql="select name from bands where id='".$scored_band[$temp]['id']."'";
	$uscoreall[$scored_band[$temp]['id']] = $scored_band[$temp]['score'];
	$basescoreall[$scored_band[$temp]['id']] = $scored_band[$temp]['basescore'];
	$sfeall[$scored_band[$temp]['id']] = $scored_band[$temp]['scoring_factor'];
	$scored_band[$i]['tts']=$time_to_start_min;
	$scored_band[$i]['mm']=$max_min;
	$scored_band[$i]['trp']=$time_remaining_percent;
	
  	$j=$i;
  }




arsort($uscoreall);

//echo "<table><tr><th>Band ID</th><th>Band Name</th><th>Your Rating</th><th>Average Rating</th><th>Starts in</th><th>Ends in</th></tr>";



/***************************************************************
*
* If desired, instead display next bands or current bands on each stage
*
****************************************************************/

//Find the user setting
$stage_display_sql="select value from user_settings_".$user." where item='Gametime View'";
// echo $stage_display_sql;
$stage_display_res=mysql_query($stage_display_sql, $master);
$stage_disp=mysql_fetch_array($stage_display_res);
$stage_display=$stage_disp['value'];
If(!empty($_GET['stagedisp'])) {
If($_GET['stagedisp']=="current") $stage_display = 1;
If($_GET['stagedisp']=="best") $stage_display = 2;
} //Closes If(!empty($_GET['stagedisp'])

If($stage_display == 1) {

	unset($uscoreall);
	//Get all the stages
	$stages_display_sql="select id from stages";
	$stages_display_res=mysql_query($stages_display_sql, $main);
	while($row=mysql_fetch_array($stages_display_res)) {
		$stage_select_sql="select id from bands where stage='".$row['id']."' and sec_end>$basetime_s order by sec_start asc limit 1";
		$stage_select_res=mysql_query($stage_select_sql, $main);
		$stage_select=mysql_fetch_array($stage_select_res);
		$uscoreall[$stage_select['id']]=1;
	} //Closes while($row=mysql_fetch_array($stages_display_res)

} //Closes If($stage_display == 1)

reset($uscoreall);
$elements=count($uscoreall);
If($elements>6) $elements = 6;
//$j
for ($i=1; $i<=$elements; $i++)
  {
	$mband=key($uscoreall);
	$sql="select b.name as name, b.id as id, r.rating as rating, sec_start, sec_end, stage, genre from bands as b left join ratings as r on r.band=b.id and r.user='$user' where b.id='$mband'";
	$res = mysql_query($sql, $main);
	$row= mysql_fetch_array($res);
	$sql_avg = "select avg(rating) as avg from ratings where band='$mband'";
	$res_avg = mysql_query($sql_avg, $main);
	$row_avg= mysql_fetch_array($res_avg);
	$sql_stage = "select stages.id as stage from stages, bands where stages.id=bands.stage and bands.id='$mband'";
	$res_stage = mysql_query($sql_stage, $main);
	$row_stage= mysql_fetch_array($res_stage);
	//Determine how many group members are at the show
	$num_friends=0;
	$sql_friends1 = "SELECT Max(id) as id, fromuser FROM `comms` where band='$mband' and fromuser!='$user' and (commtype='2' or commtype='5') group by fromuser";
	If($i == 3) $test = $sql_friends1."<br>";
	$res_friends1 = mysql_query($sql_friends1, $main);	
	while($row_friends1= mysql_fetch_array($res_friends1)) {
		$sql_friends2 = "SELECT Max(id) as max FROM `comms` where fromuser='".$row_friends1['fromuser']."' AND (commtype='2' OR commtype='5')";
//	echo $sql_friends2."<br>";
		$res_friends2 = mysql_query($sql_friends2, $main);
		$row_friends2= mysql_fetch_array($res_friends2);
		If($row_friends1['id'] == $row_friends2['max']) $num_friends=$num_friends+1;
	} //Closes while($row_friends1= my...
	$starts_in = ($row['sec_start'] - $basetime_s )/60;
	$starts_in = round( $starts_in, 0 );
	If($starts_in<=0) {
		unset($starts_in);
		$starts_in = "Now";
	} else {
		$starts_in = $starts_in;
	}// Closes else If($starts_in<=0)
	$ends_in = ($row['sec_end'] - $basetime_s )/60;
	$ends_in = round( $ends_in, 0 );
//	echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['rating']."</td><td>".$row_avg['avg']."</td><td>".$starts_in."</td><td>".$ends_in."</td></tr>";
	next($uscoreall);
	$gametime_band[$i]['id'] = $row['id'];
	$gametime_band[$i]['name'] = $row['name'];
	$gametime_band[$i]['rating'] = $row['rating'];
	$gametime_band[$i]['stage'] = $row_stage['stage'];
	$gametime_band[$i]['genre'] = getGname($master, $row['genre']);
	$gametime_band[$i]['avg'] = round( $row_avg['avg'], 1);
	$gametime_band[$i]['starts'] = $starts_in;
	$gametime_band[$i]['ends'] = $ends_in;
	$gametime_band[$i]['friends'] = $num_friends;
	switch ( $gametime_band[$i]['stage'] ) {
		case "1":
		$gametime_band[$i]['img3'] = "../includes/images/main.png";
		break;
		case "2":
		$gametime_band[$i]['img3'] = "../includes/images/outdoor.png";
		break;
		case "3":
		$gametime_band[$i]['img3'] = "../includes/images/gobi.png";
		break;
		case "4":
		$gametime_band[$i]['img3'] = "../includes/images/mojave.png";
		break;
		case "5":
		$gametime_band[$i]['img3'] = "../includes/images/sahara.png";
		break;
		default:
		$gametime_band[$i]['img3'] = "../includes/images/black.png";
		break;
	} //Closes switch ( $gametime_band[$i]['rstage'] )
	switch ( $gametime_band[$i]['friends'] ) {
		case "0":
		$gametime_band[$i]['img2'] = "../includes/images/black.png";
		break;
		case "1":
		$gametime_band[$i]['img2'] = "../includes/images/person.jpg";
		break;
		default:
		$gametime_band[$i]['img2'] = "../includes/images/people.jpg";
		break;
	} //Closes switch ( $gametime_band[$i]['friends'] )
	switch ( $gametime_band[$i]['rating'] ) {
		case "1":
		$gametime_band[$i]['img1'] = "../includes/images/1.png";
		break;
		case "2":
		$gametime_band[$i]['img1'] = "../includes/images/2.png";
		break;
		case "3":
		$gametime_band[$i]['img1'] = "../includes/images/3.png";
		break;
		case "4":
		$gametime_band[$i]['img1'] = "../includes/images/4.png";
		break;
		case "5":
		$gametime_band[$i]['img1'] = "../includes/images/5.png";
		break;
		default:
		$gametime_band[$i]['img1'] = "../includes/images/black.png";
		break;
	} //Closes switch ( $gametime_band[$i]['ratings'] )
  
  
  }//Closes for ($i=1; $i<=$elements; $i++)

} // Closes If(!empty($i)


/*****************************************************************
* 
*                        End processing of band info
*
******************************************************************/

?>
<title>Gametime</title>

</head>
<body>

<div id="upcoming">

<?php
//Display best bet data. This only occurs if $i is defined, which only happens if ther eis data to display, and the user setting is to display the data, or the button to display best bet has been pressed.




If(!empty($i)) {

for($i=1;$i<=$elements;$i++) {

	echo "<a href=\"mobile_detail.php?band=".$gametime_band[$i]['id']."&time=$basetime_s\"><div class=\"band$i band\"><p class=\"bandname\">".$gametime_band[$i]['name']."</p>";

	echo "<div class=\"rate_image\"><img src=\"".$gametime_band[$i]['img1']."\" alt=\"".$gametime_band[$i]['rating']."\" >";

	If($gametime_band[$i]['img1'] == "../includes/images/black.png") echo "<h2>".$gametime_band[$i]['genre']."</h2>";
	
	echo "</div>";

	echo "<img src=\"".$gametime_band[$i]['img2']."\" alt=\"".$gametime_band[$i]['friends']." people\" >";

	echo "<img src=\"".$gametime_band[$i]['img3']."\" alt=\"".$gametime_band[$i]['stage']." stage\" >";

//	echo "<p><dl class=\"details\">";

//	echo "<dd>".$gametime_band[$i]['stage']."</dd>";

//	If($gametime_band[$i]['starts'] != "Playing Now") echo "<h3>Starts In: </h3>";
	echo "<h1>".$gametime_band[$i]['starts'];

	If($gametime_band[$i]['starts']=="Now") echo " (".$gametime_band[$i]['ends'].")";

	echo "</h1>";

//	echo "<dt>Ends In: </dt><dd>".$gametime_band[$i]['ends']."</dd>";

//	echo "</dl></p>";

	echo "</div></a>";
}

} // Closes If(!empty($i)
?>
</div>

<div id="location">
<p><?php // echo "Current date/time is $basetime"; If($time_is_simmed == 1) echo " Time is simulated. ";?><p>

<?php
$ctime= strftime("%H:%M");
  $commtype=3;
  $uname = getUname($master, $user);
  $commstring = "$ctime $uname rated ";
echo "<a href=\"mobile_rate.php?commtype=$commtype&commstring=$commstring&time=$basetime_s&fromuser=$user&band=0\">";
?>
<img src="../includes/images/rate2.png">
</a>
<?php
/*
//Get current comms data
$sql = "select commstring from comms order by id desc limit 0,6";
$result = mysql_query($sql, $main);
while($row = mysql_fetch_array($result)) {
	echo "<p>".$row['commstring']."</p>";
}
*/



echo "<a href=\"comm_type.php?time=$basetime_s\">";
$commstring = "$ctime $uname is at ";
?>


<img src="../includes/images/messages.png">
</a>

<a href="more_info.php?commtype=5&commstring=<?php echo $commstring; ?>&fromuser=<?php echo $user; ?>&band=0">
<img src="../includes/images/places.png">
</a>
<?php 
If(empty($stage_display)) $stage_display = 2;
If($stage_display == 1) echo "<a href=\"mobile.php?stagedisp=best\"><img src=\"../includes/images/best.png\"></a>";
If($stage_display == 2) echo "<a href=\"mobile.php?stagedisp=current\"><img src=\"../includes/images/current.png\"></a>";

include "location_tracker_inlay.php"; ?>
</div> <!--end #location -->

<div id="comms">
<?php
//Get current comms data
$sql = "select commstring from comms where commtype!='2' AND commtype!='5' order by id desc limit 20";
$result = mysql_query($sql, $main);
while($row = mysql_fetch_array($result)) {
	echo "<p class=\"commstring\">".$row['commstring']."</p>";
}
?>
</div> <!--end #comms -->

</body>

<?php

}
else{
?>
<p>

You do not have sufficient access rights to view this page.

<a class="loginlink" href="<?php echo $basepage; ?>?disp=login">Log In</a>

</p>

<?php 
}
If(!empty($main)) mysql_close($main);
If(!empty($master)) mysql_close($master);

?>
</html>
