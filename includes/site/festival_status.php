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
unset($_SESSION['setTimes']);
$complete = getCompletedFestivals($master);
$incomplete = getIncompleteFestivals($master);
$verifreq = getVerifReqFestivals($master);

?>


<div id="content">
    <div id="showfestivalbuttons">
        <button type="button" id="show-complete">Show/Hide Completed Festivals</button>
        <button type="button" id="show-incomplete">Show/Hide Festivals Requiring More Information</button>
        <button type="button" id="show-verifreq">Show/Hide Festivals Requiring Verification</button>
    </div>
    <button type="button" id="show-festbuttons">Show More Festivals</button>
    <button type="button" id="show-more-functions">Show More Functions</button>
    <div id="showMoreFunctions">
        <button type="button" id="create-festival">Create A New Festival</button>
        <!--functional -->
        <br/>
        <button type="button" id="delete-festival">Delete A Festival</button>
        <br/>
        <?php if (!empty($fest)) {
            ?>

            <button type="button" id="delete-band">Delete Band From Current Festival</button>
            <br/>
            <button type="button" id="clean-fest">Clean Current Festival</button>
            <br/>

        <?php
        }
        ?>
        <button type="button" id="change-band-name">Change A Band Name</button>
        <br/>
        <button type="button" id="combine-bands">Combine Bands</button>
        <br/>
        <button type="button" id="change-venue">Add A Venue</button>
        <br/>
        <button type="button" id="change-band-priority">Add/Change A Band Priority</button>
        <br/>
        <button type="button" id="change-stage-priority">Add/Change A Stage Priority</button>
        <br/>
        <button type="button" id="add-stage-layout">Add A Stage Layout</button>
        <br/>
        <button type="button" id="change-fest-series">Add/Change A Festival Series</button>
        <br/>
        <button type="button" id="change-user-settings">Modify User Settings</button>
        <br/>
        <button type="button" id="update-missing-band-pics">Find pics for bands that do not have them</button>
        <br/>
        <button type="button" id="change-user">Change User Account</button>
        <br/>
        <button type="button" id="manage-genres">Manage Genres</button>
        <br/>
    </div>

    <div id="festivalstatuscompleted" class="festivalstatuswrapper">
        <h2>Complete Festivals</h2>
        <?php
        $statustypes = array(
            array('header', 'Header', 'header_v'),
            array('dates', 'Dates', 'dates_v'),
            array('days_venues', 'Days', 'days_venues_v'),
            array('stages', 'Stages', 'stages_v'),
            array('band_list', 'Band List', 'band_list_v'),
            array('band_priority', 'Band Priority', 'band_priority_v'),
            array('band_days', 'Band Days', 'band_days_v'),
            array('band_stages', 'Band Stages', 'band_stages_v'),
            array('set_times', 'Set Times', 'set_times_v'),
        );
        if (!empty($complete)) {
            foreach ($complete as $c) {

                drawFestStatus($c, $statustypes);
            }
        }
        ?>
    </div>
    <!-- end #festivalstatuscompleted -->

    <div id="festivalstatusincomplete" class="festivalstatuswrapper">
        <h2>Festivals that need more information added</h2>
        <?php
        if (!empty($incomplete)) {
            foreach ($incomplete as $inc) {

                drawFestStatus($inc, $statustypes);
            }
        }
        ?>
    </div>
    <!-- end #festivalstatusincomplete -->

    <div id="festivalstatusverifreq" class="festivalstatuswrapper">
        <h2>Festivals that need information verified</h2>
        <?php
        if (!empty($verifreq)) {
            foreach ($verifreq as $vr) {
                drawFestStatus($vr, $statustypes);
            }
        }
        ?>
    </div>
    <!-- end #festivalstatusverifreq -->

    <?php
    if (!empty($fest)) {
        ?>

        <div id="festivalstatuscurrent">
            <?php
            $currFest = getFestHeader($fest);
            drawFestStatus($currFest, $statustypes);
            ?>
        </div><!-- end #festivalstatusverifreq -->
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