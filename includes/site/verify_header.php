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

$fieldType = "header";
?>


<div id="content">
    <a href="<?php echo $header['website']; ?>" target="_blank">Festival Website</a><br/>
    <?php
    date_default_timezone_set('UTC');
    echo "name: " . $header['name'] . "<br />";
    echo "year: " . $header['year'] . "<br />";
    echo "description: " . $header['description'] . "<br />";
    echo "series: " . getFSname($header['series']) . "<br />";

    echo "start time (the start time of the earliest band in the fest-must be earlier than or equal to): " . strftime('%l:%M %p', $header['start_time']) . "<br />";
    echo "end time(the time latest band is scheduled to walk off-must be equal to or later than): " . strftime('%l:%M %p', ($header['start_time'] + $header['length'])) . "<br />";
    echo "website: " . $header['website'] . "<br />";
    echo "cost: " . $header['cost'] . " credits<br />";
    echo "Number of days (how long the festival is): " . $header['num_days'] . "<br />";
    echo "Number of dates(how many times the festival is repeated): " . $header['num_dates'] . "<br />";

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