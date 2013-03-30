<?php

?>

<div id="content">

<?php
$right_required = "AddBands";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If(!empty($_POST)){

//Escape entered info

	$escapedGenre = mysql_real_escape_string($_POST['genre']);

//Verify that the genre name is not already taken

	$query = "select * from genres where name='$escapedGenre'";
	$pwq = mysql_query($query, $master);
	$num = mysql_num_rows($pwq);

	If(!empty($num)){
		echo "That genere name is not unique. Genre not created.";
	}
	else{

		$query = "insert into genres (name) values ('$escapedGenre'); ";
		$upd = mysql_query($query, $master);

		
	}

}

//First, find all current days

mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");
	$query="SELECT name FROM genres ORDER BY name ASC";
	$mem_result = mysql_query($query, $master);

?>
<p>

This page allows for adding new stages to the festival.

</p>
<form action="index.php?disp=add_genres" method="post">
<table border="1">
<tr>
<th>genre</th>
</tr>
<tr>
<td>
<input type="text" name="genre" maxlength="100" size ="100">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following genres have been added for this festival.
</p>

<table border="1">
<tr>
<th>genre</th>
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
