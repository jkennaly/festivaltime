<?php

echo "<h3>Select the festival you are looking for</h3>";

//Find all festivals registered in the master database

$sql = "SHOW TABLES LIKE 'info_%'";
$result = mysql_query($sql, $master);
echo "<ul id=\"festlist\">";
while($row = mysql_fetch_array($result)) {
	$sql = "select * from ".$row['0'];
	$res = mysql_query($sql, $master);
	while($row1 = mysql_fetch_array($res)) {
		switch($row1['item']) {
			case "Festival id":
				$fest=$row1['value'];
				break;
			case "Festival Name":
				$festname=$row1['value'];
				break;
			case "Festival Year":
				$festyear=$row1['value'];
				break;
		}
	}
?>
<li><a href="<?php echo $basepage; ?>?disp=home&fest=<?php echo $fest; ?>"><?php echo $festname." ".$festyear; ?></a></li>
<?php
}
echo "</ul>";

$right_required = "AddFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>
<h3>The following link will allow you to add a new show.</h3>

<a href="<?php echo $basepage; ?>?disp=add_fest">Add new festival or concert</a>

<?php
}

$right_required = "SiteAdmin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>
<h3>The following link will allow you to delete a show.</h3>

<a href="<?php echo $basepage; ?>?disp=delete_fest">Delete festival or concert</a>

<?php
}
?>