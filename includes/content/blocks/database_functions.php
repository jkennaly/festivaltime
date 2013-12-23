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

include('includes/content/blocks/create_festival_functions.php');

function UpdateTable($source, $target, $table, $sourceuser, $sourcepassword, $sourcehost, $sourcedb, $targetuser, $targetpassword, $targethost, $targetdb, $path)
{
    //This function will copy table $table from $source database to $target, where $source and $target are the resource links to the databases. If $table already exists at $target, it will be wiped out

    //First find out if the table already exists at $target
    $sql = "select id from `" . $table . "`";
    $val = mysql_query($sql, $target);

    if ($val !== FALSE) {
        mysql_query("DROP TABLE IF EXISTS `" . $table . "`", $target) or die(mysql_error());
    }

    exec("mysqldump --user=$sourceuser --password=$sourcepassword --host=$sourcehost $sourcedb $table > $path$table.sql");

    exec("mysql --user=$targetuser --password=$targetpassword --host=$targethost $targetdb < $path$table.sql");

    exec("rm $path$table.sql");

    return true;
}

function rmTable($target, $table)
{
    //This function remove table $table from $target database.


    mysql_query("DROP TABLE IF EXISTS `" . $table . "`", $target) or die(mysql_error());

    return true;
}

function checkTable($source, $target, $stable, $ttable)
{
    //This function checks to see if $stable in $source matches the $ttable in $target. It retunrs true if they match and false if they do not.

    $sql = "select * from `" . $ttable . "`";
    $valt = mysql_query($sql, $target);

    if ($valt !== FALSE) {
        //Table exists in target

        $sql = "select * from `" . $stable . "`";
        $vals = mysql_query($sql, $source);
        if ($vals !== FALSE) {
            //Table exists in source
            If (mysql_num_rows($valt) == mysql_num_rows($vals)) {
                //They have the same number of rows
                while ($row = mysql_fetch_array($vals)) {
                    If ($row != mysql_fetch_array($valt)) return false;
                }
                return true;
            }
        } //Closes if($vals !== FALSE)
    } //Closes if($valt !== FALSE)

    return false;
}

function insertRow($table, $cols, $vals)
{
    global $master;
    $colString = "";
    $i = 0;
    foreach ($cols as $col) {
        if ($i == 0) $colString .= "`$col`";
        else $colString .= ", `$col`";
        $i++;
    }
    $valString = "";
    $i = 0;
    foreach ($vals as $val) {
        if ($i == 0) $valString .= "'" . mysql_real_escape_string($val) . "'";
        else $valString .= ", '" . mysql_real_escape_string($val) . "'";
        $i++;
    }
    $sql = "INSERT INTO `$table` ($colString)";
    $sql .= " VALUES ($valString)";
    $upd = mysql_query($sql, $master);
//	echo "<br>".$sql."<br>";
    if (!$upd) {
        echo mysql_error();
        die ('Insert row failed with: ' . $sql);
    }
    $rowID = mysql_insert_id($master);
    return $rowID;
}

function updateRow($table, $cols, $vals, $where)
{
    global $master;
    $i = 0;
    $valPair = "";
    foreach ($cols as $col) {
        if ($i == 0) $valPair .= "`" . $col . "`='" . mysql_real_escape_string($vals[$i]) . "'";
        else $valPair .= ", `" . $col . "`='" . mysql_real_escape_string($vals[$i]) . "' ";
        $i++;
    }
    $sql = "UPDATE `$table` SET $valPair WHERE $where";
//	error_log(print_r($sql, TRUE));
    $upd = mysql_query($sql, $master);

//	echo "<br>".$sql."<br>";
    if (!$upd) {
        echo mysql_error();
        die ('Update row failed with: ' . $sql);
    }

    return true;
}

function getUname($source, $userid)
{
    //This function checks $source table Users for the username of $userid

    $sql = "select username from `Users` where id='$userid'";
    $res = mysql_query($sql, $source);
    $urow = mysql_fetch_array($res);
    $uname = $urow['username'];
    return $uname;

}

function getBname($bandid)
{
    //This function checks $source table Users for the username of $userid
    global $master;
    $sql = "select name from `bands` where id='$bandid'";
    $res = mysql_query($sql, $master);
    $urow = mysql_fetch_array($res);
    $bname = $urow['name'];
    return $bname;

}

function getFname()
{
    //This function checks $source table Users for the username of $userid
    global $master, $fest;
    $sql = "select * from `info_$fest`";
    $res = mysql_query($sql, $master);


    while ($urow = mysql_fetch_array($res)) {
        If ($urow['item'] == "Festival Name") $fest_name = $urow['value'];
        If ($urow['item'] == "Festival Year") $fest_year = $urow['value'];
    }

    $fname = $fest_name . " " . $fest_year;
    return $fname;

}

function getGname($source, $genreid)
{
    //This function checks $source table genres for the name of $genreid

    $sql = "select name from `genres` where id=$genreid";
    $res = mysql_query($sql, $source);


    $grow = mysql_fetch_array($res);
    $gname = $grow['name'];

    return $gname;

}

function getBandGenre($band, $user)
{
    //This function gets the name of a genre for a given user and band

    global $master;
    $mrow['master_id'] = $band;
    //If the user has an entry in the genre table for that band, return that genre
    $sql = "select genre from bandgenres where band='" . $mrow['master_id'] . "' and user='$user'";
    $res = mysql_query($sql, $master);
    If (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $gid = $row['genre'];
    } else {
        //If the user has no entry, return the genre with the highest count
        $sql1 = "select genre, count(user) as num from bandgenres where band='" . $mrow['master_id'] . "' group by genre order by num desc limit 1";
        $res1 = mysql_query($sql1, $master);
        If (mysql_num_rows($res1) > 0) {
            $row1 = mysql_fetch_array($res1);
            $gid = $row1['genre'];
        } else $gid = 0;
    }
    $gname = getGname($master, $gid);

    return $gname;
}

function getBandGenreID($band, $user)
{
    //This function gets the id of a genre for a given user and band
    global $master;

    $mrow['master_id'] = $band;
    //If the user has an entry in the genre table for that band, return that genre
    $sql = "select genre from bandgenres where band='" . $mrow['master_id'] . "' and user='$user'";
    $res = mysql_query($sql, $master);
    If (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $gid = $row['genre'];
    } else {
        //If the user has no entry, return the genre with the highest count
        $sql1 = "select genre, count(user) as num from bandgenres where band='" . $mrow['master_id'] . "' group by genre order by num desc limit 1";
        $res1 = mysql_query($sql1, $master);
        If (mysql_num_rows($res1) > 0) {
            $row1 = mysql_fetch_array($res1);
            $gid = $row1['genre'];
        } else $gid = 0;
    }

    return $gid;
}

function getPname($stageid)
{
    //This function checks $source table stages for the name of $stageid
    global $master;
    $sql = "select `name` from `places` where `id`='$stageid'";
    $res = mysql_query($sql, $master);


    $srow = mysql_fetch_array($res);
    $sname = $srow['name'];

    return $sname;

}

function getDname($dayid)
{
    //This function checks $source table stages for the name of $stageid
    global $master;
    $sql = "select `name` from `days` where `id`='$dayid'";
    $res = mysql_query($sql, $master);


    $srow = mysql_fetch_array($res);
    $sname = $srow['name'];

    return $sname;

}

function getSuggestedDays()
{
    //This function returns an array containing id, name, priority and layout for each stage in the festival
    global $master, $fest, $festSeries;
    $sql = "select `id`, `name`, `days_offset` from `days` where festival='$fest'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($srow = mysql_fetch_array($res)) {
            $sname[] = $srow;
        }
        return $sname;
    } else {
        $sql = "select `name`, `days_offset` from `days` where festival_series='$festSeries' GROUP BY `days_offset` ORDER BY `days_offset` ASC";
        $res = mysql_query($sql, $master);
        if (mysql_num_rows($res) > 0) {
            while ($srow = mysql_fetch_array($res)) {
                $sname[] = $srow;
            }
            return $sname;
        }
    }
    return false;
}

function getAllDays()
{
    //This function returns an array containing id, name, priority and layout for each stage in the festival
    global $master, $fest;
    $sql = "select `id`, `name`, `days_offset` from `days` where `deleted` != '1' and `festival`='$fest' ORDER BY `days_offset` ASC";
    $res = mysql_query($sql, $master);
    while ($srow = mysql_fetch_array($res)) {
        $sname[] = $srow;
    }
    return $sname;
}

function getAllDates()
{
    //This function returns an array containing id, name, priority and layout for each stage in the festival
    global $master, $fest;
    $sql = "select `id`, `name`, `venue`, `basedate`, `mode` from `dates` where `deleted` != '1' and `festival`='$fest'";
    $res = mysql_query($sql, $master);
    while ($srow = mysql_fetch_array($res)) {
        $sname[] = $srow;
    }
    return $sname;
}

function getAllStages()
{
    //This function returns an array containing id, name, priority and layout for each stage in the festival
    global $master, $fest;
    $sql = "select `id`, `name`, `layout`, `priority` from `places` where `deleted` != '1' and `type`='1' and `festival`='$fest' ORDER BY `priority` ASC";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($srow = mysql_fetch_array($res)) {
            $sname[] = $srow;
        }
        return $sname;
    } else return false;
}

function getStageLayoutName($layout)
{
    global $master;
    $sql = "select description from stage_layouts where id='$layout'";
//	echo $sql;
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $slName = $row['description'];
    } else {
        $sql = "select description from stage_layouts where `default`='1' LIMIT 1";
        $res = mysql_query($sql, $master);
        $row = mysql_fetch_array($res);
        $slName = $row['description'];
    }
    echo $slName;
}

function getAllStageLayouts()
{
    //This function returns an array containing the id of each stage layout
    global $master;
    $sql = "select `id`, `description`, `default` from `stage_layouts` where `deleted` != '1'";
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        $result[] = $row;
    }
    return $result;
}

function displayStageLayoutPic($basepage, $layout)
{
    global $master;
    $sql = "select description from stage_layouts where id='$layout'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $title_content = $row['description'];

    $pgdisp = "<div class=\"stagelayoutpicwrapper\" ><img class = \"stagelayoutpic\" src=\"" . $basepage;
    $pgdisp .= "includes/content/blocks/getPicStageLayout.php?layout=" . $layout;
    $pgdisp .= "\" alt=\"stage layout pic\" /><div class=\"stagelayoutpictitle\">";
    $pgdisp .= "<p class=\"title_content\">" . $title_content . "</p>";
    $pgdisp .= "</div><!-- end .stagelayoutpictitle --></div><!-- end .stagelayoutpicwrapper -->";
    echo $pgdisp;
}

function getAvailableStagePriorities()
{
    global $master;
    $sql = "select level from stage_priorities";
    $res = mysql_query($sql, $master);
    $max_level = 0;
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $napriority[] = $row['level'];
            if ($row['level'] > $max_level) $max_level = $row['level'];
        }
        for ($i = 1; $i < $max_level; $i++) {
            if (!in_array($i, $napriority)) $priority[] = $i;
        }
    }
    for ($i = 1; $i < 6; $i++) {
        $priority[] = $max_level + $i;
    }
    return $priority;
}

function getStagePriorities()
{
    global $master;
    $sql = "select `id`, `name`, `level`, `description`, `default` from `stage_priorities` where `deleted`!='1' order by `level` asc";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $priority[] = $row;
        }
    }
    return $priority;
}

function getStagePriorityInfoFromLevel($priorityid)
{
    global $master;
    $sql = "SELECT `id`, `name`, `level`, `description`, `default` FROM `stage_priorities` where `deleted`!='1' AND `level`='$priorityid'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $priority = mysql_fetch_array($res);
    } else {
        $sql = "SELECT `id`, `name`, `level`, `description`, `default` FROM `stage_priorities` where `default`='1'";
        $res = mysql_query($sql, $master);
        $priority = mysql_fetch_array($res);
    }
    return $priority;
}

function getForumLink($master, $user, $mainforum, $forumblog)
{
    //This function checks $source table stages for the name of $stageid

    $sql = "select value from `user_settings_$user` where item='Forum Link'";
    $res = mysql_query($sql, $master);


    $srow = mysql_fetch_array($res);
    $flink = $srow['value'];
    switch ($flink) {
        case 1:
            return $forumblog;
            break;
        case 2:
            return $mainforum;
            break;
        default:
            break;

    }

    return false;

}

function getNewFestivals()
{
    //This function returns an array containing three recently added festivals
    global $master;
    $sql = "select `id`, `sitename`, `description`, `website` from `festivals` where `deleted`!='1' ORDER BY `id` DESC LIMIT 3";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $series[] = $row;
        }
        return $series;
    } else return false;
}

function getIncompleteFestivals($master)
{
    //This function returns an array containing each festival that has had all its information completed
    $sql = "select `id`, `sitename`, `description`, `website`, ";
    $sql .= "`header`, `dates`, `days_venues`, `stages`, `band_list`, `band_priority`, `band_days`, `band_stages`, `set_times`, ";
    $sql .= "`header_v`, `dates_v`, `days_venues_v`, `stages_v`, `band_list_v`, `band_priority_v`, `band_days_v`, `band_stages_v`, `set_times_v`";
    $sql .= " from `festivals` where `deleted`!='1' AND (`header`='0'";
    $sql .= " OR `dates`='0' OR `days_venues`='0' OR `stages`='0' OR `band_list`='0' OR `band_priority`='0' OR `band_days`='0' OR `band_stages`='0' OR `set_times`='0')";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $series[] = $row;
        }
        return $series;
    } else return false;
}

function getCompletedFestivals($master)
{
    //This function returns an array containing each festival that has had all its information completed and verified
    $sql = "select `id`, `sitename`, `description`, `website`, ";
    $sql .= "`header`, `dates`, `days_venues`, `stages`, `band_list`, `band_priority`, `band_days`, `band_stages`, `set_times`, ";
    $sql .= "`header_v`, `dates_v`, `days_venues_v`, `stages_v`, `band_list_v`, `band_priority_v`, `band_days_v`, `band_stages_v`, `set_times_v`";
    $sql .= " from `festivals` where `deleted`!='1'";
    $sql .= " AND `header`!='0' AND `dates`!='0' AND `days_venues`!='0' AND `stages`!='0' AND `band_list`!='0' AND `band_priority`!='0' AND `band_days`!='0' AND `band_stages`!='0' AND `set_times`!='0'";
    $sql .= " AND `header_v`!='0' AND `dates_v`!='0' AND `days_venues_v`!='0' AND `stages_v`!='0' AND `band_list_v`!='0' AND `band_priority_v`!='0' AND `band_days_v`!='0' AND `band_stages_v`!='0' AND `set_times_v`!='0'";
//    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $series[] = $row;
        }
        return $series;
    } else return false;
}

function getVerifReqFestivals($master)
{
    //This function returns an array containing each festival that has information needing verification
    $sql = "select `id`, `sitename`, `description`, `website`, ";
    $sql .= "`header`, `dates`, `days_venues`, `stages`, `band_list`, `band_priority`, `band_days`, `band_stages`, `set_times`, ";
    $sql .= "`header_v`, `dates_v`, `days_venues_v`, `stages_v`, `band_list_v`, `band_priority_v`, `band_days_v`, `band_stages_v`, `set_times_v`";
    $sql .= " from `festivals` where `deleted`!='1'";
    $sql .= " AND ( (`header`!='0' AND `header_v`='0' ) OR ( `dates`!='0' AND `dates_v`='0' ) OR ( `days_venues`!='0' AND `days_venues_v`='0' ) ";
    $sql .= "OR ( `stages`!='0' AND `stages_v`='0' ) OR ( `band_list`!='0' AND `band_list_v`='0' ) OR ( `band_priority`!='0' AND `band_priority_v`='0' ) OR ( `band_days`!='0' AND `band_days_v`='0' ) OR ( `band_stages`!='0' AND `band_stages_v`='0' ) OR ( `set_times`!='0' AND `set_times_v`='0' ) )";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $series[] = $row;
        }
        return $series;
    } else return false;
}

function getFestVenues($master)
{
    //This function returns an array containing each festival venue in FestivalTime
    $sql = "select `id`, `name`, `description`, `country`, `state`, `city`, `street_address`, `timezone` from `venues` where `deleted`!='1'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $series[] = $row;
        }
        return $series;
    } else return false;
}

function getFestHeader($fest)
{
    //This function returns an array containing the header info for one festival
    global $master;
    $sql = "select `id`, `sitename`,  `name`, `year`, `description`, `series`, `start_time`, `length`, `website`, `cost`, `num_days`, `num_dates`, ";
    $sql .= "`header`, `dates`, `days_venues`, `stages`, `band_list`, `band_priority`, `band_days`, `band_stages`, `set_times`, ";
    $sql .= "`header_v`, `dates_v`, `days_venues_v`, `stages_v`, `band_list_v`, `band_priority_v`, `band_days_v`, `band_stages_v`, `set_times_v`";
    $sql .= " from `festivals` where `deleted`!='1' and `id`='$fest'";
//	error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
//		error_log(print_r("results received from header", TRUE));
        return $row;
    } else return false;
}

function getFestSeries($master)
{
    //This function returns an array containing each festival series in FestivalTime
    $sql = "select `id`, `name`, `description` from `festival_series` where `deleted`!='1'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $series[] = $row;
        }
        return $series;
    } else return false;
}

function getFestivalsBandIsIn($band)
{
    //This function returns an array containing the id of each festival the band is registered for
    global $master;

    $sql = "select `festival` from `band_list` where `band`='$band'";
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        $final[] = $row['festival'];
    }
    If (isset($final)) {
        shuffle($final);
        return $final;
    } else return false;
}


function getFestivalsMaster($master_id, $master)
{
    //This function returns an array containing the id of each festival the band is registered for

    $sql = "select festivals from bands where id='$master_id'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $raw = $row['festivals'];
    $working = explode("-", $raw);
    $i = 0;
    foreach ($working as $v) {
        If (isInteger($v)) {
            $final[$i] = $v;
        }
        $i++;
    }
    If (isset($final)) return $final; else return false;
}


function getUpcomingFestivals()
{
    //This function returns an array containing the id of each festival with at least one date starting in the future
    global $master;
    $sql = "SELECT `festival`, `basedate` FROM `dates` WHERE `basedate` > NOW() AND `deleted`!=1 ORDER BY `dates`.`basedate` ASC";

}

function getInterestingFactor($user, $main, $master, $fest)
{
    //This function returns an array containing the id of each band in the fest, along with a rating on how interesting the band is
    //First get all the bands
    $bandlist = getAllBandsInFest();
    foreach ($bandlist as $id) {
        $interfact[$id] = 0;
    }
    // Difference between pregame rating and live rating x20
    foreach ($interfact as $bandid => &$factor) {
        $pregame = act_rating($bandid, $user, $main);
        $gametime = act_live_rating($bandid, $user, $main);
        $factor = $factor + abs($pregame - $gametime) * 20;
//		echo "<br>pregame : $pregame gametime: $gametime factor: $factor band: $bandid";
    }
//	var_dump($interfact);
    //Band with high live average you missed And with low live average ou missed
    $missedBands = getAllBandsUserMissed($user, $main);
    foreach ($missedBands as $v) {
        $avg = avg_live_rating_band($main, $v);
        if ($avg != 0) $interfact[$v] = $interfact[$v] + 20 * (abs(3 - $avg) ^ 2);
    }
    arsort($interfact);
    return $interfact;


    //If you have a postgame comment, IF is 0
}

function getBandPriorityInfoFromLevel($priorityid)
{
    global $master;
    $sql = "SELECT `id`, `name`, `level`, `description`, `default` FROM `band_priorities` where `deleted`!='1' AND `level`='$priorityid'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $priority = mysql_fetch_array($res);
    } else {
        $sql = "SELECT `id`, `name`, `level`, `description`, `default` FROM `band_priorities` where `default`='1'";
        $res = mysql_query($sql, $master);
        $priority = mysql_fetch_array($res);
    }
    return $priority;
}

function getDefaultBandPriority()
{
    //This function returns the level of the default band priority
    global $master;
    $sql = "SELECT `level` from `band_priorities` WHERE `default`='1' AND `deleted`!='1' LIMIT 1";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $raw = $row['level'];
        return $raw;
    }
    return false;
}

function getAllBandPriorities()
{
    //This function returns an array containing the ID and priority of each band in the festival
    global $master, $fest;
    $sql = "SELECT `id`, `priority`, `band` from `band_list` WHERE `deleted`!='1' AND `festival`='$fest' ORDER BY `priority` ASC";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $raw[] = $row;
        }
        return $raw;
    }
    return false;
}

function getAllAvailableBandPriorities()
{
    //This function returns an array containing the ID and priority of each band in the festival
    global $master, $fest;
    $sql = "SELECT `level`, `name`, `default` from `band_priorities` WHERE `deleted`!='1' ORDER BY `level` ASC";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $raw[] = $row;
        }
        return $raw;
    }
    return false;
}

function getAllBandsUserMissed($user, $main)
{
    //This function returns an array containing the id of each band in the festival
    $saw = getAllBandsUserSaw($user, $main);
    $where = "WHERE deleted != '1'";
    $i = 0;
    foreach ($saw as $v) {
        $where .= " AND id != '$v'";
    }
    $sql = "select id from bands " . $where;
    $res = mysql_query($sql, $main);
    while ($row = mysql_fetch_array($res)) {
        $result[] = $row['id'];
    }
    return $result;
}

function getAllBandsUserSaw($user, $main)
{
    //This function returns an array containing the id of each band in the festival
    $sql = "select band from comms where fromuser= '$user'";
    $res = mysql_query($sql, $main);
    while ($row = mysql_fetch_array($res)) {
        $result[] = $row['band'];
    }
    return $result;
}

function getActiveBands()
{
    global $master;
    $sql = "SELECT band FROM(SELECT count(band) as hits, band FROM `messages` WHERE `phptime` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR))";
    $sql .= "AND `band` > '0' GROUP BY `band` ORDER BY hits DESC LIMIT 25) AS INTER ORDER BY RAND() LIMIT 3";
//        error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        $result[] = $row['band'];
    }
    return $result;

}

function getAllBandsInFest()
{
    //This function returns an array containing the id of each band in the festival, headliners first
    global $master, $fest;
    $sql = "select `band` from `band_list` where `deleted` != '1' AND `festival`='$fest' ORDER BY `priority` ASC";
//    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        $result[] = $row['band'];
    }
    return $result;
}

function getSimilarBandNameFromName($name)
{
    //This function returns an array containing the id and name of each band with a similar name
    global $master;

    $namesize = strlen($name);
    If ($namesize < 6) $testname = $name;
    If ($namesize >= 6 && $namesize < 10) $testname = substr($name, 3);
    If ($namesize >= 10) $testname = substr($name, 4, 6);

    $sql = "select `id`, `name` from `bands` where `name` like '%" . $testname . "%'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getDayAndStageOfSetsByBandInFest($bandid)
{
    //This function returns an array containing keyed to the id of each each set by the band, containing the stage and day
    global $master, $fest;
    $sql = "select `id`, `day`, `stage` from `sets` where `deleted` != '1' and `festival`='$fest' AND `band`='$bandid'";
    $res = mysql_query($sql, $master);
    $num = mysql_num_rows($res);
    while ($row = mysql_fetch_array($res)) {
        $sets[] = $row;
    }
    if ($num == 0) $sets = $num;
    return $sets;
}

function getNumberOfSetsByBandInFest($bandid)
{
    //This function returns an array containing the id of each band in the festival
    global $master, $fest;
    $sql = "select `id` from `sets` where `deleted` != '1' and `festival`='$fest' AND `band`='$bandid'";
    $res = mysql_query($sql, $master);
    $num = mysql_num_rows($res);
    return $num;
}

function getBandIDFromName($name)
{
    //This function returns an array containing the id of each band in the festival
    global $master;
    $sql = "select `id` from `bands` where `deleted` != '1' and `name`='$name'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row['id'];
        }
        return $result;
    } else return false;
}

function getDayAndStageNeedingimes()
{
    //This function returns an array containing the id, start offset and end offset each band in the festival playing a given day and stage
    global $master, $fest;
    $sql = "select `id`, `day`, `stage` from `sets` where `deleted` != '1' and `end`='0' and `festival`='$fest' GROUP BY `day`, `stage`";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getBandsByDayAndStage($day, $stage)
{
    //This function returns an array containing the id, start offset and end offset each band in the festival playing a given day and stage
    global $master;
    $sql = "select `id`, `start`, `end`, `band` from `sets` where `deleted` != '1' and `day`='$day' and `stage`='$stage'";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getUserIDFromUserName($userName)
{
    global $master;
    $query = "SELECT `id` FROM `Users` WHERE `username`='" . $userName . "' AND `deleted`!='1'";
    $query_user = mysql_query($query, $master);
//  echo mysql_error();
    $user_row = mysql_fetch_assoc($query_user);
    $user = $user_row['id'];
    return $user;
}

function getFollowedBy($user, $master)
{
    //This function returns an array containing the id of each user the entered user is following
    $sql = "select follows from Users where id='$user'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $raw = $row['follows'];

    $working = explode("-", $raw);
    $i = 0;
    foreach ($working as $v) {
        If (isInteger($v)) {
            $final[$i] = $v;
        }
        $i++;
    }
    If (isset($final)) return $final; else return false;
}

function userIsPrivate($user, $master)
{
    //This function returns true if the given user is private
    $sql = "select value from user_settings_$user where item='Privacy'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $privacy = $row['value'];

    If ($privacy == 1) return false; else return true;
}

function userFollowsUser($follower, $followee, $master)
{
    //This function returns true if the follower follows the followee
    $sql = "select id from Users where id='$follower' AND follows like '%--$followee--%'";
    $res = mysql_query($sql, $master);
    $follows = mysql_num_rows($res);

    If ($follows > 0) return true; else return false;
}

function userBlocksUser($blocker, $blockee, $master)
{
    //This function returns true if the blocker blocks the blockee
    $sql = "select id from Users where id='$blocker' AND blocks like '%--$blockee--%'";
    $res = mysql_query($sql, $master);
    $blocks = mysql_num_rows($res);

    If ($blocks > 0) return true; else return false;
}

function userVisibleToUser($looker, $lookee, $master)
{
    //This function returns true if the lookee is visible to the looker

    if (userBlocksUser($lookee, $looker, $master)) return false;
    if (userIsPrivate($lookee, $master) && !userFollowsUser($lookee, $looker, $master)) return false;

    return true;
}

function getVisibleUsers($user, $master)
{
    //This function returns an array containing the id and name of each user visible to the entered user
    $sql = "select id, username from Users";
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        if (userVisibleToUser($user, $row['id'], $master)) $visibleUsers[] = $row;
    }
    if (empty($visibleUsers)) return false;
    return $visibleUsers;
}

function festSoon($festid)
{
    return true;
}

function getAllRegisteredFestivals($user, $master)
{
    //This function returns an array containing the festivals acessible by user
    $sql = "select access from Users where id='$user'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $raw = $row['access'];
    $working = explode("-", $raw);
    $i = 0;
    foreach ($working as $v) {
        If (isInteger($v)) {
            $final[$i] = $v;
        }
        $i++;
    }

    if (isset($final)) return $final;
    return false;
}

function getMasterBandIDFromFest($band, $main)
{
    $sql = "SELECT master_id FROM bands WHERE id = '$band'";
    $res = mysql_query($sql, $main);
    $row = mysql_fetch_array($res);
    $master = $row['master_id'];
    return $master;

}

function doesBandHaveShape($band, $master, $shapeCode)
{
    $sql = "SELECT shape FROM pics WHERE band = '$band' GROUP BY shape";
    $res = mysql_query($sql, $master);
    $codeOK = 0;
    while ($row = mysql_fetch_array($res)) {
        switch ($shapeCode) {
            case 1:
                if ($row['shape'] == "small_square") $codeOK = 1;
                break;
            case 3:
                if ($row['shape'] == "small_square") $codeOK = 1;
                if ($row['shape'] == "horizontal_rectangle") $codeOK = 1;
                break;
            case 5:
                if ($row['shape'] == "small_square") $codeOK = 1;
                if ($row['shape'] == "vertical_rectangle") $codeOK = 1;
                break;
            case 15:
                if ($row['shape'] == "small_square") $codeOK = 1;
                if ($row['shape'] == "large_square") $codeOK = 1;
                if ($row['shape'] == "horizontal_rectangle") $codeOK = 1;
                if ($row['shape'] == "vertical_rectangle") $codeOK = 1;
                break;
            default:
                break;
        }
    }
    return $codeOK;
}

function getBandPicAndShape($intMaster, $master, $shapeCode)
{
    $sql = "SELECT id, shape FROM pics WHERE band = '$intMaster' ORDER BY RAND()";
    $res = mysql_query($sql, $master);
    $codeOK = 0;
    while ($row = mysql_fetch_array($res)) {
        switch ($shapeCode) {
            case 1:
                if ($row['shape'] == "small_square") {
                    $codeOK = 1;
                    $picReturn[0] = $row['id'];
                    $picReturn[1] = $row['shape'];
                }
                break;
            case 3:
                if ($row['shape'] == "small_square" ||
                    $row['shape'] == "horizontal_rectangle"
                ) {
                    $codeOK = 1;
                    $picReturn[0] = $row['id'];
                    $picReturn[1] = $row['shape'];
                }
                break;
            case 5:
                if ($row['shape'] == "small_square" ||
                    $row['shape'] == "vertical_rectangle"
                ) {
                    $codeOK = 1;
                    $picReturn[0] = $row['id'];
                    $picReturn[1] = $row['shape'];
                }
                break;
            case 15:
                if ($row['shape'] == "small_square" ||
                    $row['shape'] == "large_square" ||
                    $row['shape'] == "horizontal_rectangle" ||
                    $row['shape'] == "vertical_rectangle"
                ) {
                    $codeOK = 1;
                    $picReturn[0] = $row['id'];
                    $picReturn[1] = $row['shape'];
                }
                break;
            default:
                break;
        }
    }
    return $picReturn;
}

function displayPic4($basepage, $bandsFestID, $bandsMasterID, $fest, $title_content)
{
    $pgdisp = "<div class=\"bandgridpicwrapper\" ><a href=\"";
    $pgdisp .= $basepage . "?disp=view_band&band=" . $bandsFestID . "&fest=" . $fest . "\"><img class = \"bandgridpic\" src=\"" . $basepage;
    $pgdisp .= "includes/content/blocks/getPicture4.php?band=" . $bandsMasterID;
    $pgdisp .= "\" alt=\"band pic\" /><div class=\"bandgridpictitle\">";
    $pgdisp .= "<p class=\"title_content\">" . $title_content . "</p>";
    $pgdisp .= "</div><!-- end .bandgridpictitle --></a></div><!-- end .bandgridpicwrapper -->";
    echo $pgdisp;
}

function displayPic3($basepage, $bandsFestID, $bandsPicID, $fest, $title_content)
{
    $pgdisp = "<div class=\"bandgridpicwrapper\" ><a href=\"";
    $pgdisp .= $basepage . "?disp=view_band&band=" . $bandsFestID . "&fest=" . $fest . "\"><img ";
    $pgdisp .= "class = \"bandgridpic\" src=\"" . $basepage;
    $pgdisp .= "includes/content/blocks/getPicture3.php?pic=";
    $pgdisp .= $bandsPicID . "\" alt=\"band pic\" /><div class=\"bandgridpictitle\">";
    $pgdisp .= "<p class=\"title_content\">" . $title_content . "</p>";
    $pgdisp .= "</div><!-- end .bandgridpictitle --></a></div><!-- end .bandgridpicwrapper -->";
    echo $pgdisp;
}

function genreList($user)
{
    //This function returns all the genres in main, with genreid, genrename, number of bands in genre,
    //number of rated bands in genre, and total rating points for all bands in genre
    global $master;
    $sql = "select `id` from `bands` where `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $ret_genre = array();
    while ($row = mysql_fetch_array($res)) {
        $test['genreid'] = getBandGenreID($row['id'], $user);
        $test['rating'] = act_rating($row['id'], $user);
        echo "<br />test: ";
        var_dump($test);
        $genreJustAdded = 0;
        foreach ($ret_genre as &$g) {
            if ($test['genreid'] == $g['id']) {
                $genreJustAdded = 1;
                $g['bands'] = $g['bands'] + 1;
                if ($test['rating'] > 0) {
                    $g['rated'] = $g['rated'] + 1;
                    $g['rating_total'] = $g['rating_total'] + $test['rating'];
                }
            }
        }
        if ($genreJustAdded == 0) {
            $new['id'] = $test['genreid'];
            $new['name'] = getBandGenre($row['id'], $user);
            $new['bands'] = 1;
            $new['rating_total'] = act_rating($row['id'], $user);
            if ($new['rating_total'] == 0) $new['rated'] = 0; else $new['rated'] = 1;
            $ret_genre[] = $new;
        }
    }
    return $ret_genre;
}

function getGenresForAllBandsInFest($user)
{
    //Find genre of every band in main
    global $master, $fest;
    $sql = "select `id`, `name` from `band_list` where `festival`='$fest' and `deleted`!='1' order by rand()";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[$row['id']]['genreid'] = getBandGenreID($row['id'], $user);
            $result[$row['id']]['genrename'] = getBandGenre($row['id'], $user);
            $result[$row['id']]['bandname'] = $row['name'];
            $result[$row['id']]['id'] = $row['id'];
        }
        return $result;
    } else return false;
}

function acceptComment($main, $master, $user, $band, $fest_id, $comment)
{
    //This function returns the genre for each band in main
    $sql = "select `master_id` from `bands` where `id`='$band'";
    $res = mysql_query($sql, $main);
    $mas_id = mysql_fetch_assoc($res);
    $band_master_id = $mas_id['master_id'];

    $query = "SELECT comment FROM comments WHERE band='$band' AND user='$user'";
    $query_comment = mysql_query($query, $main);
    $num = mysql_num_rows($query_comment);
    $comment = mysql_real_escape_string($comment);

    //New comments
    If ($num == 0) {
        $sql = "INSERT INTO comments (band, user, comment, discuss_current) VALUES ('$band', '$user', '$comment', '--" . $user . "--')";
        $sql_run = mysql_query($sql, $main);
        //	echo $sql;
        $sql = "INSERT INTO comments (band, user, comment, festival) VALUES ('$band_master_id', '$user', '$comment', '$fest_id')";
        $sql_run = mysql_query($sql, $master);
        //Get id for new comment
        $sql = "select max(id) as disc from comments";
        $sql_run = mysql_query($sql, $main);
        $res = mysql_fetch_array($sql_run);

        //set $discuss_table for comment
        $discuss_table = "discussion_" . $res['disc'];

        //Create a discussion table for the comment
        $sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
        $res = mysql_query($sql, $main);
    } else {
        $sql = "UPDATE comments SET comment='$comment' WHERE band='$band' AND user='$user'";
        $sql_run = mysql_query($sql, $main);
        $sql = "UPDATE comments SET comment='$comment' WHERE band='$band_master_id' AND user='$user' and `festival`='$fest_id'";
        $sql_run = mysql_query($sql, $master);
    }
}

function acceptRating($main, $master, $user, $band, $fest_id, $rating)
{
    $rating = mysql_real_escape_string($rating);
    $sql = "select `master_id` from `bands` where `id`='$band'";
    $res = mysql_query($sql, $main);
    $mas_id = mysql_fetch_assoc($res);
    $band_master_id = $mas_id['master_id'];
    //Find out if an exisiting rating is in
    $query = "SELECT rating FROM ratings WHERE band='$band' AND user='$user'";
    $query_rating = mysql_query($query, $main);
    //	echo mysql_error();
    $rating_row = mysql_fetch_assoc($query_rating);

    If (!isset($rating_row['rating'])) {
        $sql = "INSERT INTO ratings (band, user, rating) VALUES ('$band', '$user', '$rating')";
        $sql_run = mysql_query($sql, $main);
        $sql = "INSERT INTO ratings (band, user, rating, festival) VALUES ('$band_master_id', '$user', '$rating', '$fest_id')";
        $sql_run = mysql_query($sql, $master);
    } else {
        //	echo "With rating logic entered<br>";
        $sql = "UPDATE ratings SET rating='$rating' WHERE band='$band' AND user='$user'";
        $sql_run = mysql_query($sql, $main);
        $sql = "UPDATE ratings SET rating='$rating' WHERE band='$band_master_id' AND user='$user'";
        $sql_run = mysql_query($sql, $master);
    }
}

function acceptDiscussReply($main, $master, $user, $band, $fest_id, $discuss_table, $escapedReply, $commentid)
{
    $sql = "select `master_id` from `bands` where `id`='$band'";
    $res = mysql_query($sql, $main);
    $mas_id = mysql_fetch_assoc($res);
    $band_master_id = $mas_id['master_id'];
    $escapedReply = mysql_real_escape_string($escapedReply);
    //Find out if an exisiting rating is in

    $query = "show tables like '$discuss_table'";
    $result = mysql_query($query, $main);

    If ((mysql_num_rows($result) == 0)) {
        //table did not exist, so create it
        $sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
        $res = mysql_query($sql, $main);
    }


    $comment = $commentid;
    $discuss_table = $_POST['discuss_table'];
    $escapedReply = mysql_real_escape_string($_POST['new_reply']);

    $sql = "INSERT INTO $discuss_table (user, response) VALUES ('$user', '$escapedReply')";
    $result = mysql_query($sql, $main);
    //Update the tracking columns in the comment table to reflect the activity
    $query = "UPDATE comments SET discuss_current='--$user--' where id=$comment";
    $upd = mysql_query($query, $main);
}

function changeMode($main, $master, $festmode, $fest)
{
    switch ($festmode) {
        case 1:
            break;
        case 2:
            break;
        case "postgame":
            $sql = "SELECT id FROM Users";
            $res = mysql_query($sql, $master);
            $userString = "";
            while ($row = mysql_fetch_array($res)) {
                $userString .= "--" . $row['id'] . "--";
            }
            $sql = "SELECT max(id) as inc FROM comments";
            $res = mysql_query($sql, $main);
            $row = mysql_fetch_array($res);
            $inc = $row['inc'] + 1000;
            $query = "UPDATE comments SET discuss_current='$userString'";
            $upd = mysql_query($query, $main);
            $sql = "CREATE TABLE IF NOT EXISTS `postgame_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `band` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  `discussed` varchar(4096) NOT NULL,
  `discuss_current` varchar(4096) NOT NULL,
  `pinned` varchar(4096) NOT NULL,
  `ignored` varchar(4096) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=$inc ;";
            $res = mysql_query($sql, $main);
            break;
        default:
            break;
    }
    $sql = "UPDATE festivals SET mode='$festmode' WHERE id='$fest'";
    $res = mysql_query($sql, $master);
}

function submitPregame($main, $master, $submittedJSON)
{

    if (!empty($submittedJSON['pending_updates']['result'])) {
        $serverUpdates = $submittedJSON['pending_updates']['result'];
        foreach ($serverUpdates as $upd) {
            switch ($upd['type']) {
                case "addComment":
                    acceptComment($main, $master, $upd['json']['uid'], $upd['json']['bandid'], $upd['json']['festid'], $upd['json']['comment']);
                    $response['serverUpdated'][] = $upd['_id'];
                    break;
                case "addRating":
                    acceptRating($main, $master, $upd['json']['uid'], $upd['json']['bandid'], $upd['json']['festid'], $upd['json']['rating']);
                    $response['serverUpdated'][] = $upd['_id'];
                    break;
                case "addDiscussionReply":
                    acceptDiscussReply($main, $master, $upd['json']['uid'], $upd['json']['bandid'], $upd['json']['festid'], $upd['json']['discussTable'], $upd['json']['discussReply'], $upd['json']['commentID']);
                    $response['serverUpdated'][] = $upd['_id'];
                    break;
                default:
                    $response['error'] = 100;
                    $response["error_msg"] = "Update type unknown.";
                    break;
            }
        }
    }

    $sql = "SELECT DATABASE() as db";
    $res = mysql_query($sql, $main);
    $dbname = mysql_fetch_array($res);
    $maindb = $dbname['db'];

    $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$maindb' AND table_name NOT LIKE 'discuss_%'";
    $result = mysql_query($query, $main);
    $submittedTableState = $submittedJSON['tableStates'];

    while ($row = mysql_fetch_array($result)) {
        $table_name = $row['table_name'];
//		$debug['tableName'][] = $table_name;
        $tablesql = "SELECT * FROM `$table_name`";
        $tableres = mysql_query($tablesql, $main);
        if (empty($submittedTableState[$table_name])) {
//			$debug[] = "Table $table_name submission is empty";
            If ((mysql_num_rows($tableres) > 0)) {
                $i = 0;
                while ($tablerow = mysql_fetch_assoc($tableres)) {
                    $response['localUpdates'][$table_name][$i]['action'] = "add";
                    $response['localUpdates'][$table_name][$i]['row'] = $tablerow;
                    $i++;
                }
            }
        } else {
//			$debug[] = "Table $table_name submission is not empty";
            $i = 0;
            while ($tablerow = mysql_fetch_assoc($tableres)) {
                if ($table_name == "live_rating") $timestampCol = "time";
                else $timestampCol = "timestamp";
                $id = $tablerow['id'];
                $serverTimestamp = $tablerow[$timestampCol];
//				$debug[] = "Table $table_name checking id $id";
                unset($mobileTableRowKey);
                foreach ($submittedTableState[$table_name]['result'] as $k => $v) {
                    if ($v['id'] == $id) {
//						$debug[] = "Table $table_name checking id $id found key $k";
                        $mobileTableRowKey = $k;
                        break;
                    }
                }
                if (!isset($mobileTableRowKey)) {
                    $response['localUpdates'][$table_name][$i]['action'] = "add";
                    $response['localUpdates'][$table_name][$i]['row'] = $tablerow;
                } else {
                    if ($submittedTableState[$table_name]['result'][$mobileTableRowKey]['timestamp'] == $tablerow[$timestampCol]) {
//						$debug[] = "Table $table_name checking id $id found key $mobileTableRowKey with matching timestamp ".$tablerow[$timestampCol];

                    } else {
                        $response['localUpdates'][$table_name][$i]['action'] = "update";
                        $response['localUpdates'][$table_name][$i]['row'] = $tablerow;
                    }
                }
                $i++;
            }
        }
    }
    if (empty($response)) $response['localUpdates'] = "noChange";


//	      $response['debug'] = $debug;
    return $response;
}

?>
