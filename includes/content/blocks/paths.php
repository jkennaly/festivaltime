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


function pathfinder($scoreuser, $banddecay, $color, $daytraveltime, $nighttraveltime, $mintime, $thirstiness, $main, $master, $avg_rating)
{


    $sql = "select max(id) as rows from bands";
    $res = mysql_query($sql, $main);
    $num = mysql_fetch_assoc($res);

    $sql = "select avg(rating) as average from ratings where ratings.user='$scoreuser'";

    $res = mysql_query($sql, $main);
    $arr = mysql_fetch_assoc($res);

    $useravgrating = $arr['average'];

    $sql = "select username from Users where id='$scoreuser'";
    $res = mysql_query($sql, $master);
    $user_row = mysql_fetch_array($res);
    $scoreusername = $user_row['username'];
//echo "Showing best path for user ".$scoreusername."<br>";


    for ($i = 1; $i <= $num["rows"]; $i++) {
        $sql = "select name from bands where id='$i'";
        $res = mysql_query($sql, $main);
        $arr[$i] = mysql_fetch_assoc($res);
        $uscoreall[$i] = uscoref2($i, $scoreuser);
        $j = $i;
    }


    arsort($uscoreall);

    reset($uscoreall);

    for ($i = 1; $i <= $j; $i++) {
        If (!empty($arr[(key($uscoreall))]["name"])) {

//	echo "<tr><th>$i</th><th><a href=\"".$basepage."?disp=view_band&band=".key($uscoreall)."\">".$arr[(key($uscoreall))]["name"]."</a></th><td>".current($uscoreall)."</td></tr>";
            $bandscore[key($uscoreall)] = current($uscoreall);
        } else {
            $i = $i - 1;
            $j = $j - 1;
        }
        next($uscoreall);

    }


//First draw a grid for Day 1

//Get fest start time and length
    $sql = "select value from info where item like 'Festival Start Time%'";
    $res = mysql_query($sql, $main);
    $row = mysql_fetch_array($res);
    $fest_start_time = $row['value'];

    $sql = "select value from info where item like 'Festival Length%'";
    $res = mysql_query($sql, $main);
    $row = mysql_fetch_array($res);
    $fest_length = $row['value'];

//$fest_start_time = "10:00";
//fest length must be specified in hours
//$fest_length = 15;

//We will not be using the standard values for these variables
    unset($stage);
    unset($day);

//Get the list of stages
    $sql = "select name, id from stages where name!='Undetermined'";
    $res = mysql_query($sql, $main);
    while ($row = mysql_fetch_array($res)) {
        $stage[] = $row;
    }
//Get list of days
    $sql1 = "select id, name, date from days where name!='Undetermined'";
    $res1 = mysql_query($sql1, $main);
    while ($row = mysql_fetch_array($res1)) {
        $day[] = $row;
    }

//Loop for each day
    for ($i = 0; $i < mysql_num_rows($res1); $i++) {
        unset($currentstage);

        $fest_start_time_sec = strtotime($day[$i]['date'] . " " . $fest_start_time);

//echo "<h3>".$day[$i]['name']."</h3>";
        $fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;

//Loop for each stage
        for ($j = 0; $j < mysql_num_rows($res); $j++) {

//Loop for each 5 min increment to collect band data
            for ($k = $fest_start_time_sec; $k < ($fest_end_time_sec + $nighttraveltime * 300 + $mintime * 180); $k = $k + 300) {

                $band_end = $k + 300;
//See if a band is playing at the current time block and pull info if it does
                $sql_band = "select id, name, sec_start, sec_end, start, end, stage from bands where sec_end>'$band_end' AND sec_start<='$k' AND stage='" . $stage[$j]['id'] . "'";
                $res_band = mysql_query($sql_band, $main);
                If (mysql_num_rows($res_band) > 0) {
                    $band_row = mysql_fetch_array($res_band);
                    //Find number of blocks
                    $set_time = $band_row['sec_end'] - $band_row['sec_start'];
                    $blocks = $set_time / 300;
                    $rat_sql = "select rating from ratings where user='$scoreuser' and band='" . $band_row['id'] . "'";
                    $res_rat = mysql_query($rat_sql, $main);
                    $rat_row = mysql_fetch_array($res_rat);
                    //Lay down the band name
                    $bestpath[$k][$stage[$j]['id']]['band'] = $band_row['id'];
                    $bestpath[$k][$stage[$j]['id']]['score'] = $bandscore[$band_row['id']];
                    $bestpath[$k][$stage[$j]['id']]['rating'] = $rat_row['rating'];
                    $bestpath[$k][$stage[$j]['id']]['name'] = $band_row['name'];
                    $bestpath[$k][$stage[$j]['id']]['sec_end'] = $band_row['sec_end'];
                    $bestpath[$k][$stage[$j]['id']]['sec_start'] = $band_row['sec_start'];
                    $bestpath[$k][$stage[$j]['id']]['stage'] = $stage[$j]['id'];
                }
            }
        }

        unset($currentshow);
        unset($currentbest);
        unset($conline);
        $travelling = 0;
        $looking = 0;
        $moving = 1;
        $beertent['band'] = -1;
        $beertent['score'] = 1;
        $beertent['rating'] = 0;
        $beertent['name'] = "Beer Tent";
        $beertent['sec_end'] = $fest_end_time_sec;
        $beertent['sec_start'] = $fest_start_time_sec;
        $beertent['stage'] = -1;
        $tempband['band'] = -2;
        $tempband['score'] = -10000000;
        $tempband['rating'] = 0;
        $tempband['name'] = "Temp";
        $tempband['sec_end'] = $fest_end_time_sec;
        $tempband['sec_start'] = $fest_start_time_sec;
        $tempband['stage'] = -1;
//echo "var divday".$day[$i]['id']." = document.getElementById('day".$day[$i]['id']."');\n";
        $curdiv = "divday" . $day[$i]['id'];
        $showday = $day[$i]['id'];
        $curpc = 0;
        for ($k = $fest_start_time_sec; $k < $fest_end_time_sec; $k = $k + 300) {
            If (strftime("%H", $k) >= 19 || strftime("%H", $k) < 6) $traveltime = $nighttraveltime; else $traveltime = $daytraveltime;
            If (!empty($currentbest)) $beertent['score'] = $beertent['score'] + $thirstiness;
            If ($beertent['score'] > 3.5) $beertent['score'] = 3.5;
            If (empty($targetset)) $target['score'] = -10;
            If (empty($currentbest) && $travelling == 0) {
                $tenminmod = $traveltime * 300;
                If (isset($bestpath[$k + $tenminmod])) {
                    //Find the best 10 min band
                    $tenmin['score'] = -10;
                    foreach ($bestpath[$k + $tenminmod] as $v) {
                        //					echo "alert(\"Found a band: ".$v['name']." with a score of ".$v['score']."\");";
                        If ($v['score'] > $tenmin['score']) {
                            $tenmin = $v;
                            $target = $tenmin;
                            $travelling = 1;
                            $targetset = 1;
                            $traveltimeactual = $traveltime;
                        }
                    }
                }
            } else {
                $tenminmod = $traveltime * 300;
                $twentyminmod = $traveltime * 300 + $mintime * 30;
                $thirtyminmod = $traveltime * 300 + $mintime * 60;
                $fortyminmod = $traveltime * 300 + $mintime * 90;
                $fiftyminmod = $traveltime * 300 + $mintime * 120;
                $sixtyminmod = $traveltime * 300 + $mintime * 150;
                $seventyminmod = $traveltime * 300 + $mintime * 180;
                //Find the best 10 min band
                $tenmin = $tempband;
                foreach ($bestpath[$k + $tenminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $tenmin['score'] && $v['sec_end'] >= $k + $twentyminmod) $tenmin = $v;
//			echo "alert(\"v score is ".$v['score']." and tenmin score is ".$tenmin['score']."\");";
                }
                //Find the best 20 min band
                $twentymin = $tempband;
                foreach ($bestpath[$k + $twentyminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $twentymin['score'] && $v['sec_end'] >= $k + $thirtyminmod) $twentymin = $v;
                }
                //Find the best 30 min band
                $thirtymin = $tempband;
                foreach ($bestpath[$k + $thirtyminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $thirtymin['score'] && $v['sec_end'] >= $k + $thirtyminmod) $thirtymin = $v;
                }
                //Find the best 40 min band
                $fortymin = $tempband;
                foreach ($bestpath[$k + $fortyminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $fortymin['score'] && $v['sec_end'] >= $k + $thirtyminmod) $fortymin = $v;
                }
                //Find the best 50 min band
                $fiftymin = $tempband;
                foreach ($bestpath[$k + $fiftyminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $fiftymin['score'] && $v['sec_end'] >= $k + $thirtyminmod) $fiftymin = $v;
                }
                //Find the best 60 min band
                $sixtymin = $tempband;
                foreach ($bestpath[$k + $sixtyminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $sixtymin['score'] && $v['sec_end'] >= $k + $thirtyminmod) $sixtymin = $v;
                }
                //Find the best 70 min band
                $seventymin = $tempband;
                foreach ($bestpath[$k + $seventyminmod] as $v) {
                    If ($currentbest['name'] == $v['name']) $v['score'] = $currentbest['score'];
                    If ($v['score'] > $seventymin['score'] && $v['sec_end'] >= $k + $thirtyminmod) $seventymin = $v;
                }
                If ($looking == 1 || $moving == 1) {
                    If ($looking == 1) {
                        If ($tenmin['name'] != $currentbest['name'] && $tenmin['score'] >= $currentbest['score']) {
                            $target = $tenmin;
                            $travelling = 1;
                            $targetset = 1;
                            $traveltimeactual = $traveltime;
                        }
                    }
                    If ($moving == 1 && !empty($currentshow)) {
                        If ($tenmin == $thirtymin) {
                            $target = $tenmin;
                            $travelling = 1;
                            $targetset = 1;
                            $traveltimeactual = $traveltime;
                        }
                        If ($targetset != 1 && $tenmin['sec_end'] > $k + $thirtyminmod) {
                            If ($tenmin == $twentymin) {
                                $target = $tenmin;
                                $travelling = 1;
                                $targetset = 1;
                                $traveltimeactual = $traveltime;
                            }
                            If ($twentymin == $thirtymin && $twentymin == $fortymin && $twentymin == $fiftymin && $twentymin == $sixtymin) {
                                $target = $tenmin;
                                $travelling = 1;
                                $targetset = 1;
                                $traveltimeactual = $traveltime;
                            }
                            If ($targetset != 1) {
                                $target = $twentymin;
                                $travelling = 1;
                                $targetset = 1;
                                If ($target['sec_start'] > $k + $tenminmod) $traveltimeactual = ($target['sec_start'] - $k) / 300; else $traveltimeactual = $traveltime;
                            }
                        }
                    }
                    If ($moving == 1 && empty($currentshow) && !empty($tenmin)) {
                        $target = $tenmin;
                        $travelling = 1;
                        $targetset = 1;
                        $traveltimeactual = $traveltime;

                    }
                }
                If ($targetset == 1 && $target == $tempband) $target = $tenmin;
                If (($looking == 1 || $moving == 1) && $travelling == 1 && $beertent['score'] > $target['score']) {
                    $target = $beertent;
                }
                If ($looking == 1 && $travelling == 0 && $$beertent['score'] > $currentbest['score']) {
                    $target = $beertent;
                }
            };
            If (isset($currentshow)) $pcgone = round(($k - $currentshowstart) * 100 / ($currentshowend - $currentshowstart), 2);
            If (!empty($targetset) && isset($currentshow) && $travelling == 1) {
//		echo "var divband$currentshow$pcgone = document.getElementById('band$currentshow');\n";
                $prevdiv = $curdiv;
                $prevpc = $curpc;
                If ($currentshow < 0) $currentshow = "beer";
                $curpc = $pcgone;
                $curdiv = "divband$currentshow";
                $conline .= "var $curdiv = document.getElementById('band$currentshow');\n";
                $conline .= "connect($prevdiv, $curdiv, $prevpc, $curpc, \"#$color\", 5);\n";
                If ($currentshow == "beer") $currentshow = -1;

            }
            //First show of the day
            If (!isset($currentshow) && isset($currentbest)) {
                $currentshow = $currentbest['band'];
                $currentshowstart = $currentbest['sec_start'];
                $currentshowend = $currentbest['sec_end'];
                $status = "First show of the day";
                $minhere = 0;
                $pcgone = round(($k - $currentshowstart) * 100 / ($currentshowend - $currentshowstart), 2);
//		echo "var divband$currentshow = document.getElementById('band$currentshow');\n";
                $prevdiv = $curdiv;
                $prevpc = $curpc;
                If ($currentshow < 0) $currentshow = "beer";
                $curpc = $pcgone;
                $curdiv = "divband$currentshow";
                $conline .= "var $prevdiv = document.getElementById('day$showday');\n";
                $conline .= "var $curdiv = document.getElementById('band$currentshow');\n";
                $conline .= "connect($prevdiv, $curdiv, $prevpc, $curpc, \"#$color\", 5);\n";
                $prenode = $currentshow;
                If ($currentshow == "beer") $currentshow = -1;

            }
            If ($travelling == 0 && $moving == 0) {
                //First block seen, but not first show of day


                //Been at the show more than 20 min

                If ($minhere >= $mintime || ($minhere >= 15 && $currentbest['name'] == "Beer Tent")) {
                    $status = "Still the best option";
                    $currentbest['score'] = $currentbest['score'] - $banddecay;
                    If ($currentbest['name'] == "Beer Tent") $beertent['score'] = 1;
                    $looking = 1;
                    $moving = 0;
                }


                //First 20 min of show

                If ($currentbest['sec_end'] > $k + 300 && $minhere > 0 && $minhere < $mintime) {
                    $status = "Still at " . $currentbest['name'];
                    $currentbest['score'] = $currentbest['score'] - $banddecay;
                    If ($currentbest['name'] == "Beer Tent") $beertent['score'] = 1;
                    If ($minhere == ($mintime - 5)) $looking = 1; else $looking = 0;
                    $moving = 0;
                }
                If ($currentbest['sec_end'] <= $k + 300) {
                    $changing = 1;
                    $status = "Finishing up " . $currentbest['name'];
                    $looking = 0;
                    $moving = 1;
                }

                If ($minhere == 0 && $status != "First show of the day") {
                    $currentshow = $currentbest['band'];
                    $currentshowstart = $currentbest['sec_start'];
                    $currentshowend = $currentbest['sec_end'];
                    $status = "At a new show";
                    $minhere = 0;
                    $looking = 0;
                    $moving = 0;
                    $pcgone = round(($k - $currentshowstart) * 100 / ($currentshowend - $currentshowstart), 2);
//				echo "var divband$currentshow = document.getElementById('band$currentshow');\n";
                    $prevdiv = $curdiv;
                    $prevpc = $curpc;
                    If ($currentshow < 0) $currentshow = "beer$showday";
                    $curpc = $pcgone;
                    $curdiv = "divband$currentshow";
                    $conline .= "var $prevdiv = document.getElementById('band$prenode');\n";
                    $conline .= "var $curdiv = document.getElementById('band$currentshow');\n";
                    $conline .= "connect($prevdiv, $curdiv, $prevpc, $curpc, \"#$color\", 5);\n";
                    $prenode = $currentshow;
                    If ($currentshow == "beer") $currentshow = -1;
                }


                $prevshow = $currentshow;
            }


            If (isset($currentshow)) $minhere = $minhere + 5;

            If ($travelling > 0) {
//		If(isset($currentshow))echo "Travelling to ".$target['name']."\n";
//		If(!isset($currentshow)) echo "Travelling to first show\n";
                $looking = 0;
                $moving = 0;
                $travelling = $travelling + 1;
                $prevshow = -1;
                If ($travelling > $traveltimeactual) {
                    $currentbest = $target;
                    $targetset = 0;
                    $minhere = 0;
                    $travelling = 0;
                }
            }
        }

        If (!empty($conline)) echo $conline;

    }
    return true;
}

function random_color()
{
    mt_srand((double)microtime() * 1000000);
    $c = '';
    while (strlen($c) < 6) {
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}
