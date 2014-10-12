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
?>
<div id="content">
    <a href="<?php echo $header['website']; ?>" target="_blank">Festival Website</a><br/>


    <br/>
    <button id="festbanddayscomplete" data-fest="<?php echo $fest; ?>">Band Days Are Complete</button>
    <br/>
    <button id="stopfestcreation">Done working on this festival for now</button>
    <?php
    error_log("Log test");
    if (!empty($_POST['submitBandDays']) || !empty($_POST['submitSingleBand'])) {
        if (!empty($_POST['submitBandDays'])) {
            foreach ($_POST['update'] as $setid => $day) {
                if ($day == 0) continue;
                $table = "sets";
                $cols = array("day");
                $vals = array($day);
                $where = "`id`='" . $setid . "'";
                updateRow($table, $cols, $vals, $where);
            }
            foreach ($_POST['insert'] as $bandd => $day) {
                if ($day == 0) continue;
                $dates = getAllDates();
                foreach ($dates as $date) {
                    error_log("Processing date " . $date["id"] . " for band " . $bandd);
                    $table = "sets";
                    $cols = array("festival", "festival_series", "band", "day", "date");
                    $vals = array($fest, $festSeries, $bandd, $day, $date["id"]);
                    insertRow($table, $cols, $vals);
                }
            }
        }
        if (!empty($_POST['submitSingleBand'])) {
            $dates = getAllDates();
            foreach ($dates as $date) {
                $table = "sets";
                $cols = array("festival", "festival_series", "band", "day", "stage", "date");
                $vals = array(
                    $fest,
                    $festSeries,
                    $_POST['insertBandSetName'],
                    $_POST['insertBandSetDay'],
                    $_POST['insertBandSetStage'],
                    $date
                );
                insertRow($table, $cols, $vals);
            }
        }
        ?>

        Festival band priorities accepted. You may continue editing priorities or press the complete button.


    <?php
    }

    ?>

    <form method="post" enctype="multipart/form-data">
        <input type="submit" name="submitBandDays" value="Update All Band Days"/><br/>
        <?php
        //Get each band in the festival, and the band's priority
        $bandPriorities = getAllBandPriorities();
        $availDates = getAllDates();
        $availDays = getAllDays();
        $availStages = getAllStages();
        //For each band in the festival, get the number of sets currently registered
        foreach ($bandPriorities as $bandInList) {
            $setNum = getNumberOfSetsByBandInFest($bandInList['band']);
            if ($setNum == 0) {
                ?>
                <b><?php echo getBname($bandInList['band']); ?></b> Add new set
                <select name="insert[<?php echo $bandInList['band']; ?>]">
                    <?php
                    foreach ($availDays as $a) {
                        echo "<option value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
                    }
                    ?>
                </select><br/>

            <?php
            } else {
                $sets = getDayAndStageOfSetsByBandInFest($bandInList['band']);
                foreach ($sets as $set) {

                    ?>
                    <b><?php echo getBname($bandInList['band']); ?></b> (<?php echo getPname($set['stage']); ?> set)
                    <select name="update[<?php echo $set['id']; ?>]">
                        <?php
                        foreach ($availDays as $a) {
                            if ($set['day'] != $a['id']) echo "<option value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
                        }
                        ?>
                    </select><br/>

                <?php
                }
            }
        }


        ?>
        <input type="submit" name="submitBandDays" value="Update All Band Days"/>
    </form>
    <p>Use the form below to add another set for an entered band.</p>

    <form method="post" enctype="multipart/form-data">
        <select name="insertBandSetName">
            <?php
            foreach ($bandPriorities as $b) {
                $setNum = getNumberOfSetsByBandInFest($b['band']);
                echo "<option value=\"" . $b['band'] . "\">" . getBname($b['band']) . "-Currently $setNum set(s)</option>";
            }
            ?>
        </select>
        <select name="insertBandSetDate">
            <?php
            foreach ($availDates as $aDt) {
                echo "<option value=\"" . $aDt['id'] . "\">" . $aDt['name'] . "</option>";
            }
            ?>
        </select>
        <select name="insertBandSetDay">
            <?php
            foreach ($availDays as $aD) {
                echo "<option value=\"" . $aD['id'] . "\">" . $aD['name'] . "</option>";
            }
            ?>
        </select>
        <select name="insertBandSetStage">
            <?php
            foreach ($availStages as $aS) {
                echo "<option value=\"" . $aS['id'] . "\">" . $aS['name'] . "</option>";
            }
            ?>
        </select><br/>
        <input type="submit" name="submitSingleBand" value="Add set for this band">
    </form>


    <script type="text/javascript">
        <!--
        var basepage = "<?php echo $basepage; ?>";
        //-->
    </script>
    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/create.js"></script>
</div> <!-- end #content -->