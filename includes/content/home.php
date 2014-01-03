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

    $bandTiers = getAllAvailableBandPriorities();
    foreach ($bandTiers as $bT) {
        $bandsAtLevel = getBandsAtPriority($bT['level']);
        if (!$bandsAtLevel) continue;
        $bandsToDisplay = count($bandsAtLevel);
        if ($bandsToDisplay == 0) continue;
        echo "<div id=\"level-" . $bT['level'] . "\" class=\"bands-by-level\">";
        $bandsDisplayed = 0;
        unset($displayedBandID);
        $displayedBandID = array();
        //Find all bands with no pic and write the names out
        $bandsMissingPics = 0;
        foreach ($bandsAtLevel as &$b) {
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
        $picArray = array(
            array(0, 0),
            array(0, 0),
            array(0, 0),
            array(0, 0),
        );

        $x = 0;
        do {
            $bandDisplayedThisLoop = 0;
//		echo $i." is in for loop<br>";
            foreach ($bandsAtLevel as &$big) {
                //               echo "Band ID: " . $big. "<br />";
                switch ($x) {
                    case 0:
                        $shapeCode = 15;
                        break;
                    case 1:
                        if ($picArray[0][1] == 1) $shapeCode = 15;
                        else $shapeCode = 3;
                        break;
                    case 2:
                        if ($picArray[1][1] == 1) $shapeCode = 15;
                        else $shapeCode = 3;
                        break;
                    case 3:
                        if ($picArray[2][1] == 1) $shapeCode = 5;
                        else $shapeCode = 1;
                        break;
                    default:
                        break;
                }
                //	echo $shapeCode." is shape code<br>";
                $bandOK = (doesBandHaveShape($big, $shapeCode));
                //	echo "Does band have shape? $bandOK<br>";

                $bandOK = ($bandOK && !in_array($big, $displayedBandID));
                //	echo "Is band in array? $bandOK<br>";

                if ($bandOK) {
//				echo $big['bandname']." is ok<br>";
                    $bandPicResult = getBandPicAndShape($big, $shapeCode);
                    if ($bandPicResult[1] == "small_square") {
                        //	echo "<br>Found a small_square";
                        $picArray[$x][0] = 1;
                    }
                    if ($bandPicResult[1] == "large_square") {
                        //	echo "<br>Found a large_square";
                        $picArray[$x][0] = 1;
                        $picArray[$x + 1][0] = 1;
                        $picArray[$x][1] = 1;
                        $picArray[$x + 1][1] = 1;
                    }
                    if ($bandPicResult[1] == "horizontal_rectangle") {
                        //	echo "<br>Found a horizontal_rectangle";
                        $picArray[$x][0] = 1;
                        $picArray[$x + 1][0] = 1;
                    }
                    if ($bandPicResult[1] == "vertical_rectangle") {
                        //	echo "<br>Found a vertical_rectangle";
                        $picArray[$x][0] = 1;
                        $picArray[$x][1] = 1;
                    }

                    displayPic3($big, $bandPicResult[0], getBname($big));
                    $bandsDisplayed++;
                    $bandDisplayedThisLoop = 1;
                    if ($bandsToDisplay == $bandsDisplayed) {
                        //	echo "Breaking Loop";
                        break(2);
                    }
                    $displayedBandID[] = $big;
//				echo count($displayedBandID)." is count of displayed bands.";


                    while ($picArray[$x][0] == 1) {
                        $x = $x + 1;
                        if ($x > 3) {
                            $x = 0;
                            $picArray[0][0] = $picArray[0][1];
                            $picArray[1][0] = $picArray[1][1];
                            $picArray[2][0] = $picArray[2][1];
                            $picArray[3][0] = $picArray[3][1];
                            $picArray[0][1] = 0;
                            $picArray[1][1] = 0;
                            $picArray[2][1] = 0;
                            $picArray[3][1] = 0;
                        }
                    }
                } //else echo $big['bandname']." is NOT ok<br>";
            }
        } while ($bandDisplayedThisLoop == 1);
        if ($bandsToDisplay != $bandsDisplayed) {
            foreach ($bandsAtLevel as $leftOver) {
                if (!in_array($leftOver, $displayedBandID)) {
                    displayPic4($leftOver, getBname($leftOver));
                }
            }
        }
        echo "</div> <!-- end #level-" . $bT['level'] . " -->";
    }

    ?>


</div> <!-- end #content -->