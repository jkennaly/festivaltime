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
$right_required = "SiteAdmin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_POST['rmvfest'])) {
	$query="select dbname from festivals where id = '".$_POST["rmvfest"];
	$result = mysql_query($query, $master);
	$dropping=mysql_fetch_array($result);
	$query="DROP DATABASE `".$dropping['dbname']."`";
	echo $query;
	$result = mysql_query($query, $main);
	$sql = "DELETE FROM festivals WHERE id = '".$_POST["rmvfest"]."'";
	$upd = mysql_query($sql, $master);
	$sql2 = "DROP TABLE info_".$_POST["rmvfest"]."";
	$drop = mysql_query($sql2, $master);
	$sql3 = "DROP TABLE info_".$_POST["rmvfest"]."";
	$dropdb = mysql_query($sql3, $master);

} else {
	$query="SELECT id, CONCAT(name, ' ', year) as name FROM festivals ORDER BY id ASC";
	$mem_result = mysql_query($query, $master);
?>	
	</p>
<form action="index.php?disp=delete_fest" method="post">
<p>
<input type="radio" name="rmvfest" value="0" checked="checked">Do not delete any fests
</p>

<table border="1">
<tr>
<th>Festival</th>
<th>delete festival</th>
</tr>
<?php
while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["name"]."</td><td><input type=radio name=\"rmvfest\" value=".$row["id"]."></td></tr>";
}
?>
</table>
<input type="submit">
</form>
<?php
}

}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
