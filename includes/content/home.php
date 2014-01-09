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
    /*
    $allBands = getAllBandsInFest();
    foreach ($allBands as $b) {
        echo getBname($b) . "<br />";
    }
    */

    $displayNumSetting = getUserSetting($user, 74);
    $displayRatedSetting = getUserSetting($user, 75);
    $displaySortSetting = getUserSetting($user, 76);
    switch ($displayNumSetting) {
        case 1:
            $bandsToDisplay = 5;
            break;
        case 2:
            $bandsToDisplay = 10;
            break;
        case 3:
            $bandsToDisplay = 20;
            break;
        case 4:
            $bandsToDisplay = 1000;
            break;
        default:
            $bandsToDisplay = 25;
            break;
    }

    if ($displaySortSetting == 1) $bandTiers = getAllAvailableBandPriorities();
    if ($displaySortSetting == 2) $bandTiers = getGenresForAllBandsInFest($user);

    $bandsDisplayed = 0;
    foreach ($bandTiers as $bT) {


        if ($displaySortSetting == 1) {
            $bandsAtLevel = getBandsAtPriority($bT);
            $levelName = "";
        }
        if ($displaySortSetting == 2) {
            $bandsAtLevel = getAllBandsOfAGenreInFest($user, $bT);
            $levelName = getGname($bT);
        }
        if (!$bandsAtLevel) continue;
//        $bandsToDisplay = count($bandsAtLevel);

        if ($bandsToDisplay == 0) continue;
        if ($bandsDisplayed >= $bandsToDisplay) break;
        echo "<div id=\"level-" . $bT . "\" class=\"bands-by-level\">";
        echo "<h2>" . $levelName . "</h2>";
        unset($displayedBandID);
        $displayedBandID = array();
        //Find all bands with no pic and write the names out
        $bandsMissingPics = 0;
        foreach ($bandsAtLevel as &$b) {
            if ($displayRatedSetting == 1 && act_rating($b, $user) != 0) {
                $displayedBandID[] = $b;
                continue;
            }
            if (!doesBandHaveShape($b, 15)) {
                if (!$bandsMissingPics) {
                    echo '<div class="bands-with-no-pic">';
                    $bandsMissingPics = 1;
                }
                echo '<a href="' . $basepage . "?disp=view_band&band=" . $b . "&fest=" . $fest . "\">" . getBname($b) . "</a><br>";
                $bandsDisplayed++;
                $displayedBandID[] = $b;
            }

        }
        if ($bandsMissingPics) echo '</div> <!-- end .bands-with-no-pic -->';
        $bandsLeft = $bandsToDisplay - $bandsDisplayed;


        displayBandPicArray($bandsAtLevel);
        echo "</div> <!-- end #level-" . $bT . " -->";
    }

    ?>

    <script src="includes/js/home.js"></script>
</div> <!-- end #content -->