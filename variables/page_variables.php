<?php
/*
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/ 


//This query collects data about the current band, if one is specified
If(!empty($_SESSION['user'])) {
$uname = $_SESSION['user'];



include $baseinstall."includes/content/blocks/scoring_functions.php";
include $baseinstall."includes/content/blocks/search_selection_function.php";



$query="SELECT id FROM `Users` WHERE username='".$_SESSION['user']."'";
//echo $query;
$query_user = mysql_query($query, $master);
//echo mysql_error();
$user_row = mysql_fetch_assoc($query_user);
$user = $user_row['id'];


//Gets the current average rating of all ratings and the current user
$sql_curr_avg = "select avg(rating) as average from ratings left join bands on ratings.band=bands.id";

$res = mysql_query($sql_curr_avg, $main);
$curr_avg_rate = mysql_fetch_assoc($res);
$avg_rating = $curr_avg_rate['average'];
If(empty($avg_rating)) $avg_rating = "0.0";

$sql_curr_avg = "select avg(rating) as average from ratings where ratings.user='$user'";

$res = mysql_query($sql_curr_avg, $main);
$curr_avg_rate = mysql_fetch_assoc($res);
$uavg_rating = $curr_avg_rate['average'];


//Gets the current average rating of all ratings and the current user
$sql_curr_avg = "select avg(rating) as average from ratings left join bands on ratings.band=bands.id";

$res = mysql_query($sql_curr_avg, $master);
$mcurr_avg_rate = mysql_fetch_assoc($res);
$mavg_rating = $mcurr_avg_rate['average'];
If(empty($mavg_rating)) $mavg_rating = "0.0";

$sql_curr_avg = "select avg(rating) as average from ratings where ratings.user='$user'";

$res = mysql_query($sql_curr_avg, $master);
$mcurr_avg_rate = mysql_fetch_assoc($res);
$muavg_rating = $mcurr_avg_rate['average'];


If( !empty($_REQUEST['band']) || !empty($_REQUEST['comment']) ) {

//If the comment parameter is passed, get the band info from that comment

If( empty($_REQUEST['band']) && !empty($_REQUEST['comment']) ) {
	$sql = "SELECT band from comments where id='".$_REQUEST['comment']."'";
	$res = mysql_query($sql, $main);
	$arr = mysql_fetch_assoc($res);
	$band = $arr['band'];
}  //Closes If( empty($_REQUEST['band']) && !empty($_REQUEST['comment']) )

//If the band parameteris passed, use that
If(!empty($_REQUEST['band'])) {
$band = $_REQUEST['band'];
} // Closes If(!empty($_REQUEST['band']))

$sql = "SELECT d.name as dayname, s.name as stagename, sec_start as stimes, sec_end as etimes, bands.master_id as master_id, bands.day as day, bands.stage as stage, bands.id as id, bands.name as name, bands.start as stime, bands.end as etime, avg(r1.rating) as rating, ((count(r1.rating)-1)*.05+1)*(avg(r1.rating)-".$avg_rating.") as score FROM `bands` LEFT JOIN ratings as r1 ON bands.id=r1.band  LEFT JOIN ratings as r2 ON bands.id=r2.band  LEFT JOIN days as d ON bands.day=d.id LEFT JOIN stages as s ON bands.stage=s.id WHERE bands.id='$band'";


$res = mysql_query($sql, $main);
If(mysql_num_rows($res)>0) {
$arr = mysql_fetch_assoc($res);

$stimes = $arr['stimes'];
$etimes = $arr['etimes'];
$day = $arr['day'];
$stage = $arr['stage'];
$name = $arr['name'];
$stime = $arr['stime'];
$etime = $arr['etime'];
$rating = $arr['rating'];
$score = $arr['score'];
$dayname = $arr['dayname'];
$stagename = $arr['stagename'];
$band_master_id = $arr['master_id'];

$genrename = getBandGenre($main, $master, $band, $user);
$genre = getBandGenreID($main, $master, $band, $user);



} // Closes If(mysql_num_rows($res)>0)





$sql = "select rating as urating from ratings where user='$user' and band='$band'";

$res = mysql_query($sql, $main);
$arr = mysql_fetch_assoc($res);

$uscore = uscoref($band, $user, $avg_rating, $main);


} // Closes If( !empty($_REQUEST['band']) || !empty($_REQUEST['comment']) )

//Get info on current festival
$sql_info_get="select * from info";
$res_info=mysql_query($sql_info_get, $main);
while ($row=mysql_fetch_array($res_info)) {
	If($row['item']== "Festival id") $fest_id=$row['value'];
	If($row['item']== "Festival Identifier Begin") $fest_id_start=$row['value'];
	If($row['item']== "Festival Identifier End") $fest_id_end=$row['value'];	
	If($row['item']== "Festival Name") $fest_name=$row['value'];
	If($row['item']== "Festival Year") $fest_year=$row['value'];
} // Closes while ($row=mysql_fetch_array($res))


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
