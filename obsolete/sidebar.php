<div id="sidebar">

<?php
if(session_id() && $_SESSION['user']){
?>

<h3>Manage Users</h3>
	<li><a href="<?php echo $basepage; ?>?disp=change_password">Change Password</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=edit_users">Edit Users</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=view_users">View Users</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=add_users">Add Users</a></li>

<h3>Manage Festival</h3>
	<li><a href="<?php echo $basepage; ?>?disp=add_days">Add Days</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=add_stages">Add Stages</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=add_bands">Add Bands</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=edit_bands">Edit Bands</a></li>


<h3>Social</h3>
	<li><a href="<?php echo $basepage; ?>?disp=discuss_index">Discuss the Music</a></li>

<h3>Stats</h3>
	<li><a href="<?php echo $basepage; ?>?disp=band_scores">Band Scores</a></li>

<h3>The Show</h3>
	<li><a href="<?php echo $basepage; ?>?disp=view_band">View Band</a></li>


<h3>Admin</h3>
	<li><a href="<?php echo $basepage; ?>?disp=time_update">Prep</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=home2">Development Home</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=inclusion_test">Development Test</a></li>
	<li><a href="<?php echo $basepage; ?>?disp=search_dev">Search Test</a></li>
	
<?php
}
else{

mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname) or die( "Unable to select database");

$query = "select * from Users";
$pwq = mysql_query($query);
$num = mysql_num_rows($pwq);

If(!$num){
	echo "<li><a href=\"$basepage?disp=create_tables\">Create Tables</a></li>";
}
mysql_close();
}
?>
</div> <!-- end #sidebar -->

