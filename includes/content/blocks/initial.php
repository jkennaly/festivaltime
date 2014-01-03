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


?>

<div id="genrebands">

    <?php

    $genreList = genreList($user);
    $bandList = getGenresForAllBandsInFest($user);


    foreach ($genreList as &$g) {
        if (empty($g['name'])) $g['name'] = "Bands that need a genre";
//	echo $g['name']."-".$g['id']."<br>";
        foreach ($bandList as &$b) {
            if ($g['id'] == $b['genreid']) $g['band'][] = $b;
            unset ($b);
        }
    }
    /*
    unset($g);

    foreach ($genreList as $g){
        echo "<h2>".$g['name']."</h2>";
        echo "<h4>".$g['bands']." in this genre</h4>";
        foreach ($g['band'] as $b){
            echo $b['bandname']."<br>";
        }
    }
    */

    unset($g);

    foreach ($genreList as &$g) {
        echo "<div id=\"genre" . $g['name'] . "\" class=\"bandsbygenre\">";
        echo "<h2>" . $g['name'] . "</h2>";
        echo "<h4>" . $g['bands'] . " in this genre</h4>";
        $bandsInGenre = $g['band'];
        $bandsToDisplay = count($bandsInGenre);
        $bandsDisplayed = 0;
        unset($displayedBandID);
        $displayedBandID = array();
        //Find all bands with no pic and write the names out
        foreach ($bandsInGenre as &$b) {
            if (!doesBandHaveShape($b['id'], 15)) {
                echo $b['bandname'] . "<br>";
                $bandsDisplayed++;
                $displayedBandID[] = $b['id'];
            }
        }
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
            foreach ($bandsInGenre as &$big) {
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
                $bandOK = (doesBandHaveShape($big['id'], $shapeCode));
                //	echo "Does band have shape? $bandOK<br>";

                $bandOK = ($bandOK && !in_array($big['id'], $displayedBandID));
                //	echo "Is band in array? $bandOK<br>";

                if ($bandOK) {
//				echo $big['bandname']." is ok<br>";
                    $bandPicResult = getBandPicAndShape($big['id'], $shapeCode);
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

                    displayPic3($big['id'], $bandPicResult[0], $big['bandname']);
                    $bandsDisplayed++;
                    $bandDisplayedThisLoop = 1;
                    if ($bandsToDisplay == $bandsDisplayed) {
                        //	echo "Breaking Loop";
                        break(2);
                    }
                    $displayedBandID[] = $big['id'];
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
            foreach ($bandsInGenre as $leftOver) {
                if (!in_array($leftOver['id'], $displayedBandID)) {
                    displayPic4($leftOver['id'], $leftOver['bandname']);
                }
            }
        }
        echo "</div> <!-- end #genre" . $g['name'] . " -->";
    }



    ?>

    ?>

</div><!-- End #genrebands -->
