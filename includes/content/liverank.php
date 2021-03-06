<?php
/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/ 


$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<div id="content">
<?php

$post_target = $basepage."?disp=liverank";

//Calculate 10min from now and 2 hours from now

?>
<form action="<?php echo $post_target; ?>" method="post">
<input type="number" name="min_percent" value="50">Minimum percent of the band that must remain before the score starts to drop</input><br>
<input type="number" name="max_min" value="30">Number of minutes in the future when the band's score reaches its maximum</input><br>
<input type="number" name="hours_out" value="2">THIS MUST BE LONGER THAN THE LONGEST SET TIME. Number of hours in the future when the band's score starts to increase</input><br>
<input type="number" name="time_to_reach_stage" value="10">Number of minutes to assume it will take to reach the stage</input><br>
<input type="datetime" name="basetime" value="2013-04-12 15:45">
<input type="submit" value="Go!">
</form>


<?php

If(!empty($_POST)) {
$basetime = $_POST['basetime'];
$min_percent = $_POST['min_percent'];
$max_min = $_POST['max_min'];
$hours_out = $_POST['hours_out'];
$time_to_reach_stage = $_POST['time_to_reach_stage'];


} else {
$basetime = "2013-04-12 15:45";
$min_percent = 50;
$max_min = 30;
$hours_out = 2;
$time_to_reach_stage = 10;
} //Closes If(!empty($_POST))


$basetime_s = strtotime($basetime);



echo "Time used for calculation is ".$basetime;
echo "<br>All calculations assume it will take $time_to_reach_stage minutes to get to the stage.<br>";
echo "In order to get the full score, the band must start within $max_min minutes, and at least $min_percent of their set must remain. Only bands that will start within the next $hours_out hours and are currently playing are considered.<br><br>";

$t_10min = $basetime_s+60*$time_to_reach_stage;
$t_2hr = $basetime_s+60*60*$hours_out;

$sql = "select * from bands where (sec_end> '$t_10min' and sec_end < '$t_2hr') OR (sec_start > '$t_10min' and sec_start < '$t_2hr')";

//echo $sql."<br>";


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

echo "<table><tr><th>Band ID</th><th>Live Rank</th><th>Band Name</th><th>Live Score</th><th>Scoring Factor</th><th>Base Score</th></tr>";


$max_i = count($uscoreall);
reset($uscoreall);
//$j
for ($i=1; $i<=$max_i; $i++)
  {
	$mband=key($uscoreall);
	$sql="select name from bands where id='$mband'";
	$name_res = mysql_query($sql, $main);
	$name= mysql_fetch_array($name_res);
	echo "<tr><td>".key($uscoreall).
		"</td><th>$i</th><th><a href=\"".$basepage.
		"?disp=view_band&band=".key($uscoreall).
		"\">".$name['name'].
		"</a></th><td>".current($uscoreall).
		"</td><td>".$sfeall[key($uscoreall)].
		"</td><td>".$basescoreall[key($uscoreall)].
		"</td></tr>";
	next($uscoreall);
  
  }
echo "</table>";

} //Closes If(!empty($active_band)

/*
for ($i=1; $i<=$6; $i++) {
	echo $mband[$i]."<br>";
}

*/










?>
</div> <!-- end #content -->
<?php
}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>

