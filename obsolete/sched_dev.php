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
$sql = "select name as stagename from stages where name!='Undetermined'";

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
} // Closes while($row = my_sql_fetch_array($res)) 


//Draw a row with i columns every 5 min from start time for fest length
for ($k=$fest_start_time_sec;$k<=$fest_end_time_sec;$k=$k+900) {
	echo "<tr><th rowspan=\"3\">".strftime("%I:%M %p", $k)."</th>";
	for ($l=0;$l<3;$l++) {
		If($l!=0) echo "<tr>";
		for ($j=1;$j<=$i;$j++) {
			$sql_band = "select name from bands where sec_start<='$k' AND sec_end >='$k' AND stage='$j'";
			$res_band = mysql_query($sql_band);
			$row_band = mysql_fetch_array($res_band);
			echo "<td>".$row_band['name']."</td>";
			
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

