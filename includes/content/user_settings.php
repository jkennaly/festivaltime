<?php
$right_required = "EditSelf";
If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
If (empty($_POST['user']) || $_POST['user'] == $user){
?>

<div id="content">
<?php

$post_target = $basepage."?disp=user_settings";


//If there is a POST['chg_setting'], change the value in the user_setting_template

If(!empty($_POST['chg_setting'])) {	
	
	$sql="update user_settings_$user set value='".$_POST['item_select']."' where item='".$_POST['name']."'";
	$res = mysql_query($sql);

	

} //Closes If(!empty($_POST['chg_setting...
//End change settings item logic

//Cycle through each item
$query="select item, value from user_settings_$user";
$result = mysql_query($query);
echo mysql_error()."<br>";

while($cycle=mysql_fetch_array($result)) {
$item = $cycle['item'];
echo "<h3>$item</h3>";

$sql = "select item from user_settings where name='$item' and value='".$cycle['value']."'";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
echo "<h5>Current selection is ".$row['item']."</h5>";

//Display existing user settings and selectors to change them
$sql = "select value, item from user_settings where name='$item'";
$res = mysql_query($sql);

?>
<form name="chg_<?php echo $item; ?>"action="<?php echo $post_target; ?>" method="post">
<select name="item_select">
<?php 
while($row = mysql_fetch_array($res)) {
	echo "<option value=".$row['value'].">".$row['item']."</option>";
}
	
?>
</select>
<input type="hidden" name="name" value="<?php echo $cycle['item']; ?>">
<input type="hidden" name="user" value="<?php echo $user; ?>">
<input type="submit" name="chg_setting" value="Change this setting">
</form>
<?php
}


?>
</div> <!-- end #content -->
<?php
}
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>

