<?php

/* This block updates the database if a rating star was clicked
*  This block requires the following variables: none
*/

$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_POST['new_comment'])){
	$query="SELECT comment FROM comments WHERE band='$band' AND user='$user'";
	$query_comment = mysql_query($query, $main);
	$comment_row = mysql_fetch_assoc($query_comment);
	
	//New comments
	If ( !isset($comment_row['comment']) ) {
	$comment = mysql_real_escape_string($_POST["new_comment"]);
	$sql = "INSERT INTO comments (band, user, comment, discuss_current) VALUES ('$band', '$user', '$comment', '--".$user."--')";
	$sql_run = mysql_query($sql, $main);
//	echo $sql;
	$sql = "INSERT INTO comments (band, user, comment, festival) VALUES ('$band_master_id', '$user', '$comment', '$fest_id')";
	$sql_run = mysql_query($sql, $master);	
	//Get id for new comment
	$sql = "select max(id) as disc from comments";
	$sql_run = mysql_query($sql, $main);
	$res = mysql_fetch_array($sql_run);
	
	//set $discuss_table for comment
	$discuss_table = "discussion_".$res['disc'];

	//Create a discussion table for the comment
	$sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
	$res = mysql_query($sql, $main);
	}
	
	//Modified comments
	If ( isset($_POST['new_comment']) && isset($comment_row['comment']) ) {
	$comment = mysql_real_escape_string($_POST["new_comment"]);
	$sql = "UPDATE comments SET comment='$comment' WHERE band='$band' AND user='$user'";
	$sql_run = mysql_query($sql, $main);
	$sql = "UPDATE comments SET comment='$comment' WHERE band='$band_master_id' AND user='$user'";
	$sql_run = mysql_query($sql, $master);	
	}
	
	
	
	
	
	}
}
?>