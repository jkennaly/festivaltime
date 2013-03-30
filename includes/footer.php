<?php

?>

<div id="footer">

<p>Copyright &copy 2013 Jason Kennaly</p>
<p><?php
if(session_id() && !empty($_SESSION['user'])){
	echo "User ".$_SESSION['user']." is currently logged in.<br>";
	echo "Current access level is ".$_SESSION['level'].".<br>";
?>
Having trouble with the site? <a href="mailto:festivaltime.us@gmail.com">Send an email to admin</a>
<?php
}
else{
	echo "No user is currently logged in.";
}	

?></p>
</div> <!-- end #footer -->

