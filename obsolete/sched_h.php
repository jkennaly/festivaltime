<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<div id="content">
<?php

//Sets the target for all POST actions
$post_target=$basepage."?disp=sched";

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

//We will not be using the standard values for these variables
unset($stage);
unset($day);

//Get the list of stages
$sql = "select name, id from stages where name!='Undetermined'";
$res = mysql_query($sql);
while($row=mysql_fetch_array($res)){
	$stage[] = $row;
}
//Get list of days
$sql1 = "select id, name, date from days where name!='Undetermined'";
$res1 = mysql_query($sql1);
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
$sql_band = "select id, name, sec_start, sec_end, start, end, stage from bands where sec_start<'$band_end' AND sec_start>='$k' AND stage='".$stage[$j]['id']."'";
$res_band = mysql_query($sql_band);
If(mysql_num_rows($res_band)>0) {
	$band_row=mysql_fetch_array($res_band);
	//Find number of blocks
	$set_time = $band_row['sec_end'] - $band_row['sec_start'];
	$blocks = $set_time/300;
	$rat_sql = "select rating from ratings where user='$user' and band='".$band_row['id']."'";
	$res_rat = mysql_query($rat_sql);
	$rat_row=mysql_fetch_array($res_rat);
	//Lay down the band name
	echo "<td class=\"rating".$rat_row['rating']."\" colspan=\"$blocks\">"."<a href=\"".$basepage."?disp=view_band&band=".$band_row['id']."\">".$band_row['name']."</a></td>";
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
</div> <!-- end #content -->
<?php
mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>

