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
$right_required = "EditFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If(!empty($_POST)){

//Escape entered info

	$escapedfestType = mysql_real_escape_string($_POST['festtype']);

//Verify that the genre name is not already taken

	$query = "select * from festtypes where name='$escapedfestType'";
	$pwq = mysql_query($query, $master);
	$num = mysql_num_rows($pwq);

	If(!empty($num)){
		echo "That fest type name is not unique. fest type not created.";
	}
	else{

		$query = "insert into festtypes (name) values ('$escapedfestType'); ";
		$upd = mysql_query($query, $master);

		
	}

}

//First, find all current days

	$query="SELECT name FROM festtypes ORDER BY name ASC";
	$mem_result = mysql_query($query, $master);

?>

<form action="index.php?disp=add_festtypes" method="post">
<table border="1">
<tr>
<th>festtype</th>
</tr>
<tr>
<td>
<input type="text" name="festtype" maxlength="100" size ="100">
</td>

</tr>
</table>
<input type="submit">
</form>

<p>
The following festtypes have been added for this festival. Adding new fest types requires adding the code to back them up!
</p>

<table border="1">
<tr>
<th>festtype</th>
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
