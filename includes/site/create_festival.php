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
$right_required = "AddFest";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}

If (!empty($_POST['submitFestHeader'])) {
    $start_hour = $_POST['hour'];
    if ($start_hour == 12) $start_hour = 0;
    $start_period = $_POST['period'];
    if ($period == "AM") $start_time = 3600 * $start_hour;
    else $start_time = 3600 * ($start_hour + 12);

    $length = $_POST['length'] * 3600;

    if ($_POST['num_dates'] < 1) die('There must be at least one date! Press back on your browser and try again.');

    $name = $_POST["name"];
    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $name)) {
        die('Only letters, numbers and white space allowed! Press back on your browser and try again.');
    }
    $festnamelower = str_replace($outlawcharacters, "", strtolower($_POST['name']));
    $festyearlower = str_replace($outlawcharacters, "", strtolower($_POST['year']));
    $db_name = "festival_" . $festnamelower . "_" . $festyearlower;
    $sitename = $name . " " . $festyearlower;

    $website = $_POST["url"];
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
        die('Website must be valid! Press back on your browser and try again.');
    }

    // Insert into database
    $table = "festivals";
    $cols = array("name", "year", "dbname", "series", "sitename",
        "creator", "start_time", "length", "website", "description", "cost", "num_days", "num_dates", "header");
    $vals = array($name, $festyearlower, $db_name, $_POST['series'], $sitename,
        $user, $start_time, $length, $website, $_POST['description'], $_POST['cost'], $_POST['num_days'], $_POST['num_dates'], $user);
    insertRow($table, $cols, $vals);

    ?>


    <div id="content">
        Festival basic info accepted.

        <br/>
        <button id="festcheckstatus">See Festival Status</button>
        <br/>
        <button id="stopfestcreation">Done working on this festival for now</button>
    </div> <!-- end #content -->

<?php
} else {

    If (!empty($_POST['submitFestSeries'])) {

        // Insert into database
        $table = "festival_series";
        $cols = array("user", "name", "description");
        $vals = array($user, $_POST['name'], $_POST['descrip']);
        insertRow($table, $cols, $vals);

    }

    $series = getFestSeries($master);
    ?>
    <div id="content">
        To begin the festival creation process, some basic info about the festival needs to be collected.
        At any time during the process, you can press the "Done working on this festival for now" button,
        and you (or someone else) can pick up where you left later.

        <form action="<?php echo $basepage . "?disp=create_festival"; ?>" method="post" enctype="multipart/form-data">
            Festival Series
            <img alt="info_icon" src="includes/images/emblem-notice.png"
                 title="The name of the festival series (e.g., Coachella, Bonnaroo, etc.)"/>:
            <select name="series">
                <?php
                foreach ($series as $s) {
                    echo "<option value=\"" . $s['id'] . "\">" . $s['name'] . "</option>";
                }
                ?>
            </select>
            <button type="button" id="newfestseries">Add new fest series</button>
            <br/>
            <input size="100" type="text" name="name" value="Festival Name"><br/>
            <select name="year">
                <?php
                for ($i = 0; $i < 5; $i++) {
                    echo "<option value=\"" . ($i + date("Y")) . "\">" . ($i + date("Y")) . "</option>";
                }
                ?>
            </select><br/>
            <input size="100" type="text" name="url" value="Main festival website"><br/>
            <input size="100" type="text" name="description" value="Description of the festival"><br/>
            Start Time
            <img alt="info_icon" src="includes/images/emblem-notice.png"
                 title="Roughly the start time of the earliest band. If  you're not sure, earlier is better."/>:
            <select name="hour">
                <?php
                for ($i = 1; $i < 13; $i++) {
                    echo "<option value=\"" . $i . "\">" . $i . "</option>";
                }
                ?>
            </select>
            :00
            <select name="period">
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select><br/>
            Day length
            <img alt="info_icon" src="includes/images/emblem-notice.png"
                 title="The number of hours in a festival day. 10:00 AM - 2:00 AM is 16. If  you're not sure, longer is better."/>:
            <select name="length">
                <?php
                for ($i = 1; $i < 25; $i++) {
                    echo "<option value=\"" . $i . "\">" . $i . "</option>";
                }
                ?>
            </select><br/>
            Cost
            <img alt="info_icon" src="includes/images/emblem-notice.png"
                 title="in FestivalTime credits; figure 1 per fest day"/>:
            <select name="cost">
                <?php
                for ($i = 0; $i < 6; $i++) {
                    echo "<option value=\"" . $i . "\">" . $i . "</option>";
                }
                ?>
            </select><br/>
            Number of festival dates
            <img alt="info_icon" src="includes/images/emblem-notice.png"
                 title="not days; Coachlla has two dates, Lollapalooza has 1"/>:
            <input type="number" name="num_dates" min="1" max="100" value="1"><br/>
            Number of festival days
            <img alt="info_icon" src="includes/images/emblem-notice.png"
                 title="number of days in the festival-Coachella has 3, Bonnaroo has 4"/>:
            <input type="number" name="num_days" min="1" max="30" value="1"><br/>
            <input type="submit" name="submitFestHeader" value="Submit">
        </form>
        <br/>
        <br/>

        <button id="stopfestcreation">Done working on this festival for now</button>

        <div id="overlay_form">
            <?php
            include_once('includes/content/blocks/add_fest_series_form.php');
            ?>
        </div>
        <!-- end #overlay_form -->
    </div> <!-- end #content -->




<?php
}


?>
<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>
