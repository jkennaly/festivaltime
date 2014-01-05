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

<div id="content">


<?php
$right_required = "CreateNotes";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}

If (!empty($_POST['replacePic'])) {
    if ($_FILES["file"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    } else {
        /*
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Stored in: " . $_FILES["file"]["tmp_name"];
        */
        foreach ($_POST['replacePic'] as $k => $v) {
            $delPic = $k;
        }
        $band = $_POST['band'];

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
            if ($size['0'] > 310 || $size['1'] > 310) $shape = "large_square";
            else $shape = "small_square";
        } else if ($ratio <= 0.66) $shape = "horizontal_rectangle";
        else $shape = "vertical_rectangle";

        $image = new SimpleImage();
        $image->load($file);
        switch ($shape) {
            case "large_square":
                $image->resize(410, 410);
                break;
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
        if ($size[0] < 205 || $size[1] < 205) echo "Sorry, pictures must be at least 205x205 pixels.";
        else {
            // Insert into database
            $sql = "INSERT INTO pics (`pic`, `scaled_pic`, `user`, `band`, `name`, `type`, `size`, `width`, `height`, `h2w_ratio`, `shape`)";
            $sql .= " VALUES ('$data', '$data2', '$user', '$band', '" . $_FILES["file"]["name"] . "', '" . $_FILES["file"]["type"] . "', '" . ($_FILES["file"]["size"] / 1024) . "', '" . $size['0'] . "', '" . $size['1'] . "', '" . $ratio . "', '" . $shape . "');";
            $upd = mysql_query($sql, $master);
//echo "<br>".$sql."<br>";
            echo mysql_error();
            deletePic($delPic);
        }
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
    $picType = 1;

    $file = "image";
    for ($i = 0; $i < 8; $i++) {
        $file .= randAlphaNum();
    }

    file_put_contents($file, getPic($picID, $picType));

    $image = new SimpleImage();
    $image->load($file);

    $image->crop($x1, $y1, $x2, $y2);


    // Insert into database
    $ratio = $h / $w;
    $shape = "0";
    if ($ratio > 0.66 && $ratio < 1.5) {
        $shape = "small_square";
    } else if ($ratio <= 0.66) $shape = "horizontal_rectangle";
    else $shape = "vertical_rectangle";
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
    $data2 = file_get_contents($file);

//        $data2 = mysql_real_escape_string(file_get_contents($file));
    unlink($file);
    if ($h < 205 || $w < 205) $reviewed = 0;
    else $reviewed = $user;
    $table = "pics";
    $cols = array("scaled_pic", "reviewed");
    $vals = array($data2, $reviewed);
    $where = "`id`='$picID'";
    updateRow($table, $cols, $vals, $where);
}


//getPicInfo
$picID = getReviewPic();
$picType = 1;
$info = getPicInfo($picID, $picType);
if ($info['width'] < 205 || $info['height'] < 205) {
    ?>
    This pic is small; consider getting a larger one. Height and width after cropping should be at least 210 pixels.
    <br/>
<?php
}
?>
<a href="http://www.google.com/search?q=<?php echo str_replace(" ", "%20", getBname($info['band'])) . " band"; ?>
    &tbm=isch" target="_blank">Search Google for <?php echo getBname($info['band']); ?> pics</a>
<br/>

<form method="post" enctype="multipart/form-data">
    <label for="file">Filename:</label>
    <input type="file" name="file" id="file" required/><br>
    <input type="hidden" value="<?php echo $info['band']; ?>" name="band"/>
    <input type="submit" name="replacePic[<?php echo $picID; ?>]" value="Replace this pic"/>
</form>
<br/>


<?php
displayPicForCrop($picID, $picType);
?>
<form class="coords" method="post">
    <input type="hidden" size="4" id="x1" name="x1"/>
    <input type="hidden" size="4" id="y1" name="y1"/>
    <input type="hidden" size="4" id="x2" name="x2"/>
    <input type="hidden" size="4" id="y2" name="y2"/>
    <input type="hidden" size="4" id="w" name="w"/>
    <input type="hidden" size="4" id="h" name="h"/>
    <input type="hidden" size="4" id="ar" name="ar"/>
    <input type="hidden" name="picID" value="<?php echo $picID; ?>"/>
    <input type="submit" name="submitScaledPic" value="Crop & Save"/>
</form>
<br/>

<button id="square">Square</button>
<button id="vertTangle">Vertical Rectangle</button>
<button id="horizTangle">Horizontal Rectangle</button>


<script>
    var jCropAspectRatio = 1;
    jQuery(function ($) {

        $('#target').Jcrop({
            onSelect: showCoords,
            bgColor: 'black',
            bgOpacity: .4,
            aspectRatio: jCropAspectRatio
        }, function () {
            jcrop_api = this;
        });
        $("#square").click(function () {
            jCropAspectRatio = 1;
            jQuery('#ar').val(jCropAspectRatio);
            jcrop_api.setAspectRatio(jCropAspectRatio);
        });
        $("#vertTangle").click(function () {
            jCropAspectRatio = 0.5;
            jQuery('#ar').val(jCropAspectRatio);
            jcrop_api.setAspectRatio(jCropAspectRatio);
        });
        $("#horizTangle").click(function () {
            jCropAspectRatio = 2;
            jQuery('#ar').val(jCropAspectRatio);
            jcrop_api.setAspectRatio(jCropAspectRatio);
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
</div> <!-- end #content -->