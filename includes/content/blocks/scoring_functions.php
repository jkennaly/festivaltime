<?php

function uscoref($band, $user, $avg_rating) {

$sql1 = "SELECT  ((count(rating)-1)*.05+1)*(avg(rating)-$avg_rating) as score FROM ratings WHERE band='$band'";

$res = mysql_query($sql1);
$arr = mysql_fetch_assoc($res);
$score = $arr['score'];

$sql = "select rating as urating from ratings where user='$user' and band='$band'";

$res = mysql_query($sql);
$arr = mysql_fetch_assoc($res);
$urating = $arr['urating'];

$sql_curr_avg = "select avg(rating) as average from ratings where ratings.user='$user'";

$res = mysql_query($sql_curr_avg);
$curr_avg_rate = mysql_fetch_assoc($res);
$uavg_rating = $curr_avg_rate['average'];

//If($urating) $uscore = (2*($urating- $uavg_rating) + $score)/3;
If($urating) $uscore = $urating + $score;
If(!$urating) $uscore = $uavg_rating + $score;




return $uscore;
}

?>

