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

if (!empty($_POST['submitNewBandName'])) {
    $cols = array("name");
    $vals = array($_POST['newName']);
    $where = "`id`='" . $_POST['bandID'] . "'";
    $table = "bands";

    updateRow($table, $cols, $vals, $where);
}


?>


<div id="content">
    <?php
    if (empty($_POST['submitEditBand'])) {
        $allBands = getAllBands();
        ?>
        <h3>Bands currently in the site:</h3>
        <form method="post" enctype="multipart/form-data">
            <select name="rename">
                <?php
                if (!empty($allBands)) {
                    foreach ($allBands as $b) {
                        ?>
                        <option value="<?php echo $b; ?>"><?php echo getBname($b); ?></option>
                    <?php
                    }
                } else echo "This site currently has no bands.";
                ?>
            </select>
            <input type="submit" name="submitEditBand" value="Edit this band's name"/>
        </form>
    <?php
    } else {
        ?>
        Editing band name: <?php echo getBname($_POST['rename']); ?><br/>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="newName" value="<?php echo getBname($_POST['rename']); ?>"/>
            <input type="hidden" name="bandID" value="<?php echo $_POST['rename']; ?>"/>
            <input type="submit" name="submitNewBandName" value="Finalize name edit"/>
        </form>

    <?php
    }
    ?>
</div> <!-- end #content -->


<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>