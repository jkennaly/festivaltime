<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<div id="content">
<?php

//Sets the target for all POST actions
$post_target=$basepage."?disp=sched_dev";

//First draw a grid for Day 1

//Get fest start time and length
$sql="select value from info where item like 'Festival Start Time%'";
$res=mysql_query($sql);
$row=mysql_fetch_array($res);
$fest_start_time = $row['value'];

$sql="select value from info where item like 'Festival Length%'";
$res=mysql_query($sql);
$row=mysql_fetch_array($res);
$fest_length = $row['value'];

//$fest_start_time = "10:00";
//fest length must be specified in hours
//$fest_length = 15;

//Get the list of stages
$sql = "select name as stagename, id from stages where name!='Undetermined'";

//Get list of days
$sql1 = "select name as dayname, date as daydate from days where name!='Undetermined'";
$res1 = mysql_query($sql1);




//Index is incremented in 5 min increments to draw the table

while($day = mysql_fetch_array($res1)){
$res = mysql_query($sql);
$i=0;
$fest_start_time_sec = strtotime($day['daydate']." ".$fest_start_time);
echo "<br> Day date is ".$day['daydate']." and fest start time is ".$fest_start_time;
echo "<h3>".$day['dayname']."</h3>";
$fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;

//Draw first row of stage names
echo "<table class=\"schedtable\"><tr><th>Time</th>";
while($row = mysql_fetch_array($res)) {
echo "<th>".$row['stagename']."</th>";
$i=$i+1;
$stageid[]=$row;
} // Closes while($row = my_sql_fetch_array($res)) 


//Draw a row with i columns every 5 min from start time for fest length
for ($k=$fest_start_time_sec;$k<=$fest_end_time_sec;$k=$k+900) {
	echo "<tr><th rowspan=\"3\">".strftime("%I:%M %p", $k)."</th>";
	for ($l=0;$l<3;$l++) {
		If($l!=0) echo "<tr>";
		for ($j=1;$j<=$i;$j++) {
			If(empty($ticked[$j])) $ticked[$j] = 0;
			If(empty($ticks[$j])) $ticks[$j] = 0;
			If(empty($band_name_prev[$j])) $band_name_prev[$j] = 0;
			$k_temp=$k+300*$l;
			$sql_band = "select name, sec_start, sec_end, start, end, stage from bands where sec_start<='$k_temp' AND sec_end >'$k_temp' AND stage='$j'";
			$res_band = mysql_query($sql_band);
			$row_band = mysql_fetch_array($res_band);
			If(!empty($row_band['name'])) $band_current[$j]=1;
			If(empty($row_band['name'])){ $band_current[$j]=0; $ticks[$j]=0;  $ticked[$j]=0; }
			If($ticked[$j]>0 ) $ticked[$j] = $ticked[$j] +1;
			If(empty($band_current[$j])) $band_current[$j]=0;
			If(empty($band_current_prev[$j])) $band_current_prev[$j]=0;
If(    (   ($band_current[$j]==1 && $band_current_prev[$j] == 0 )  || ($band_name_prev[$j] != $row_band['name'])   ) && !empty($row_band['name']) ) {$ticks[$j] = ($row_band['sec_end'] - $row_band['sec_start'])/300; $ticked[$j] = 1;}
			If($ticked[$j] == 1 ) echo "<td rowspan=\"".$ticks[$j]."\">".$row_band['name']." stage ".$row_band['stage']." start ".$row_band['start']." end ".$row_band['end']."</td>";
			If($ticked[$j] == 0 ) echo "<td></td>";
			$band_current_prev[$j] = $band_current[$stageid[$j-1]['id']];
			$band_name_prev[$j] = $row_band['name'];
		} // Closes for ($j=1;$j<=$i;$j++)
		echo "</tr>";
	} // Closes for ($l=0;$l<3;$l++)
} //Closes for ($k=$fest_start_time_sec,$k+300,$k<=$fest_end_time_sec)
echo "</table><!-- end .schedtable -->";
} //Closes while($day = mysql_fetch_array($res1)

?>
</div> <!-- end #content -->
<?php
mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>

