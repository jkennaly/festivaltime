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

    $bandLevels = getBandPriorities();
    $foundLevel = 0;
    foreach ($bandLevels as $level) {
        $bandsAtLevel = getAllBandsAtLevel($level['level']);
        foreach ($bandsAtLevel as $b) {
            if (!doesBandHaveShape($b, 15)) {
                ?>
                <a href="<?php echo $basepage; ?>?disp=pic_band&band=<?php echo $b; ?>"><?php echo getBname($b); ?></a>
                <br/>
                <?php
                $foundLevel = 1;
            }
        }
        if ($foundLevel) break;
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