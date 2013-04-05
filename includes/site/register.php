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

If(!empty($_GET['regcode'])) $getregcode = mysql_real_escape_string($_GET['regcode']);

If(!isset($_SESSION['level'])){

//Once the information is submitted, store it in the database
If($_POST){

//Escape entered info

    $escapedName = mysql_real_escape_string($_POST['username']);
    $escapedPW = mysql_real_escape_string($_POST['password']);
    $escapedEmail = mysql_real_escape_string($_POST['email']);
    $escapedRegCode = mysql_real_escape_string($_POST['regcode']);
    $credited="NULL";
    $credentials_version = "1"; //Credentials v1 is the first version to incorporate keys and the credited field

//Verify that the username is not already taken

    $query = "select * from Users where username='$escapedName' OR email='$escapedEmail'";
    $pwq = mysql_query($query, $master);
    $num = mysql_num_rows($pwq);
    
//If the user is registering without a registration code, assign A1A1A1A1A1
If($escapedRegCode == "") {$escapedRegCode = "A1A1A1A1A1"; $credited = "NULL";}
    //If the registration code is not blank, try to find it's source
    //We will use the $usepublic variable to track success-if we find a better group, the user will go there
    $usepublic=1;
    //Now we will check through all the possible key types until we find it
    //Check user-specific public and private keys
    $query = "select `id`, `group`, `public_key`, `private_key` from `Users` where `private_key`='$escapedRegCode' OR `public_key`='$escapedRegCode'";
//        echo $query."<br />";
    $result = mysql_query($query, $master);
    If(mysql_num_rows($result)== 1){
        $row=mysql_fetch_array($result);
        $credited = $row['id'];
//        echo "sign up credited to $credited<br />";
        If($row['private_key'] == $escapedRegCode) {
            //If the user has registered with another user's private key, find the granter's private group
            //Start by getting the types of groups that are private
            $query = "select id from grouptypes where access='private'";
            $result = mysql_query($query, $master);
            $i=0;
            while($row1=mysql_fetch_array($result)){
                If($i>0) $where = " OR type ='".$row1['id']."'";
                else $where = "type ='".$row1['id']."'";
                $i++;
            }
            //Now find the group info on all the private groups the user has created
            $query = "select id, cap, name, creator from groups where ($where) AND creator='".$row['id']."' order by id desc";
            $result = mysql_query($query, $master);
            while($row1=mysql_fetch_array($result)){
                If(group_count($row1['id'], $master) < $row1['cap']) {
                    $groupchosen = $row1['id'];
                    $usepublic = 0;
                    $usermessage = "You have been assigned to ".getUname($master, $row['id'])."'s group called ".$row1['name']."<br />";
                }
            }
        }
    $groups_avail=avail_public_groups($master);
        foreach($groups_avail as $v){
            If(in_group($v, $row['id'], $master) && $usepublic == 1) {
                $usermessage = "You have been assigned to the public group called ".getGroupname($master, $v)."<br />";
                $groupchosen = $v;
                $usepublic = 0;
            }
        }
        
    }
    
    //Check group keys
    If($usepublic == 1) {
        $query = "select id, name, cap, creator from `groups` where `key`='$escapedRegCode'";
        $result = mysql_query($query, $master);
        If(mysql_num_rows($result) == 1){
            $row=mysql_fetch_array($result);
            $credited = $row['creator'];
            If(group_count($row['id'], $master) < $row['cap']) {
                $usermessage = "You have been assigned to the group called ".$row['name']."<br />";
                $groupchosen = $row['id'];
                $usepublic = 0;
            }
        }
    }
    
    //Check special keys
    If($usepublic == 1) {
        $query = "select * from `special_keys` where `key`='$escapedRegCode'";
        $result = mysql_query($query, $master);
        If(mysql_num_rows($result) == 1){
        echo "Spec key key found entered<br />";
            $row=mysql_fetch_array($result);
            $effective = strtotime($row['effective']);
            $credited = $row['credit'];
            If($effective < time() && $effective + $row['duration']*24*3600 > time() && $row['used'] < $row['cap']) {
                $usermessage = "You have joined using the special key called ".$row['descrip']."<br />";
                $query = "update `special_keys` set used=used+1 where `key`='$escapedRegCode'";
                $upd = mysql_query($query, $master);
            }
            If($effective >= time()){
                $usermessage = "You have joined using an offer that is not yet effective.<br />";
            }
            If($effective + $row['duration']*24*3600 <= time()){
                $usermessage = "You have joined using an expired offer.<br />";
            }
            If($row['used'] >= $row['cap']){
                $usermessage = "You have joined using an offer that has already been used the maximum number of times.<br />";
            }
        }
    }
    
    //If the key has not resulted in being assigned to any group, use the group the user picked
    If($usepublic == 1) {$groupchosen = $_POST['group']; }

    
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
        $pubkey="";
        for ($i=0; $i < 10; $i++) { 
            $pubkey .= randAlphaNum();
        }
        $prikey="";
        for ($i=0; $i < 10; $i++) { 
            $prikey .= randAlphaNum();
        }

        $query = "insert into Users (username, hashedpw, salt, level, email, used_key, `group`, public_key, private_key, credited)";
        $query .= " values ('$escapedName', '$hashedPW', '$salt', 'public', '$escapedEmail', '$escapedRegCode', '--$groupchosen--', '$pubkey', '$prikey', '$credited'); ";
//        echo $query;
        $upd = mysql_query($query, $master);
//Get the id for the new user
        $query = "select max(id) as id from Users";
        $res = mysql_query($query, $master);
        $max = mysql_fetch_array($res);
//Verify that the user was created

        $query = "select username from Users where id='".$max['id']."'";
        $res = mysql_query($query, $master);
        $name = mysql_fetch_array($res);
        If($name['username'] != $escapedName) die("User not created");
        echo $usermessage;

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
<input type="text" name="username" maxlength="60" size ="30"></input>
</td>
</tr>

<tr>
<th>email</th>
</tr>

<tr>
<td>
<input type="email" name="email" maxlength="100" size ="45"></input>
</td>
</tr>

<?php

If(!isset($getregcode)){
    ?>
<tr>
<th>registration code (if you don't have one, just leave it blank)</th>
</tr>

<tr>
<td>
<input type="text" name="regcode" maxlength="10" size ="10"></input>
</td>
</tr>
    <?php
    } else{
        ?>
<input type="hidden" name="regcode" value="<?php echo $getregcode ?>"></input>
        <?php
    }
    
?>


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
<input type="password" name="password" maxlength="30" size ="30"></input>
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

