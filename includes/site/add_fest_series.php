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
    <?php

    ?>

    <?php
    If (!empty($_POST)) {

        // Insert into database
        $table = "festival_series";
        $cols = array("user", "name", "description");
        $vals = array($user, $_POST['name'], $_POST['descrip']);
        insertRow($table, $cols, $vals);

    }
    $used = getFestSeries($master);
    ?>
    <p>

        This page allows for adding a festival series.
    </p>

    <?php
    foreach ($used as $u) {
        ?>
        <br><br>
        <div class="festserieswrapper">

            <div class="festseriesname">
                Fest Series Name:
                <?php echo $u['name'] ?>
            </div>
            <!-- end .festseriesname -->

            <div class="festseriesdescrip">
                Festival Series Description:
                <?php echo $u['description'] ?>
            </div>
            <!-- end .festseriesdescrip -->

        </div> <!-- end .festserieswrapper -->

    <?php
    }
    ?>

    <form action="<?php echo $basepage . "?disp=add_fest_series"; ?>" method="post" enctype="multipart/form-data">
        <input size="100" type="text" name="name" value="Replace this text with a name of the festival series"><br>
        <input size="100" type="text" name="descrip"
               value="Replace this text with a description of the festival series"><br>
        <input type="submit" name="submit" value="Submit">
    </form>

</div> <!-- end #content -->

