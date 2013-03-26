<?php

$right_required = "AddBands";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_POST['day'])){
//Get the date for the day
		$sql="select date from days where id='".$_POST['day']."'";
		$res=mysql_query($sql, $main);
		$row=mysql_fetch_array($res);
		$fest_day_date = $row['date'];

//Get fest start time and length
$sql="select value from info where item like 'Festival Start Time%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_start_time = $fest_day_date." ".$row['value'];

$sql="select value from info where item like 'Festival Length%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_length = $row['value'];






$fest_start_time_sec = strtotime($fest_start_time);
$fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;



}// Closes If(!empty($_POST['day']))



//echo "Edits logic approached<br>";


//Once the information is submitted, store it in the database
If(!empty($_POST['edits'])){
//echo "Edits logic entered<br>";

//Escape entered info

	$escapedName = mysql_real_escape_string($_POST['name']);
	$escapedStart = mysql_real_escape_string($_POST['start']);
	$escapedEnd = mysql_real_escape_string($_POST['end']);

//Verify that the band name is not already taken

	$query = "select * from bands where name='$escapedName' AND id!='$band'";
	$pwq = mysql_query($query, $main);
	$num = mysql_num_rows($pwq);

	If(!empty($num)){
		echo "That band name is not unique. Band not edited.";
	} else{
		//If either time is less than the fest start, roll it forward one day
$band_start = $fest_day_date." ".$_POST['start'];
$band_start_time_sec = strtotime($band_start);
$fest_start_time_sec = strtotime($fest_start_time);
If($band_start_time_sec < $fest_start_time_sec) $band_start_time_sec = $band_start_time_sec+24*3600;
$band_start = strftime("%Y-%m-%d %H:%M", $band_start_time_sec);



$band_end = $fest_day_date." ".$_POST['end'];
$band_end_time_sec = strtotime($band_end);
$fest_start_time_sec = strtotime($fest_start_time);
If($band_end_time_sec < $fest_start_time_sec) $band_end_time_sec = $band_end_time_sec+24*3600;
$band_end = strftime("%Y-%m-%d %H:%M", $band_end_time_sec);

		

		If($band_start_time_sec > $band_end_time_sec) {
			echo "Ends before it starts. Try entering again.";
			$i=1;
		}
		
		
				

		If(empty($i)){
		$query = "update bands set name='$escapedName', start='$escapedStart', end='$escapedEnd', day='".$_POST['day']."', stage='".$_POST['stage']."', sec_start='$band_start_time_sec', sec_end='$band_end_time_sec', start='$band_start', end='$band_end' where id=$band";
		$upd = mysql_query($query, $main);
		$name = $escapedName;
		$day=$_POST['day'];
		$stage=$_POST['stage'];
		$stime=$band_start;
		$etime=$band_end;
		
		//handle genres
		
		$gsql="select id from bandgenres where band='$band_master_id' and user='$user'";
		$gres = mysql_query($gsql, $master);
		If(mysql_num_rows($gres)>0) $query = "update bandgenres set genre='".$_POST['genre']."' where band='$band_master_id' and user='$user'";
		else $query = "insert into bandgenres (band, genre, user) values ('$band_master_id', '$user', '".$_POST['genre']."')";
//		echo $query."<br />";
		$gupd = mysql_query($query, $master);
		$genre=$_POST['genre'];
		} //Closes If(!empty($i)
		
	} //If($num) else

} //If($_POST['edits'])



//Get data to make the pick lists
	$query="select name, id from days";
	$query_day = mysql_query($query, $main);
	$query="select name, id from stages";
	$query_stage = mysql_query($query, $main);
	$query="select name, id from genres order by name asc";
	$query_genre = mysql_query($query, $master);


If(!empty($band)) {

//echo "Currently editing $name. Use the selector below to choose a different band.";
?>

<form action="<?php echo $post_target ?>" method="post">
<table border="1">
<tr>
<th>band name</th>
<th>day</th>
<th>stage</th>
<th>genre</th>
<th>start time</th>
<th>end time</th>
</tr>

<tr>
<td>
<input type="text" name="name" value="<?php echo $name; ?>" maxlength="100" size ="30">
</td>
<td>
<select name="day">
<?php 
while($row = mysql_fetch_array($query_day)) {
	If ($day==$row["id"]) echo "<option selected=\"selected\" value=".$row["id"].">".$row["name"]."</option>";
	If ($day!=$row["id"]) echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>
</td>
<td>
<select name="stage">
<?php 
while($row = mysql_fetch_array($query_stage)) {
	If ($stage==$row["id"]) echo "<option selected=\"selected\" value=".$row["id"].">".$row["name"]."</option>";
	If ($stage!=$row["id"]) echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>
</td>
<td>
<select name="genre">
<?php 
while($row = mysql_fetch_array($query_genre)) {
	If ($genre==$row["id"]) echo "<option selected=\"selected\" value=".$row["id"].">".$row["name"]."</option>";
	If ($genre!=$row["id"]) echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>
</td>
<td>
<input type="time" name="start" value="<?php echo substr($stime, 11, 5) ?>">
</td>
<td>
<input type="time" name="end" value="<?php echo substr($etime, 11, 5); ?>">
</td>

</table>

<input type="hidden" name="band" value="<?php echo $band; ?>">
<input type="submit" name="edits" value="Confirm">
</form>
<?php

} //Closes If(!empty($band))

} 


?>
