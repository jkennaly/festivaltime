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

//Get band info

//Get stage info

//Get day info

//Get self made pregame remarks

//Get pregame remarks from followed

//Get genre names

//Get stage layouts

//Bring it all home
$data['followedUsers'] = $followedUsers;
$data['unfollowedUsers'] = $unfollowedUsers;
header('Content-Type: application/json');
echo json_encode($data);