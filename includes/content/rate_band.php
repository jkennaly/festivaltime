<div id="content">



<?php
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

?>
<p>

This page allows for rating the bands.

</p>

<?php

UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);
	
	

	If ($_REQUEST["band"]) {
		echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to view a different band.</a></p>";
	}


//	$band = $_REQUEST["band"];

	$query="SELECT id FROM Users WHERE username='".$_SESSION['user']."'";
	$query_user = mysql_query($query, $main);
	$user_row = mysql_fetch_assoc($query_user);
	$query="SELECT rating FROM Users, ratings WHERE band='$band' AND ratings.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_rating = mysql_query($query, $main);
	$rating_row = mysql_fetch_assoc($query_rating);

	If ( isset($_POST['new_rating']) && !isset($rating_row['rating']) ) {
	echo "No rating logic entered<br>";
	$userid = $user_row['id'];
	$rating = $_POST["new_rating"];
	$sql = "INSERT INTO ratings (band, user, rating) VALUES ('$band', '$userid', '$rating')";
	$sql_run = mysql_query($sql, $main);
	$sql = "INSERT INTO ratings (band, user, rating, festival) VALUES ('$band_master_id', '$userid', '$rating', '$fest_id')";
	$sql_run = mysql_query($sql, $master);	
	}

	If ( isset($_POST['new_rating']) && isset($rating_row['rating']) ) {
	echo "With rating logic entered<br>";
	$userid = $user_row['id'];
	$rating = $_POST["new_rating"];
	$sql = "UPDATE ratings SET rating='$rating' WHERE band='$band' AND user='$userid'";
	$sql_run = mysql_query($sql, $main);
	$sql = "UPDATE ratings SET rating='$rating' WHERE band='$band_master_id' AND user='$userid'";
	$sql_run = mysql_query($sql, $master);	
	}


	If($band){
	$query="select name from bands where id='$band'";
	$query_band = mysql_query($query, $main);
	$band_row = mysql_fetch_assoc($query_band);
	$query="SELECT rating FROM Users, ratings WHERE band='$band' AND ratings.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_rating = mysql_query($query, $main);
	$rating_row = mysql_fetch_assoc($query_rating);
	
	$query="SELECT Users.username AS username, Users.username AS name, rating, comment, descrip, clicks, links.id as link FROM Users LEFT JOIN ratings ON Users.id=ratings.user AND ratings.band='$band' LEFT JOIN comments ON Users.id=comments.user AND comments.band='$band' LEFT JOIN links ON Users.id=links.user AND links.band='$band' WHERE Users.username='".$_SESSION['user']."' GROUP BY Users.id";

	$query_comment = mysql_query($query, $main);



	include $baseinstall."includes/content/blocks/band_info_home.php";

If(isset($rating_row['rating'])) {
	echo "Your current rating for this band is ".$rating_row['rating']."<br>";
} else {
	echo "You do not have a current rating for this band.<br>";
}
?>

<form action="index.php?disp=rate_band&band=<?php echo $band; ?>" method="post">
<table border="1">
<tr>
<th>New Rating</th>
</tr>

<tr>
<td>
<select name="new_rating">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
</select>
</td>
</tr>

</table>

<input type="submit" value="Rate this band">
</form>

<?php
$i = 1;
while ($comment_row = mysql_fetch_assoc($query_comment)) {

	If( $comment_row['username'] == $_SESSION['user'] ) {
		$i_ret = $i;
		$i = 0;
	}

	$table[$i] = "<table border=1><tr><th>User:</th><td>";
	$table[$i] .= $comment_row['name'];


	If( $comment_row['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th><a href=\"".$basepage."?disp=rate_band&band=".$band."\">Rating:</a></th><td>";
	} else {
		$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
	}

	$table[$i] .= $comment_row['rating'];


	If( $comment_row['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th><a href=\"".$basepage."?disp=link_band&band=".$band."\">Link:</a></th><td>";
	} else {
		$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
	}


	$table[$i] .= "<a href=\"".$basepage."links.php?linkid=".$comment_row['link']."\">".$comment_row['descrip']."</a>";


	If( $comment_row['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th colspan=2><a href=\"".$basepage."?disp=comment_band&band=".$band."\">Comment:</a></th></tr><tr><td colspan=2>";
	} else {
		$table[$i] .= "</td></tr><tr><th colspan=2>Comment:</th></tr><tr><td colspan=2>";
	}

	$table[$i] .= $comment_row['comment'];
	$table[$i] .= "</td></tr></table>";

If( $comment_row['username'] == $_SESSION['user'] ) {
	$i = $i_ret;
}
$i_max = $i;
$i = $i +1;	
}


If(!isset($i_ret)){
	
	echo "<br><a href=\"".$basepage."?disp=comment_band&band=".$band."\">Click here to comment on the band.</a>";
	echo "<br><a href=\"".$basepage."?disp=link_band&band=".$band."\">Click here to a link to the band.</a>";

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
