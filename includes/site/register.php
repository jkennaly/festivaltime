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

If(!isset($_SESSION['level'])){

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

        $query = "insert into Users (username, hashedpw, salt, level) values ('$escapedName', '$hashedPW', '$salt', 'public'); ";
        echo $query;
        echo mysql_error();
        $upd = mysql_query($query, $master);
//Get the id for the new user
        $query = "select max(id) as id from Users";
        $res = mysql_query($query, $master);
        $max = mysql_fetch_array($res);

//Create a settings table for the user


$sql = "CREATE TABLE user_settings_".$max['id']." (id int NOT NULL AUTO_INCREMENT, item varchar( 255 ) NOT NULL ,value varchar( 255 ) NOT NULL, PRIMARY KEY (id))";
$sql2 =  "INSERT INTO user_settings_".$max['id']." SELECT * FROM user_settings_template;";

        $res = mysql_query($sql, $master);
        echo mysql_error()."<br>";
        $res = mysql_query($sql2, $master);
        echo mysql_error()."<br>";
    }

}

?>

<form action="index.php?disp=register" method="post">
<table border="1">
<tr>
<th>username</th>
</tr>

<tr>
<td>
<input type="text" name="username" maxlength="30" size ="30">
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
echo "You are already logged in!";
}
?>
</div> <!-- end #content -->

