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
?>
<div id="content">
    <a href="<?php echo $header['website']; ?>" target="_blank">Festival Website</a><br/>
    <?php
    If (!empty($_POST['submitFestDays'])) {

        for ($i = 0; $i < $_SESSION['days_added']; $i++) {

            $name = $_POST['name'][$i];
            $offset = $_POST['days_offset'][$i];
            $dayID = $_POST['dayID'][$i];
            //Validation

            // Insert into database
            $table = "days";
            $cols = array("festival", "festival_series", "name", "days_offset");
            $vals = array($fest, $festSeries, $name, $offset);
            if ($dayID > 0) {
                $where = "`id`='$dayID'";
                updateRow($table, $cols, $vals, $where);
            } else insertRow($table, $cols, $vals);
        }
        $table = "festivals";
        $cols = array("days_venues", "days_venues_v");
        $vals = array($user, 0);
        $where = "`id`=$fest";
        updateRow($table, $cols, $vals, $where);
        ?>



        Festival day info accepted.

        <br/>
        <button id="festcheckstatus">See Festival Status</button>
        <br/>
        <button id="stopfestcreation">Done working on this festival for now</button>


    <?php
    } else {


        $header = getFestHeader($fest);

        $num_days = $header['num_days'];
        $currDays = getSuggestedDays();
        if ($currDays) $num_sug_days = count($currDays);
        else $num_sug_days = 0;

        for ($i = $num_sug_days; $i < $num_days; $i++) {
            $blankData = array(
                'id' => 0,
                'name' => "Day Name",
                'days_offset' => $i
            );
            $currDays[$i] = $blankData;
        }

        ?>





        <form action="<?php echo $basepage . "?disp=edit_days_venues"; ?>" method="post" enctype="multipart/form-data">
            <?php
            $i = 0;

            foreach ($currDays as $dd) {
                ?>
                <div class="festeditday">
                    <input size="30" type="text" name="name[<?php echo $i; ?>]" value="<?php echo $dd['name']; ?>"><br/>
                    Offset Days
                    <img alt="info_icon" src="includes/images/emblem-notice.png"
                         title="The offset days is the number of days after the date basedate that this day. E.g., for a Fri-SAT fest, offseet is 0 Fri, 1 Sat, 2 Sun"/>:
                    <input type="number" name="days_offset[<?php echo $i; ?>]" min="0" max="30"
                           value="<?php echo $dd['days_offset']; ?>"><br/>
                </div> <!-- end .festeditday -->
                <input type="hidden" name="dayID[<?php echo $i; ?>]" value="<?php echo $dd['id']; ?>">
                <?php
                $i++;
                if ($i >= $num_days) break;
            }

            $_SESSION['days_added'] = $i;
            ?>

            <input type="submit" name="submitFestDays" value="Submit">
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