<div id="content">

<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

include "includes/content/blocks/filter_functions.php";

//Sets the target for all POST actions
$post_target=$basepage."?disp=home2";

//echo $post_target;

	mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");

//Get data to make the check lists
	$query="select name, id from days";
	$query_day = mysql_query($query);
	$query="select name, id from stages";
	$query_stage = mysql_query($query);
	$query="SELECT id FROM Users WHERE username='".$_SESSION['user']."'";
	$query_user = mysql_query($query);
	$user_row = mysql_fetch_assoc($query_user);
	$userid = $user_row["id"];

include "includes/content/blocks/discussed.php";

include "includes/content/blocks/commented.php";

include "includes/content/blocks/recommended.php";

include "includes/content/blocks/filter_display.php";

include "includes/content/blocks/display_bands.php";



mysql_close();
}
else{
?>
<p>

You do not have sufficient access rights to view this page.

</p>

<?php 
}

?>
</div> <!-- end #content -->
