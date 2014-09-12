<?php
/*
//Copyright (c) 2013-2014 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/


function uscoref($band, $user, $avg_rating, $mysql_link)
{

    $sql1 = "SELECT  ((count(rating)-1)*.05+1)*(avg(rating)-$avg_rating) as score FROM ratings WHERE band='$band'";

    $res = mysql_query($sql1, $mysql_link);
    If (!empty($res)) {
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
        If ($urating) $uscore = $urating + $score;
        If (!$urating) $uscore = $uavg_rating + $score;
    } else {
        $uscore = 0;
    } // Closes else If(!empty($res))


    return $uscore;
}

function uscoref2($band, $user)
{

    $sql1 = "SELECT avg(content) as score FROM `messages` WHERE `band`='$band' and `fromuser`='$user' and `remark`='2' and `deleted`!='1'";
echo $sql1."<br />";

//echo "alert(\"Found a band: ".$band." with a user  of ".$user."\");";

    $res = mysql_query($sql1, $mysql_link);
    If (mysql_num_rows($res) > 0) {
        $arr = mysql_fetch_assoc($res);
        $uscore = $arr['score'];
    }
    If (empty($uscore)) {
        $sql_curr_avg = "SELECT avg(content) as average FROM `messages` WHERE `band`='$band' and `remark`='2' and `deleted`!='1'";
        $res1 = mysql_query($sql_curr_avg, $mysql_link);
        If (mysql_num_rows($res1) > 0) {
            $curr_avg_rate = mysql_fetch_assoc($res1);
            $uscore = $curr_avg_rate['average'];
        }
        If (empty($uscore)) $uscore = 0;
    }

    return $uscore;
}

function count_digit($number)
{
    return strlen((string)$number);
}

function act_rating($band, $user)
{
//This function returns the pregame rating for a given user for a given band in a given fest, or 0 if unrated
    global $master, $fest;
    $sql = "SELECT `content` as rating FROM `messages` as rating WHERE `band`='$band' and `fromuser`='$user' and `remark`='2' and `mode`='1' and `festival`='$fest' and `deleted`!='1'";
//    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    If (!empty($row['rating'])) $rate = $row['rating'];
    else $rate = 0;
    return $rate;
}

function act_live_rating($band, $user)
{
//This function returns the gametime rating for a given user for a given band in a given fest, or 0 if unrated
    global $master, $fest;
    $sql = "SELECT `content` as rating, `id` FROM `messages` WHERE `band`='$band' and `fromuser`='$user' and `remark`='2' and `mode`='2' and `festival`='$fest' and `deleted`!='1' ORDER BY id DESC LIMIT 1";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    If (!empty($row['rating'])) $rate = $row['rating'];
    else $rate = 0;
    return $rate;
}

function avg_live_rating($fest)
{
    //This function returns the average live rting for a given festival
    global $master;
    $sql = "SELECT AVG(`content`) as rate FROM `messages` WHERE `festival`='$fest' AND `remark`='2' AND `mode`='2'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) !== 0) {
        $row = mysql_fetch_array($res);
        return $row['rate'];
    } else return 0;
}

function avg_live_rating_band($band)
{
    //This function returns the average live rting for one band for a given festival
    global $master, $fest;
    $sql = "SELECT AVG(`content`) as rate FROM `messages` WHERE `festival`='$fest' AND `remark`='2' AND `mode`='2' AND `band`='$band'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) !== 0) {
        $row = mysql_fetch_array($res);
        return $row['rate'];
    } else return 0;

}

?>

