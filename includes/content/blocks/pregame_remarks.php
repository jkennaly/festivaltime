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

global $fest;

include('includes/content/blocks/accept_discuss_reply.php');
include('includes/content/blocks/accept_follow.php');

//Write the comment for the viewer
drawPregameRemark($user, $user, $band, $fest, $mode);

//Next write any comments from the the people the viewer follows
$userList = getFollowedBy($user);
foreach ($userList as $u) {
    drawPregameRemark($user, $u, $band, $fest, $mode);
}

//Finally, comments from the non-followed
$userList2 = getVisibleUsers($user);
foreach ($userList2 as $u) {
    if (!in_array($u, $userList) && !($u == $user)) drawPregameRemark($user, $u, $band, $fest, $mode);
}



