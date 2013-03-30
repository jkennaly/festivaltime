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
$right_required = "EditSite";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If(!empty($_POST)){

//Escape entered info

	$escapedgroup = mysql_real_escape_string($_POST['group']);
    $groupType = $_POST['grouptype'];

//Verify that the group name is not already taken

	$query = "select * from groups where name='$escapedgroup'";
	$pwq = mysql_query($query, $master);
	$num = mysql_num_rows($pwq);

	If(!empty($num)){
		echo "That group name is not unique. group not created.";
	}
	else{

		$query = "insert into groups (name, creator, type) values ('$escapedgroup', $user, $groupType); ";
		$upd = mysql_query($query, $master);

		
	}

}

//First, find all current days
    
    $groupt_sql = "select id, name from `grouptypes`";
    $groupt_res = mysql_query($groupt_sql, $master);
	$query="SELECT g.name as name, t.name as type FROM groups as g LEFT JOIN grouptypes as t on g.type=t.id ORDER BY name ASC";
	$mem_result = mysql_query($query, $master);

?>
<p>

This page allows for adding new groups to FestivalTime.

</p>
<form action="index.php?disp=add_groups" method="post">
<table border="1">
<tr>
<th>group</th>
<th>group type</th>
</tr>
<tr>
<td>
<input type="text" name="group" maxlength="100" size ="100">
</td>
<td>
<select name="grouptype">
<?php 
while($row = mysql_fetch_array($groupt_res)) {
    echo "<option ";
//    If($row['value']=="public") echo "selected=\"SELECTED\" ";
    echo "value=".$row['id'].">".$row['name']."</option>";
}

?>
</select>
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
<th>grouptype</th>
</tr>

<?php 
while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["name"]."</td><td>".$row["type"]."</td></tr>";

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
