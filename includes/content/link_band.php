<div id="content">



<?php
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

?>
<p>

This page allows for creating a link to the band.

</p>

<?php
		include "/var/www/festival/coachella/2013/includes/content/blocks/band_info_home.php";
	

	If ($_REQUEST["band"]) {
		echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to view a different band.</a></p>";
	}

	mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");

	$band = $_REQUEST["band"];

	$query="SELECT id FROM Users WHERE username='".$_SESSION['user']."'";
	$query_user = mysql_query($query);
	$user_row = mysql_fetch_assoc($query_user);
	$query="SELECT link FROM Users, links WHERE band='$band' AND links.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_link = mysql_query($query);
	$link_row = mysql_fetch_assoc($query_link);

	If ( isset($_POST['new_link']) && !isset($link_row['link']) ) {
	$userid = $user_row['id'];
	$link = mysql_real_escape_string($_POST["new_link"]);
	$descrip = mysql_real_escape_string($_POST["new_descrip"]);
	$sql = "INSERT INTO links (band, user, link, descrip) VALUES ('$band', '$userid', '$link', '$descrip')";
	$sql_run = mysql_query($sql);	
	}

	If ( isset($_POST['new_link']) && isset($link_row['link']) ) {
	$userid = $user_row['id'];
	$link = mysql_real_escape_string($_POST["new_link"]);
	$descrip = mysql_real_escape_string($_POST["new_descrip"]);
	$sql = "UPDATE links SET link='$link', descrip='$descrip', clicks='0' WHERE band='$band' AND user='$userid'";
	$sql_run = mysql_query($sql);	
	}

	If($band){
	$query="select name from bands where id='$band'";
	$query_band = mysql_query($query);
	$band_row = mysql_fetch_assoc($query_band);
	$query="SELECT link, clicks, descrip FROM Users, links WHERE band='$band' AND links.user=Users.id AND Users.username='".$_SESSION['user']."'";
	$query_link = mysql_query($query);
	$link_row = mysql_fetch_assoc($query_link);
	
	$query="SELECT Users.username AS username, Users.username AS name, rating, comment, descrip, clicks, links.id as link FROM Users LEFT JOIN ratings ON Users.id=ratings.user AND ratings.band='$band' LEFT JOIN comments ON Users.id=comments.user AND comments.band='$band' LEFT JOIN links ON Users.id=links.user AND links.band='$band' WHERE Users.username='".$_SESSION['user']."' GROUP BY Users.id";

	$query_comment1 = mysql_query($query);
?>


<table border="1">
<tr>
<th>band name</th>
</tr>
<tr>
<td><?php echo $band_row['name']; ?></td>
</tr>
</table>

<?php
If(isset($link_row['link'])) {
	echo "Your current link for this band is ".$link_row['link'].".<br>";
	echo "The description for the link is ".$link_row['descrip'].".<br>";
	echo "This link has ".$link_row['clicks']." clicks.<br>";
} else {
	echo "You do not have a current link for this band.<br>";
}
?>

<form action="index.php?disp=link_band&band=<?php echo $band; ?>" method="post">
<table border="1">
<tr>
<th>New link</th>
</tr>

<tr>
<td>
<textarea rows="4" cols="64" name="new_link"></textarea>
</td>
</tr>

<tr>
<th>Link Description</th>
</tr>

<tr>
<td>
<input type="text" maxlength="25" name="new_descrip">
</td>
</tr>

</table>

<input type="submit" value="Add link">
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
		$table[$i] .= "</td></tr><tr><th><a href=\"".$basepage."?disp=rate_band&band=".$band."\">Rating:</a></th><td>";
	} else {
		$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
	}

	$table[$i] .= $comment_row1['rating'];


	If( $comment_row1['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th><a href=\"".$basepage."?disp=link_band&band=".$band."\">Link:</a></th><td>";
	} else {
		$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
	}


	$table[$i] .= "<a href=\"".$basepage."links.php?linkid=".$comment_row1['link']."\">".$comment_row1['descrip']."</a>";


	If( $comment_row1['username'] == $_SESSION['user'] ) {	
		$table[$i] .= "</td></tr><tr><th colspan=2><a href=\"".$basepage."?disp=comment_band&band=".$band."\">Comment:</a></th></tr><tr><td colspan=2>";
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
	
	echo "<br><a href=\"".$basepage."?disp=rate_band&band=".$band."\">Click here to rate the band.</a>";
	echo "<br><a href=\"".$basepage."?disp=comment_band&band=".$band."\">Click here to comment on the band.</a>";

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
	$query_band = mysql_query($query);
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

mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
