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
    <?php
    date_default_timezone_set('UTC');
    If (!empty($_POST['submitFestDates'])) {

        for ($i = 0; $i < $_SESSION['dates_added']; $i++) {

            $name = $_POST['name'][$i];
            $venue = $_POST['venue'][$i];
            $basedate = $_POST['basedate'][$i];
            $mode = 1;
            $dateID = $_POST['dateID'][$i];
            //Validation

            // Insert into database
            $table = "dates";
            $cols = array("festival", "festival_series", "name", "venue", "basedate", "mode");
            $vals = array($fest, $festSeries, $name, $venue, $basedate, $mode);
            if ($dateID > 0) {
                $where = "`id`='$dateID'";
                updateRow($table, $cols, $vals, $where);
            } else {
                insertRow($table, $cols, $vals);
                $dateID = getNewestDate();
            }
            $festBands = getAllBandsInFest();
            foreach ($festBands as $festBand) {
                $setDetails = getSetDetailsForBand($festBand);
                if ($setDetails) {

                    $table = "sets";
                    $cols = array("festival", "festival_series", "band", "day", "stage", "date", "start", "end");
                    $vals = array(
                        $setDetails[0]["festival"],
                        $setDetails[0]["festival_series"],
                        $setDetails[0]["band"],
                        $setDetails[0]["day"],
                        $setDetails[0]["stage"],
                        $dateID,
                        $setDetails[0]["start"],
                        $setDetails[0]["end"]
                    );
                    insertRow($table, $cols, $vals);
                }
            }
        }
        $table = "festivals";
        $cols = array("dates", "dates_v");
        $vals = array($user, 0);
        $where = "`id`=$fest";
        updateRow($table, $cols, $vals, $where);
        ?>


        Festival date info accepted.

        <br/>
        <button id="festcheckstatus">See Festival Status</button>
        <br/>
        <button id="stopfestcreation">Done working on this festival for now</button>


    <?php
    } else {


        $date = date('Y-m-d', time());

        $blankData = array(
            'id' => 0,
            'name' => "Date Name",
            'venue' => 0,
            'basedate' => $date
        );

        $header = getFestHeader($fest);

        $num_dates = $header['num_dates'];
        $currDates = getAllDates();

        if (!empty($currDates)) $defined_dates = count($currDates);
        else $currDates = 0;

        if ($currDates) {
            foreach ($currDates as $c) {
                $default_date[] = $c;
            }
        }

        for ($i = count($currDates); $i < $num_dates; $i++) {
            $default_date[] = $blankData;
        }
        If (!empty($_POST)) {

            // Insert into database
            $table = "venues";
            $cols = array("user", "name", "description", "country", "state", "city", "street_address", "timezone");
            $vals = array($user, $_POST['name'], $_POST['descrip'], $_POST['country'], $_POST['state'], $_POST['city'], $_POST['address'], $_POST['userTimeZone']);
            insertRow($table, $cols, $vals);

        }
        $used = getFestVenues($master);

        $venues = getFestVenues($master);
        ?>




        <form action="<?php echo $basepage . "?disp=edit_dates"; ?>" method="post" enctype="multipart/form-data">
            <?php
            $i = 0;

            foreach ($default_date as $dd) {
                ?>
                <div class="festeditdate">
                    <input size="30" type="text" name="name[<?php echo $i; ?>]" value="<?php echo $dd['name']; ?>"><br/>
                    Festival Venue
                    <img alt="info_icon" src="includes/images/emblem-notice.png" title="The venue of the festival"/>:
                    <select name="venue[<?php echo $i; ?>]">
                        <?php
                        foreach ($venues as $v) {
                            if ($v['id'] != $dd['venue']) echo "<option value=\"" . $v['id'] . "\">" . $v['name'] . "</option>";
                            else echo "<option selected=\"selected\" value=\"" . $v['id'] . "\">" . $v['name'] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="button" id="newfestvenue">Add new fest venue</button>
                    <br/>
                    Base Date
                    <img alt="info_icon" src="includes/images/emblem-notice.png"
                         title="The base date is the first (or only) date of the festival date"/>:
                    <input type="date" name="basedate[<?php echo $i; ?>]" value="<?php echo $dd['basedate']; ?>"/>
                    <input type="hidden" name="dateID[<?php echo $i; ?>]" value="<?php echo $dd['id']; ?>">
                </div> <!-- end .festeditdate -->
                <?php
                $i++;
            }

            $_SESSION['dates_added'] = $i;
            ?>

            <input type="submit" name="submitFestDates" value="Submit">
        </form>

        <div id="overlay_form">
            <?php
            include_once('includes/content/blocks/add_fest_venue_form.php');

            ?>
        </div>
        <!-- end #overlay_form -->



    <?php
    }

    ?>
    <script type="text/javascript">
        var basepage = "<?php echo $basepage; ?>";
    </script>
    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/create.js"></script>
</div> <!-- end #content -->