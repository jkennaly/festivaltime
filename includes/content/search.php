<div id="content">

<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){



include $baseinstall."includes/content/blocks/searchbox.php";
/*
?>
<div id="searchbox">
<form action="<?php echo $basepage."?disp=search"; ?>" method="post">
<input type="text" size="64" name="search_query" autofocus="autofocus"></textarea>
<br>
<input type="checkbox" checked="checked" name="bands" value="true">Bands</input>
<input type="checkbox" checked="checked" name="comments" value="true">Comments</input> 
<?php
$temp_right = "CreateNotes";
If(CheckRights($_SESSION['level'], $temp_right)) echo "<input type=\"checkbox\" checked=\"checked\" name=\"discussions\" value=\"true\">Discussions</input>";
?>
<br>
<input type="submit" value="Search">
</form>
</div> <!-- end #searchbox -->

<?php
*/
UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);

If(!empty($_POST['search_query'])){
	$escapedQuery = mysql_real_escape_string($_POST['search_query']);
	
//Update the tracking columns in the comment table to reflect the activity
	If(!empty($_POST['bands'])) {
		
		$sql = "select id, name from bands where name like '%$escapedQuery%'";
		$result = mysql_query($sql, $main);
		If(mysql_num_rows($result) > 0 ) {
			echo "<h4>The following bands were found like \"<i>$escapedQuery</i>\":</h4>";
			include $baseinstall."includes/content/blocks/display_bands.php";
			
		}	
		If(mysql_num_rows($result) == 0 ) echo "No bands found like \"$escapedQuery\"<br>";
	} //Closes If($_POST['bands'])

	If(!empty($_POST['comments'])) {
		
		$sql = "select comments.id as id, bands.id as band, comment, username, name from comments left join Users on comments.user=Users.id left join bands on comments.band=bands.id where comments.comment like '%$escapedQuery%'";
		$result = mysql_query($sql, $main);
		If(mysql_num_rows($result) > 0 ) {
			echo "<div id=\"commentlist\"><h4>The following comments were found like \"<i>$escapedQuery</i>\":</h4>";
			while($row=mysql_fetch_array($result)) {
				echo "<p>Comment from ".$row['username']." about <a href=\"".$basepage."?disp=view_band&band=".$row['band']."\">".$row['name']."</a><br>".$row['comment']."</p>";
			} //Closes while($row=mysql_fetch_array($result)) 
			echo "</div><!-- end #commentlist -->";
		} // Closes If(mysql_num_rows($result) > 0 )
		If(mysql_num_rows($result) == 0 ) echo "<div id=\"commentlist\"><p>No comments found like \"$escapedQuery\"</p></div><!-- end #commentlist -->";
	} //Closes If($_POST['comments'])

	If(!empty($_POST['discussions'])) {
		$j=0; //Nonzero value for $j indicates discussion result
		$sql = "select comments.id as id, bands.id as band, CONCAT(LEFT(comment, 75), '...') as comment, username, name from comments left join Users on comments.user=Users.id left join bands on comments.band=bands.id where (comments.discussed like '%--%' OR comments.discuss_current like '%--%')";
		$result = mysql_query($sql, $main);
		If(mysql_num_rows($result) > 0 ) {
			while($row=mysql_fetch_array($result)) { //cycles every comment with a discussion
				//Search through each discussion for the string
				$query = "select username, response from discussion_".$row['id']." as d left join Users on d.user=Users.id where d.response like '%$escapedQuery%'";
				$res = mysql_query($query, $main);
				$i=0; //Used to indicate no results found in this discussion yet
				
				while($drow=mysql_fetch_array($res)) { //cycles every reply within discussion
					If($j == 0) echo "<div id=\"discussionlist\"><h4>The following discussions were found like \"<i>$escapedQuery</i>\":</h4>";
					$j=1;
					If($i == 0) echo "<h5>In the ".$row['name']." discussion about ".$row['username']."'s comment \"".$row['comment']."\"</h5>";
					$i=$i + 1;
					echo "<p>".$drow['username']." <a href=\"".$basepage."?disp=discussion&comment=".$row['id']."\">said</a>:<br>".$drow['response']."</p>";
				} //Closes while($drow=mysql_fetch_array($res))
			} //Closes while($row=mysql_fetch_array($result)) 
			echo "</div><!-- end #discussionlist -->";
		} // Closes If(mysql_num_rows($result) > 0 )
		If($j == 0 ) echo "<div id=\"discussionlist\"><p>No discussions found like \"$escapedQuery\"</p></div><!-- end #commentlist -->";
	} //Closes If($_POST['comments'])

	
} //Closes If($_POST['search_query'])




rmTable($main, "Users");
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";

}

?>
</div> <!-- end #content -->
