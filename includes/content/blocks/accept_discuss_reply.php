<?php

If(!empty($_POST['new_reply'])){
    $comment=$_POST['comment'];
    $discuss_table=$_POST['discuss_table'];
	$escapedReply = mysql_real_escape_string($_POST['new_reply']);
		
	$sql = "INSERT INTO $discuss_table (user, response) VALUES ('$user', '$escapedReply')";
	$result = mysql_query($sql, $main);
//Update the tracking columns in the comment table to reflect the activity
	$query = "UPDATE comments SET discuss_current='--$user--' where id=$comment";
	$upd = mysql_query($query, $main);
} //Closes If($_POST['new_reply'])
?>