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
$right_required = "EditSite";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If(!empty($_POST)){

//Escape entered info

	$escapedDescrip = mysql_real_escape_string($_POST['descrip']);
    $cap = $_POST['cap'];
    $credit = $_POST['credit'];
    $effective = $_POST['effective'];
    $duration = $_POST['duration'];

    $key="";
    for ($i=0; $i < 10; $i++) { 
        $key .= randAlphaNum();
    }
	$query = "insert into special_keys (descrip, creator, `key`, cap, effective, duration, credit) values ('$escapedDescrip', '$user', '$key', '$cap', '$effective', '$duration', '$credit'); ";
//    echo $query;
	$upd = mysql_query($query, $master);

		
	

}

//First, find all current days
    
    $groupt_sql = "select * from `special_keys`";
    $groupt_res = mysql_query($groupt_sql, $master);

?>
<p>

In order for a special key to work, it will have to be programmed into the site code!

</p>
<form action="index.php?disp=add_special_key" method="post">
<table border="1">
<tr>
<th>description</th>
</tr>
<tr>
<td>
<input type="text" name="descrip" maxlength="100" size ="30">
</td>
</tr>
<tr>
<th>credited user</th>
</tr>
<tr>
<td>
<select name="credit">
    <?php
       $query = "select id, username as name from Users";
       $result = mysql_query($query, $master);
       while($row=mysql_fetch_array($result)){
            If($row['id'] == $user) echo "<option value=\"".$row['id']."\" selected=\"selected\">".$row['name']."</option>";
            else echo "<option value=\"".$row['id']."\">".$row['name']."</option>";
       }
    ?>
</select>
</td>
</tr>
<tr>
<th>cap</th>
</tr>
<tr>
<td>
<input type="number" name="cap" min="0" max="1000000" step="1" value="20">
</td>
</tr>
<tr>
<th>effective</th>
</tr>
<tr>
<td>
<input type="date" name="effective" value="<?php echo date('Y-m-d'); ?>">
</td>
</tr>
<tr>
<th>duration</th>
</tr>
<tr>
<td>
<input type="number" name="duration" min="1" max="365" step="1" value="90">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following special keys have been defined.
</p>

<table border="1">
<tr>
<th>key</th>
<th>description</th>
<th>creator</th>
<th>credited to</th>
<th>cap</th>
<th>used</th>
<th>effective</th>
<th>duration</th>
</tr>

<?php 
while($row = mysql_fetch_array($groupt_res)) {
	echo "<tr><td>".$row["key"]."</td><td>".$row["descrip"]."</td><td>".getUname($master, $row["creator"])."</td><td>".getUname($master, $row["credit"])."</td>";
	echo "<td>".$row["cap"]."</td><td>".$row["used"]."</td><td>".$row["effective"]."</td><td>".$row["duration"]."</td></tr>";

}
?>

</table>

<?php


}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
