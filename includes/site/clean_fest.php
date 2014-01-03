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
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}


if (!empty($_POST['submitClean'])) {
    $setList = getAllSetsInFest();
    $bandList = getAllBandsInFest();
    foreach ($setList as $setL) {
        $setBand[] = $setL['band'];
    }
    foreach ($bandList as $bandL) {
        if (in_array($bandL, $setBand)) continue;
        $cols = array("deleted");
        $vals = array(1);
        $where = "`band`='" . $bandL . "' AND `festival`='$fest'";
        $table = "band_list";

        updateRow($table, $cols, $vals, $where);
    }

}


?>
<div id="content">
    This feature will remove all bands from the current festival that do not have a set assigned.
    <form method="post" enctype="multipart/form-data">

        <input type="submit" name="submitClean"
               value="Remove bands from <?php echo $header['sitename']; ?> without a set assigned">
    </form>
</div> <!-- end #content -->