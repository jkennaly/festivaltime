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
    <?php
    if (!empty($_POST['submitBandPriorities'])) {
        foreach ($_POST['band'] as $bandp => $level) {
            $table = "band_list";
            $cols = array("priority");
            $vals = array($level);
            $where = "`band`='" . $bandp . "' AND `festival`='$fest' AND `deleted`!='1'";
            updateRow($table, $cols, $vals, $where);
        }
        ?>

        Festival band priorities accepted. You may continue editing priorities or press the complete button.

        <br/>
        <button id="festbandprioritiescomplete" data-fest="<?php echo $fest; ?>">Band Priorities Are Complete</button>
        <br/>
        <button id="stopfestcreation">Done working on this festival for now</button>
    <?php
    }

    ?>

    <form method="post" enctype="multipart/form-data">
        <input type="submit" name="submitBandPriorities" value="Update All Band Priority Levels"/>
        <?php

        $bandPriorities = getAllBandPriorities();
        $availPrior = getAllAvailableBandPriorities();


        foreach ($bandPriorities as $s) {
            $priority = getBandPriorityInfoFromLevel($s['priority']);
            ?>
            <div class="bandpriorityselectwrapper">
                <div class="bandname">
                    <?php echo getBname($s['band']); ?>
                </div>
                <!-- end .bandname -->

                <div class="bandpriority">
                    <select name="band[<?php echo $s['band']; ?>]">
                        <?php
                        foreach ($availPrior as $a) {
                            if ($s['priority'] != $a['level']) echo "<option value=\"" . $a['level'] . "\">" . $a['name'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $a['level'] . "\">" . $a['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- end .bandpriority -->

            </div> <!-- end #bandpriorityselectwrapper -->
        <?php
        }

        ?>
        <input type="submit" name="submitBandPriorities" value="Update All Band Priority Levels"/>
    </form>

    <?php

    ?>

</div> <!-- end #content -->


<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>