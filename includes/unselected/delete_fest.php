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


$right_required = "EditFest";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "\">FestivalTime</a>");
}

If (!empty($_POST['rmvfest'])) {
    /*
$query="select dbname from festivals where id = '".$_POST["rmvfest"]."'";
$result = mysql_query($query, $master);
$dropping=mysql_fetch_array($result);
$query="DROP DATABASE ".$dropping['dbname'];
$result = mysql_query($query, $master);
if (!$result) {
    die('Invalid query: <br />'.$query.'<br />' . mysql_error());
}
$sql = "DELETE FROM festivals WHERE id = '".$_POST["rmvfest"]."'";
$upd = mysql_query($sql, $master);
$sql2 = "DROP TABLE info_".$_POST["rmvfest"]."";
$drop = mysql_query($sql2, $master);
$sql3 = "UPDATE bands SET festivals=REPLACE(festivals, '--".$_POST["rmvfest"]."--', '-') WHERE festivals like '%--".$_POST["rmvfest"]."--%'";
//	echo "<br>".$sql3."<br>";
$upd3 = mysql_query($sql3, $master);
    */


    $cols = array("deleted");
    $vals = array(1);
    $festToDelete = $_POST["rmvfest"];

    $festToDeleteTables = array("band_list", "dates", "days", "sets", "festival_monitor");
    foreach ($festToDeleteTables as $fd) {

        $where = "`festival`='" . $festToDelete . "'";
        $table = $fd;
        updateRow($table, $cols, $vals, $where);
    }

    $cols = array("deleted");
    $vals = array(1);
    $where = "`id`='" . $festToDelete . "'";
    $table = "festivals";

    updateRow($table, $cols, $vals, $where);


} else {

$myFestivals = userFestivals($user);
?>
<div id="content">
    </p>
    <form action="index.php?disp=delete_fest" method="post">
        <p>
            <input type="radio" name="rmvfest" value="0" checked="checked">Do not delete any fests
        </p>

        <table border="1">
            <tr>
                <th>Festival</th>
                <th>delete festival</th>
            </tr>
            <?php
            foreach ($myFestivals as $row) {
                echo "<tr><td>" . getFname($row) . "</td><td><input type=radio name=\"rmvfest\" value=" . $row . "></td></tr>";
            }
            ?>
        </table>
        <input type="submit">
    </form>
    <?php
    }


    ?>
</div> <!-- end #content -->
