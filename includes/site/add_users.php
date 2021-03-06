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
If($_POST){

//Escape entered info

	$escapedName = mysql_real_escape_string($_POST['username']);
	$escapedPW = mysql_real_escape_string($_POST['password']);

//Verify that the username is not already taken

	$query = "select * from Users where username='$escapedName'";
	$pwq = mysql_query($query, $master);
	$num = mysql_num_rows($pwq);

	If($num){
		echo "That username is not unique. User not created.";
	}
	else{
// generate a random salt to use for this account
		$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

//$salt = bin2hex(76);

		$saltedPW =  $escapedPW . $salt;

		$hashedPW = hash('sha256', $saltedPW);
       

		$query = "insert into Users (username, hashedpw, salt, level) ";
		$query .= "values ('$escapedName', '$hashedPW', '$salt', '".$_POST['access_level']."' ); ";
		$upd = mysql_query($query, $master);
//Get the id for the new user
		$query = "select max(id) as id from Users";
		$res = mysql_query($query, $master);
		$max = mysql_fetch_array($res);

//Create a settings table for the user


$sql = "CREATE TABLE user_settings_".$max['id']." (id int NOT NULL AUTO_INCREMENT, item varchar( 255 ) NOT NULL ,value varchar( 255 ) NOT NULL, `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id))";
$sql2 =  "INSERT INTO user_settings_".$max['id']." SELECT * FROM user_settings_template;";

		$res = mysql_query($sql, $master);
		echo mysql_error()."<br>";
		$res = mysql_query($sql2, $master);
		echo mysql_error()."<br>";
	}
}

$access_sql = "select value, name from access_levels";
$res_sql = mysql_query($access_sql, $master);


?>
<p>

This page allows for creation of new users.

</p>
<form action="index.php?disp=add_users" method="post">
<table border="1">
<tr>
<th>username</th>
<th>access level</th>
</tr>

<tr>
<td>
<input type="text" name="username" maxlength="30" size ="30">
</td>
<td>

<select name="access_level">
<?php 
while($row = mysql_fetch_array($res_sql)) {
	echo "<option ";
	If($row['value']=="public") echo "selected=\"SELECTED\" ";
	echo "value=".$row['value'].">".$row['name']."</option>";
}

?>
</select>

</td>
</tr>


</table>
<table border="1">
<tr>
<th>password</th>
</tr>

<tr>
<td>
<input type="password" name="password" maxlength="30" size ="30">
</td>
</tr>

</table>
<input type="submit">
</form>
<?php


}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
