<?php
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
?>


<div id="content">

<?php
$right_required = "ViewUsers";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//First, find all users

	$query="SELECT username, level, `group` FROM Users ORDER BY level ASC";
	$mem_result = mysql_query($query, $master);

	
?>
<p>

This page shows all users who currently have access to the site. If the user is associated with a member, the member name is shown.

</p>

<table border="1">
<tr>
<th>username</th>
<th>access level</th>
<th>groups</th>
</tr>

<?php 

while($row = mysql_fetch_array($mem_result)) {
	$g=str_replace("--", " ", $row["group"]);
	$g_exp = explode(" ", $g);
	echo "<tr><td>".$row["username"]."</td><td>".$row["level"]."</td>";
	echo "<td>";
	foreach($g_exp as $g) {
		$sql_group = "select name from groups where id='$g'";
		$res_group = mysql_query($sql_group, $master);
		while($rowc = mysql_fetch_array($res_group))	echo $rowc["name"]."/";
	}// Closes foreach($g_exp as $g)
	echo "</td></tr>";
}  //Closes while($row = mysql_fetch_array($mem_result))
?>

</table>
<?php

mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";

}

?>
</div> <!-- end #content -->
