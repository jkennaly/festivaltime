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
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

	If (!empty($_REQUEST["viewuser"]) && userVisibleToUser($user, $_REQUEST["viewuser"], $master)) {

$viewUser = mysql_real_escape_string($_REQUEST["viewuser"]);
echo getUname($master, $viewUser);


} else {
//If no visile user is passed through GET or POST, display a selector

$visibleUsers = getVisibleUsers($user, $master);
?>
<form action="index.php?disp=view_user" method="post">
<select name="viewuser">
<?php 
foreach ($visibleUsers as $row) {
	echo "<option value=".$row['id'].">".$row['username']."</option>";
}
	
?>
</select>
<input type="submit">
</form>
<?php
	}

}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
