<div id="content">

<?php
$right_required = "EditFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


	mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");


//Once the information is submitted, store it in the database
If($_POST){

//Escape entered info

	$escapedDay = mysql_real_escape_string($_POST['day']);

//Verify that the day name is not already taken

	$query = "select * from days where name='$escapedDay'";
	$pwq = mysql_query($query);
	$num = mysql_num_rows($pwq);

	If($num){
		echo "That day name is not unique. Day not created.";
	}
	else{

		$query = "insert into days (name) values ('$escapedDay'); ";
		$upd = mysql_query($query);

		
	}

}

//First, find all current days

mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");
	$query="SELECT name FROM days ORDER BY id ASC";
	$mem_result = mysql_query($query);

?>
<p>

This page allows for adding new days to the festival.

</p>
<form action="index.php?disp=add_days" method="post">
<table border="1">
<tr>
<th>day</th>
</tr>
<tr>
<td>
<input type="text" name="day" maxlength="25" size ="25">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following days have been added for this festival.
</p>

<table border="1">
<tr>
<th>day</th>
</tr>

<?php 
while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["name"]."</td></tr>";

}
?>

</table>

<?php



mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
