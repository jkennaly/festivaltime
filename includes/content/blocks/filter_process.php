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


If (!empty($_POST['new']) || !empty($_POST['all_bands'])) {
    unset($_SESSION['filter']);
    If (!empty($_POST['day'])) $_SESSION['filter']['day'] = $_POST['day'];
    If (!empty($_POST['stage'])) $_SESSION['filter']['stage'] = $_POST['stage'];
    If (!empty($_POST['genre'])) $_SESSION['filter']['genre'] = $_POST['genre'];
    If (!empty($_POST['time'])) $_SESSION['filter']['time'] = $_POST['time'];
    If (!empty($_POST['comments'])) $_SESSION['filter']['comments'] = $_POST['comments'];
    If (!empty($_POST['ratings'])) $_SESSION['filter']['ratings'] = $_POST['ratings'];
    If (!empty($_POST['links'])) $_SESSION['filter']['links'] = $_POST['links'];
    If (!empty($_POST['sort'])) $_SESSION['filter']['sort'] = $_POST['sort'];

} //Closes If(!empty($_POST['new']))
/*
echo "filter_process test";
var_dump($_SESSION['filter']);
*/
//Pull the POST data into regular arrays
if (!empty($_SESSION['filter']['day'])) {
    $where .= MatchFilter($_SESSION['filter']['day'], "day", "bands");
    $where_active = 1;
}

if (!empty($_SESSION['filter']['stage'])) {
    foreach ($_SESSION['filter']['stage'] as $check) {
        $stage[] = $check;
    }
}

if (!empty($_SESSION['filter']['genre'])) {
    foreach ($_SESSION['filter']['genre'] as $check) {
        $genre[] = $check;
    }
}

if (!empty($_SESSION['filter']['time'])) {
    foreach ($_SESSION['filter']['time'] as $check) {
        $time[] = $check;
    }
}

if (!empty($_SESSION['filter']['comments'])) {
    foreach ($_SESSION['filter']['comments'] as $check) {
        $comments[] = $check;
    }
}

if (!empty($_SESSION['filter']['ratings'])) {
    foreach ($_SESSION['filter']['ratings'] as $check) {
        $ratings[] = $check;
    }
}

if (!empty($_SESSION['filter']['links'])) {
    foreach ($_SESSION['filter']['links'] as $check) {
        $links[] = $check;
    }
}


//process filters

if (!empty($stage)) {
    If ($where_active == 1) $where .= " AND ";
    $where .= MatchFilter($stage, "stage", "bands");
    $where_active = 1;
}

if (!empty($genre)) {
    If ($where_active == 1) $where .= " AND ";
    $where .= MatchFilter($genre, "genre", "bands");
    $where_active = 1;
}

if (!empty($time)) {
    foreach ($time as $check) {
        echo "Time condition " . $check . " is selected.<br>";
    }
}

if (!empty($comments)) {
    foreach ($comments as $check) {

        If ($check == "ihave") {
            echo "Displaying bands I have commented on.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalIncludeFilter("id", "bands", "band", "comments", "user", $userid, $master);
            $where_active = 1;
        }


        If ($check == "ihavenot") {
            echo "Displaying bands I have not commented on.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalExcludeFilter("id", "bands", "band", "comments", "user", $userid, $master);
            $where_active = 1;
        }

        If ($check == "none") {
            echo "Displaying bands no one has commented on.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalExcludeFilter("id", "bands", "band", "comments", "'1'", "1", $master);
            $where_active = 1;

        }

        If ($check == "someone") {
            echo "Displaying bands someone has commented on.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalIncludeFilter("id", "bands", "band", "comments", "'1'", "1", $master);
            $where_active = 1;

        }

        If ($check == "many") {
            echo "Displaying bands many have commented on.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalMinimumFilter("id", "bands", "band", "comments", "count(*)", "2", $master);
            $where_active = 1;

        }
    }
}


if (!empty($ratings)) {

    foreach ($ratings as $check) {
        If ($where_active == 1) $where .= " AND ";
        switch ($check) {
            case "ihave":
                echo "Displaying bands I have rated.<br>";
                $where .= ExternalIncludeFilter("id", "bands", "band", "ratings", "user", $userid, $master);
                break;
            case "ihavenot":
                echo "Displaying bands I have not rated.<br>";
                $where .= ExternalExcludeFilter("id", "bands", "band", "ratings", "user", $userid, $master);
                break;
            case "none":
                echo "Displaying bands no one has rated.<br>";
                $where .= ExternalExcludeFilter("id", "bands", "band", "ratings", "'1'", "1", $master);
                break;
            case "someone":
                echo "Displaying bands someone has rated.<br>";
                $where .= ExternalIncludeFilter("id", "bands", "band", "ratings", "'1'", "1", $master);
                break;
            case "many":
                echo "Displaying bands many have rated.<br>";
                $where .= ExternalMinimumFilter("id", "bands", "band", "ratings", "count(*)", "2", $master);
                break;
            case "high":
                echo "Displaying bands with a high rating.<br>";
                $where .= ExternalMinimumFilter("id", "bands", "band", "ratings", "avg(rating)", "3.5", $master);
                break;
            case "low":
                echo "Displaying bands with a low rating.<br>";
                $where .= ExternalMaximumFilter("id", "bands", "band", "ratings", "avg(rating)", "2.5", $master);
                break;
        }
        $where_active = 1;
    }
}

if (!empty($links)) {

    foreach ($links as $check) {

        If ($check == "ihave") {
            echo "Displaying bands I have linked.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalIncludeFilter("id", "bands", "band", "links", "user", $userid, $master);
            $where_active = 1;
        }


        If ($check == "ihavenot") {
            echo "Displaying bands I have not linked.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalExcludeFilter("id", "bands", "band", "links", "user", $userid, $master);
            $where_active = 1;
        }

        If ($check == "none") {
            echo "Displaying bands no one has linked.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalExcludeFilter("id", "bands", "band", "links", "'1'", "1", $master);
            $where_active = 1;

        }

        If ($check == "someone") {
            echo "Displaying bands someone has linked.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalIncludeFilter("id", "bands", "band", "links", "'1'", "1", $master);
            $where_active = 1;

        }

        If ($check == "many") {
            echo "Displaying bands many have linked.<br>";

            If ($where_active == 1) $where .= " AND ";
            $where .= ExternalMinimumFilter("id", "bands", "band", "links", "count(*)", "2", $master);
            $where_active = 1;

        }
    }
}


if (!empty($_SESSION['filter']['sort'])) {


    foreach ($_SESSION['filter']['sort'] as $check) {
        switch ($check) {
            case "name":
                If ($sort_active != 1) {
                    echo "Bands sorted alphabetically.<br>";
                    $order .= "name ";
                    $sort_active = 1;
                }
                break;
            case "stime":
                If ($sort_active != 1) {
                    echo "Bands sorted by start time.<br>";
                    $order .= "stime ";
                    $sort_active = 1;
                }
                break;
            case "etime":
                If ($sort_active != 1) {
                    echo "Bands sorted by end time.<br>";
                    $order .= "etime ";
                    $sort_active = 1;
                }
                break;
            case "added":
                If ($sort_active != 1) {
                    echo "Bands sorted by date added to the system.<br>";
                    $order .= "id ";
                    $sort_active = 1;
                }
                break;
            case "invert":
                If ($sort_active == 1) {
                    echo "Sort order is reversed.<br>";
                    $order .= "desc";
                }
                break;
        } //Close Switch case
    } //Close foreach
}
//Close If


//compose query

$sql = $select;
$sql .= $from;

If (strlen($where) > 6) $sql .= $where;
If ($sort_active == 1) $sql .= $order;

// echo "<br>$sql<br>";

$result = mysql_query($sql, $master);


?>

