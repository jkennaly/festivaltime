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


if (!empty($_POST)) {
    session_destroy();
    Login($master);
}
if(!empty($_SESSION['user'])){
    if (empty($_POST)) echo "User $uname already logged in. Press Log Out first to change user.";
    $user =
        include $baseinstall . "includes/unselected/main.php";
}
else{
?>
<div id="content">
<form id='login' action='index.php?disp=login' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Login</legend>
<input type='hidden' name='submitted' id='submitted' value='1'/>
<label for='username' >UserName*:</label>
<input type='text' name='username' id='username'  maxlength="50" />
<label for='password' >Password*:</label>
<input type='password' name='password' id='password' maxlength="50" />
<input type='submit' name='Submit' value='Submit' />
</fieldset>
</form>
<p>Or <a href="<?php echo $basepage; ?>?disp=register">Register</a></p>
</div> <!-- end #content -->

<?php
}


function Login($mysql_link)
{
	if(isset($_SESSION['uid'])){
		session_destroy();
	}

    if(empty($_POST['username']))
    {
        die("UserName is empty!");
        return false;
    }

    if(empty($_POST['password']))
    {
        die("Password is empty!");
        return false;
    }
    
    If(!CheckLoginInDB($_POST['username'],$_POST['password'], $mysql_link)){
		die("That username or password is invalid.");
		return false;
	}

    session_start();

    $_SESSION['user'] = mysql_real_escape_string($_POST['username']);

	$query = "select * from Users where username = '".$_SESSION['user']."'";
	$pwq = mysql_query($query, $mysql_link);
	$row = mysql_fetch_assoc($pwq);
	$_SESSION['level'] = $row['level'];
    global $user;
    $user = $row['id'];
    $sql = "UPDATE Users SET count=count+1 WHERE username='".$_SESSION['user']."'";
	$pwq = mysql_query($sql, $mysql_link);



   return true;
}

function CheckLoginInDB($username,$password, $mysql_link)
{
$escapedName = mysql_real_escape_string($username);
$escapedPW = mysql_real_escape_string($password);
$saltQuery = "select salt from Users where username = '$escapedName';";
$result = mysql_query($saltQuery, $mysql_link);
$row = mysql_fetch_assoc($result);
$salt = $row['salt'];

$saltedPW =  $escapedPW . $salt;

$hashedPW = hash('sha256', $saltedPW);

    $query = "select * from Users where username = '$escapedName' and hashedpw = '$hashedPW' AND `deleted`!='1'; ";

    $pwq = mysql_query($query, $mysql_link);

# if nonzero query return then successful login

$row = mysql_fetch_assoc($pwq);

//echo "Logging in user ".$row["username"]."<br>";

    If($row["username"] == $escapedName){
	
	return true;
}
return false;
}
?>

