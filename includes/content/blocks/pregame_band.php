<?php



//echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to choose from a list of all the bands</a> or select Home from the Nav bar up top to use the filters</p>";



$post_target = $basepage."?disp=view_band&band=$band";

// Collect data display comments, etc.
//If $band is defined
If(!empty($band)) {

If(empty($_POST['edit']) && empty($_POST['edits']))	{
	include $baseinstall."includes/content/blocks/band_info.php";
	$priors = getFestivals($band, $main, $master);
	If(count($priors)>2) {
		foreach($priors as $v) {
			$priorname=getFname($master, $v['fest']);
			$priorband=$v['band'];
			If( $v['fest'] != $fest) echo "<a href=\"".$basepage."?disp=view_band&band=".$priorband."&fest=".$v['fest']."\">".$priorname."</a><br />";
		}
	}
?>

<form id="edit_band_button" action="<?php echo $basepage."?disp=view_band&band=$band"; ?>" method="post">
<input type="submit" value="Edit Band Info" name="edit">
</form>

<?php
} //Closes If(empty($_POST['edit']))

If(!empty($_POST['edit']) || !empty($_POST['edits']))	{
	include $baseinstall."includes/content/blocks/band_info_edit.php";
?>

<form id="edit_band_button" action="<?php echo $basepage."?disp=view_band&band=$band"; ?>" method="post">
<input type="submit" value="Done Editing" name="done">
</form>

<?php
} //Closes If(!empty($_POST['edit']))
	//query to pull basic data



	//If the page viewer was referred by a recommendation, set it to followed
	If(!empty($_GET["recomm"])) {
		$sql = "UPDATE recommendations SET followed='1' WHERE touser='$user' AND band='$band'";
		$res = mysql_query($sql, $main);
	}  //Closes If($_GET["recomm"])
	//end recommedations section


	include "includes/content/blocks/recommendations.php";

	include "includes/content/blocks/liveranked.php";
	
	UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);

	$query="SELECT Users.username AS username, Users.username AS name, rating, comment, descrip, links.id as link, comments.id as comid FROM Users LEFT JOIN ratings ON Users.id=ratings.user AND ratings.band='$band' LEFT JOIN comments ON Users.id=comments.user AND comments.band='$band' LEFT JOIN links ON Users.id=links.user AND links.band='$band' GROUP BY Users.id";
	
	$query_comment = mysql_query($query, $main);

	$i = 1;
	while ($comment_row = mysql_fetch_assoc($query_comment)) {

//This If statement ensures that there is data to display
		If( $comment_row['rating'] || $comment_row['comment'] || $comment_row['link'] ) {

			If( $comment_row['username'] == $_SESSION['user'] ) {
				$i_ret = $i;
				$i = 0;
			} //Closes If( $comment_row['username']...
		$temp_right = "CreateNotes";
		If(!empty($comment_row['comid']) && CheckRights($_SESSION['level'], $temp_right)) {
			$table[$i] = "<form action=\"".$basepage."?disp=discussion&comment=".$comment_row['comid']."\" method=\"post\"><input class=\"responsebutton\" type=\"submit\" value=\"Discuss this comment\"></form><table class=\"commenttable\"><tr><th>User:</th><td>";
		} else {
			$table[$i] = "<table class=\"commenttable\"><tr><th>User:</th><td>";
		} //Closes else If($comment_row['comid'])
		$table[$i] .= $comment_row['name'];

		If( $comment_row['username'] == $_SESSION['user'] ) {	
			$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
		} else {
			$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
		} //Closes else If( $comment_row['username']...

		$table[$i] .= $comment_row['rating'];

		If( $comment_row['username'] == $_SESSION['user'] ) {	
			$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
		} else {
			$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
		} //Closes else If( $comment_row['username']...

		$table[$i] .= "<a href=\"".$basepage."links.php?linkid=".$comment_row['link']."\"  target=\"_blank\">".$comment_row['descrip']."</a>";	


		If( $comment_row['username'] == $_SESSION['user'] ) {	
			$table[$i] .= "</td></tr><tr><th colspan=2 class=\"commentrow\">Comment:</th></tr><tr><td colspan=2>";
		} else {
			$table[$i] .= "</td></tr><tr><th colspan=2 class=\"commentrow\">Comment:</th></tr><tr><td colspan=2>";
		} //Closes else If( $comment_row['username']...

		$table[$i] .= $comment_row['comment'];
		$table[$i] .= "</td></tr></table>";

		If( $comment_row['username'] == $_SESSION['user'] ) {
			$i = $i_ret;
		} //Closes If( $comment_row['username']...
		$i_max = $i;
		$i = $i +1;
		//Closes the If loop preventing users with no data from being displayed
		} // Closes  If( $comment_row['rating'] ||...

		
	}//Closes while ($comment_row = mysql_fetch_assoc($query_comment))

} else { //closes If(!empty($band))
	echo "Band is empty.<br>";
}
?>
<div id="userinfo">

<?php

//echo "<br>User comments, ratings, and links for this band:<a class=\"helplink\" href=\"$basepage?disp=about&band=$band#commenting\">Click here for help with this section</a><br>";


If(!isset($i_ret)){
//Execute this logic if the user has not rated, commented or linked the band	


	If(!empty($table)) {
		foreach ($table as $val) {
			echo "<br>".$val."<br>";
		} //Closes foreach ($table as $val)
	} //Closes If(!empty($table) 
} else {
//Execute this logic if the user has rated, commented or linked the band
	for ($i=0; $i<=$i_max; $i++) {
		If(isset($table[$i])) {
			echo "<br>".$table[$i]."<br>";
		} //Closes If(isset($table[$i]))
	} //Closes for ($i=0; $i<=$i_max; $i++)

}//Closes If(!isset($i_ret)) else
rmTable($main, "Users");
echo "</div> <!-- end #userinfo -->";

?>
