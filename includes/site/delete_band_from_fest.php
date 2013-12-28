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


if (!empty($_POST['deleteBand'])) {
    $cols = array("deleted");
    $vals = array(1);
    $where = "`band`='" . $_POST['deleteBand'] . "' AND `festival`='$fest'";
    $table = "band_list";

    updateRow($table, $cols, $vals, $where);

    $table = "sets";

    updateRow($table, $cols, $vals, $where);
}

$bandPriorities = getAllBandPriorities();

?>
<div id="content">
    <form method="post" enctype="multipart/form-data">
        <select name="deleteBand">
            <?php
            foreach ($bandPriorities as $b) {
                echo "<option value=\"" . $b['band'] . "\">" . getBname($b['band']) . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="submitDeleteBand" value="Remove band from <?php echo $header['sitename']; ?>">
    </form>
</div> <!-- end #content -->