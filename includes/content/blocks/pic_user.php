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

    If (!empty($_POST['submitScaledPic'])) {
        $x1 = $_POST['x1'];
        $x2 = $_POST['x2'];
        $y1 = $_POST['y1'];
        $y2 = $_POST['y2'];
        $h = $_POST['h'];
        $w = $_POST['w'];
        $picID = $_POST['picID'];
        $picType = 2;

        $file = "image";
        for ($i = 0; $i < 8; $i++) {
            $file .= randAlphaNum();
        }

        file_put_contents($file, getPic($picID, $picType));

        $image = new SimpleImage();
        $image->load($file);

        $image->crop($x1, $y1, $x2, $y2);

        $image->resize(105, 105);

        $image->save($file);
        $data2 = file_get_contents($file);

//        $data2 = mysql_real_escape_string(file_get_contents($file));
        unlink($file);
        $table = "pics_users";
        $cols = array("scaled_pic");
        $vals = array($data2);
        $where = "`user`='$picID'";
        updateRow($table, $cols, $vals, $where);
    }

    ?>
    <div id="pic-user">
        Upload a new user profile picture:
        <form method="post" enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submitUserPic" value="Submit User Picture">
    </form>

        Or crop your existing picture:
        <?php
        $picType = 2;
        displayPicForCrop($user, $picType);
        ?>
        <form class="coords" method="post">
            <input type="hidden" size="4" id="x1" name="x1"/>
            <input type="hidden" size="4" id="y1" name="y1"/>
            <input type="hidden" size="4" id="x2" name="x2"/>
            <input type="hidden" size="4" id="y2" name="y2"/>
            <input type="hidden" size="4" id="w" name="w"/>
            <input type="hidden" size="4" id="h" name="h"/>
            <input type="hidden" size="4" id="ar" name="ar"/>
            <input type="hidden" name="picID" value="<?php echo $user; ?>"/>
            <input type="submit" name="submitScaledPic" value="Crop & Save"/>
        </form>
        <br/>
    </div>
    <button type="button" id="show-pics">Change my User Pic</button>
    <br/>
<?php
}
?>
<script>
    var jCropAspectRatio = 1;
    jQuery(function ($) {
        <?php
        if(!empty($_POST['submitUserPic']) || !empty($_POST['submitScaledPic']) ){
        ?>
        $("#pic-user").show();
        $("#show-pics").hide();
        <?php
        } else{
        ?>
        $("#pic-user").hide();
        <?php
        }
        ?>

        $("#target").load(function () {

            var width = $("#target").width();
            var height = $("#target").height();

            $('#target').Jcrop({
                onSelect: showCoords,
                bgColor: 'black',
                bgOpacity: .4,
                setSelect: [ 0, 0, width, height ],
                aspectRatio: jCropAspectRatio
            }, function () {
                jcrop_api = this;
            });
        });

        $("#show-pics").click(function () {
            $("#pic-user").show();
        });

    });
    function showCoords(c) {
        jQuery('#x1').val(c.x);
        jQuery('#y1').val(c.y);
        jQuery('#x2').val(c.x2);
        jQuery('#y2').val(c.y2);
        jQuery('#w').val(c.w);
        jQuery('#h').val(c.h);
        jQuery('#ar').val(jCropAspectRatio);

    }
    ;
</script>