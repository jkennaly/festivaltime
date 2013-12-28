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

    /*
     <p id='position'>Unclicked</p>
    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/layout.js"></script>
    */

    ?>

    <?php
    If (!empty($_POST)) {

        // Insert into database
        $table = "band_priorities";
        $cols = array("user", "name", "level", "description");
        $vals = array($user, $_POST['name'], $_POST['level'], $_POST['descrip']);
        insertRow($table, $cols, $vals);

    }
    $used = getBandPriorities($master);
    $availPrior = getAvailableBandPriorities($master);
    ?>
    <p>

        This page allows for adding a priority of a band. A lower priority means a more important band-1 is the
        biggest band in the festival. 100 might be you playing the radio at your tent. These priorities are not
        associated with a particular festival, so keep the names generic.
    </p>

    <?php
    foreach ($used as $u) {
        ?>
        <br><br>
        <div class="bandpriwrapper">
            <div class="bandprilevel">
                Band Priority Level:
                <?php echo $u['level'] ?>
            </div>
            <!-- end .bandprilevel -->

            <div class="bandpriname">
                Band Priority Name:
                <?php echo $u['name'] ?>
            </div>
            <!-- end .bandpriname -->

            <div class="bandpridescrip">
                Band Priority Description:
                <?php echo $u['description'] ?>
            </div>
            <!-- end .bandpridescrip -->
            <?php
            if ($u['default'] == 1) {
                ?>
                <div class="bandpridefault">
                    This is the default priority for new bands.
                </div> <!-- end .bandpridefault -->
            <?php
            }
            ?>


        </div> <!-- end .bandpriwrapper -->

    <?php
    }
    ?>

    <form action="<?php echo $basepage . "?disp=add_band_priority"; ?>" method="post" enctype="multipart/form-data">
        <select name="level">
            <?php
            foreach ($availPrior as $a) {
                echo "<option value=\"" . $a . "\">" . $a . "</option>";
            }
            ?>
        </select>
        <input size="100" type="text" name="name" value="Replace this text with a name of the band priority"><br/>
        <input size="100" type="text" name="descrip"
               value="Replace this text with a description of the band priority"><br/>
        <input type="submit" name="submit" value="Submit">
    </form>

</div> <!-- end #content -->

