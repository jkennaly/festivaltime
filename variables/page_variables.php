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

if ($festivaltimeContext != 1) die('This page load was out of context');

//This query collects data about the current band, if one is specified
//echo "Here we go page variables<br />";
If (!empty($_SESSION['user'])) {
    $uname = $_SESSION['user'];
    $user = getUserIDFromUserName($uname);
    if (empty($user)) session_destroy();

}
if (!empty($_REQUEST['band'])) {
    $band = $_REQUEST['band'];
}

date_default_timezone_set("America/Los_Angeles");

