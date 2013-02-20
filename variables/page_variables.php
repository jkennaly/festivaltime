<?php


/* These variables are available on every page in the coachella site.
*  To ensure they are not stale, they are requested every time a content page
*  is loaded.
*/

//This query collects data about the current band, if one is specified
If(!empty($_SESSION['user'])) {
$uname = $_SESSION['user'];

include "/var/www/festival/coachella/2013/includes/content/blocks/scoring_functions.php";
include "/var/www/festival/coachella/2013/includes/content/blocks/search_selection_function.php";


$query="SELECT id FROM `Users` WHERE username='".$_SESSION['user']."'";
$query_user = mysql_query($query);
$user_row = mysql_fetch_assoc($query_user);
$user = $user_row['id'];


//Gets the current average rating of all ratings and the current user
$sql_curr_avg = "select avg(rating) as average from ratings left join bands on ratings.band=bands.id";

$res = mysql_query($sql_curr_avg);
$curr_avg_rate = mysql_fetch_assoc($res);
$avg_rating = $curr_avg_rate['average'];

$sql_curr_avg = "select avg(rating) as average from ratings where ratings.user='$user'";

$res = mysql_query($sql_curr_avg);
$curr_avg_rate = mysql_fetch_assoc($res);
$uavg_rating = $curr_avg_rate['average'];


If( !empty($_REQUEST['band']) || !empty($_REQUEST['comment']) ) {

//If the comment parameter is passed, get the band info from that comment

If( empty($_REQUEST['band']) && !empty($_REQUEST['comment']) ) {
	$sql = "SELECT band from comments where id='".$_REQUEST['comment']."'";
	$res = mysql_query($sql);
	$arr = mysql_fetch_assoc($res);
	$band = $arr['band'];
}  //Closes If( empty($_REQUEST['band']) && !empty($_REQUEST['comment']) )

//If the band parameteris passed, use that
If(!empty($_REQUEST['band'])) $band = $_REQUEST['band'];

$sql = "SELECT d.name as dayname, s.name as stagename, g.name as genrename, sec_start as stimes, sec_end as etimes, bands.day as day, bands.genre as genre, bands.stage as stage, bands.id as id, bands.name as name, bands.start as stime, bands.end as etime, avg(r1.rating) as rating, ((count(r1.rating)-1)*.05+1)*(avg(r1.rating)-$avg_rating) as score FROM `bands` LEFT JOIN ratings as r1 ON bands.id=r1.band  LEFT JOIN ratings as r2 ON bands.id=r2.band  LEFT JOIN days as d ON bands.day=d.id LEFT JOIN stages as s ON bands.stage=s.id LEFT JOIN genres as g ON bands.genre=g.id WHERE bands.id='$band'";


$res = mysql_query($sql);
$arr = mysql_fetch_assoc($res);

$stimes = $arr['stimes'];
$etimes = $arr['etimes'];
$day = $arr['day'];
$stage = $arr['stage'];
$genre = $arr['genre'];
$name = $arr['name'];
$stime = $arr['stime'];
$etime = $arr['etime'];
$rating = $arr['rating'];
$score = $arr['score'];
$dayname = $arr['dayname'];
$stagename = $arr['stagename'];
$genrename = $arr['genrename'];

$sql = "select rating as urating from ratings where user='$user' and band='$band'";

$res = mysql_query($sql);
$arr = mysql_fetch_assoc($res);

$urating = $arr['urating'];
$uscore = uscoref($band, $user, $avg_rating);


} // Closes If( !empty($_REQUEST['band']) || !empty($_REQUEST['comment']) )

} // ClosesIf(!empty($_SESSION['user'])

//*$uname = The username of the currently logged in user
//*$user = The id of the currently logged in user
//*$uavg_rating = The average rating of all the current users ratings
//*$avg_rating = The average rating of all bands
//*$band = If there is a band $_REQUEST (either through POST or GET), the id of the band
//$name = If there is a band $_REQUEST (either through POST or GET), the name of the band
//*$stage = If there is a band $_REQUEST (either through POST or GET), the stage of the band
//*$stagename = If there is a band $_REQUEST (either through POST or GET), the name of the stage of the band
//*$stimes = If there is a band $_REQUEST (either through POST or GET), the start time of the band as an integer in seconds
//*$etimes = If there is a band $_REQUEST (either through POST or GET), the end time of the band as an integer in seconds
//*$stime = If there is a band $_REQUEST (either through POST or GET), the start time of the band as a string
//*$etime = If there is a band $_REQUEST (either through POST or GET), the end time of the band as a string
//*$day = If there is a band $_REQUEST (either through POST or GET), the day the band plays
//*$dayname = If there is a band $_REQUEST (either through POST or GET), the name of the day the band plays
//$day_until = If there is a band $_REQUEST (either through POST or GET), the number of days until the band plays
//$min_until = If there is a band $_REQUEST (either through POST or GET), the number of minutes until the band plays
//*$rating = If there is a band $_REQUEST (either through POST or GET), the average rating of the band
//*$uscore = If there is a band $_REQUEST (either through POST or GET), the score of the band for the current user
//*$score = If there is a band $_REQUEST (either through POST or GET), the general score of the band
//*$urating = If there is a band $_REQUEST (either through POST or GET), and the user has rated that band, the rating for the band from the current user


?>
