<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
//Draw elements common to portrait and landscape

//Sets the target for all POST actions
$post_target=$basepage."?disp=sched";

?>

<script type="text/javascript" src="includes/js/lines.js"></script>

<table ><caption>Color Code (Band names are links to band details)</caption>
<tr>
<td class="rating"><a>Unrated</a></td><td class="rating1"><a>Rating=1</a></td><td class="rating2"><a>Rating=2</a></td><td class="rating3"><a>Rating=3</a></td><td class="rating4"><a>Rating=4</a></td><td class="rating5"><a>Rating=5</a></td>
</tr>
</table>

<?php
If(empty($_POST['landscape'])) {
?>

<form action="<?php echo $post_target; ?>" method="post">
<input type="submit" name="landscape" value="Flip orientation">
</form>

<?php
$usersql="select id, username from Users";
$userres=mysql_query($usersql, $master);
while($row=mysql_fetch_array($userres)) {
	
	
include $baseinstall."includes/content/blocks/paths.php";
	

//Set some variables for use
$banddecay=0.25; //$banddecay is the rate at which the score drops for a band you are at; there is no decay for the last 5 min
$traveltime = 2; //Traveltime is the number of 5min blocks it takes to go from one placeto another
$mintime = 20; //$mintime is the minimum amount of time the user will stay at a show once committing
$thirstiness = 0.04; //$thristiness affects how fast score for beer tent accumulates	

?>
<input type="button" onclick="bestPath<?php echo $row['id']; ?>();" value="Show <?php echo $row['username']; ?>'s Best Path" />

<script type="text/javascript">
window.bestPath<?php echo $row['id']; ?> = function () {
<?php
$jsuser = $row['id'];
echo "alert(\"Wait until you get the completion before scrolling the screen.\");\n";
pathfinder($row['id'], $banddecay, $traveltime, $mintime, $thirstiness, $main, $master);
echo "alert(\"Paths complete!\");";
?>
}
</script>
</input>

<?php
}
?>

<div id="content">
<?php

//include $baseinstall."includes/content/blocks/user_selector.php";

//First draw a grid for Day 1

//Get fest start time and length
$sql="select value from info where item like 'Festival Start Time%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_start_time = $row['value'];

$sql="select value from info where item like 'Festival Length%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_length = $row['value'];

//$fest_start_time = "10:00";
//fest length must be specified in hours
//$fest_length = 15;

//Get the list of stages
$sql = "select name as stagename, id from stages where name!='Undetermined'";

//Get list of days
$sql1 = "select name as dayname, date as daydate, id from days where name!='Undetermined'";
$res1 = mysql_query($sql1, $main);

//Index is incremented in 5 min increments to draw the table

while($day = mysql_fetch_array($res1)){
$res = mysql_query($sql, $main);
$i=0;
$fest_start_time_sec = strtotime($day['daydate']." ".$fest_start_time);
echo "<br> Day date is ".$day['daydate']." and fest start time is ".$fest_start_time;
echo "<h3 id=\"day".$day['id']."\">".$day['dayname']."</h3>";
$fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;

//Draw first row of stage names
echo "<table class=\"schedtable\"><tr><th>Time</th>";
while($row = mysql_fetch_array($res)) {
echo "<th>".$row['stagename']."</th>";
$i=$i+1;
$stageid[]=$row;
} // Closes while($row = my_sql_fetch_array($res)) 
echo "<th>Beer Tent</th></tr>";

//Draw a row with i columns every 5 min from start time for fest length
for ($k=$fest_start_time_sec;$k<$fest_end_time_sec;$k=$k+900) {
	echo "<tr><th rowspan=\"3\">".strftime("%I:%M %p", $k)."</th>";
	for ($l=0;$l<3;$l++) {
		If($l!=0) echo "<tr>";
		for ($j=1;$j<=$i;$j++) {
			If(empty($ticked[$j])) $ticked[$j] = 0;
			If(empty($ticks[$j])) $ticks[$j] = 0;
			If(empty($band_name_prev[$j])) $band_name_prev[$j] = 0;
			$k_temp=$k+300*$l;
			$sql_band = "select id, name, sec_start, sec_end, start, end, stage, genre from bands where sec_start<='$k_temp' AND sec_end >'$k_temp' AND stage='$j'";
			$res_band = mysql_query($sql_band, $main);
			$row_band = mysql_fetch_array($res_band);
			If(!empty($row_band['name'])) {
				$band_current[$j]=1;
				$rat_sql = "select rating from ratings where user='$user' and band='".$row_band['id']."'";
				$res_rat = mysql_query($rat_sql, $main);
				$rat_row=mysql_fetch_array($res_rat);
			}
			If(empty($row_band['name'])){ $band_current[$j]=0; $ticks[$j]=0;  $ticked[$j]=0; }
			If($ticked[$j]>0 ) $ticked[$j] = $ticked[$j] +1;
			If(empty($band_current[$j])) $band_current[$j]=0;
			If(empty($band_current_prev[$j])) $band_current_prev[$j]=0;
If(    (   ($band_current[$j]==1 && $band_current_prev[$j] == 0 )  || ($band_name_prev[$j] != $row_band['name'])   ) && !empty($row_band['name']) ) {$ticks[$j] = ($row_band['sec_end'] - $row_band['sec_start'])/300; $ticked[$j] = 1;}
			If($ticked[$j] == 1 ) echo "<td id=\"band".$row_band['id']."\" class=\"rating".$rat_row['rating']."\" rowspan=\"".$ticks[$j]."\">"."<a href=\"".$basepage."?disp=view_band&band=".$row_band['id']."\">".$row_band['name']."<br />".getGname($master, $row_band['genre'])."</a></td>";
			If($ticked[$j] == 0 ) echo "<td></td>";
			$band_current_prev[$j] = $band_current[$stageid[$j-1]['id']];
			$band_name_prev[$j] = $row_band['name'];
		} // Closes for ($j=1;$j<=$i;$j++)
		IF($k_temp==$fest_start_time_sec) {$totalrows=($fest_start_time_sec-$fest_end_time_sec)/(-300);echo "<td id=\"bandbeer".$day['id']."\" rowspan=\"$totalrows\"></td></tr>";} else echo "</tr>";
	} // Closes for ($l=0;$l<3;$l++)
} //Closes for ($k=$fest_start_time_sec,$k+300,$k<=$fest_end_time_sec)
echo "</table><!-- end .schedtable -->";
} //Closes while($day = mysql_fetch_array($res1)

?>
</div> <!-- end #content -->
<?php
}else {
//Begin landscape logic
?>

<div id="landscape">


<form action="<?php echo $post_target; ?>" method="post">
<input type="submit" name="portrait" value="Flip orientation">
</form>

<?php

//First draw a grid for Day 1

//Get fest start time and length
$sql="select value from info where item like 'Festival Start Time%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_start_time = $row['value'];

$sql="select value from info where item like 'Festival Length%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_length = $row['value'];

//$fest_start_time = "10:00";
//fest length must be specified in hours
//$fest_length = 15;

//We will not be using the standard values for these variables
unset($stage);
unset($day);

//Get the list of stages
$sql = "select name, id from stages where name!='Undetermined'";
$res = mysql_query($sql, $main);
while($row=mysql_fetch_array($res)){
	$stage[] = $row;
}
//Get list of days
$sql1 = "select id, name, date from days where name!='Undetermined'";
$res1 = mysql_query($sql1, $main);
while($row=mysql_fetch_array($res1)){
	$day[] = $row;
}
for($i=0;$i<mysql_num_rows($res1);$i++) {

$fest_start_time_sec = strtotime($day[$i]['date']." ".$fest_start_time);

echo "<h3>".$day[$i]['name']."</h3>";
$fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;


//First open the table and lay down the times in 15min increments
echo "<table class=\"lsched\"><tr><th>Time</th>";

for ($k=$fest_start_time_sec;$k<$fest_end_time_sec;$k=$k+900) {

echo "<th class=\"ltime\" colspan=\"3\">".strftime("%I:%M %p", $k)."</th>";

}

echo "</tr>";

for($j=0;$j<mysql_num_rows($res);$j++) {

//Now lay down the stages

echo "<tr><th>".$stage[$j]['name']."</th>";

for ($k=$fest_start_time_sec;$k<$fest_end_time_sec;$k=$k+300) {

$band_end = $k+300;
//See if a band starts at the current time block and pull info if it does
$sql_band = "select id, name, sec_start, sec_end, start, end, stage, genre from bands where sec_start<'$band_end' AND sec_start>='$k' AND stage='".$stage[$j]['id']."'";
$res_band = mysql_query($sql_band, $main);
If(mysql_num_rows($res_band)>0) {
	$band_row=mysql_fetch_array($res_band);
	//Find number of blocks
	$set_time = $band_row['sec_end'] - $band_row['sec_start'];
	$blocks = $set_time/300;
	$rat_sql = "select rating from ratings where user='$user' and band='".$band_row['id']."'";
	$res_rat = mysql_query($rat_sql, $main);
	$rat_row=mysql_fetch_array($res_rat);
	//Lay down the band name
	echo "<td class=\"rating".$rat_row['rating']."\" colspan=\"$blocks\">"."<a href=\"".$basepage."?disp=view_band&band=".$band_row['id']."\">".$band_row['name']."<br />".getGname($master, $band_row['genre'])."</a></td>";
	//Skip index to end of band
	$k = $band_row['sec_end'] - 300;
} else {
	echo "<td></td>";

} // Closes else If(mysql_num_rows($res_band>0)
}



echo "</tr>";
}


echo "</table>";

}

?>
</div> <!-- end #landscape -->
<?php

}
} else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>

