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

if (!empty($_POST['submitCombinedBandID'])) {
    $tablesArray = array("bandgenres", "messages", "pics", "sets");
    $changeTo = $_POST['combinedBandID'];
    $changeToFests = getFestivalsBandIsIn($changeTo);
    $changeToDelFests = getFestivalsBandDeletedFrom($changeTo);
    if (!is_array($changeToFests)) $changeToFests = array($changeToFests);
    if (!is_array($changeToDelFests)) $changeToDelFests = array($changeToDelFests);


    foreach ($_POST['bandID'] as $changeFrom) {
        if ($changeFrom == $changeTo) continue;

        //Find festivals with both $changeFrom and $changeTo
        $changeFromFests = getFestivalsBandIsIn($changeFrom);
        if (is_array($changeFromFests)) {
            foreach ($changeFromFests as $cFF) {
                if (in_array($cFF, $changeToFests)) {
                    //For festivals with both bands, delete $changeFrom from band_list
                    $cols = array("deleted");
                    $vals = array(1);
                    $where = "`band`='" . $changeFrom . "' AND `festival`='$cFF'";
                    $table = "band_list";

                    updateRow($table, $cols, $vals, $where);
                } elseif (in_array($cFF, $changeToDelFests)) {
                    //Where the changeto has been deleted from a festival, undelete the changeto band, then delete the changefrom band
                    $cols = array("deleted");
                    $vals = array(0);
                    $where = "`band`='" . $changeTo . "' AND `festival`='$cFF'";
                    $table = "band_list";
                    updateRow($table, $cols, $vals, $where);

                    $cols = array("deleted");
                    $vals = array(1);
                    $where = "`band`='" . $changeFrom . "' AND `festival`='$cFF'";
                    $table = "band_list";
                    updateRow($table, $cols, $vals, $where);
                } else {
                    //For festivals with only $changeFrom, update the band_list to $changeTo
                    $cols = array("band");
                    $vals = array($changeTo);
                    $where = "`band`='" . $changeFrom . "' AND `festival`='$cFF'";
                    $table = "band_list";
                    updateRow($table, $cols, $vals, $where);

                }
            }
        }


        $cols = array("band");
        $vals = array($changeTo);
        $where = "`band`='" . $changeFrom . "'";
        foreach ($tablesArray as $table) {
            updateRow($table, $cols, $vals, $where);
        }
        $table = "bands";
        $cols = array("deleted");
        $vals = array(1);
        $where = "`id`='" . $changeFrom . "'";
        updateRow($table, $cols, $vals, $where);
    }

}


?>


<div id="content">
    <p>Use this tool to combine bands that are the same-for example, one entry has a different than another.</p>
    <?php
    if (empty($_POST['submitCombineBands'])) {
        $allBands = getAllBands();
        ?>
        <h3>Select all bands to combine into one:</h3>
        <form method="post" enctype="multipart/form-data">
            <?php
            if (!empty($allBands)) {
                foreach ($allBands as $b) {
                    ?>
                    <input type="checkbox" name="combineBands[]" value="<?php echo $b; ?>" /><?php echo getBname($b); ?>
                    <br/>
                <?php
                }
            } else echo "This site currently has no bands.";
            ?>
            <input type="submit" name="submitCombineBands" value="Combine All Checked Bands"/>
        </form>
    <?php
    } else {
        ?>
        <form method="post" enctype="multipart/form-data">
            <?php
            foreach ($_POST['combineBands'] as $b) {
                ?>
                Combining band name: <?php echo getBname($b); ?><br/>
                <input type="hidden" name="bandID[]" value="<?php echo $b; ?>"/>
            <?php
            }
            ?>
            Select the band to combine them into:
            <select name="combinedBandID">
                <?php
                foreach ($_POST['combineBands'] as $b) {
                    ?>
                    <option value="<?php echo $b; ?>"><?php echo getBname($b); ?></option>
                <?php
                }
                ?>
            </select>


            <input type="submit" name="submitCombinedBandID" value="Finalize name edit"/>
        </form>

    <?php
    }
    ?>
</div> <!-- end #content -->


<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>