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
$right_required = "ViewUsers";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//First, find all users

	$query="SELECT username, level FROM Users ORDER BY level ASC";
	$mem_result = mysql_query($query, $master);

	
?>
<p>

This page shows all users who currently have access to the site. If the user is associated with a member, the member name is shown.

</p>

<table border="1">
<tr>
<th>username</th>
<th>access level</th>
</tr>

<?php 

while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["username"]."</td><td>".$row["level"]."</td></tr>";
}  //Closes while($row = mysql_fetch_array($mem_result))
?>

</table>
<?php

mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";

}

?>
</div> <!-- end #content -->
