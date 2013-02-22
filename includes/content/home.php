<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<div id="content">
<?php


include "includes/content/blocks/filter_functions.php";

//Sets the target for all POST actions
$post_target=$basepage."?disp=home#bandlist";

//echo $post_target;


//Get data to make the check lists
	$query="select name, id from days";
	$query_day = mysql_query($query, $main);
	$query="select name, id from stages";
	$query_stage = mysql_query($query, $main);
	$query="SELECT id FROM Users WHERE username='".$_SESSION['user']."'";
	$query_user = mysql_query($query, $master);
	$user_row = mysql_fetch_assoc($query_user);
	$userid = $user_row["id"];

include "includes/content/blocks/discussed.php";

include "includes/content/blocks/commented.php";

include "includes/content/blocks/recommended.php";

include "includes/content/blocks/filter_display.php";

If(!empty($_GET['disp'])) {
	If($_GET['disp'] == "home") include "includes/content/blocks/filter_process.php";
} //Closes If(!empty($disp)) 


echo "<p>Bands:<a class=\"helplink\" href=\"".$basepage."?disp=about#bands\">Click here for help with this section</a></p>";


If(!empty($result)) include "includes/content/blocks/display_bands.php";

?>
</div> <!-- end #content -->
<?php
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>

