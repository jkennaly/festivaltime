<div id="content">

<?php
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//find all discussion tables

	echo "<h2>Currently active discussions</h2>";

	$sql = "SHOW TABLES LIKE 'discussion_%'";

	$result = mysql_query($sql);

while($row = mysql_fetch_array($result)) {
	$comment = str_replace ( "discussion_" , "" , $row['0'] );
	$query = "select * from comments where id='$comment'";
	$comment_res = mysql_query($query);
	$comment_row = mysql_fetch_array($comment_res);
	$user_query = "select username from Users where id='".$comment_row['user']."'";
	$user_res = mysql_query($user_query);
	$user_row = mysql_fetch_array($user_res);
	$band_query = "select name from bands where id='".$comment_row['band']."'";
	$band_res = mysql_query($band_query);
	$band_row = mysql_fetch_array($band_res);
	echo "<p class=\"discussionindex\"><a href=\"$basepage?disp=discussion&comment=".$comment."\">Discuss Comment $comment, from ".$user_row['username'].", regarding ".$band_row['name']."</a></p>";
} //Closes while($row = mysql_fetch_array($result))

}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
