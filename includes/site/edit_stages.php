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

    $stages = getAllStages();

    If (!empty($_POST)) {

        if (empty($_POST['submitNewStage'])) {
            foreach ($stages as $updated) {
                $post_edit = "update-" . $updated['id'];
                $post_delete = "delete-" . $updated['id'];
//		echo $post_edit."<br />";
                if (!empty($_POST[$post_edit])) {
                    // Insert into database
                    $table = "places";
                    $cols = array("name", "priority", "layout");
                    $vals = array($_POST['name'], $_POST['level'], $_POST['layout']);
                    $where = "`id`='" . $updated['id'] . "'";
                    updateRow($table, $cols, $vals, $where);
                }
                if (!empty($_POST[$post_delete])) {

                    // Insert into database
                    $table = "places";
                    $cols = array("deleted");
                    $vals = array(1);
                    $where = "`id`='" . $updated['id'] . "'";
                    updateRow($table, $cols, $vals, $where);
                }
            }
        }

        If (!empty($_POST['submitNewStage'])) {

            // Insert into database
            $table = "places";
            $cols = array("festival", "festival_series", "type", "name", "priority", "layout");
            $vals = array($fest, $festSeries, 1, $_POST['name'], $_POST['level'], $_POST['layout']);
            insertRow($table, $cols, $vals);
        }

        $stages = getAllStages();
    }

    ?>



    <br/>
    <button id="feststagsecomplete" data-fest="<?php echo $fest; ?>">Stages are complete</button>
    <br/>
    <button id="festcheckstatus">See Festival Status</button>
    <br/>
    <button id="stopfestcreation">Done working on this festival for now</button>

    <h2>Current stages</h2>

    <?php
    $availPrior = getStagePriorities();

    $layouts = getAllStageLayouts();
    if ($stages) {
        foreach ($stages as $s) {
            $priority = getStagePriorityInfoFromLevel($s['priority']);
            ?>
            <div class="stagewrapper">
                <form action="<?php echo $basepage . "?disp=edit_stages"; ?>" method="post"
                      enctype="multipart/form-data">
                    <div class="stagename">
                        Stage Name:
                        <?php echo $s['name'] ?><br/>
                        <input size="30" type="text" name="name" value="<?php echo $s['name']; ?>"><br/>
                    </div>
                    <!-- end .stagename -->

                    <div class="stagepriority">
                        Stage Priority
                        <?php echo $priority['name']; ?>
                        <img alt="info_icon" src="includes/images/emblem-notice.png"
                             title="<?php echo $priority['description']; ?>"/>:

                        <br/>
                        <select name="level">
                            <?php
                            foreach ($availPrior as $a) {
                                if ($s['priority'] != $a['level']) echo "<option value=\"" . $a['level'] . "\">" . $a['name'] . "</option>";
                                else echo "<option selected=\"selected\" value=\"" . $a['level'] . "\">" . $a['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- end .stagepriority -->

                    <div class="stagelayout">
                        Stage Layout:
                        <a href="includes/content/blocks/getPicStageLayout.php?layout=<?php echo $s['layout'] ?>"
                           class="thickbox">
                            <?php echo getStageLayoutName($s['layout'], $master) ?>
                        </a><br/>
                        <select name="layout">
                            <?php
                            foreach ($layouts as $l) {
                                if ($l['id'] != $s['layout']) echo "<option value=\"" . $l['id'] . "\">" . $l['description'] . "</option>";
                                else echo "<option selected=\"selected\" value=\"" . $l['id'] . "\">" . $l['description'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- end .stagelayout -->

                    <input type="submit" name="update-<?php echo $s['id']; ?>"
                           value="Update <?php echo $s['name']; ?>"/>
                    <input type="submit" name="delete-<?php echo $s['id']; ?>"
                           value="Delete <?php echo $s['name']; ?>"/>

                </form>
            </div> <!-- end .stagewrapper -->
            <br/>
        <?php
        }
    }



    ?>
    <div id="addnewstage">
        <h2>Add a new Stage</h2>

        <form action="<?php echo $basepage . "?disp=edit_stages"; ?>" method="post" enctype="multipart/form-data">
            <input size="30" type="text" name="name" value="Name of the stage here"><br/>
            Select Priority:
            <select name="level">
                <?php
                foreach ($availPrior as $a) {
                    if (1 != $a['default']) echo "<option value=\"" . $a['level'] . "\">" . $a['name'] . "</option>";
                    else echo "<option selected=\"selected\" value=\"" . $a['level'] . "\">" . $a['name'] . "</option>";
                }
                ?>

            </select><br/>
            Select Layout:
            <select name="layout">
                <?php
                foreach ($layouts as $l) {
                    if ($l['default'] != 1) echo "<option value=\"" . $l['id'] . "\">" . $l['description'] . "</option>";
                    else echo "<option selected=\"selected\" value=\"" . $l['id'] . "\">" . $l['description'] . "</option>";
                }
                ?>
            </select><br/>
            <input type="submit" name="submitNewStage" value="Add New Stage">
        </form>
    </div>
    <!-- end .addnewstage -->

    <script type="text/javascript">
        var basepage = "<?php echo $basepage; ?>";
    </script>
    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/create.js"></script>
    <script type="text/javascript" src="includes/js/thickbox.js"></script>
</div> <!-- end #content -->


