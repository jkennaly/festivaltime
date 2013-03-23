<div id="content">



<?php
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);


?>
<p>

This page allows for making comments.

</p>

<?php
	
	

	If ($_REQUEST["band"]) {
		echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to view a different band.</a></p>";
	}
//	$band = $_REQUEST["band"];

	$query="SELECT id FROM Users WHERE username='".$_SESSION['user']."'";
	$query_user = mysql_query($query, $main);
	$user_row = mysql_fetch_assoc($query_user);
	$query="SELECT comment FROM Users, comments WHERE band='$band' AND comments.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_comment = mysql_query($query, $main);
	$comment_row = mysql_fetch_assoc($query_comment);

	If ( isset($_POST['new_comment']) && !isset($comment_row['comment']) ) {
	$userid = $user_row['id'];
	$comment = mysql_real_escape_string($_POST["new_comment"]);
	$sql = "INSERT INTO comments (band, user, comment, discuss_current) VALUES ('$band', '$userid', '$comment', '--".$userid."--')";
	$sql_run = mysql_query($sql, $main);
//	echo $sql;
	$sql = "INSERT INTO comments (band, user, comment, festival) VALUES ('$band_master_id', '$userid', '$comment', '$fest_id')";
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

	If ( isset($_POST['new_comment']) && isset($comment_row['comment']) ) {
	$userid = $user_row['id'];
	$comment = mysql_real_escape_string($_POST["new_comment"]);
	$sql = "UPDATE comments SET comment='$comment' WHERE band='$band' AND user='$userid'";
	$sql_run = mysql_query($sql, $main);
	$sql = "UPDATE comments SET comment='$comment' WHERE band='$band_master_id' AND user='$userid'";
	$sql_run = mysql_query($sql, $master);	
	}

	If($band){
	$query="select name from bands where id='$band'";
	$query_band = mysql_query($query, $main);
	$band_row = mysql_fetch_assoc($query_band);
	$query="SELECT comment FROM Users, comments WHERE band='$band' AND comments.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_comment = mysql_query($query, $main);
	$comment_row = mysql_fetch_assoc($query_comment);
	
	$query="SELECT Users.username AS username, Users.username AS name, rating, comment, descrip, clicks, links.id as link FROM Users LEFT JOIN ratings ON Users.id=ratings.user AND ratings.band='$band' LEFT JOIN comments ON Users.id=comments.user AND comments.band='$band' LEFT JOIN links ON Users.id=links.user AND links.band='$band' WHERE Users.username='".$_SESSION['user']."' GROUP BY Users.id";

	$query_comment1 = mysql_query($query, $main);



	include $baseinstall."includes/content/blocks/band_info.php";

If(isset($comment_row['comment'])) {
	echo "Your current comment for this band is ".$comment_row['comment']."<br>";
} else {
	echo "You do not have a current comment for this band.<br>";
}
?>

<form action="index.php?disp=comment_band&band=<?php echo $band; ?>" method="post">
<table border="1">
<tr>
<th>New Comment</th>
</tr>

<tr>
<td>
<textarea rows="16" cols="64" name="new_comment"></textarea>
</td>
</tr>

</table>

<input type="submit" value="Save comment">
</form>

<?php
$i = 1;
while ($comment_row1 = mysql_fetch_assoc($query_comment1)) {

	If( $comment_row1['username'] == $_SESSION['user'] ) {
		$i_ret = $i;
		$i = 0;
	}

	$table[$i] = "<table border=1><tr><th>User:</th><td>";
	$table[$i] .= $comment_row1['name'];


	If( $comment_row1['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
	} else {
		$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
	}

	$table[$i] .= $comment_row1['rating'];


	If( $comment_row1['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
	} else {
		$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
	}


	$table[$i] .= "<a href=\"".$basepage."links.php?linkid=".$comment_row1['link']."\">".$comment_row1['descrip']."</a>";


	If( $comment_row1['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th colspan=2>Comment:</th></tr><tr><td colspan=2>";
	} else {
		$table[$i] .= "</td></tr><tr><th colspan=2>Comment:</th></tr><tr><td colspan=2>";
	}

	$table[$i] .= $comment_row1['comment'];
	$table[$i] .= "</td></tr></table>";

If( $comment_row1['username'] == $_SESSION['user'] ) {
	$i = $i_ret;
}
$i_max = $i;
$i = $i +1;	
}


If(!isset($i_ret)){
	

	foreach ($table as $val) {
		echo "<br>".$val."<br>";
	}
} else {

	for ($i=0; $i<=$i_max; $i++) {
		If(isset($table[$i])) {
			echo "<br>".$table[$i]."<br>";
		}
	}

}

} else {

	$query="select name, id from bands";
	$query_band = mysql_query($query, $main);
?>
<form action="index.php?disp=view_band" method="post">
<select name="band">
<?php 
while($row = mysql_fetch_array($query_band)) {
	echo "<option value=".$row['id'].">".$row['name']."</option>";
}
	
?>
</select>
<input type="submit">
</form>
<?php
	}

rmTable($main, "Users");
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
