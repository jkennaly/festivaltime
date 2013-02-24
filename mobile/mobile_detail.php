<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php 
include("../variables/variables.php");

$main = mysql_connect($dbhost,$dbuser,$dbpw);
$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");
@mysql_select_db($master_db, $master) or die( "Unable to select master database");


 session_start(); 
 include('../variables/page_variables.php');  
 include('../includes/check_rights.php'); 
 include('../includes/content/blocks/database_functions.php');

?>
<title>Should I check out...</title>

</head>
<body>



<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

	$band = $_REQUEST["band"];

	If(!empty($band)){
	$query="SELECT AVG(rating) AS avg FROM `ratings` WHERE band='$band'";
	$query_rating = mysql_query($query, $main);
	$rating_row = mysql_fetch_assoc($query_rating);
	echo $rating_row['avg'];
	$query="SELECT SUM(clicks) AS clicks FROM `links` WHERE band='$band'";
	$query_link = mysql_query($query, $main);
	$link_row = mysql_fetch_assoc($query_link);
	
//This function pulls the Users data from the master for temporary use
UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);


	$query="SELECT Users.username AS username, Users.username AS name, rating, comment, descrip, links.id as link FROM Users LEFT JOIN ratings ON Users.id=ratings.user AND ratings.band='$band' LEFT JOIN comments ON Users.id=comments.user AND comments.band='$band' LEFT JOIN links ON Users.id=links.user AND links.band='$band' GROUP BY Users.id";
	
	$query_comment = mysql_query($query, $main);


$avg_rating = $rating_row['avg'];



/*******************************************************************
*
*           Begin inserted logic for detemrining commstring
*
*******************************************************************/

$sql = "select bands.start as stime, bands.end as etime, stages.name as stage, bands.id as id, bands.name as name, sec_start, sec_end from bands, stages where bands.id='$band'";
$res = mysql_query($sql, $main);
$band_row = mysql_fetch_array($res);
$name=$band_row['name'];
$band=$band_row['id'];
$stage=$band_row['stage'];

$query="SELECT id, username FROM Users WHERE username='".$_SESSION['user']."'";
$query_user = mysql_query($query, $main);
$user_row = mysql_fetch_array($query_user);
$user = $user_row['id'];
$uname = $user_row['username'];


$stime= substr($band_row['stime'], 11, 5);
$etime= substr($band_row['etime'], 11, 5);
$ctime= strftime("%H:%M");

/*******************************************************************
*
*           End inserted logic for detemrining commstring
*
*******************************************************************/

$commstring = "$ctime $uname is at $name $stage $stime $etime";


$time_untils = $band_row['sec_start'] - $_GET['time'];
$time_untile = $band_row['sec_end'] - $_GET['time'];

$time_untils = round($time_untils/60, 0);
$time_untile = round($time_untile/60, 0);

If($time_untils<=0) $time_untils = "On now";

?>

<div id="header_container">
    <div id="header">
        <?php echo $band_row["name"]."-".$band_row["stage"]; ?>
    </div>
</div>




<div id="band_details">
<a href="comm_confirm.php?commstring=<?php echo $commstring; ?>&commtype=2&fromuser=<?php echo $user; ?>&band=<?php echo $band; ?>">

<table border="1">
<tr>
<th>band name</th>
<th>average rating</th>
<th>min until start</th>
<th>min until end</th>
</tr>
<tr>
<td><?php echo $band_row['name']; ?></td>
<td><?php echo round($avg_rating, 1); ?></td>
<td><?php echo $time_untils; ?></td>
<td><?php echo $time_untile; ?></td>
</tr>
</table>

<?php
//Begin display friends at show

$sql_friends = "SELECT DISTINCT username as friend, comms.fromuser as sender FROM `comms` left join Users on comms.fromuser=Users.id where band='$band' and fromuser!='$user'";
$res_friends = mysql_query($sql_friends, $main);
$num_friends=mysql_num_rows($res_friends);

If($num_friends>0) {
echo "<div class=\"friends\"><dl><dt>People at the show:</dt>";


while($row=mysql_fetch_array($res_friends)) {
//For each person at the show, check to see if they have issued a live rating, and return the most recent one
	$sql_raters="select * from live_rating where user='".$row['sender']."' and band='$band' order by id desc limit 0,1";
	$res_raters=mysql_query($sql_raters, $main);
	If(mysql_num_rows($res_raters)>0) {
		$rate_row = mysql_fetch_array($res_raters);
		echo "<dd>".$row['friend'].":</dd>";
		If(!empty($rate_row['rating'])) echo "<dt>Rated show a ".$rate_row['rating'].":</dt>";
		If(!empty($rate_row['comment'])) echo "<dt>Said: ".$rate_row['comment'].":</dt>";
	} else echo "<dd>".$row['friend']."</dd>";
}
echo "</dl></div><!-- end#friends -->";
} // Closes If($num_friends>0)

//End display friends at show


$i = 1;
while ($comment_row = mysql_fetch_assoc($query_comment)) {

//This If statement ensures that there is data to display
If( $comment_row['rating'] || $comment_row['comment'] || $comment_row['link'] ) {

	If( $comment_row['username'] == $_SESSION['user'] ) {
		$i_ret = $i;
		$i = 0;
	}

	$table[$i] = "<table border=1><tr><th>User:</th><td>";
	$table[$i] .= $comment_row['name'];


	$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";

	$table[$i] .= $comment_row['rating'];	


	$table[$i] .= "</td></tr><tr><th colspan=2>Comment:</th></tr><tr><td colspan=2>";

	$table[$i] .= $comment_row['comment'];
	$table[$i] .= "</td></tr></table>";

	If( $comment_row['username'] == $_SESSION['user'] ) {
		$i = $i_ret;
	}
$i_max = $i;
$i = $i +1;
//Closes the If loop preventing users with no data from being displayed
}	

//Closes the while loop
}

If(!empty($table)) {
If(!isset($i_ret)) {
	

	foreach ($table as $val) {
		echo "<br>".$val."<br>";
	} // Closes foreach ($table as $val)
} else {

	for ($i=0; $i<=$i_max; $i++) {
		If(isset($table[$i])) {
			echo "<br>".$table[$i]."<br>";
		} // Closes If(isset($table[
	}//Closes for ($i=0; $i<=$i_max; $

} // Closes else If(!isset($i_re
} // Closes If(!empty($table))

} else {

	$query="select name, id from bands";
	$query_band = mysql_query($query, $main);
?>
<form action="mobile_detail.php" method="get">
<select name="band">
<?php 
while($row = mysql_fetch_array($query_band)) {
	echo "<option value=".$row['id'].">".$row['name']."</option>";
}
	
?>
</select>
<input type="submit">
</form>
</a>
</div> <!--end #band_details -->


<?php
	}


rmTable($main, "Users");
mysql_close();
}
else{
?>
<p>

You do not have sufficient access rights to view this page.

<a class="loginlink" href="<?php echo $basepage; ?>?disp=login">Log In</a>

</p>

<?php 
}
If(!empty($main)) mysql_close($main);
If(!empty($master)) mysql_close($master);
/*
<!--- Footer disabled
<div id="footer_container">
    <div id="footer">
        <div class="time">
	<?php echo $disp_str; ?>
	</div>
        <div class="stage">
	<?php echo $band_row["stage"]; ?>
	</div>
        <div class="score">
	<?php echo $band_row["score"]; ?>
	</div>
    </div>
</div>
Footer disabled ---->
*/

?>



</body>
</html>
