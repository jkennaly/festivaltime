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


//echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to choose from a list of all the bands</a> or select Home from the Nav bar up top to use the filters</p>";


$post_target = $basepage . "?disp=view_band&band=$band";

// Collect data display comments, etc.
//If $band is defined
If (empty($_POST['edit']) && empty($_POST['edits'])) {
    include $baseinstall . "includes/content/blocks/band_info.php";
    $priors = getFestivalsBandIsIn($band);
    If (count($priors) > 1) {
        foreach ($priors as $v) {
            $priorname = $header['sitename'];;
            $priorband = getFestBandIDFromMaster($band_master_id, $v, $master);
            If ($v != $fest) echo "<a href=\"" . $basepage . "?disp=view_band&band=" . $priorband . "&fest=" . $v . "\">" . $priorname . "</a><br />";
        }
    }
    ?>

    <form id="edit_band_button" action="<?php echo $basepage . "?disp=view_band&band=$band"; ?>" method="post">
        <input type="submit" value="Edit Band Info" name="edit">
    </form>

<?php
} //Closes If(empty($_POST['edit']))

If (!empty($_POST['edit']) || !empty($_POST['edits'])) {
    include $baseinstall . "includes/content/blocks/band_info_edit.php";
    ?>

    <form id="edit_band_button" action="<?php echo $basepage . "?disp=view_band&band=$band"; ?>" method="post">
        <input type="submit" value="Done Editing" name="done">
    </form>

<?php
} //Closes If(!empty($_POST['edit']))
//query to pull basic data


//If the page viewer was referred by a recommendation, set it to followed
If (!empty($_GET["recomm"])) {
    $sql = "UPDATE recommendations SET followed='1' WHERE touser='$user' AND band='$band'";
    $res = mysql_query($sql, $main);
} //Closes If($_GET["recomm"])
//end recommedations section


include "includes/content/blocks/recommendations.php";

include "includes/content/blocks/liveranked.php";

