<?php

/* This block displays links to the bands with nine most recent comments.
*  This block requires the following variables: none
*/

$temp_right = "CreateNotes";
If(isset($_SESSION['level']) && !empty($user) && CheckRights($_SESSION['level'], $temp_right)){

//Display header for discussions section



//Find all the current discussions that are not ignored, plus all pinned discussions

	$sql = "SHOW TABLES LIKE 'discussion_%'";

	$result = mysql_query($sql);
//Used to cap the max number of discussion results displayed
	$i=0;
	$j=0;
while($row = mysql_fetch_array($result)) {
	
	$comment = str_replace ( "discussion_" , "" , $row['0'] );
	$query = "select * from comments where id='$comment' AND (discuss_current NOT LIKE '%--$user--%' OR pinned LIKE '%--$user--%') AND ignored NOT LIKE '%--$user--%'";
	$comment_res = mysql_query($query);
	
	If(mysql_num_rows($comment_res) > 0){
		IF($j == 0) echo "<div id=\"discussions\" class=\"activelist\"><p class=\"activehead\">Bands that have new discussion activity:<a class=\"helplink\" href=\"".$basepage."?disp=about#discussions\">Click here for help with this section</a></p>";
		$comment_row = mysql_fetch_array($comment_res);
		$user_query = "select username from Users where id='".$comment_row['user']."'";
		$user_res = mysql_query($user_query);
		$user_row = mysql_fetch_array($user_res);
		$band_query = "select name from bands where id='".$comment_row['band']."'";
		$band_res = mysql_query($band_query);
		$band_row = mysql_fetch_array($band_res);
		echo "<p class=\"discussionindex\"><a href=\"$basepage?disp=discussion&comment=".$comment."\">Discuss ".$user_row['username']."'s comment regarding ".$band_row['name']."</a></p>";
		If(!strpos($comment_row['pinned'], "-$user--")) $i=$i+1;
		$j = $j+1;
		If($i>3) break;
	} // Closes If(mysql_num_rows($comment_res) > 0)
	
	If($i>3) break;
} //Closes while($row = mysql_fetch_array($result))
If($j>0) echo "<br /></div><!-- End #discussions -->";

//End of active discussions
}

?>
