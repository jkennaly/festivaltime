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

$right_required = "ViewNotes";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}



?>
<div id="content">

    <?php
    //My festivals
    $myFestivals = userFestivals($user);
    $activeFests = getActiveFests();
    if (!empty($myFestivals) && !empty($activeFests)) $excludeFests = array_merge($myFestivals, $activeFests);
    elseif (!empty($myFestivals)) $excludeFests = $myFestivals;
    elseif (!empty($activeFests)) $excludeFests = $activeFests;
    else $excludeFests = array();
    $upFests = getUpcomingFests($excludeFests);
    $pastFests = getPastsFests($excludeFests);
    ?>
    <h3>My festivals:</h3>

    <?php
    if (empty($myFestivals))
        echo "You are not signed up for any festivals yet.<br />";
    else {
        ?>
        <ul>
            <?php
            foreach ($myFestivals as $myF) {
                ?>
                <li>
                    <a href="<?php echo $basepage; ?>?disp=home&fest=<?php echo $myF; ?>"><?php echo getFname($myF); ?></a>
                </li>

            <?php
            }
            ?>
        </ul>
    <?php
    }


    //Users

    //Account Status
    $creditsAvailable = getCurrentCredits($user);
    ?>
    <b>Number of credits currently available: </b><?php echo $creditsAvailable; ?><br/>

    <?php

    //Currently Active festivals
    ?>
    <h3>Active festivals:</h3>

    <?php
    $activeFound = 0;
    if (empty($activeFests))
        echo "There are not any currently active festivals.<br />";
    else {
        ?>
        <ul>
            <?php
            foreach ($activeFests as $aF) {
                if (in_array($aF, $myFestivals)) continue;
                $activeFound = 1;
                ?>
                <li>
                    <a href="<?php echo $basepage; ?>?disp=home&fest=<?php echo $aF; ?>"><?php echo getFname($aF); ?></a>
                </li>

            <?php
            }
            ?>
        </ul>
        <?php
        if (empty($activeFound)) echo "You are already signed up for all currently active festivals.<br />";
    }



    //Upcoming festivals
    ?>
    <h3>Upcoming festivals:</h3>

    <?php
    $upFound = 0;
    if (empty($upFests))
        echo "There are not any upcoming festivals.<br />";
    else {
        ?>
        <ul>
            <?php
            foreach ($upFests as $uF) {
                if (in_array($uF, $myFestivals)) continue;
                $upFound = 1;
                ?>
                <li>
                    <a href="<?php echo $basepage; ?>?disp=home&fest=<?php echo $uF; ?>"><?php echo getFname($uF); ?></a>
                </li>

            <?php
            }
            ?>
        </ul>
        <?php
        if (empty($upFound)) echo "You are already signed up for all upcoming festivals.<br />";
    }



    //Past Festivals
    ?>
    <h3>Past festivals:</h3>

    <?php
    $pastFound = 0;
    if (empty($pastFests))
    echo "There are not any past festivals.<br />";
    else {
    foreach ($pastFests as $pF){
    if (in_array($pF, $myFestivals)) continue;
    $pastFound = 1;
    ?>
    <a href="<?php echo $basepage . "?disp=home&fest=" . $pF . "\">" . getFname($pF); ?></a><br/>

<?php
    }
    if (empty($pastFound)) echo "You are already signed up for all past festivals.<br />";
    }
    ?>

</div> <!-- end #content -->
