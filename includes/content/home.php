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


$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<div id="content">
<?php


include "includes/content/blocks/filter_functions.php";

//Sets the target for all POST actions
$post_target=$basepage."?disp=home";

//echo $post_target;


//Get data to make the check lists
	$query="select name, id from days";
	$query_day = mysql_query($query, $main);
	$query="select name, id from stages";
	$query_stage = mysql_query($query, $main);
	$query="select name, id from genres";
	$query_genre = mysql_query($query, $master);
	$query="SELECT id FROM Users WHERE username='".$_SESSION['user']."'";
	$query_user = mysql_query($query, $master);
	$user_row = mysql_fetch_assoc($query_user);
	$userid = $user_row["id"];


include "includes/content/blocks/activity.php";

// include "includes/content/blocks/suggestions.php";

If(!empty($_GET['filter_disp'])) {include "includes/content/blocks/filter_display.php"; } else include "includes/content/blocks/filter_hide.php"; 

If(!empty($_GET['disp'])) {
	If($_GET['disp'] == "home") include "includes/content/blocks/filter_process.php";
} //Closes If(!empty($disp)) 

If(empty($_GET['disp'])) {
include "includes/content/blocks/filter_process.php";
}
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

