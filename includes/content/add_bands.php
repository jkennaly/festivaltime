<div id="content">

<?php
$right_required = "AddBands";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Get data to make the pick lists
	$query="select name, id from days";
	$query_day = mysql_query($query, $main);
	$query="select name, id from stages";
	$query_stage = mysql_query($query, $main);


//Once the information is submitted, store it in the database
If($_POST){

//Escape entered info

	$escapedName = mysql_real_escape_string($_POST['name']);
	$escapedStart = mysql_real_escape_string($_POST['start']);
	$escapedEnd = mysql_real_escape_string($_POST['end']);

//Verify that the band name is not already taken

	$query = "select * from bands where name='$escapedName'";
	$pwq = mysql_query($query, $main);
	$num = mysql_num_rows($pwq);

	If($num){
		echo "That band name is not unique. Band not created.";
	}
	else{

		$query = "insert into bands (name, start, end, day, stage) values ('$escapedName', '$escapedStart', '$escapedEnd', '".$_POST['day']."', '".$_POST['stage']."' ); ";
		$upd = mysql_query($query, $main);

		
	}

}

?>
<p>

This page allows for adding new bands.

</p>
<form action="index.php?disp=add_bands" method="post">
<table border="1">
<tr>
<th>band name</th>
<th>day</th>
<th>stage</th>
<th>start time</th>
<th>end time</th>
</tr>

<tr>
<td>
<input type="text" name="name" maxlength="100" size ="30">
</td>
<td>
<select name="day">
<?php 
while($row = mysql_fetch_array($query_day)) {
	echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>
</td>
<td>
<select name="stage">
<?php 
while($row = mysql_fetch_array($query_stage)) {
	echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>
</td>
<td>
<input type="time" name="start">
</td>
<td>
<input type="time" name="end">
</td>

</table>

<input type="submit">
</form>
<?php



mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
