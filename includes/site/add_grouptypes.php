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


?>

<div id="content">

<?php
$right_required = "EditUsers";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If(!empty($_POST)){

//Escape entered info

	$escapedGroupType = mysql_real_escape_string($_POST['grouptype']);
    $escapedGroupAccess = mysql_real_escape_string($_POST['access']);

//Verify that the genre name is not already taken

	$query = "select * from grouptypes where name='$escapedGroupType'";
	$pwq = mysql_query($query, $master);
	$num = mysql_num_rows($pwq);

	If(!empty($num)){
		echo "That group type name is not unique. Group type not created.";
	}
	else{

		$query = "insert into grouptypes (name, access) values ('$escapedGroupType', '$escapedGroupAccess'); ";
		$upd = mysql_query($query, $master);

		
	}

}

//First, find all current days

	$query="SELECT name, access FROM grouptypes ORDER BY name ASC";
	$mem_result = mysql_query($query, $master);

?>

<form action="index.php?disp=add_grouptypes" method="post">
<table border="1">
<tr>
<th>grouptype</th><th>access</th>
</tr>
<tr>
<td>
<input type="text" name="grouptype" maxlength="100" size ="100">
</td>

<td>
<input type="text" name="access" maxlength="20" size ="20">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following grouptypes have been added for this festival.
</p>

<table border="1">
<tr>
<th>grouptype</th>
</tr>

<?php 
while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["name"]."</td></tr>";

}
?>
<tr>
<th>access</th>
</tr>

<?php 
while($row = mysql_fetch_array($mem_result)) {
    echo "<tr><td>".$row["name"]."</td></tr>";

}
?>

</table>

<?php


}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
