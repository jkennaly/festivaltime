#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<div id="content">

<?php
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);

	$comment = $_REQUEST['comment'];

	$post_target = "index.php?disp=discussion&comment=".$comment;

	include $baseinstall."includes/content/blocks/band_info.php";

//Get comment info
$sql = "select comment, username as commenter from comments left join Users on comments.user=Users.id where comments.id='$comment'";
$comment_res = mysql_query($sql, $main);

//Process displaying comment info

If (!empty($_REQUEST['comment'])) {
	$discuss_table = "discussion_".$_REQUEST['comment'];
	$sql = "SHOW TABLES LIKE '$discuss_table'";

	$result = mysql_query($sql, $main);

	If((mysql_num_rows($result) == 0) && (mysql_num_rows($comment_res) != 0)) {
//table did not exist, so create it
		$sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
		$res = mysql_query($sql, $main);
//Logic if discussion tabel does not exist
	} else {
//Logic if table already exists	
//IF a new reply is incoming, add it to the database

//If the discussion has been pinned, log that into the database
If(!empty($_POST['unpin'])){
	$sql = "select pinned from comments where id='$comment'";
	$res = mysql_query($sql, $main);
	$result = mysql_fetch_array($res);
	$stripped = str_replace ( "--$user--" , " " , $result['pinned'] );
	$query = "UPDATE comments SET pinned='$stripped' where id=$comment";
	$upd = mysql_query($query, $main);
} //If($_POST['pin'])

//If the discussion has been ignored, log that into the database
If(!empty($_POST['unignore'])){
	$sql = "select ignored from comments where id='$comment'";
	$res = mysql_query($sql, $main);
	$result = mysql_fetch_array($res);
	$stripped = str_replace ( "--".$user."--" , " " , $result['ignored'] );
	$query = "UPDATE comments SET ignored='$stripped' where id=$comment";
	$upd = mysql_query($query, $main);
} //If($_POST['ignore'])

//If the discussion has been pinned, log that into the database
If(!empty($_POST['pin'])){
	$query = "UPDATE comments SET pinned=CONCAT(pinned,'--$user--') where id=$comment";
	$upd = mysql_query($query, $main);
} //If($_POST['pin'])

//If the discussion has been ignored, log that into the database
If(!empty($_POST['ignore'])){
	$query = "UPDATE comments SET ignored=CONCAT(ignored,'--$user--') where id=$comment";
	$upd = mysql_query($query, $main);
} //If($_POST['ignore'])

	//See if the user is already listed as having discussed the comment
		$sql = "select * from comments where id='$comment'";
		$comment_result = mysql_query($sql, $main);
		$comment_row = mysql_fetch_array($comment_result);
		
	If(!empty($_POST['new_reply'])){
		$escapedReply = mysql_real_escape_string($_POST['new_reply']);
		$sql = "INSERT INTO $discuss_table (user, response) VALUES ('$user', '$escapedReply')";
		$result = mysql_query($sql, $main);
//Update the tracking columns in the comment table to reflect the activity

//IF the user has not participated in this discussion before
		If(strpos($comment_row['discussed'], "--$user--")){
			$query = "UPDATE comments SET discussed=CONCAT(discussed,'--$user--'), discuss_current='--$user--' where id=$comment";
			$upd = mysql_query($query, $main);
		} else {
//If the user has discussed this comment before
			$query = "UPDATE comments SET discuss_current='--$user--' where id=$comment";
			$upd = mysql_query($query, $main);
		} //Closes else If(mysql_num_rows($result)
} //Closes If($_POST['new_reply'])
	
	} // Closes If((mysql_num_rows($result) == 0) && (mysql_num_rows($comment_res) != 0))

//Check to see if the user is current on this discussion. If not, make it happen
$sql = "select * from comments where id='$comment' AND discuss_current LIKE '%--$user--%'";
$result = mysql_query($sql, $main);
//IF the user is not current on this discussion, set user to be current
If(mysql_num_rows($result) == 0){
	$query = "UPDATE comments SET  discuss_current=CONCAT(discuss_current,'--$user--') where id=$comment";
	$upd = mysql_query($query, $main);
} //Closes If(mysql_num_rows($result) == 0)

/*
//now that discussion table exists, display comment first

$row = mysql_fetch_array($comment_res);
echo "<h5 id=\"commenter\">".$row['commenter']."</h5><p id=\"disscussioncomment\">".$row['comment']."</p>";

//Now display all the existing replies
$sql = "select d.id, u.username as uname, d.response as reply, d.created as time from $discuss_table as d left join Users as u on d.user=u.id";
$res = mysql_query($sql, $main);
while($row = mysql_fetch_array($res)) {
	echo "<p class=\"responder\">".$row['uname']." at ".$row['time']."<p><p id=\"reply\">".$row['reply']."</p>";
} //Closes while($row = mysql_fetch_array($res))
*/
//Now show pin/unpin and ignore/unignore buttons and a textarea for leaving a new response if there are any responses
If(!empty($comment_row)) {

//Check to see if this discussion is pinned/ignored
If(strpos($comment_row['pinned'], "-$user--")) {
	$pinned=1;
?>
<form action="<?php echo $post_target; ?>" method="post">
<input type="submit" name="unpin" value="Remove pin this discussion on your home page">
</form>
<?php
} else {
	$pinned = 0;
?>
<form action="<?php echo $post_target; ?>" method="post">
<input type="submit" name="pin" value="Pin this discussion to your home page">
</form>
<?php
} //If(strpos($comment_row['pinned'], "-$user--")

If(strpos($comment_row['ignored'], "-$user--")) {
	$ignored=1;
?>
<form action="<?php echo $post_target; ?>" method="post">
<input type="submit" name="unignore" value="Stop ignoring this discussion">
</form>
<?php
} else {
	$ignored = 0;
?>
<form action="<?php echo $post_target; ?>" method="post">
<input type="submit" name="ignore" value="Ignore this discussion">
</form>
<?php

} //If(strpos($comment_row['ignored'], "-$user--")

}//Closes If(!empty($comment_row)

?>




<form action="<?php echo $post_target; ?>" method="post">
<table>
<tr>
<th>Add to the discussion</th>
</tr>

<tr>
<td>
<textarea rows="16" cols="64" name="new_reply"></textarea>
</td>
</tr>

</table>

<input type="submit" value="Send response">
</form>

<?php
}




//End discussion table logic

//No comment selected.


rmTable($main, "Users");

}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";

}

?>
</div> <!-- end #content -->
