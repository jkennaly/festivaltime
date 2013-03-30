<?php

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

?>

