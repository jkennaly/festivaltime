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

$festivaltimeContext = 1;

session_start();


include('../variables/variables.php');
include($baseinstall . 'includes/content/blocks/database_functions.php');
include($baseinstall . 'includes/check_rights.php');

$master = mysql_connect($dbhost, $master_dbuser, $master_dbpw);
@mysql_select_db($master_db, $master) or die("Unable to select master database");

include($baseinstall . 'variables/page_variables.php');
if (getGametimeKey($user) != $_COOKIE['key']) die;
$fest = $_COOKIE['fest'];
$date = $_COOKIE['date'];


$right_required = "ViewNotes";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die();
}

//Get information about the user

//Get all users the user is following
$followed = getFollowedBy($user);
$followedUsers = array();
foreach ($followed as $f) {
    $uName = getUname($f);
    $followedUsers[] = array('id' => $f, 'userName' => $uName);
}

//Get all users the user is not following
$visibleUsers = getVisibleUsers($user);
$unfollowedUsers = array();
foreach ($visibleUsers as $vU) {

    $uName = getUname($vU);
    if (!in_array($vU, $followed) && $vU != $user) $unfollowedUsers[] = array('id' => $vU, 'userName' => $uName);
}

//Get set info
$baseTimes = getBaseTimeFromDate($date, $fest);
$sets = getAllSetsInFest();
foreach ($sets as &$set) {
    $set['start'] = $set['start'] + $baseTimes[$set['day']];
    $set['end'] = $set['end'] + $baseTimes[$set['day']];
}

//Get band info
$bandIDs = getAllBandsInFest();
foreach ($bandIDs as $b) {
    $bandName = getBname($b);
    $bandGenre = getBandGenre($b, $user);
    $baseScore = getBaseScore($b, $user, $fest);
    $bands[] = array('id' => $b, 'bandName' => $bandName, 'bandGenre' => $bandGenre, 'baseScore' => $baseScore);
}

//Get stage info
$stages = getAllStages();
$layouts = array();
$layoutTemp = array();
foreach ($stages as $s) {
    if (!in_array($s['layout'], $layoutTemp)) $layoutTemp[] = $s['layout'];
}
foreach ($layoutTemp as $l) {
    $pic = file_get_contents('includes/content/blocks/getPicStageLayout.php?layout=' . $l['layout']);
    $layouts[] = array('layout-' . $l, $pic);
}


//Get day info
$days = getAllDays();

//Get self made pregame remarks
$pregameSelf = getAllUserRemarksForFest($user, $fest, 1);

//Get pregame remarks from followed
$pregameFollowed = getAllUserFollowedRemarksForFest($user, $fest, 1);

//Bring it all home
$data['followedUsers'] = $followedUsers;
$data['unfollowedUsers'] = $unfollowedUsers;
$data['bands'] = $bands;
$data['sets'] = $sets;
$data['stages'] = $stages;
$data['layouts'] = $layouts;
$data['days'] = $days;
$data['pregameSelf'] = $pregameSelf;
$data['pregameFollowed'] = $pregameFollowed;
$data['updateURL'] = $basepage . "/gametime/gametime_update.php";


header('Content-Type: application/json');
echo json_encode($data);