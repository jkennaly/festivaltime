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

        // Insert into database
        $table = "messages_privacy";
        $cols = array("name", "description");
        $vals = array($_POST['name'], $_POST['descrip']);
        insertRow($master, $table, $cols, $vals);

    }
    $used = getMessagePrivacies($master);
    ?>
    <p>

        This page allows for adding a message privacy setting.
    </p>

    <?php
    if (!empty($used)) foreach ($used as $u) {
        ?>
        <br><br>
        <div class="messagepriwrapper">
            <div class="messagepriname">
                Message Privacy Name:
                <?php echo $u['name'] ?>
            </div>
            <!-- end .messagepriname -->
            <div class="messagepridescrip">
                Message Privacy Description:
                <?php echo $u['description'] ?>
            </div>
            <!-- end .messagepridescrip -->


        </div> <!-- end .messagepriwrapper -->

    <?php
    }
    ?>

    <form action="<?php echo $basepage . "?disp=add_message_privacy"; ?>" method="post" enctype="multipart/form-data">
        <input size="100" type="text" name="name" value="Replace this text with a name of the message privacy"><br>
        <input size="100" type="text" name="descrip"
               value="Replace this text with a description of the message privacy"><br>
        <input type="submit" name="submit" value="Submit">
    </form>

</div> <!-- end #content -->

