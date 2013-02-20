<div id="content">

<?php
$right_required = "ViewUsers";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//First, find all users

mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");
	$query="SELECT username, level, `group` FROM Users ORDER BY level ASC";
	$mem_result = mysql_query($query);
$user_row =array();
while($row = mysql_fetch_array($mem_result)) {
	$user_row[]=$row;
}
	
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
foreach($user_row as $rowc) {
	echo "<tr><td>".$rowc["username"]."</td><td>".$rowc["level"]."</td><td>".$rowc["group"]."</td></tr>";

}
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
