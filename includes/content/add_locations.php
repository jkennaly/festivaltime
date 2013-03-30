#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<div id="content">

<?php
$right_required = "EditFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If($_POST){

//Escape entered info

	$escapedLoc = mysql_real_escape_string($_POST['location']);

//Verify that the day name is not already taken

	$query = "select * from locations where name='$escapedlocation'";
	$pwq = mysql_query($query, $main);
	$num = mysql_num_rows($pwq);

	If($num){
		echo "That location name is not unique. location not created.";
	}
	else{

		$query = "insert into locations (name) values ('$escapedLoc'); ";
		$upd = mysql_query($query, $main);

		
	}

}

//First, find all current days

	$query="SELECT name FROM locations ORDER BY id ASC limit 0,6";
	$mem_result = mysql_query($query, $main);

?>
<p>

This page allows for adding new locations to the festival.

</p>
<form action="index.php?disp=add_locations" method="post">
<table border="1">
<tr>
<th>location</th>
</tr>
<tr>
<td>
<input type="text" autofocus name="location" maxlength="100" size ="100">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following locations have been added for this festival. Only the first 6 are actually useable.
</p>

<table border="1">
<tr>
<th>location</th>
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
