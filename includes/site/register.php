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
    $escapedEmail = mysql_real_escape_string($_POST['email']);
    $escapedRegCode = mysql_real_escape_string($_POST['regcode']);
    $groupchosen = $_POST['group'];

//Verify that the username is not already taken

    $query = "select * from Users where username='$escapedName' OR email='$escapedEmail'";
    $pwq = mysql_query($query, $master);
    $num = mysql_num_rows($pwq);
    
//If the user is registering without a registration code, assign A1A1A1A1A1
If($escapedRegCode == "") $escapedRegCode = "A1A1A1A1A1";
    
//Validation
    If($num != 0){
        echo "That username or email address is not unique. User not created.";
        break;
    }
    If(strlen($escapedName) < 4){
        echo "Please choose a username that is at least 4 characters.";
        break;
    }
    If(strlen($escapedPW) < 4){
        echo "Please choose a password that is at least 4 characters.";
        break;
    }
    If(in_string($outlawcharacters, $escapedName)) {
        echo "You may not use special characters in your username.";
        break;        
    }
    If(email_bad($escapedEmail)) {
        echo "$escapedEmail email is not valid.";
        break;        
    }
    

// generate a random salt to use for this account
        $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

//$salt = bin2hex(76);

        $saltedPW =  $escapedPW . $salt;

        $hashedPW = hash('sha256', $saltedPW);

        $query = "insert into Users (username, hashedpw, salt, level, email, used_key, `group`) values ('$escapedName', '$hashedPW', '$salt', 'public', '$escapedEmail', '$escapedRegCode', '--$groupchosen--'); ";
        $upd = mysql_query($query, $master);
//Get the id for the new user
        $query = "select max(id) as id from Users";
        $res = mysql_query($query, $master);
        $max = mysql_fetch_array($res);
//Verify that theu ser was created

        $query = "select username from Users where id='".$max['id']."'";
        $res = mysql_query($query, $master);
        $name = mysql_fetch_array($res);
        If($name['username'] != $escapedName) die("User not created");

//Create a settings table for the user


$sql = "CREATE TABLE user_settings_".$max['id']." (id int NOT NULL AUTO_INCREMENT, item varchar( 255 ) NOT NULL ,value varchar( 255 ) NOT NULL, PRIMARY KEY (id))";
$sql2 =  "INSERT INTO user_settings_".$max['id']." SELECT * FROM user_settings_template;";

        $res = mysql_query($sql, $master);
        echo mysql_error()."<br>";
        $res = mysql_query($sql2, $master);
        echo mysql_error()."<br>";
    }



?>

<form action="index.php?disp=register" method="post">
<table border="1">
<tr>
<th>username</th>
</tr>

<tr>
<td>
<input type="text" name="username" maxlength="60" size ="30">
</td>
</tr>

<tr>
<th>email</th>
</tr>

<tr>
<td>
<input type="text" name="email" maxlength="100" size ="45">
</td>
</tr>

<tr>
<th>registration code (if you don't have one, just leave it blank)</th>
</tr>

<tr>
<td>
<input type="text" name="regcode" maxlength="10" size ="10">
</td>
</tr>

<tr>
<th>Pick a public group to join (you can change it later)</th>
</tr>

<tr>
<td>
<select name="group">
    <?php
    $groups_avail=avail_public_groups($master);
        foreach($groups_avail as $v){
            echo "<option value=\"".$v['id']."\">".$v['name']."</option>";
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
echo "You are already logged in!";
}
?>
</div> <!-- end #content -->

