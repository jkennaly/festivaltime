<div id="content">

<?php
$right_required = "EditSite";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


	mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");


//Once the information is submitted, store it in the database
If(!empty($_POST)){

//Escape entered info

	$escapedgroup = mysql_real_escape_string($_POST['group']);
	echo $escapedgroup;

//Verify that the group name is not already taken

	$query = "select * from groups where name='$escapedgroup'";
	$pwq = mysql_query($query, $main);
	$num = mysql_num_rows($pwq);

	If(!empty($num)){
		echo "That genere name is not unique. group not created.";
	}
	else{

		$query = "insert into groups (name, creator) values ('$escapedgroup', $user); ";
		echo $query;
		$upd = mysql_query($query, $main);

		
	}

}

//First, find all current days

mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");
	$query="SELECT name FROM groups ORDER BY name ASC";
	$mem_result = mysql_query($query, $main);

?>
<p>

This page allows for adding new stages to the festival.

</p>
<form action="index.php?disp=add_groups" method="post">
<table border="1">
<tr>
<th>group</th>
</tr>
<tr>
<td>
<input type="text" name="group" maxlength="100" size ="100">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following groups have been added for this festival.
</p>

<table border="1">
<tr>
<th>group</th>
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
