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
?>
<div id="content">
    <a href="<?php echo $header['website']; ?>" target="_blank">Festival Website</a><br/>
    <?php

    if (!empty($_POST['submitDayAndStage'])) {
        $_SESSION['setTimes']['day'] = $_POST['day'];
        $_SESSION['setTimes']['stage'] = $_POST['stage'];

    }

    if (!empty($_POST['submitBandSchedule'])) {
        foreach ($_POST['start'] as $sID => $sT) {
            $startTime = $sT['hour'] + $sT['min'];

            $table = "sets";
            $cols = array("start");
            $vals = array($startTime);
            $where = "`id`='" . $sID . "' AND `deleted`!='1'";
            updateRow($table, $cols, $vals, $where);
        }
        foreach ($_POST['end'] as $sID => $sT) {
            $startTime = $sT['hour'] + $sT['min'];

            $table = "sets";
            $cols = array("end");
            $vals = array($startTime);
            $where = "`id`='" . $sID . "' AND `deleted`!='1'";
            updateRow($table, $cols, $vals, $where);
        }


    }

    ?>




    <?php

    //Pick a day and stage
    $availStages = getAllStages();
    $availDays = getAllDays();

    //Find Stage/Day combinations that still need set times
    $remaining = getDayAndStageNeedingimes();
    if ($remaining) {
        foreach ($remaining as $r) {
            echo "There are still sets that need times for day/stage " . getDname($r['day']) . "/" . getPname($r['stage']) . "<br />";
        }
    } else echo '<button id="festbandsetimescomplete" data-fest="' . $fest . '">Band Set Times Complete</button><br />';


    ?>
    <button id="stopfestcreation">Done working on this festival for now</button>
    <br/>

    <div class="topform">
        <form method="post" enctype="multipart/form-data">
            <select name="day">
                <?php
                foreach ($availDays as $a) {
                    if (!empty($_SESSION['setTimes']['day']) && $_SESSION['setTimes']['day'] == $a['id']) echo "<option selected=\"selected\" value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
                    else echo "<option value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
                }
                ?>
            </select>
            <select name="stage">
                <?php
                foreach ($availStages as $aS) {
                    if (!empty($_SESSION['setTimes']['stage']) && $_SESSION['setTimes']['stage'] == $aS['id']) echo "<option selected=\"selected\" value=\"" . $aS['id'] . "\">" . $aS['name'] . "</option>";
                    else echo "<option value=\"" . $aS['id'] . "\">" . $aS['name'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" name="submitDayAndStage" value="See schedule for this day and stage"/>
        </form>
    </div>


    <?php

    if (!empty($_SESSION['setTimes'])){

    $header = getFestHeader($fest);
    $festStart = $header['start_time'];
    $festLength = $header['length'];

    //Construct hour and minute selection options

    for ($i = ($festStart / 3600); $i < (($festLength + $festStart) / 3600); $i++) {
        $modI = $i;
        if ($i > 12 && $i < 25) $modI = $i - 12;
        if ($i > 24) $modI = $i - 24;
        $time['hours'] = $modI;
        $time['sec'] = ($i - ($festStart / 3600)) * 3600;
        $availHours[] = $time;
    }
    unset($time);
    for ($i = 0; $i < 60; $i = $i + 5) {
        $time['min'] = $i;
        $time['sec'] = $i * 60;
        $availMin[] = $time;
    }
    ?>

    <form method="post" enctype="multipart/form-data">
        <div id="bandDisplay" class="bandSource">
            Tip: You can drag and drop the bands to match the schedule order before putting the times in.
            <?php


            //Get bands on the target day and stage
            $bandList = getBandsByDayAndStage($_SESSION['setTimes']['day'], $_SESSION['setTimes']['stage']);
            foreach ($bandList as $b) {
                //change current start and end time into seconds of offset
                $start = $b['start'];
                $end = $b['end'];

                $startMinInSec = ($start % 3600);
                $startHourInSec = $start - $startMinInSec;
                $endMinInSec = ($end % 3600);
                $endHourInSec = $end - $endMinInSec;
                ?>

                <div id="band-<?php echo $b['band']; ?>" class="scheduleBand"><b><?php echo getBname($b['band']); ?></b><br/>
                    Start Time:
                    <select name="start[<?php echo $b['id']; ?>][hour]">
                        <?php
                        foreach ($availHours as $aH) {
                            if ($startHourInSec != $aH['sec']) echo "<option value=\"" . $aH['sec'] . "\">" . $aH['hours'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $aH['sec'] . "\">" . $aH['hours'] . "</option>";
                        }
                        ?>
                    </select>
                    <select name="start[<?php echo $b['id']; ?>][min]">
                        <?php
                        foreach ($availMin as $aM) {
                            if ($startMinInSec != $aM['sec']) echo "<option value=\"" . $aM['sec'] . "\">" . $aM['min'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $aM['sec'] . "\">" . $aM['min'] . "</option>";
                        }
                        ?>
                    </select><br/>
                    End Time:
                    <select name="end[<?php echo $b['id']; ?>][hour]">
                        <?php
                        foreach ($availHours as $aH) {
                            if ($endHourInSec != $aH['sec']) echo "<option value=\"" . $aH['sec'] . "\">" . $aH['hours'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $aH['sec'] . "\">" . $aH['hours'] . "</option>";
                        }
                        ?>
                    </select>
                    <select name="end[<?php echo $b['id']; ?>][min]">
                        <?php
                        foreach ($availMin as $aM) {
                            if ($endMinInSec != $aM['sec']) echo "<option value=\"" . $aM['sec'] . "\">" . $aM['min'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $aM['sec'] . "\">" . $aM['min'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php
            }



            ?>
            <input type="submit" name="submitBandSchedule" value="Update Schedule"/>
        </div>
    </form>

    <!--
        <table id="scheduleTable">
            <tr><th colspan="2"><h4><?php// echo getDname($_SESSION['setTimes']['day'])."-".getSname($_SESSION['setTimes']['stage']); ?></h4></th></tr>
            <?php
            /*
            for($t = 0; $t < $festLength; $t = $t + 300){
               if($t % 3600 == 0){
                   $hour = ($t + $festStart) / 3600;
                   if($hour > 12 && $hour < 25) $hour = $hour - 12;
                   if($hour > 24 ) $hour = $hour - 24;
                   $time = $hour.":00";
                   ?>
                    <tr><th rowspan="12"><?php echo $time; ?></th><td id="time-<?php echo $t; ?>">&nbsp</td> </tr>
            <?php
               }
                else {
                    ?>
                    <tr><td id="time-<?php echo $t; ?>">&nbsp</td> </tr>
            <?php
                }
            }
            */
            ?>
        </table>
        -->
</div>
    <div id="scheduleDisplay">


    </div>
<?php
}
?>



<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script src="includes/js/jquery-ui-1.9.1.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>
<script type="text/javascript" src="includes/js/create-ui.js"></script>
</div> <!-- end #content -->