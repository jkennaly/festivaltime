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
    <br/>
    <button id="festbandlistcomplete" data-fest="<?php echo $fest; ?>">Band List is Complete</button>
    <br/>
    <button id="stopfestcreation">Done working on this festival for now</button>
    <?php

    if (empty($_SESSION['bandsAdded']))
        include('includes/content/blocks/bandlist_upload.php');
    else {
        unset($_SESSION['bandsAdded']);
        ?>

        Festival band list accepted.


        <br/>
        <button id="festaddmorebands">Add More Bands</button>

    <?php
    }
    $allBands = getAllBandsInFest();
    ?>
    <h3>Current Bands in this festival:</h3>
    <?php
    if (!empty($allBands)) {
        foreach ($allBands as $b) {
            echo getBname($b) . "<br>";
        }
    } else echo "This festival currently has no bands.";
    ?>


</div> <!-- end #content -->
<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>