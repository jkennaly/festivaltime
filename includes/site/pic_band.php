<?php
/*
//Copyright (c) 2013-2014 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/


?>

<div id="content">


    <?php
    $right_required = "CreateNotes";
    If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
        die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
    }

    If (!empty($_POST)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br>";
        } else {
            /*  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
              echo "Type: " . $_FILES["file"]["type"] . "<br>";
              echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
              echo "Stored in: " . $_FILES["file"]["tmp_name"];
  */

            $fileo = $_FILES["file"]["tmp_name"];
            // you migh want to escape it just in case
            $data = mysql_real_escape_string(file_get_contents($fileo));

            $file = "image";
            for ($i = 0; $i < 8; $i++) {
                $file .= randAlphaNum();
            }

            file_put_contents($file, file_get_contents($fileo));

            $size = getimagesize($file);
            $ratio = $size['1'] / $size['0'];
            $shape = "0";
            if ($ratio > 0.66 && $ratio < 1.5) {
                $shape = "small_square";
            } else if ($ratio <= 0.66) $shape = "horizontal_rectangle";
            else $shape = "vertical_rectangle";

            $image = new SimpleImage();
            $image->load($file);
            switch ($shape) {
                case "large_square":
                case "small_square":
                    $image->resize(205, 205);
                    break;
                case "horizontal_rectangle":
                    $image->resize(410, 205);
                    break;
                case "vertical_rectangle":
                    $image->resize(205, 410);
                    break;
                default:
                    break;
            }

            $image->save($file);
            $data2 = mysql_real_escape_string(file_get_contents($file));
            unlink($file);
            // Insert into database
            if ($size[0] < 205 || $size[1] < 205) echo "Sorry, pictures must be at least 205x205 pixels.";
            else {
                $sql = "INSERT INTO pics (`pic`, `scaled_pic`, `user`, `band`, `name`, `type`, `size`, `width`, `height`, `h2w_ratio`, `shape`)";
            $sql .= " VALUES ('$data', '$data2', '$user', '$band', '" . $_FILES["file"]["name"] . "', '" . $_FILES["file"]["type"] . "', '" . ($_FILES["file"]["size"] / 1024) . "', '" . $size['0'] . "', '" . $size['1'] . "', '" . $ratio . "', '" . $shape . "');";
            $upd = mysql_query($sql, $master);
//echo "<br>".$sql."<br>";
            echo mysql_error();
            }
        }

    }

    ?>
    <p>

        This page allows for adding a picture of a band.

    </p>

    <?php

    $post_target = $basepage . "?disp=view_band&band=" . $band;

    include $baseinstall . "includes/content/blocks/site_band_info.php";

    echo "<a href=\"http://www.google.com/search?q=" . str_replace(" ", "%20", getBname($band)) . "&tbm=isch\" target=\"_blank\">Search Google for band pics</a>";

    ?>
    <form action="<?php echo $basepage . "?disp=pic_band&band=" . $band; ?>" method="post"
          enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submit" value="Submit">
    </form>


</div> <!-- end #content -->
