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


?>
<?php
$right_required = "CreateNotes";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {

} else {

    If (!empty($_POST['submitUserPic'])) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br>";
        } else {
            echo "Upload: " . $_FILES["file"]["name"] . "<br>";
            echo "Type: " . $_FILES["file"]["type"] . "<br>";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];


            $fileo = $_FILES["file"]["tmp_name"];
            // you migh want to escape it just in case
            $data = mysql_real_escape_string(file_get_contents($fileo));

            $file = "image";
            for ($i = 0; $i < 8; $i++) {
                $file .= randAlphaNum();
            }

            file_put_contents($file, file_get_contents($fileo));

            $image = new SimpleImage();
            $image->load($file);
            $image->resize(105, 105);
            $image->save($file);
            $data2 = mysql_real_escape_string(file_get_contents($file));
            unlink($file);
            // Insert into database
            $sql = "INSERT INTO pics_users (`pic`, `scaled_pic`, `user`, `filename`, `type`)";
            $sql .= " VALUES ('$data', '$data2', '$user', '" . $_FILES["file"]["name"] . "', '" . $_FILES["file"]["type"] . "');";
            $upd = mysql_query($sql, $master);
//echo "<br>".$sql."<br>";
            echo mysql_error();

        }

    }

    ?>
    <form method="post" enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submitUserPic" value="Submit User Picture">
    </form>

<?php
}

