<?php
$right_required = "SiteAdmin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<div id="content">
<?php

$post_target = $basepage."?disp=edit_user_settings";
?>
<!-- Form for submissions
<form action="<?php echo $post_target; ?>" method="post" name="search">
</form>
-->

<h4>Any new settings must have at least one each of name and item.</h4>

<?php

//If there is a POST['chg_default'], change the value in the user_setting_template

If(!empty($_POST['chg_default'])) {	
	
	$sql="update user_settings_template set value='".$_POST['value']."' where item='".$_POST['name']."'";
	$res = mysql_query($sql, $main);
	echo $sql;
	echo mysql_error()."<br>";

	

} //Closes If(!empty($_POST['chg_default...
//End change default item logic

//If there is a POST['new_item'], add the item to the database

If(!empty($_POST['new_item'])) {
	//First escape the two entered values
	$escapedName = mysql_real_escape_string($_POST['name']);
	$escapedItem = mysql_real_escape_string($_POST['item']);
	$escapedValue = mysql_real_escape_string($_POST['value']);

	//Second add the new record into the user_settings table
	$sql="insert into user_settings (name, item, value) values ('$escapedName', '$escapedItem', '$escapedValue')";
	$res = mysql_query($sql, $main);
	echo mysql_error()."<br>";

	

} //Closes If(!empty($_POST['ne...
//End add item logic

//If there is a POST['rmv_setting'], delete the setting from the database

If(!empty($_POST['rmv_setting'])) {
	$escapedName = str_replace("%20", " ", $_POST['rmv_setting']);

	//First remove all records from the user_settings table
	$sql="delete from user_settings where name='".$escapedName."'";
	$res = mysql_query($sql, $main);
	echo mysql_error()."<br>";

	//Second, remove all records from each existing user_settings_xx table
	$sqla="show tables like 'user_settings_%'";
	$resa = mysql_query($sqla, $main);

	while($row=mysql_fetch_array($resa)) {
		$sql="delete from ".$row[0]." where item='".$escapedName."'";
		$res = mysql_query($sql, $main);

	} //Closes while($row=mysql_fetch_array($resa)

} //Closes If(!empty($_POST['rm...
//End delete setting logic

//If there is a POST['new_setting'], add the setting to the database

If(!empty($_POST['new_setting'])) {
	//First escape the two entered values
	$escapedName = mysql_real_escape_string($_POST['name']);
	$escapedItem = mysql_real_escape_string($_POST['item']);
	$escapedValue = mysql_real_escape_string($_POST['value']);
	$escapedDescrip = mysql_real_escape_string($_POST['description']);

	//Second add the new record into the user_settings table
	$sql="insert into user_settings (name, item, value, description) values ('$escapedName', '$escapedItem', '$escapedValue', '$escapedDescrip')";
	$res = mysql_query($sql, $main);
	echo mysql_error()."<br>";

	//Third, add the new setting into each existing user_settings_xx table
	$sqla="show tables like 'user_settings_%'";
	$resa = mysql_query($sqla, $main);

	while($row=mysql_fetch_array($resa)) {
		$sql="insert into ".$row[0]." (item, value) values ('$escapedName', '$escapedValue')";
		$res = mysql_query($sql, $main);

	} //Closes while($row=mysql_fetch_array($resa)

} //Closes If(!empty($_POST['ne...
//End add new setting logic

//Display existing user settings and the defaults from the template
$sql = "select * from user_settings order by name, value asc";
$res = mysql_query($sql, $main);
$setting_prev = "";

	$i=0;
while($row=mysql_fetch_array($res)) {
	$settings[]=$row;
	If($row['name'] != $setting_prev) {
		If($i>0) {
?>
</select>
<!--<input type="hidden" name="name" value="<?php echo $row['name']; ?>">-->
<input type="hidden" name="name" value="<?php echo $setting_prev; ?>">
<input type="submit" name="chg_default" value="Set new default"><br>
</form>

<?php
echo $text;
} //Close If($row['name'] != $setting_prev) previous chg_default form
		echo "<h3>".$row['name']."</h3>";
		echo "<h4>".$row['description']."</h4>";
		echo "<h5>Current default:";
		$text="";
		$def_sql = "select value from user_settings_template where item='".$row['name']."'";
		$def_res = mysql_query($def_sql, $main);
		$def = mysql_fetch_array($def_res);
		$def_q = "select item from user_settings where name='".$row['name']."' and value='".$def['value']."'";
		$def_r = mysql_query($def_q, $main);
		$de = mysql_fetch_array($def_r);
		
		echo $de['item']."</h5>";
$value = str_replace(" ", "%20", $row['name']);		
		?>

<form name="add_<?php echo $value; ?>" action="<?php echo $post_target; ?>" method="post" >
<input size="100" type="text" name="item" value="Add an item to setting <?php echo $row['name']; ?> by typing the name of the new item here"><br>
<input type="hidden" name="value" value="<?php echo $row['value']+1; ?>">
<input type="hidden" name="name" value="<?php echo $row['name']; ?>">
<input type="submit" name="new_item" value="And pressing this button to store it"><br>
</form>

		<?php
	
	echo "<form name=\"def_".$value."\" action=\"".$post_target."\" method=\"post\" ><select name=\"value\">";
	} //Closes If($row['name'] != $se...
	$text .= "Item ".$row['value']." is ".$row['item']."<br>";
	echo "<option value=".$row['value'].">".$row['item']."</option>";
	$setting_prev = $row['name'];
	$i++;
} // Closes while($row=mysql_fetch_array($res))

//Closes the last chg_default form
?>
</select>
<input type="hidden" name="name" value="<?php echo $setting_prev; ?>">
<input type="submit" name="chg_default" value="Set new default"><br>
</form>

<?php
//Prints the last rows of settings info
echo $text;
//End display exsiitng user settings

//Add new user setting
?>
<h3>Add new setting</h3>
<h5>Adding a new setting will add that setting to the template and all existing users. The first item you add in will be the default until changed.</h5>
<form action="<?php echo $post_target; ?>" method="post" >
<input size="100" type="text" name="name" value="Name of new setting here"><br>
<input size="100" type="text" name="description" value="Description of new setting here"><br>
<input size="100" type="text" name="item" value="Name of first item in new setting here"><br>
<input type="hidden" name="value" value="1">
<input type="submit" name="new_setting" value="Add New Setting"><br>
</form>

<?php
//End add new user setting form

//Delete user setting
?>
<h3>Delete a setting</h3>
<h5>Deleting a setting will delete that setting from the template and all existing users. Any changes users have made to this setting will be lost if it is recreated in the future.</h5>
<form action="<?php echo $post_target; ?>" method="post" >
<select name="rmv_setting">
<?php
$name_prev = "";
foreach($settings as $v) {
	$value = str_replace(" ", "%20", $v['name']);
	If($v['name'] != $name_prev) echo "<option value=".$value.">".$v['name']."</option>";
	$name_prev=$v['name'];
} //Closes foreach($settings as $v)
	
?>
</select>
<input type="submit" name="delete_setting" value="Delete Setting"><br>
</form>
<?php
//End delete new user setting form


?>
</div> <!-- end #content -->
<?php
} else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
} //Closes else If(isset($_SESSION['le...

?>

