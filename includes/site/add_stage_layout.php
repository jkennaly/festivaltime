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

    If (!empty($_POST)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br>";
        } else {
            echo "Upload: " . $_FILES["file"]["name"] . "<br>";
            echo "Type: " . $_FILES["file"]["type"] . "<br>";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];
            $file = $_FILES["file"]["tmp_name"];
            unlink($file);
            // Insert into database
            $table = "stage_layouts";
            $cols = array("image", "user", "filename", "type", "description");
            $vals = array(file_get_contents($file), $user, $_POST['name'], $_FILES["file"]["type"], $_POST['descrip']);
            insertRow($table, $cols, $vals);

        }

    }

    ?>
    <p>

        This page allows for adding a layout of a stage.

    </p>

    <form action="<?php echo $basepage . "?disp=add_stage_layout"; ?>" method="post" enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br/>
        <input size="100" type="text" name="name" value="Replace this text with a name of the stage layout"><br/>
        <input size="100" type="text" name="descrip"
               value="Replace this text with a description of the stage layout"><br/>
        <input type="submit" name="submit" value="Submit">
    </form>

</div> <!-- end #content -->
