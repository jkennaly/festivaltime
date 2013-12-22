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


function uscoref($band, $user, $avg_rating, $mysql_link) {

$sql1 = "SELECT  ((count(rating)-1)*.05+1)*(avg(rating)-$avg_rating) as score FROM ratings WHERE band='$band'";

$res = mysql_query($sql1, $mysql_link);
If(!empty($res)) {
$arr = mysql_fetch_assoc($res);
$score = $arr['score'];

$sql = "select rating as urating from ratings where user='$user' and band='$band'";

$res = mysql_query($sql, $mysql_link);
$arr = mysql_fetch_assoc($res);
$urating = $arr['urating'];

$sql_curr_avg = "select avg(rating) as average from ratings where ratings.user='$user'";

$res = mysql_query($sql_curr_avg, $mysql_link);
$curr_avg_rate = mysql_fetch_assoc($res);
$uavg_rating = $curr_avg_rate['average'];

//If($urating) $uscore = (2*($urating- $uavg_rating) + $score)/3;
If($urating) $uscore = $urating + $score;
If(!$urating) $uscore = $uavg_rating + $score;
} else {
$uscore = 0;
} // Closes else If(!empty($res))



return $uscore;
}

function uscoref2($band, $user, $avg_rating, $mysql_link) {

$sql1 = "SELECT avg(rating) as score FROM ratings WHERE band='$band' and user='$user'";
//echo $sql1."<br />";

//echo "alert(\"Found a band: ".$band." with a user  of ".$user."\");";

$res = mysql_query($sql1, $mysql_link);
If(mysql_num_rows($res)>0) {
	$arr = mysql_fetch_assoc($res);
	$uscore = $arr['score'];
} 
If(empty($uscore)) {
	$sql_curr_avg = "select avg(rating) as average from ratings where band='$band'";
	$res1 = mysql_query($sql_curr_avg, $mysql_link);
	If(mysql_num_rows($res1)>0) {
		$curr_avg_rate = mysql_fetch_assoc($res1);
		$uscore = $curr_avg_rate['average'];
	} 
If(empty($uscore)) $uscore = $avg_rating;
}

return $uscore;
}

function count_digit($number) {
return strlen((string) $number);
}

function act_rating($band, $user)
{
//This function returns the rating for a given user for a given band in a given fest, or 0 if unrated
    global $master, $fest;
    $sql = "SELECT `content` FROM `messages` WHERE `band`='$band' and `fromuser`='$user' and `remark`='2' and `mode`='1' and `festival`='$fest' and `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $row=mysql_fetch_array($res);
    If (!empty($row['rating'])) $rate = $row['content'];
    else $rate = 0;
return $rate;
}

function act_live_rating($band, $user)
{
//This function returns the gametime rating for a given user for a given band in a given fest, or 0 if unrated
    global $master, $fest;
    $sql = "SELECT `content` as rating, `id` FROM `messages` WHERE `band`='$band' and `fromuser`='$user' and `remark`='2' and `mode`='2' and `festival`='$fest' and `deleted`!='1' ORDER BY id DESC LIMIT 1";
    $res = mysql_query($sql, $master);
    $row=mysql_fetch_array($res);
If(!empty($row['rating'])) $rate = $row['rating'];
else $rate = 0;
return $rate;
}

function all_live_rating($main) {
	//This function returns all gametime ratings for a given festival
$sql = "SELECT lr.id as id, lr.user as user, lr.band as band, lr.rating as rating, lr.`comment` as comment, lr.`msgtime` as time
FROM `live_rating` lr
LEFT JOIN `live_rating` lr2 ON lr.user = lr2.user AND lr.band = lr2.band AND lr.id < lr2.id
WHERE lr2.user IS NULL
ORDER BY lr.id";
$res=mysql_query($sql, $main);
while($row=mysql_fetch_array($res)){
	$result[] = $row;
}
return $result;
}

function avg_live_rating ($main){
	//This function returns the average live rting for a given festival
	$ratingArray = all_live_rating($main);
	$i = 0;
	$total = 0;
	foreach ($ratingArray as $v){
		$total = $total + $v['rating'];
		$i++;
	}
	$avg = $total/$i;
	return $avg;
}

function avg_live_rating_band ($main, $band){
	//This function returns the average live rting for one band for a given festival
	
	$sql = "SELECT lr.id as id, lr.user as user, lr.band as band, lr.rating as rating, lr.`comment` as comment, lr.`msgtime` as time
	FROM `live_rating` lr
	LEFT JOIN `live_rating` lr2 ON lr.user = lr2.user AND lr.band = lr2.band AND lr.id < lr2.id
	WHERE lr2.user IS NULL AND lr.band='$band'
	ORDER BY lr.id";
	
	$res=mysql_query($sql, $main);
	if (mysql_num_rows($res) !== 0) {
	$i = 0;
	$total = 0;
	while($row=mysql_fetch_array($res)){
		$total = $total + $row['rating'];
		$i++;
	}
	$avg = $total/$i;
	}
	else return false;
	return $avg;
}

?>

