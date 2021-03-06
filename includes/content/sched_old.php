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


$right_required = "ViewNotes";
If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {
//Draw elements common to portrait and landscape

//Sets the target for all POST actions
    $post_target = $basepage . "?disp=sched";

    date_default_timezone_set($festtimezone);

    ?>

    <script type="text/javascript" src="includes/js/lines.js"></script>

    <table>
        <caption>Color Code (Band names are links to band details)</caption>
        <tr>
            <td class="rating"><a>Unrated</a></td>
            <td class="rating1"><a>Rating=1</a></td>
            <td class="rating2"><a>Rating=2</a></td>
            <td class="rating3"><a>Rating=3</a></td>
            <td class="rating4"><a>Rating=4</a></td>
            <td class="rating5"><a>Rating=5</a></td>
        </tr>
    </table>

    <?php
    If (empty($_POST['landscape'])) {
        ?>

        <form action="<?php echo $post_target; ?>" method="post">
            <input type="submit" name="landscape" value="Flip orientation">
        </form>
        <?php
        $post_target = $basepage;
        ?>
        <form action="<?php echo $post_target; ?>" method="get">
            <input type="submit" name="best_paths" value="Show Best Paths">
            <input type="hidden" name="disp" value="sched">
        </form>

        <?php
        $usersql = "select id, username from Users";
        $userres = mysql_query($usersql, $master);

        If (!empty($_GET['best_paths'])) {

            include $baseinstall . "includes/content/blocks/paths.php";


            while ($row = mysql_fetch_array($userres)) {

                $color = random_color();


//Set some variables for use
                $banddecay = 0.25; //$banddecay is the rate at which the score drops for a band you are at; there is no decay for the last 5 min
                $daytraveltime = 1; //Traveltime is the number of 5min blocks it takes to go from one placeto another
                $nighttraveltime = 2; //Traveltime is the number of 5min blocks it takes to go from one placeto another
                $mintime = 20; //$mintime is the minimum amount of time the user will stay at a show once committing
                $thirstiness = 0.04; //$thristiness affects how fast score for beer tent accumulates

                ?>
                <input style="background-color:#<?php echo $color; ?>" type="button"
                       onclick="bestPath<?php echo $row['id']; ?>();"
                       value="Show <?php echo $row['username']; ?>'s Best Path"/>

                <script type="text/javascript">
                    window.bestPath<?php echo $row['id']; ?> = function () {
                        <?php
                        //Determine variables from user settings
                        $setting_sql = "select * from user_settings_".$row['id'];
                        $settings_res = mysql_query($setting_sql, $master);
                        while($row2=mysql_fetch_array($settings_res)) {
                            If($row2['item'] == "Minimum time at a band") $mintimeval = $row2['value'];
                            If($row2['item'] == "Travel Time-night") $nighttraveltimeval = $row2['value'];
                            If($row2['item'] == "Travel time-day") $daytraveltimeval = $row2['value'];
                            If($row2['item'] == "Thirstiness") $thirstinessval = $row2['value'];
                            If($row2['item'] == "Band boredom") $banddecayval = $row2['value'];
                        }

                        //Minimum Time
                        switch ($mintimeval) {
                            case 1:
                                $mintime = 20;
                                break;
                            case 2:
                                $mintime = 10;
                                break;
                            case 3:
                                $mintime = 30;
                                break;
                            case 4:
                                $mintime = 40;
                                break;
                            case 5:
                                $mintime = 50;
                                break;
                            default:
                                $mintime = 20;
                                break;
                        }

                        //Band decay
                        switch ($banddecayval) {
                            case 1:
                                $banddecay = 0.25;
                                break;
                            case 2:
                                $banddecay = 0.1;
                                break;
                            case 3:
                                $banddecay = 0;
                                break;
                            case 4:
                                $banddecay = 0.5;
                                break;
                            default:
                                $banddecay = 0.25;
                                break;
                        }

                        //Thirstiness
                        switch ($thirstinessval) {
                            case 1:
                                $thirstiness = 0.05;
                                break;
                            case 2:
                                $thirstiness = 0;
                                break;
                            case 3:
                                $thirstiness = 0.1;
                                break;
                            default:
                                $thirstiness = 0.05;
                                break;
                        }

                        //Travel time-day
                        switch ($daytraveltimeval) {
                            case 1:
                                $daytraveltime = 1;
                                break;
                            case 2:
                                $daytraveltime = 2;
                                break;
                            case 3:
                                $daytraveltime = 3;
                                break;
                            case 4:
                                $daytraveltime = 4;
                                break;
                            default:
                                $daytraveltime = 1;
                                break;
                        }

                        //Travel time-night
                        switch ($nighttraveltimeval) {
                            case 1:
                                $nighttraveltime = 2;
                                break;
                            case 2:
                                $nighttraveltime = 1;
                                break;
                            case 3:
                                $nighttraveltime = 3;
                                break;
                            case 4:
                                $nighttraveltime = 4;
                                break;
                            case 5:
                                $nighttraveltime = 5;
                                break;
                            default:
                                $nighttraveltime = 2;
                                break;
                        }

                        echo "alert(\"Wait until you get the completion before scrolling the screen.\");\n";
                        pathfinder($row['id'], $banddecay, $color, $daytraveltime, $nighttraveltime, $mintime, $thirstiness, $main, $master, $avg_rating);
                        echo "alert(\"Paths complete!\");";
                        ?>
                    }
                </script>
                </input>

            <?php
            }
        }
        ?>

        <div id="content">
            <?php

            //include $baseinstall."includes/content/blocks/user_selector.php";

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

            $sql = "select min(id) as minid, max(id) as maxid from stages where name!='Undetermined'";
            $res = mysql_query($sql, $main);
            $row = mysql_fetch_array($res);
            $minid = $row['minid'];
            $maxid = $row['maxid'];

            $sql = "select id from stages where name='Undetermined'";
            $res = mysql_query($sql, $main);
            $row = mysql_fetch_array($res);
            $nonstage = $row['id'];

            //Get the list of stages
            $sql = "select name as stagename, id from stages where name!='Undetermined'";
            $res = mysql_query($sql, $main);


            //Get list of days
            $sql1 = "select name as dayname, date as daydate, id from days where name!='Undetermined'";
            $res1 = mysql_query($sql1, $main);

            //Index is incremented in 5 min increments to draw the table

            while ($day = mysql_fetch_array($res1)) {
                mysql_data_seek($res, 0);
                $fest_start_time_sec = strtotime($day['daydate'] . " " . $fest_start_time);
                echo "<br> Day date is " . $day['daydate'] . " and fest start time is " . $fest_start_time;
                echo "<h3 id=\"day" . $day['id'] . "\">" . $day['dayname'] . "</h3>";
                $fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;

                //Draw first row of stage names
                echo "<table class=\"schedtable\"><tr><th>Time</th>";

                while ($row = mysql_fetch_array($res)) {
                    echo "<th>" . $row['stagename'] . "</th>";
                    $stageid[] = $row;
                } // Closes while($row = my_sql_fetch_array($res))
                echo "<th>Beer Tent</th></tr>";

//Draw a row with i columns every 5 min from start time for fest length
                for ($k = $fest_start_time_sec; $k < $fest_end_time_sec; $k = $k + 900) {
                    echo "<tr><th rowspan=\"3\">" . strftime("%I:%M %p", $k) . "</th>";
                    for ($l = 0; $l < 3; $l++) {
                        If ($l != 0) echo "<tr>";
                        for ($j = $minid; $j <= $maxid; $j++) {
                            If ($j != $nonstage) {
                                If (empty($ticked[$j])) $ticked[$j] = 0;
                                If (empty($ticks[$j])) $ticks[$j] = 0;
                                If (empty($band_name_prev[$j])) $band_name_prev[$j] = 0;
                                $k_temp = $k + 300 * $l;
                                $sql_band = "select id, name, sec_start, sec_end, start, end, stage, genre from bands where sec_start<='$k_temp' AND sec_end >'$k_temp' AND stage='$j'";
                                $res_band = mysql_query($sql_band, $main);
                                $row_band = mysql_fetch_array($res_band);
                                If (!empty($row_band['name'])) {
                                    $band_current[$j] = 1;
                                    $rat_sql = "select rating from ratings where user='$user' and band='" . $row_band['id'] . "'";
                                    $res_rat = mysql_query($rat_sql, $main);
                                    $rat_row = mysql_fetch_array($res_rat);
                                }
                                If (empty($row_band['name'])) {
                                    $band_current[$j] = 0;
                                    $ticks[$j] = 0;
                                    $ticked[$j] = 0;
                                }
                                If ($ticked[$j] > 0) $ticked[$j] = $ticked[$j] + 1;
                                If (empty($band_current[$j])) $band_current[$j] = 0;
                                If (empty($band_current_prev[$j])) $band_current_prev[$j] = 0;
                                If ((($band_current[$j] == 1 && $band_current_prev[$j] == 0) || ($band_name_prev[$j] != $row_band['name'])) && !empty($row_band['name'])) {
                                    $ticks[$j] = ($row_band['sec_end'] - $row_band['sec_start']) / 300;
                                    $ticked[$j] = 1;
                                }
                                If ($ticked[$j] == 1) echo "<td id=\"band" . $row_band['id'] . "\" class=\"rating" . $rat_row['rating'] . "\" rowspan=\"" . $ticks[$j] . "\">" . "<a href=\"" . $basepage . "?disp=view_band&band=" . $row_band['id'] . "\">" . $row_band['name'] . "<br />" . getBandGenre($row_band['id'], $user) . "</a></td>";
                                If ($ticked[$j] == 0) echo "<td></td>";
                                $band_current_prev[$j] = $band_current[$j];
                                $band_name_prev[$j] = $row_band['name'];
                            }
                        } // Closes for ($j=1;$j<=$i;$j++)
                        IF ($k_temp == $fest_start_time_sec) {
                            $totalrows = ($fest_start_time_sec - $fest_end_time_sec) / (-300);
                            echo "<td id=\"bandbeer" . $day['id'] . "\" rowspan=\"$totalrows\"></td></tr>";
                        } else echo "</tr>";
                    } // Closes for ($l=0;$l<3;$l++)
                } //Closes for ($k=$fest_start_time_sec,$k+300,$k<=$fest_end_time_sec)
                echo "</table><!-- end .schedtable -->";
            } //Closes while($day = mysql_fetch_array($res1)

            ?>
        </div> <!-- end #content -->
    <?php
    } else {
//Begin landscape logic
        ?>

        <div id="landscape">


            <form action="<?php echo $post_target; ?>" method="post">
                <input type="submit" name="portrait" value="Flip orientation">
            </form>

            <?php

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
            for ($i = 0; $i < mysql_num_rows($res1); $i++) {

                $fest_start_time_sec = strtotime($day[$i]['date'] . " " . $fest_start_time);

                echo "<h3>" . $day[$i]['name'] . "</h3>";
                $fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;


//First open the table and lay down the times in 15min increments
                echo "<table class=\"lsched\"><tr><th>Time</th>";

                for ($k = $fest_start_time_sec; $k < $fest_end_time_sec; $k = $k + 900) {

                    echo "<th class=\"ltime\" colspan=\"3\">" . strftime("%I:%M %p", $k) . "</th>";

                }

                echo "</tr>";

                for ($j = 0; $j < mysql_num_rows($res); $j++) {

//Now lay down the stages

                    echo "<tr><th>" . $stage[$j]['name'] . "</th>";

                    for ($k = $fest_start_time_sec; $k < $fest_end_time_sec; $k = $k + 300) {

                        $band_end = $k + 300;
//See if a band starts at the current time block and pull info if it does
                        $sql_band = "select id, name, sec_start, sec_end, start, end, stage, genre from bands where sec_start<'$band_end' AND sec_start>='$k' AND stage='" . $stage[$j]['id'] . "'";
                        $res_band = mysql_query($sql_band, $main);
                        If (mysql_num_rows($res_band) > 0) {
                            $band_row = mysql_fetch_array($res_band);
                            //Find number of blocks
                            $set_time = $band_row['sec_end'] - $band_row['sec_start'];
                            $blocks = $set_time / 300;
                            $rat_sql = "select rating from ratings where user='$user' and band='" . $band_row['id'] . "'";
                            $res_rat = mysql_query($rat_sql, $main);
                            $rat_row = mysql_fetch_array($res_rat);
                            //Lay down the band name
                            echo "<td class=\"rating" . $rat_row['rating'] . "\" colspan=\"$blocks\">" . "<a href=\"" . $basepage . "?disp=view_band&band=" . $band_row['id'] . "\">" . $band_row['name'] . "<br />" . getBandGenre($band_row['id'], $user) . "</a></td>";
                            //Skip index to end of band
                            $k = $band_row['sec_end'] - 300;
                        } else {
                            echo "<td></td>";

                        } // Closes else If(mysql_num_rows($res_band>0)
                    }


                    echo "</tr>";
                }


                echo "</table>";

            }

            ?>
        </div> <!-- end #landscape -->
    <?php

    }
} else {
    echo "This page requires a higher level access than you currently have.";

    include $baseinstall . "includes/site/login.php";
}

?>

