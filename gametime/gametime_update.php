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


$right_required = "CreateNotes";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die();
}
$post = json_decode($_POST['json'], true);

if ($post['action'] == "submitUpdate")
    foreach ($post as $p) {
        if ($p == "submitUpdate") continue;

    }

header('Content-Type: application/json');
echo json_encode($data);