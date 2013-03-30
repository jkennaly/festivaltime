<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<meta name="author" content="" />

<link rel="stylesheet" type="text/css" href="../styles/mobile.css" media="screen" />

<?php 
session_start(); 

include('../variables/variables.php');

$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

function isInteger($input){
    return(ctype_digit(strval($input)));
}

If(!empty($_GET['fest']) && isInteger($_GET['fest'])) {
	$_SESSION['fest'] = $_GET['fest'];
} 
 include('../includes/check_rights.php');
 include('../includes/content/blocks/database_functions.php'); 
include('../includes/content/blocks/other_functions.php'); 

If(!empty($_SESSION['fest'])){

include('../variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");


 include('../variables/page_variables.php'); 
}

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

$sql = "select bands.start as stime, bands.end as etime, stages.name as stage, bands.id as id, bands.name as name, sec_start, sec_end, genre from bands, stages where bands.id='$band'";
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
$commstring_rate = "$ctime $uname rated $name with a ";


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
</div> <!--end#header_container -->

<div id="button_row">
<a href="comm_confirm.php?commstring=<?php echo $commstring; ?>&commtype=2&fromuser=<?php echo $user; ?>&band=<?php echo $band; ?>">
<img src="../includes/images/checkin.png">
</a>
<a href="more_info.php?commstring=<?php echo $commstring_rate; ?>&commtype=3&fromuser=<?php echo $user; ?>&band=<?php echo $band; ?>&time=<?php echo $_GET['time']; ?>">
<img src="../includes/images/rate.png">
</a>
<a href="mobile.php">
<img src="../includes/images/home.png">
    </a>
</div><!--end#button_row -->


<div id="band_details">

<div class="band_info"><dl>

<dd><?php echo $band_row['name']; ?></dd>
<dd><?php echo getGname($master, $band_row['genre']); ?></dd>
<dd>Group average: <?php echo round($avg_rating, 1); ?></dd>
<dd>
<?php
If($time_untils>0) echo "Starts in ".$time_untils." min</dd>";
else echo "Ends in ".$time_untile." min</dd>";


?>

</dl></div><!---End .band_info -->



<?php
//Begin display friends at show


	//Determine how many group members are at the show
	$num_friends=0;
	$sql_friends1 = "SELECT Max(id) as id, fromuser FROM `comms` where band='$band' and fromuser!='$user' and (commtype='2' or commtype='5') group by fromuser";
//	echo $sql_friends1."<br>";
	$res_friends1 = mysql_query($sql_friends1, $main);
	$i=0;	
	while($row_friends1= mysql_fetch_array($res_friends1)) {
		$sql_friends2 = "SELECT Max(id) as max FROM `comms` where fromuser='".$row_friends1['fromuser']."' AND (commtype='2' OR commtype='5')";
//	echo $sql_friends2."<br>";
		$res_friends2 = mysql_query($sql_friends2, $main);
		$row_friends2= mysql_fetch_array($res_friends2);
		$friend[$i]=$row_friends1;
		$friend[$i]['status']= "left";
		If($row_friends1['id'] == $row_friends2['max']) {
			$num_friends=$num_friends+1;
			$friend[$i]['status']= "present";
		} //Closes If($row_friends1['id'] == $row_friends2['max'])
		$i++;
	} //Closes while($row_friends1= my...

If(!empty($friend)) {
echo "<div class=\"friends\"><dl>";


foreach($friend as $k => $v) {
//For each person at the show, check to see if they have issued a live rating, and return the most recent one
	If($v['status'] == "present") {
		$sql_raters="select * from live_rating where user='".$v['fromuser']."' and band='$band' order by id desc limit 0,1";
//echo $sql_raters."<br>";
		$res_raters=mysql_query($sql_raters, $main);
		If(mysql_num_rows($res_raters)>0) {
			$rate_row = mysql_fetch_array($res_raters);
			echo "<dd class=\"friends".$rate_row['rating']."\">".getUname($master, $v['fromuser'])."(at the show):";
			If(!empty($rate_row['rating'])) echo "".$rate_row['rating']."/";
			If(!empty($rate_row['comment'])) echo "".$rate_row['comment']."";
			echo "</dd>";
		} else echo "<dd>".getUname($master, $v['fromuser'])."(at the show)</dd>";
	} //Closes If($v['status'] == "present")

	If($v['status'] == "left") {
		$sql_raters="select * from live_rating where user='".$v['fromuser']."' and band='$band' order by id desc limit 0,1";
//		echo $sql_raters;
		$res_raters=mysql_query($sql_raters, $main);
		If(mysql_num_rows($res_raters)>0) {
			$rate_row = mysql_fetch_array($res_raters);
			echo "<dd class=\"friends".$rate_row['rating']."\">".getUname($master, $v['fromuser'])."(left the show):";
			If(!empty($rate_row['rating'])) echo "".$rate_row['rating']."/";
			If(!empty($rate_row['comment'])) echo "".$rate_row['comment']."";
			echo "</dd>";
		} else echo "<dd>".getUname($master, $v['fromuser'])."(left the show)</dd>";
	} //Closes If($v['status'] == "present")
}// Closes foreach($friend as $k => $v)

echo "</dl></div><!-- end .friends -->";

} // Closes If(!empty($friend))



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
