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


//First, find all users

	If(!empty($_POST["delete_user"])){
		$sql = "DELETE FROM Users WHERE id = '".$_POST["delete_user"]."'";
		$upd = mysql_query($sql, $master);
		$sql2 = "DROP TABLE user_settings_".$_POST["delete_user"]."";
		$drop = mysql_query($sql2, $master);
	}

	If(!empty($_POST["add_group"])){
		$check_sql = "select * from Users where id='".$_POST['add_group']."' AND `group` like '%--".$_POST['group']."--%'";
		$check_res = mysql_query($check_sql, $master);
		If(mysql_num_rows($check_res)==0) {
		$sql = "UPDATE Users SET `group`=CONCAT(`group`, '--".$_POST['group']."--') WHERE id='".$_POST['add_group']."'";
		$upd = mysql_query($sql, $master);
		} else echo "User is already part of that group.<br>";
	}

	If(!empty($_POST["rmv_group"])){
		$check_sql = "select * from Users where id='".$_POST['rmv_group']."' AND `group` like '%--".$_POST['group']."--%'";
		$check_res = mysql_query($check_sql, $master);
		If(mysql_num_rows($check_res)==0) {
		echo "User is not part of that group.<br>";
		} else {
			$sql = "select `group` from Users where id='".$_POST['rmv_group']."'";
			$res = mysql_query($sql, $master);
			$result = mysql_fetch_array($res);

			$stripped = str_replace ( "--".$_POST['group']."--" , " " , $result['group'] );
			$query = "UPDATE Users SET `group`='$stripped' where id='".$_POST['rmv_group']."'";
			$upd = mysql_query($query, $master);
		}
	}
	
	If(!empty($_POST["acl_radio"])){
		$usermoded="new_acl".$_POST["acl_radio"];
		$sql = "UPDATE Users SET level='".$_POST[$usermoded]."' WHERE id = '".$_POST["acl_radio"]."'";
//		echo $sql;
		$upd = mysql_query($sql, $master);
	}

	$query="SELECT id, username, level, `group` FROM Users ORDER BY level ASC";
	$mem_result = mysql_query($query, $master);
	$query="SELECT id, name FROM groups ORDER BY id ASC";
	$query_groups = mysql_query($query, $master);
	$acl_sql="select * from access_levels";
	$acl_res=mysql_query($acl_sql, $master);
?>
<p>

This page shows all users who currently have access to the site, except for the currently logged in user. Only other users may be modified through this page.

</p>
<form action="index.php?disp=edit_users" method="post">
<p>
<input type="radio" name="delete_user" value="0" checked="checked">Do not delete any users
</p>
<p>
<input type="radio" name="add_group" value="0" checked="checked">Do not add any users to group</p>
<p>
<input type="radio" name="rmv_group" value="0" checked="checked">Do not remove any users from a group</p>
<p>
<input type="radio" name="acl" value="0" checked="checked">Do not change any user access levels</p>
<select name="group">

<?php 
while($row_allgroups = mysql_fetch_array($query_groups)) {
	echo "<option value=".$row_allgroups['id'].">".$row_allgroups['name']."</option>";
}
	
?>
</select>
<table border="1">
<tr>
<th>username</th>
<th>access level</th>
<th>Change user access level</th>
<th>groups</th>
<th>Add user to selected group</th>
<th>Remove user from selected group</th>
<th>delete user</th>
</tr>

<?php 

while($row = mysql_fetch_array($mem_result)) {
	$g=str_replace("--", " ", $row["group"]);
	$g_exp = explode(" ", $g);
	echo "<tr><td>".$row["username"]."</td><td><select name=\"new_acl".$row["id"]."\">";
	mysql_data_seek($acl_res, 0);
	while($acl_row = mysql_fetch_array($acl_res)) {
		If($acl_row['value'] == $row["level"]) echo "<option selected=\"selected\" value=\"".$acl_row['value']."\">".$acl_row['name']."</option>";
		else echo "<option value=\"".$acl_row['value']."\">".$acl_row['name']."</option>";
	}

	echo "</td><td><input type=radio name=\"acl_radio\" value=".$row["id"]."></td><td>";
	foreach($g_exp as $g) {
	$sql_group = "select name from groups where id='$g'";
	$res_group = mysql_query($sql_group, $master);
	while($rowc = mysql_fetch_array($res_group))	echo $rowc["name"]."/";
	}
	echo "</select></td>";
	echo "<td><input type=radio name=\"add_group\" value=".$row["id"]."></td>";
	echo "<td><input type=radio name=\"rmv_group\" value=".$row["id"]."></td>";
	If($row["username"] != $_SESSION['user']) {
		echo "<td><input type=radio name=delete_user value=".$row["id"]."></td></tr>";
	} else {
		echo "<td></td></tr>";
	} // Closes If($row["username"] != $_SESSION['user'])
}  //Closes while($row = mysql_fetch_array($mem_result))
?>

</table>

<input type="submit">
</form>
<?php

mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
