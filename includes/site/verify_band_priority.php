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

$fieldType = "band_priority";
?>

<div id="content">
    <a href="<?php echo $header['website']; ?>" target="_blank">Festival Website</a><br/>
    <?php
    $festLevels = getPrioritiesUsedInFest();
    foreach ($festLevels as $fL) {
        echo "<h2>" . getLname($fL) . "</h2>";
        $levelBands = getAllBandsAtLevelInFest($fL);
        foreach ($levelBands as $lB) {
            echo getBname($lB) . "<br />";
        }
    }

    if ($user == $header[$fieldType]) echo "<b>You entered this information.</b>";
    else {
        ?><br/>
        <button id="festVerifyComplete" data-fest="<?php echo $fest; ?>" data-field="<?php echo $fieldType; ?>">Verify
            this information is correct and complete
        </button>
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