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


?>

<div id="content">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//Sets the target for all POST actions
$post_target=$basepage."?disp=band_scores";

//Find all users that share a group with the current user

//First get all the groups the user is in
$groups_in = in_groups($user, $master);

//Find all the users in the system

//Get fest start time and length
$sql="select value from info where item like 'Festival Start Time%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_start_time = $row['value'];

$sql="select value from info where item like 'Festival Length%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_length = $row['value'];

//$fest_start_time = "10:00";
//fest length must be specified in hours
//$fest_length = 15;

$sql="select min(id) as minid, max(id) as maxid from stages where name!='Undetermined'";
$res = mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$minid = $row['minid'];
$maxid = $row['maxid'];

$sql="select id from stages where name='Undetermined'";
$res = mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$nonstage = $row['id'];

//Get the list of stages
$sql = "select name as stagename, id from stages where name!='Undetermined'";
$res = mysql_query($sql, $main);


//Get list of days
$sql1 = "select name as dayname, date as daydate, id from days where name!='Undetermined'";
$res1 = mysql_query($sql1, $main);

foreach($groups_in as $v) {
    echo "<h2>".$v['name']."</h2>";
    $users=group_members($v['id'], $master);
    foreach($users as $val){
        echo "<h3>".$val['name']."</h3>";
        echo "<h4>Ratings</h4>";
        echo "Total ratings: ".total_ratings($val['id'], $main)."<br />";
        for ($i=1;$i<=5;$i++){
            echo "Ratings of $i: ".rating_count($val['id'], $i, $main)."<br />";
        }
        echo "<h4>Decisions</h4>";
    mysql_data_seek($res1, 0);
        
       while($day = mysql_fetch_array($res1)){
    mysql_data_seek($res, 0);
    $fest_start_time_sec = strtotime($day['daydate']." ".$fest_start_time);
//    echo "<br> Day date is ".$day['daydate']." and fest start time is ".$fest_start_time;
//    echo "<h3 id=\"day".$day['id']."\">".$day['dayname']."</h3>";
    $fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;

    //Draw first row of stage names
//    echo "<table class=\"schedtable\"><tr><th>Time</th>";
    
    while($row = mysql_fetch_array($res)) {
//        echo "<th>".$row['stagename']."</th>";
        $stageid[]=$row;
    } // Closes while($row = my_sql_fetch_array($res)) 
//    echo "<th>Beer Tent</th></tr>";

//Draw a row with i columns every 5 min from start time for fest length
$tough_count = 3;
for($ind=1;$ind<=$tough_count;$ind++) {
    $toughest[$ind] = 0;
    $toughest_5[$ind] =0;
    $toughest_4[$ind] =0;
    $toughest_time[$ind] =0;
}
for ($k=$fest_start_time_sec;$k<$fest_end_time_sec;$k=$k+900) {
    $toughness[$k] = 0;
    $kbands_sql = "select id, name from bands where sec_start<=$k and sec_end>$k";   
    $kbands_res = mysql_query($kbands_sql, $main);
    If(mysql_num_rows($kbands_res) > 0) {
        $cur_5 = 0;
        $cur_4 = 0;
        while($band_row = mysql_fetch_array($kbands_res)){
//            echo $band_row['name']." is playing now, rated ";
            $cur_rate = act_rating($band_row['id'], $val['id'], $main);
 //           echo $cur_rate."<br />";
            $toughness[$k] = $toughness[$k] + $cur_rate;
            If($cur_rate == 5) $cur_5 = $cur_5 +1;
            If($cur_rate == 4) $cur_4 = $cur_4 +1;
        }
    }
//    echo "checking time $k on ".$day['dayname']." for ".$val['name']."; toughness is ".$toughness[$k]."; current toughest was ".$toughest_time[1]." with a ".$toughest[1]."<br />";  
    foreach($toughest as $key => $test)
            If($toughness[$k] > $test) {
 //               echo "toughness is ".$toughness[$k]." testing against $test with $key<br /><br />";
                for($ind=$tough_count;$ind>$key;$ind--){
 //                   echo "ind is $ind<br />";
                    $in_min = $ind - 1;
                    $toughest[$ind] = $toughest[$in_min];
                    $toughest_5[$ind] = $toughest_5[$in_min];
                    $toughest_4[$ind] = $toughest_4[$in_min];
                    $toughest_time[$ind] = $toughest_time[$in_min];
                }
                $toughest[$key] = $toughness[$k];
                $toughest_5[$key] = $cur_5;
                $toughest_4[$key] = $cur_4;
                $toughest_time[$key] = $k;
                break;
            }
        
}
echo "The most difficult $tough_count times on ".$day['dayname']." were :<br />";
foreach ($toughest as $key => $v){
    echo strftime ( "%l:%M %p", $toughest_time[$key])." with a ".$toughest[$key]."<br />"; 
}
       
}
        
        echo "<h4>Genres</h4>";
        $genrelist = genreList($main, $master, $val['id']);
        foreach($genrelist as $val) {
            If($val['rating_total'] > 0) $rating = round($val['rating_total']/$val['rated'], 1);
            else $rating = 0;
            echo "Genre: ".$val['name']." Total bands: ".$val['bands']." Rated bands: ".$val['rated']." Average rating: ".$rating."<br />";
        }
    }
}


}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
