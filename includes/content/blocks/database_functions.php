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

function getUSItem($setting, $value)
{
    //This function checks $source table Users for the username of $userid
    global $master;
    $sql = "select `item` from `user_setting_value` where `user_setting`='$setting' AND `value`='$value'";
    $res = mysql_query($sql, $master);
    $urow = mysql_fetch_array($res);
    $uname = $urow['item'];
    return $uname;

}

function getUname($userid)
{
    //This function checks $source table Users for the username of $userid
    global $master;
    $sql = "select username from `Users` where id='$userid'";
    $res = mysql_query($sql, $master);
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

function getFname($fest)
{
    //This function checks $source table Users for the username of $userid
    $h = getFestHeader($fest);

    $fname = $h['sitename'];
    return $fname;

}

function getFSname($festSeries)
{
    //This function checks $source table Users for the username of $userid
    global $master;
    $sql = "select `name` from `festival_series` where `id`='$festSeries'";
    $res = mysql_query($sql, $master);
    $urow = mysql_fetch_array($res);
    $bname = $urow['name'];
    return $bname;

}

function getLname($level)
{
    //This function checks $source table Users for the username of $userid
    global $master;
    $sql = "select `name` from `band_priorities` where `level`='$level'";
    $res = mysql_query($sql, $master);
    $urow = mysql_fetch_array($res);
    $bname = $urow['name'];
    return $bname;

}

function getVname($venue)
{
    //This function checks $source table Users for the username of $userid
    global $master;
    $sql = "select `name` from `venues` where `id`='$venue'";
    $res = mysql_query($sql, $master);
    $urow = mysql_fetch_array($res);
    $bname = $urow['name'];
    return $bname;

}

function getGname($genreid)
{
    //This function checks $source table genres for the name of $genreid
    global $master;
    $sql = "select name from `genres` where id=$genreid";
    $res = mysql_query($sql, $master);


    $grow = mysql_fetch_array($res);
    $gname = $grow['name'];

    return $gname;

}

function getBandGenre($band, $user)
{
    //This function gets the name of a genre for a given user and band

    $gname = getGname(getBandGenreID($band, $user));

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
    $genreSetting = getUserSetting($user, 73);
    if ($genreSetting == 2) return $gid;
    return getParentGenre($gid);


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

function getAllUserSettings()
{
    //This function returns all the possible user settings

    global $master;
    $sql = "select `id`, `name`, `description` from `user_setting` where `deleted`!='1'";
    $res = mysql_query($sql, $master);

    if (mysql_num_rows($res) > 0) {
        $value = array();
        while ($row = mysql_fetch_array($res)) {
            $value[] = $row;
        }
        return $value;
    }
    return false;
}

function getDefaultValueForUserSetting($setting)
{
    //This function returns the default value for the given user setting

    return getUserSetting(0, $setting);
}

function getPermValuesForUserSetting($setting)
{
    //This function returns the permissible values for the given user setting

    global $master;
    $sql = "select `item`, `value` from `user_setting_value` where `user_setting`='$setting' AND `deleted`!='1'";
    $res = mysql_query($sql, $master);

    if (mysql_num_rows($res) > 0) {
        $value = array();
        while ($row = mysql_fetch_array($res)) {
            $value[] = $row;
        }
        return $value;
    }
    return false;
}

function getUserSetting($user, $setting)
{
    //This function returns the current value of $setting for $user

    global $master;
    $sql = "select `value` from `user_setting_current` where `user`='$user' AND `user_setting`='$setting'";
    $res = mysql_query($sql, $master);

    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $value = $row['value'];
        return $value;
    }
    $sql = "select `value` from `user_setting_current` where `user`='0' AND `user_setting`='$setting'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $value = $row['value'];
    return $value;
}

function setUserSetting($user, $setting, $value)
{
    //This function sets a new value of $setting for $user

    global $master;
    $sql = "select `value` from `user_setting_current` where `user`='$user' AND `user_setting`='$setting'";
    $res = mysql_query($sql, $master);

    if (mysql_num_rows($res) > 0) {
        $table = "user_setting_current";
        $cols = array("value");
        $vals = array($value);
        $where = "`user`='" . $user . "' AND `user_setting`='$setting'";
        updateRow($table, $cols, $vals, $where);
        return true;
    }
    $table = "user_setting_current";
    $cols = array("user", "user_setting", "value");
    $vals = array($user, $setting, $value);
    insertRow($table, $cols, $vals);
    return true;
}

function createUserSetting($settingName, $settingDescription, $valueName)
{
    //This function creates a new setting with one permissible value

    $table = "user_setting";
    $cols = array("name", "description");
    $vals = array($settingName, $settingDescription);
    $setting = insertRow($table, $cols, $vals);
    createPermissibleUserSettingValue($setting, $valueName);

    return true;
}

function deleteUserSetting($setting)
{
    //This function deletes a user setting
    $table = "user_setting";
    $cols = array("deleted");
    $vals = array(1);
    $where = "`id`='$setting'";
    updateRow($table, $cols, $vals, $where);
    $table = "user_setting_current";
    $where = "`user_setting`='$setting'";
    updateRow($table, $cols, $vals, $where);
    $table = "user_setting_value";
    updateRow($table, $cols, $vals, $where);
    return true;
}

function createPermissibleUserSettingValue($setting, $name)
{
    //This function sets a new value of $setting for $user

    $values = getPermValuesForUserSetting($setting);
    $i = 0;
    foreach ($values as $v) {
        if ($v['value'] > $i) $i = $v['value'];
    }
    $i = $i + 1;

    $table = "user_setting_value";
    $cols = array("item", "user_setting", "value");
    $vals = array($name, $setting, $i);
    insertRow($table, $cols, $vals);
    return true;
}

function setDefaultUserSettingValue($setting, $value)
{
    //This function sets a new default value of $setting

    return setUserSetting(0, $setting, $value);
}

function getForumLink($user, $mainforum, $forumblog)
{
    $flink = getUserSetting($user, 70);
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

function purchaseFest($user, $fest)
{
    global $master;
    //First get the cost of the festival
    $header = getFestHeader($fest);
    $cost = $header['cost'];
    //Verify that the user has enough credits to mkae the purchase
    $sql = "SELECT `credits` FROM `Users` WHERE `id`='$user'";
    $res = mysql_query($sql, $master);
    if (!mysql_num_rows($res) > 0) return false;
    $row = mysql_fetch_array($res);
    $creditsAvail = $row['credits'];
    if ($creditsAvail < $cost) return false;
    //Now perform the transaction: add the user to the festival:
    $table = "festival_monitor";
    $cols = array("festival", "user", "phptime");
    $vals = array($fest, $user, time());
    insertRow($table, $cols, $vals);
    //And debit the credits
    $sql = "UPDATE  `Users` SET  `credits` = ( credits - $cost) WHERE  `id` =  '$user'";
    return mysql_query($sql, $master);

}

function getActiveFests()
{
    //This function returns an array containing three recently added festivals
    global $master;
    //First query: Get every festival that has started or is just about to, along with the base date of the last date of the festival
    $sql = "SELECT festival, max(`basedate`) as max FROM `dates` WHERE `basedate` <= DATE_ADD(NOW(), INTERVAL 2 DAY) AND `deleted`!='1' GROUP BY `festival`";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) == 0) return false;
    while ($row = mysql_fetch_array($res)) {
        $checkFest = $row['festival'];
        $maxDate = $row['max'];
        $sql2 = "SELECT * FROM `days` WHERE `festival`='$checkFest' AND DATE_ADD('$maxDate', INTERVAL (days_offset + 2 ) DAY) >= NOW() GROUP BY FESTIVAL";
        $res2 = mysql_query($sql2, $master);
        if (mysql_num_rows($res2) > 0) {
            $activeFest[] = $checkFest;
        }
    }
    if (!empty($activeFest)) return $activeFest;
    else return false;
}

function getUpcomingFests($excludeFests)
{
    //This function returns an array upcoming fests that are not currently active. $excludeFests is an array to exclude any festivals you don't want returned
    global $master;
    //First query: Get every festival that is about to start
    $sql = "SELECT festival FROM `dates` WHERE `basedate` > DATE_ADD(NOW(), INTERVAL 2 DAY) AND `deleted`!='1' GROUP BY `festival`";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) == 0) return false;
    while ($row = mysql_fetch_array($res)) {
        $checkFest = $row['festival'];

        if (!in_array($checkFest, $excludeFests)) {
            $activeFest[] = $checkFest;
        }
    }
    if (!empty($activeFest)) return $activeFest;
    else return false;
}

function getPastsFests($excludeFests)
{
    //This function returns an array upcoming fests that are not currently active. $excludeFests is an array to exclude any festivals you don't want returned
    global $master;
    //First query: Get every festival that has started
    $sql = "SELECT festival FROM `dates` WHERE `basedate` < DATE_SUB(NOW(), INTERVAL 1 DAY) AND `deleted`!='1' GROUP BY `festival`";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) == 0) return false;
    while ($row = mysql_fetch_array($res)) {
        $checkFest = $row['festival'];

        if (!is_array($excludeFests) || !in_array($checkFest, $excludeFests)) {
            $pastFest[] = $checkFest;
        }
    }
    if (!empty($pastFest)) return $pastFest;
    else return false;
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

function getFestivalsBandDeletedFrom($band)
{
    //This function returns an array containing the id of each festival the band is registered for
    global $master;

    $sql = "select `festival` from `band_list` where `band`='$band' AND `deleted`='1'";
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        $final[] = $row['festival'];
    }
    If (isset($final)) {
        shuffle($final);
        return $final;
    } else return false;
}

function getFestivalsBandIsIn($band)
{
    //This function returns an array containing the id of each festival the band is registered for
    global $master;

    $sql = "select `festival` from `band_list` where `band`='$band' AND `deleted`!='1'";
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

function getPrioritiesUsedInFest()
{
    //This function returns an array containing the ID and priority of each band in the festival
    global $master, $fest;
    $sql = "SELECT `priority` from `band_list` WHERE `deleted`!='1' AND `festival`='$fest' GROUP BY `priority` ASC";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $raw[] = $row['priority'];
        }
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
    global $master;
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

function getBandsAtPriority($priority)
{
    //This function returns an array containing the ID of each band in the festival at the given priority
    global $master, $fest;
    $sql = "SELECT `band` from `band_list` WHERE `deleted`!='1' AND `priority`='$priority' AND `festival`='$fest'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $raw[] = $row['band'];
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

function getAllBands()
{
    //This function returns an array containing the id of each band in the festival, headliners first
    global $master;
    $sql = "select `id` from `bands` where `deleted` != '1' ORDER BY `name` ASC";
//    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        $result[] = $row['id'];
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
    $name = mysql_real_escape_string($name);

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

function getFavoriteBands($user)
{
    global $master;
    $sql = "select `band` from `messages` where `fromuser`='$user' AND `remark`='2' AND `content`>='5' GROUP BY `band`";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row['band'];
        }
        return $result;
    } else return false;
}

function deleteSet($setid)
{
    //This function returns an array containing keyed to the id of each each set by the band, containing the stage and day
    $table = "sets";
    $cols = array("deleted");
    $vals = array(1);
    $where = "`id`='" . $setid . "'";
    updateRow($table, $cols, $vals, $where);
    return true;
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
    $name = mysql_real_escape_string($name);
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
    $sql = "select `id`, `start`, `end`, `band` from `sets` where `deleted` != '1' and `day`='$day' and `stage`='$stage' ORDER BY `start` ASC";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getBandsByDay($day)
{
    //This function returns an array containing the id, start offset and end offset each band in the festival playing a given day and stage
    global $master;
    $sql = "select `id`, `start`, `end`, `band` from `sets` where `deleted` != '1' and `day`='$day' ORDER BY `stage`, `start` ASC";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getBandsByStage($stage)
{
    //This function returns an array containing the id, start offset and end offset each band in the festival playing a given day and stage
    global $master;
    $sql = "select `id`, `start`, `end`, `band` from `sets` where `deleted` != '1' and `stage`='$stage' ORDER BY `day`, `start` ASC";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getAllSetsInFest()
{
    //This function returns an array containing the id, start offset and end offset each band in the festival playing a given day and stage
    global $master, $fest;
    $sql = "select `id`, `band` from `sets` where `deleted` != '1' and `festival`='$fest' ORDER BY `band` ASC";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getAvailableBandPriorities()
{
    global $master;
    $sql = "select `level` from `band_priorities` WHERE `deleted`!='1' ORDER BY `level` ASC";
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

function getBandPriorities()
{
    global $master;
    $sql = "select `id`, `name`, `level`, `description`, `default` from `band_priorities` where `deleted`!='1' order by `level` asc";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $priority[] = $row;
        }
    }
    return $priority;
}

function getAllBandsAtLevel($level)
{
    global $master;
    $sql = "SELECT `band` FROM `band_list` WHERE `priority`='$level' AND `deleted`!='1' GROUP BY `band`";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $priority[] = $row['band'];
        }
    }
    return $priority;
}

function getAllBandsAtLevelInFest($level)
{
    global $master, $fest;
    $sql = "SELECT `band` FROM `band_list` WHERE `priority`='$level' AND `deleted`!='1' AND `festival`='$fest' GROUP BY `band`";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $priority[] = $row['band'];
        }
    }
    return $priority;
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

function getUsersFollowing($user, $viewingUser)
{
    //This function returns an array containing the id of each user the entered user is followed by that are visible to $viewingUser
    $followers = array();
    $possible = getVisibleUsers($viewingUser);
    foreach ($possible as $u) {
        if (userFollowsUser($u, $user)) $followers[] = $u;
    }
    return $followers;
}

function getFollowedBy($user)
{
    //This function returns an array containing the id of each user the entered user is following
    global $master;
    $sql = "select follows from Users where id='$user'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $raw = $row['follows'];

    $working = explode("-", $raw);
    $final = array();
    foreach ($working as $v) {
        If (isInteger($v)) {
            $final[] = $v;
        }
    }
    If (!empty($final)) return $final; else return false;
}

function userIsPrivate($user)
{
    //This function returns true if the given user is private
    $privacy = getUserSetting($user, 68);

    If ($privacy == 1) return false; else return true;
}

function followUser($follower, $followee)
{
    //This function sets the follower to following the followee
    global $master;
    if (userVisibleToUser($follower, $followee)) {
        $sql = "UPDATE `Users` SET `follows` = CONCAT(`follows`,'--$followee--') WHERE `id`='$follower'";
        mysql_query($sql, $master);
        return true;
    } else return false;

}

function unFollowUser($follower, $followee)
{
    //This function sets the follower to not be following the followee anymore
    global $master;
    if (userFollowsUser($follower, $followee)) {
        $sql = "UPDATE `Users` SET `follows` = REPLACE(`follows`,'--$followee--','') WHERE `id`='$follower'";
        mysql_query($sql, $master);
        return true;
    } else return true;

}

function blockUser($blocker, $blockee)
{
    //This function sets the blocker to block the blockee
    global $master;
    $sql = "UPDATE `Users` SET `blocks` = CONCAT(`blocks`,'--$blockee--') WHERE `id`='$blocker'";
    mysql_query($sql, $master);
    return true;
}

function unBlockUser($blocker, $blockee)
{
    //This function sets the blocker to not be blocking the $blockee anymore
    global $master;
    if (userBlocksUser($blocker, $blockee)) {
        $sql = "UPDATE `Users` SET `blocks` = REPLACE(`blocks`,'--$blockee--','') WHERE `id`='$blocker'";
        mysql_query($sql, $master);
        return true;
    } else return true;

}

function deleteUser($deleteUser)
{
    //This function deletes a user
    $followers = getUsersFollowing($deleteUser, $deleteUser);
    foreach ($followers as $f) {
        unFollowUser($f, $deleteUser);
    }
    $table = "Users";
    $cols = array("deleted");
    $vals = array(1);
    $where = "`id`='$deleteUser'";
    updateRow($table, $cols, $vals, $where);
    return true;
}

function drawFollowButtons($user, $profileUser)
{
    if ($user == $profileUser) return false;
    ?>

    <form method="post">
        <?php
        if (!userFollowsUser($user, $profileUser)) {
            ?>
            <button type="submit" name="submitFollow" value="<?php echo $profileUser; ?>">
                Follow <?php echo getUname($profileUser); ?></button><br/>
        <?php
        } else {
            ?>
            <button type="submit" name="submitUnfollow" value="<?php echo $profileUser; ?>">
                Unfollow <?php echo getUname($profileUser); ?></button><br/>
        <?php
        }
        if (!userBlocksUser($user, $profileUser)) {
            ?>
            <button type="submit" name="submitBlock" value="<?php echo $profileUser; ?>">
                Block <?php echo getUname($profileUser); ?></button>
        <?php
        } else {
            ?>
            <button type="submit" name="submitUnblock" value="<?php echo $profileUser; ?>">
                Unblock <?php echo getUname($profileUser); ?></button>
        <?php
        }
        ?>

    </form>
<?php
}

function userFollowsUser($follower, $followee)
{
    //This function returns true if the follower follows the followee
    global $master;
    $sql = "select id from Users where id='$follower' AND follows like '%--$followee--%'";
    $res = mysql_query($sql, $master);
    $follows = mysql_num_rows($res);

    If ($follows > 0) return true; else return false;
}

function userBlocksUser($blocker, $blockee)
{
    //This function returns true if the blocker blocks the blockee
    global $master;
    $sql = "select id from Users where id='$blocker' AND blocks like '%--$blockee--%'";
    $res = mysql_query($sql, $master);
    $blocks = mysql_num_rows($res);

    If ($blocks > 0) return true; else return false;
}

function userVisibleToUser($looker, $lookee)
{
    //This function returns true if the lookee is visible to the looker

    if (userBlocksUser($lookee, $looker)) return false;
    if (userIsPrivate($lookee) && !userFollowsUser($lookee, $looker)) return false;

    return true;
}

function getVisibleUsers($user)
{
    //This function returns an array containing the id and name of each user visible to the entered user
    global $master;
    $sql = "select `id` from `Users` where `deleted`!='1'";
    $res = mysql_query($sql, $master);
    while ($row = mysql_fetch_array($res)) {
        if (userVisibleToUser($user, $row['id'])) $visibleUsers[] = $row['id'];
    }
    if (empty($visibleUsers)) return false;
    return $visibleUsers;
}

function doesBandHaveShape($band, $shapeCode)
{
    global $master;
    $sql = "SELECT `shape` FROM `pics` WHERE `band` = '$band' AND `deleted` != '1' GROUP BY shape";
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

function getBandPicAndShape($intMaster, $shapeCode)
{
    global $master;
    $sql = "SELECT `id`, `shape` FROM `pics` WHERE `band` = '$intMaster' AND `deleted`!='1' ORDER BY RAND()";
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

function displayPic4($bandID, $title_content)
{
    global $fest, $basepage;
    $pgdisp = "<div class=\"bandgridpicwrapper\" ><a href=\"";
    $pgdisp .= $basepage . "?disp=view_band&band=" . $bandID . "&fest=" . $fest . "\"><img class = \"bandgridpic\" src=\"" . $basepage;
    $pgdisp .= "includes/content/blocks/getPicture4.php?band=" . $bandID;
    $pgdisp .= "\" alt=\"band pic\" /><div class=\"bandgridpictitle\">";
    $pgdisp .= "<p class=\"title_content\">" . $title_content . "</p>";
    $pgdisp .= "</div><!-- end .bandgridpictitle --></a></div><!-- end .bandgridpicwrapper -->";
    echo $pgdisp;
}

function displayPic3($bandsFestID, $bandsPicID, $title_content)
{
    global $fest, $basepage;
    $pgdisp = "<div class=\"bandgridpicwrapper\" ><a href=\"";
    $pgdisp .= $basepage . "?disp=view_band&band=" . $bandsFestID . "&fest=" . $fest . "\"><img ";
    $pgdisp .= "class = \"bandgridpic\" src=\"" . $basepage;
    $pgdisp .= "includes/content/blocks/getPicture3.php?pic=";
    $pgdisp .= $bandsPicID . "\" alt=\"band pic\" /><div class=\"bandgridpictitle\">";
    $pgdisp .= "<p class=\"title_content\">" . $title_content . "</p>";
    $pgdisp .= "</div><!-- end .bandgridpictitle --></a></div><!-- end .bandgridpicwrapper -->";
    echo $pgdisp;
}

function displayPicForCrop($picID, $picType)
{
    global $basepage;
    if ($picType == 1) {
        $pgdisp = "<img id=\"target\" class = \"bandgridpic\" src=\"" . $basepage;
        $pgdisp .= "includes/content/blocks/getPicture5.php?pic=";
        $pgdisp .= $picID . "\" alt=\"band pic\" />";
        echo $pgdisp;
    }
}

function getPicInfo($picID, $picType)
{
    global $master;
    $result = array();
    if ($picType == 1) {
        $sql = "SELECT `width`, `height`, `reviewed`, `band` FROM `pics` WHERE `id`='$picID'";
        $res = mysql_query($sql, $master);
        if (mysql_num_rows($res) > 0) {
            $result = mysql_fetch_array($res);
        }
    }
    return $result;

}

function getPic($picID, $picType)
{
    global $master;
    $result = array();
    if ($picType == 1) {
        $sql = "SELECT `pic` FROM `pics` WHERE `id`='$picID'";
        $res = mysql_query($sql, $master);
        if (mysql_num_rows($res) > 0) {
            $row = mysql_fetch_array($res);
            $result = $row['pic'];
        }
    }
    return $result;

}

function getReviewPic()
{
    global $master;
    $result = 0;
    $sql = "SELECT `id` FROM `pics` WHERE `deleted`!='1' AND `reviewed`='0' LIMIT 1";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $result = $row['id'];
    }
    return $result;

}

function deletePic($picID)
{
    $table = "pics";
    $cols = array("deleted");
    $vals = array(1);
    $where = "`id`='$picID'";
    updateRow($table, $cols, $vals, $where);
}

function getBandsWithPics()
{
    global $master;
    $sql = "SELECT `band` FROM `pics` WHERE `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $result = array();
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row['band'];
        }
    }
    return $result;
}

function checkIfAllBandsHavePics()
{
    $bandList = getAllBands();
    $picBands = getBandsWithPics();
    foreach ($bandList as $b) {
        if (in_array($b, $picBands)) return false;
    }
    return true;
}

function checkIfAllBandPicsReviewed()
{

    global $master;
    $sql = "SELECT `id` FROM `pics` WHERE `deleted`!='1' AND `reviewed`='0'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        return false;
    }
    return true;
}

function getAllGenres()
{
    //This function returns all genres as an array, containing id, parent and name
    global $master;
    $sql = "select `id`, `name`, `parent` from `genres` where `deleted`!='1' ORDER BY `name` ASC";
    $res = mysql_query($sql, $master);
    $genres = array();
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $genres[] = $row;
        }
    }
    return $genres;
}

function getAllVisibleGenres($user)
{
    //This function returns all genres visible to user as an array, containing id and name
    global $master;
    $genreSetting = getUserSetting($user, 73);

    if ($genreSetting == 2) {
        $sql = "select `id`, `name` from `genres` where `deleted`!='1' ORDER BY `name` ASC";
        $res = mysql_query($sql, $master);
        $genres = array();
        if (mysql_num_rows($res) > 0) {
            while ($row = mysql_fetch_array($res)) {
                $genres[] = $row;
            }
        }
        return $genres;
    }
    $sql = "select `id`, `name` from `genres` where `deleted`!='1' AND `parent`=`id` ORDER BY `name` ASC";
    $res = mysql_query($sql, $master);
    $genres = array();
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $genres[] = $row;
        }
    }
    return $genres;
}

function getParentGenres()
{
    //This function returns all genres as an array, containing id, parent and name
    global $master;
    $sql = "select `genre` from `genre_parents` where `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $genres = array();
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $genres[] = $row['genre'];
        }
    }
    return $genres;
}

function getParentGenre($genre)
{
    //This function returns all genres as an array, containing id, parent and name
    global $master;
    $sql = "select `parent` from `genres` where `id`='$genre' AND `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $genre = 98;
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $genre = $row['parent'];
    }
    return $genre;
}

function setParentGenre($genre, $parent)
{
    //This function sets the parent of $genre to $parent
    $table = "genres";
    $cols = array("parent");
    $vals = array($parent);
    $where = "`id`='" . $genre . "'";
    updateRow($table, $cols, $vals, $where);

    return true;
}

function addParentGenre($parent)
{
    //This function adds $parent as a parent genre

    $table = "genre_parents";
    $cols = array("genre");
    $vals = array($parent);
    insertRow($table, $cols, $vals);

    return true;
}

function addGenre($genreName, $parent)
{
    //This function adds $parent as a parent genre

    $table = "genres";
    $cols = array("name", "parent");
    $vals = array($genreName, $parent);
    insertRow($table, $cols, $vals);

    return true;
}

function genreList($user)
{
    //This function returns all the genres in main, with genreid, genrename, number of bands in genre,
    //number of rated bands in genre, and total rating points for all bands in genre
    global $master;

    //Get whether to use parent genres or all genres
    $genreSetting = getUserSetting($user, 73);


    $sql = "select `id` from `bands` where `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $ret_genre = array();
    while ($row = mysql_fetch_array($res)) {
        $test['genreid'] = getBandGenreID($row['id'], $user);
        $test['rating'] = act_rating($row['id'], $user);
//        echo "<br />test: ";
//        var_dump($test);
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
    $sql = "select `band` as id from `band_list` where `festival`='$fest' and `deleted`!='1' order by rand()";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[$row['id']]['genreid'] = getBandGenreID($row['id'], $user);
            $result[$row['id']]['genrename'] = getBandGenre($row['id'], $user);
            $result[$row['id']]['bandname'] = getBname($row['id']);
            $result[$row['id']]['id'] = $row['id'];
        }
        return $result;
    } else return false;
}

function drawPregameRemark($viewingUser, $user, $band, $fest, $mode)
{
    global $basepage;
    $userCommentRaw = getUserRemarkOnBandForFest($user, $band, $fest, $mode, 1);
    if ($userCommentRaw != "") {
        $userComment = $userCommentRaw['content'];
        $userCommentID = $userCommentRaw['id'];
        //Note that $viewingUser is seeing this comment
        $discussPoint = userDiscussionPointOnMessage($viewingUser, $userCommentID);
        if ($discussPoint < 0) userHasSeenMessage($viewingUser, $userCommentID);
        $currentDiscussPoint = currentMessageDiscussionPoint($userCommentID);
        $visibleDiscussions = getVisibleDiscussions($userCommentID, $viewingUser);
        if (count($visibleDiscussions) == 0) $discussButtonText = "Start Discussion";
        else {
            if ($discussPoint < $currentDiscussPoint) $discussButtonText = "New Discussion!";
            else $discussButtonText = "View Discussion";
        }

        //draw a row for the user's comment
        ?>
        <div class="commentRow">
            <div class="commenter">
                <div class="commentPic">
                    <a href="<?php echo $basepage . "?disp=user_profile&profileUser=" . $user; ?>">
                        <?php displayScaledUserPic($user); ?>
                    </a>
                </div>
                <!-- end .commentPic -->
                <div class="commentInfo">
                    <div class="commenterName"><?php echo getUname($user); ?></div>
                    <!-- end .commenterName -->
                    <div
                        class="commenterRating"><?php echo displayStars($band, $user, "displaystars", $basepage . "includes/images"); ?></div>
                    <!-- end .commenterRating -->
                    <?php drawFollowButtons($viewingUser, $user); ?>
                    <div class="viewDiscussion">
                        <button type="button" class="viewDiscussionButton"
                                data-messageid="<?php echo $userCommentID; ?>"
                                data-viewingUser="<?php echo $viewingUser; ?>"><?php echo $discussButtonText; ?></button>
                    </div>
                </div>
                <!-- end .commentInfo -->
            </div>
            <!-- end .commenter -->
            <div class="commentContent"><?php echo $userComment; ?></div>
            <!-- end .commentContent -->
            <div id="discussion-<?php echo $userCommentID; ?>" class="discussion">
                <?php foreach ($visibleDiscussions as $vD) drawDiscussionLine($vD, $viewingUser, $band); ?>
                <form method="post">
                    <textarea class="discussText" rows="6" cols="64"
                              name="discuss[<?php echo $userCommentID; ?>]"></textarea>

                    <div class="clearfloat"></div>
                    <input class="discussSubmit" type="submit" name="submitDiscussion" value="Submit Discussion">
                </form>
            </div>
            <!-- end .discussion -->
        </div> <!-- end .commentRow -->
        <div class="clearfloat"></div>
    <?php

    }
}

function userDiscussionPointOnMessage($viewingUser, $messageID)
{
    global $master;
    $sql = "SELECT `read_discussion` FROM `discussion_monitor` WHERE `message`='$messageID' AND `user`='$viewingUser' AND `deleted`!='1'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $discussPoint = $row['read_discussion'];
        return $discussPoint;
    } else return (-1);

}

function userHasSeenMessage($viewingUser, $messageID)
{
    if (userDiscussionPointOnMessage($viewingUser, $messageID) < 0) {
        $table = "discussion_monitor";
        $cols = array("user", "message", "read_discussion", "phptime");
        $vals = array($viewingUser, $messageID, 0, time());
        insertRow($table, $cols, $vals);
    }
    return true;
}

function userCurrentOnMessage($viewingUser, $messageID)
{
    $currentPoint = currentMessageDiscussionPoint($messageID);
    $table = "discussion_monitor";
    $cols = array("user", "message", "read_discussion", "phptime");
    $vals = array($viewingUser, $messageID, $currentPoint, time());

    if (userDiscussionPointOnMessage($viewingUser, $messageID) < 0) {
        insertRow($table, $cols, $vals);
    } else {
        $where = "`user`='$viewingUser' AND `message`='$messageID'";
        updateRow($table, $cols, $vals, $where);
    }

    return true;
}

function currentMessageDiscussionPoint($messageID)
{
    global $master;
    $sql = "SELECT count(`id`) as total FROM `discussions` WHERE `message`='$messageID'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $discussPoint = $row['total'];
        if ($discussPoint == 0) $discussPoint = (-1);
        return $discussPoint;
    } else return (-1);
}

function getVisibleDiscussions($messageID, $viewingUser)
{
    global $master;
    $userList = getVisibleUsers($viewingUser);
    if (count($userList) == 0) return false;
    $where = "";
    foreach ($userList as $k => $u) {
        if ($k == 0) $where = "`user`='" . $u . "'";
        else $where .= " OR `user`='" . $u . "'";
    }
    $sql = "SELECT `id` FROM `discussions` WHERE `message`='$messageID' AND ( " . $where . " )";
    //   error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row['id'];
        }
        return $result;
    } else return array();
}

function getDiscussionLine($discussionID)
{
    global $master;
    //Get most recent comment by user on band for fest
    $sql = "SELECT `content`, `user`, `timestamp` FROM `discussions` WHERE `id`='$discussionID'";
    //    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result = $row;
        }
        return $result;
    } else return "";
}

function drawDiscussionLine($discussionID, $viewingUser, $band)
{
    global $basepage;
    $userDiscussionRaw = getDiscussionLine($discussionID);
    $userDiscussion = $userDiscussionRaw['content'];
    $user = $userDiscussionRaw['user'];
    $userDiscussionTime = $userDiscussionRaw['timestamp'];
    if ($userDiscussion != "") {
        ?>
        <div class="discussionRow">
            <div class="discussionIndent"></div>
            <!-- end .discussionIndent -->
            <div class="discusser">
                <div class="discussionPic">
                    <a href="<?php echo $basepage . "?disp=user_profile&profileUser=" . $user; ?>">
                        <?php displayScaledUserPic($user); ?>
                    </a>
                </div>
                <!-- end .discussionPic -->
                <div class="discussionInfo">
                    <div class="discusserName"><?php echo getUname($user); ?></div>
                    <!-- end .discusserName -->
                    <div
                        class="discusserRating"><?php echo displayStars($band, $user, "displaystars", $basepage . "includes/images"); ?></div>
                    <!-- end .discusserRating -->
                    <?php
                    drawFollowButtons($viewingUser, $user);
                    ?>
                    <div class="discusserTime"><?php echo $userDiscussionTime; ?></div>
                    <!-- end .discusserTime -->
                </div>
                <!-- end .discussionInfo -->
            </div>
            <!-- end .discusser -->
            <div class="discussionContent"><?php echo $userDiscussion; ?></div>
            <!-- end .discussionContent -->
        </div> <!-- end .discussionRow -->
        <div class="clearfloat"></div>
    <?php

    }
}

function getBandSetsByFestival($band)
{
    //Get info of all sets played by band in fest
    global $master, $fest;
    $sql = "SELECT `id`, `day`, `stage`, `start`, `end` FROM `sets` WHERE `band`='$band' AND `festival`='$fest' AND `deleted`!='1'";
//    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result[] = $row;
        }
        return $result;
    } else return false;
}

function getUserRemarkOnBandForFest($user, $band, $fest, $mode, $remark)
{
    global $master;
    //Get most recent comment by user on band for fest
    $sql = "SELECT `id`, `content` FROM `messages` WHERE `fromuser`='$user' AND `band`='$band' AND `festival`='$fest' AND `mode`='$mode' AND `remark`='$remark' ORDER BY `id` DESC LIMIT 1";
//        error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result = $row;
        }
        return $result;
    } else return "";
}

function getUserCommentOnBandForFest($user, $band, $fest, $mode)
{
    global $master;
    //Get most recent comment by user on band for fest
    $sql = "SELECT `content` FROM `messages` WHERE `fromuser`='$user' AND `band`='$band' AND `festival`='$fest' AND `mode`='$mode' AND `remark`='1' ORDER BY `id` DESC LIMIT 1";
    //    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result = $row['content'];
        }
        return $result;
    } else return "";
}

function getUserRatingOnBandForFest($user, $band, $fest, $mode)
{
    global $master;
    //Get most recent comment by user on band for fest
    $sql = "SELECT `content` FROM `messages` WHERE `fromuser`='$user' AND `band`='$band' AND `festival`='$fest' AND `mode`='$mode' AND `remark`='2' ORDER BY `id` DESC LIMIT 1";
    //    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result = $row['content'];
        }
        return $result;
    } else return 0;
}

function getUserLinkOnBandForFest($user, $band, $fest, $mode)
{
    global $master;
    //Get most recent comment by user on band for fest
    $sql = "SELECT `content` as link, `subject` as descrip FROM `messages` WHERE `fromuser`='$user' AND `band`='$band' AND `festival`='$fest' AND `mode`='$mode' AND `remark`='4' ORDER BY `id` DESC LIMIT 1";
    //    error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $result = $row;
        }
        return $result;
    } else return 0;
}

function getAverageRatingForBandByUsersFollowers($user, $band)
{
    global $master;
    $userBase = getFollowedBy($user);
    $sql = "SELECT AVG(`content`) as rating FROM `messages` WHERE `deleted`!='1' AND `band`='$band' AND `remark`='2' AND ";
    $sql .= "( `fromuser`='$user' ";
    if (is_array($userBase)) {
        foreach ($userBase as $u) {
            $sql .= "OR `fromuser`='$u' ";
        }
    }
    $sql .= ")";
    $res = mysql_query($sql, $master);
//    error_log(print_r($sql, TRUE));
    if (mysql_num_rows($res) == 1) {
        while ($row = mysql_fetch_array($res)) {
            $result = $row['rating'];
        }
        return $result;
    } else return 0;
}

function displayUnscaledUserPic($user)
{
    global $basepage;
    $disp = "<div class=\"unscaledUserPicWrapper\" ><img class = \"unscaledUserPic\" src=\"" . $basepage;
    $disp .= "includes/content/blocks/getUUserPicture.php?user=" . $user;
    $disp .= "\" alt=\"user pic\" /></div><!-- end .unscaledUserPicWrapper -->";
    echo $disp;
}

function displayScaledUserPic($user)
{
    global $basepage;
    $disp = "<div class=\"scaledUserPicWrapper\" ><img class = \"scaledUserPic\" src=\"" . $basepage;
    $disp .= "includes/content/blocks/getSUserPicture.php?user=" . $user;
    $disp .= "\" alt=\"user pic\" /></div><!-- end .scaledUserPicWrapper -->";
    echo $disp;
}

function getBandsToRate($user, $fest, $displayCount)
{
    global $master;
    $userList = getFollowedBy($user);
    if (!is_array($userList) || count($userList) == 0) return false;
    $where = "";
    foreach ($userList as $k => $u) {
        if ($k == 0) $where = "`fromuser`='" . $u . "'";
        else $where .= " OR `fromuser`='" . $u . "'";
    }
    $sql = "SELECT `id`, `band` FROM `messages` WHERE `festival`='$fest' AND ( " . $where . " ) AND `remark`='2' AND `mode`='1' AND `content` > '3'";
//      error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    $result = array();
    $i = 0;
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $currentRating = act_rating($row['band'], $user);
            if ($currentRating == 0 && !in_array($row['band'], $result)) {
                $result[] = $row['band'];
                $i = $i + 1;
            }
            if ($i >= $displayCount) break;
        }
        if ($i > 0) return $result;
    }
    $allBands = getAllBandsInFest();
    foreach ($allBands as $b) {
        $currentRating = act_rating($b, $user);
        if ($currentRating == 0) {
            $result[] = $b;
            $i = $i + 1;
        }
        if ($i >= $displayCount) break;
    }
    return $result;

}

function getNewPregameCommentBands($user, $fest, $displayCount)
{
    global $master;
    $userList = getFollowedBy($user);
    if (!is_array($userList) || count($userList) == 0) return false;
    $where = "";
    foreach ($userList as $k => $u) {
        if ($k == 0) $where = "`fromuser`='" . $u . "'";
        else $where .= " OR `fromuser`='" . $u . "'";
    }
    $sql = "SELECT `id`, `band` FROM `messages` WHERE `festival`='$fest' AND ( " . $where . " ) AND `remark`='1' AND `mode`='1' AND `timestamp` > DATE_SUB(NOW(), INTERVAL 7 DAY)";
//      error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    $result = array();
    $i = 0;
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            $discussPoint = userDiscussionPointOnMessage($user, $row['id']);
            if ($discussPoint < 0 && !in_array($row['band'], $result)) {
                $result[] = $row['band'];
                $i = $i + 1;
            }
            if ($i >= $displayCount) break;
        }
        return $result;
    } else return array();
}

function getNewPregameDiscussionBands($user, $fest, $displayCount)
{
    global $master;
    $userList = getFollowedBy($user);
    if (!is_array($userList) || count($userList) == 0) return false;
    $where = "";
    foreach ($userList as $k => $u) {
        if ($k == 0) $where = "`fromuser`='" . $u . "'";
        else $where .= " OR `fromuser`='" . $u . "'";
    }
    //Get all pregame remarks from the festival
    $sql = "SELECT `id`, `band` FROM `messages` WHERE `festival`='$fest' AND `remark`='1' AND `mode`='1'";
//   error_log(print_r($sql, TRUE));
    $res = mysql_query($sql, $master);
    $result = array();
    $i = 0;
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_array($res)) {
            if (userDiscussionPointOnMessage($user, $row['id']) < currentMessageDiscussionPoint($row['id'])) {
                $result[] = $row['band'];
                $i = $i + 1;
            }
            if ($i >= $displayCount) break;
        }
        return $result;
    } else return array();
}


function acceptRemark($user, $band, $fest, $content, $mode, $remark, $subject, $touser)
{
    //This function stores the pregame remark for the current fest, user, and band, updating or creating as needed
    if ($remark != 3) {
        $existingRemarkRaw = getUserRemarkOnBandForFest($user, $band, $fest, $mode, $remark);
        $existingRemark = $existingRemarkRaw['content'];
    } else $existingRemark = "";

    //New comments
    If ($existingRemark != "") {
        $table = "messages";
        $cols = array("subject", "content");
        $vals = array($subject, $content);
        $where = "`fromuser`='" . $user . "' AND `band`='$band' AND `festival`='$fest' `remark`='$remark' AND `mode`='$mode' AND `deleted`!='1'";
        updateRow($table, $cols, $vals, $where);
        return true;
    } else {
        if ($remark != 3) $touser = (-1);
        $header = getFestHeader($fest);
        $table = "messages";
        $cols = array("festival", "band", "remark", "privacy", "mode", "fromuser", "festival_series", "content", "subject", "touser");
        $vals = array($fest, $band, $remark, 1, $mode, $user, $header['series'], $content, $subject, $touser);
        insertRow($table, $cols, $vals);
        return true;
    }
}

function acceptDiscussReply($user, $message, $content)
{
    $table = "discussions";
    $cols = array("user", "message", "content", "phptime");
    $vals = array($user, $message, $content, time());
    insertRow($table, $cols, $vals);
    userCurrentOnMessage($user, $message);

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
//                $debug['tableName'][] = $table_name;
        $tablesql = "SELECT * FROM `$table_name`";
        $tableres = mysql_query($tablesql, $main);
        if (empty($submittedTableState[$table_name])) {
//                        $debug[] = "Table $table_name submission is empty";
            If ((mysql_num_rows($tableres) > 0)) {
                $i = 0;
                while ($tablerow = mysql_fetch_assoc($tableres)) {
                    $response['localUpdates'][$table_name][$i]['action'] = "add";
                    $response['localUpdates'][$table_name][$i]['row'] = $tablerow;
                    $i++;
                }
            }
        } else {
//                        $debug[] = "Table $table_name submission is not empty";
            $i = 0;
            while ($tablerow = mysql_fetch_assoc($tableres)) {
                if ($table_name == "live_rating") $timestampCol = "time";
                else $timestampCol = "timestamp";
                $id = $tablerow['id'];
                $serverTimestamp = $tablerow[$timestampCol];
//                                $debug[] = "Table $table_name checking id $id";
                unset($mobileTableRowKey);
                foreach ($submittedTableState[$table_name]['result'] as $k => $v) {
                    if ($v['id'] == $id) {
//                                                $debug[] = "Table $table_name checking id $id found key $k";
                        $mobileTableRowKey = $k;
                        break;
                    }
                }
                if (!isset($mobileTableRowKey)) {
                    $response['localUpdates'][$table_name][$i]['action'] = "add";
                    $response['localUpdates'][$table_name][$i]['row'] = $tablerow;
                } else {
                    if ($submittedTableState[$table_name]['result'][$mobileTableRowKey]['timestamp'] == $tablerow[$timestampCol]) {
//                                                $debug[] = "Table $table_name checking id $id found key $mobileTableRowKey with matching timestamp ".$tablerow[$timestampCol];

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


//              $response['debug'] = $debug;
    return $response;
}

function getCurrentCredits($user)
{
    //This function returns the number of credits the user currently has.
    global $master;
    $sql = "SELECT `credits` FROM `Users` WHERE `id`='$user' AND `deleted`!='1'";
    $res = mysql_query($sql, $master);
    $row = mysql_fetch_array($res);
    $credits = $row['credits'];
    return $credits;
}

function userFestivals($user)
{
    //This function returns the number of credits the user currently has.
    global $master;
    $sql = "SELECT `festival` FROM `festival_monitor` WHERE `user`='$user' AND `deleted`!='1'";
    $res = mysql_query($sql, $master);
    if (mysql_num_rows($res) == 0) return array();
    while ($row = mysql_fetch_array($res)) {
        $credits[] = $row['festival'];
    }
    return $credits;
}