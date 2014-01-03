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
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}

if (!empty($_POST['submitFollow'])) {
    $followee = $_POST['submitFollow'];
    $follower = $user;
    followUser($follower, $followee);
}
if (!empty($_POST['submitUnfollow'])) {
    $followee = $_POST['submitUnfollow'];
    $follower = $user;
    unFollowUser($follower, $followee);
}
if (!empty($_POST['submitBlock'])) {
    $blockee = $_POST['submitBlock'];
    $blocker = $user;
    blockUser($blocker, $blockee);
}
if (!empty($_POST['submitUnblock'])) {
    $blockee = $_POST['submitUnblock'];
    $blocker = $user;
    unBlockUser($blocker, $blockee);
}