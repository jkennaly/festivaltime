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
    <?php

    /*
     <p id='position'>Unclicked</p>
    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/layout.js"></script>
    */

    ?>

    <?php
    If (!empty($_POST)) {

        // Insert into the database
        $table = "places";
        $cols = array("festival", "festival_series", "type", "name", "priority", "layout");
        $vals = array($fest, $festSeries, 1, $_POST['name'], $_POST['level'], $_POST['layout']);
        insertRow($table, $cols, $vals);

    }
    $used = getStagePriorities($master);
    ?>
    <p>

        This page allows for adding new stages to the festival.

    </p>

    <?php
    $stages = getAllStages($main);
    $availPrior = getStagePriorities($master);
    $layouts = getAllStageLayouts($master);
    foreach ($stages as $s) {
        $priority = getPriorityInfoFromID($master, $s['priority']);
        ?>
        Current stages:
        <br/><br/>
        <div class="stagewrapper">
            <div class="stagename">
                Stage Name:
                <?php echo $s['name'] ?><br/>
            </div>
            <!-- end .stagename -->

            <div class="stagepriority">
                Stage Priority Name:
                <?php echo $priority['name']; ?>
                <br/>
                Stage Priority Description:
                <?php echo $priority['description']; ?><br/>
            </div>
            <!-- end .stagepriority -->

            <div class="stagelayout">
                Stage Layout:
                <a href="includes/content/blocks/getPicStageLayout.php?layout=<?php echo $s['layout'] ?>"
                   class="thickbox">
                    <?php echo getStageLayoutName($s['layout'], $master) ?>
                </a><br/>

            </div>
            <!-- end .stagelayout -->

        </div> <!-- end .stagewrapper -->

    <?php
    }
    ?>
    <br/><br/>
    Add new stage:


    <form action="<?php echo $basepage . "?disp=add_stage"; ?>" method="post" enctype="multipart/form-data">
        <input size="30" type="text" name="name" value="Name of the stage here"><br/>
        Select Priority:
        <select name="priority">
            <?php
            foreach ($availPrior as $a) {
                if (1 != $a['default']) echo "<option value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
                else echo "<option selected=\"selected\" value=\"" . $a['id'] . "\">" . $a['name'] . "</option>";
            }
            ?>
            Select Layout:
        </select><br/>
        <select name="layout">
            <?php
            foreach ($layouts as $l) {
                if ($l['default'] != 1) echo "<option value=\"" . $l['id'] . "\">" . $l['description'] . "</option>";
                else echo "<option selected=\"selected\" value=\"" . $l['id'] . "\">" . $l['description'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="submitNewStage" value="Submit">
    </form>

    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/thickbox.js"></script>
</div> <!-- end #content -->

