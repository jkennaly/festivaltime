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

<div id="bandgrid">


    <?php

    $right_required = "ViewNotes";
    If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
        die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
    }

    //Get the interesting factor for each band in the festival

    $interestingBands = getInterestingFactor($user, $main, $master, $fest);

    /*
    foreach ($interestingBands as $bandid => $if){
        echo "<br>".getBname($main, $bandid)." factor: ".$if;
    }
    */
    $picArray = array(
        array(0, 0, 0),
        array(0, 0, 0),
        array(0, 0, 0),
        array(0, 0, 0),
    );

    $arrayComplete = false;
    $x = 0;
    $y = 0;

    while (!$arrayComplete) {
        $total = array_sum($interestingBands);

        $select = rand(1, $total);

        reset($interestingBands);
        do {
            $select = $select - current($interestingBands);
            $chosen = key($interestingBands);
            $if = current($interestingBands);
            next($interestingBands);
        } while ($select > 0);
        prev($interestingBands);
        $interestingBands[key($interestingBands)] = 0;

        switch ($x) {
            case 0:
                switch ($y) {
                    case 0:
                        $shapeCode = 15;
                        break;
                    case 1:
                        $shapeCode = 15;
                        break;
                    case 2:
                        $shapeCode = 3;
                        break;
                    default:
                        break;
                }
                break;
            case 1:
                switch ($y) {
                    case 0:
                        if ($picArray[0][1] == 1) $shapeCode = 15;
                        else $shapeCode = 3;
                        break;
                    case 1:
                        if ($picArray[0][2] == 1) $shapeCode = 15;
                        else $shapeCode = 3;
                        break;
                    case 2:
                        $shapeCode = 3;
                        break;
                    default:
                        break;
                }
                break;
            case 2:
                switch ($y) {
                    case 0:
                        if ($picArray[1][1] == 1) $shapeCode = 15;
                        else $shapeCode = 3;
                        break;
                    case 1:
                        if ($picArray[1][2] == 1) $shapeCode = 15;
                        else $shapeCode = 3;
                        break;
                    case 2:
                        $shapeCode = 3;
                        break;
                    default:
                        break;
                }
                break;
            case 3:
                switch ($y) {
                    case 0:
                        if ($picArray[2][1] == 1) $shapeCode = 5;
                        else $shapeCode = 1;
                        break;
                    case 1:
                        if ($picArray[2][2] == 1) $shapeCode = 5;
                        else $shapeCode = 1;
                        break;
                    case 2:
                        $shapeCode = 1;
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }

//echo "<br>intMaster: $chosen shapecode: $shapeCode x: $x y: $y";
        $bandOK = doesBandHaveShape($chosen, $shapeCode);

        if ($bandOK) {
            $bandPicResult = getBandPicAndShape($chosen, $shapeCode);
            if ($bandPicResult[1] == "small_square") {
//	echo "<br>Found a small_square";
                $picArray[$x][$y] = 1;
            }
            if ($bandPicResult[1] == "large_square") {
//	echo "<br>Found a large_square";
                $picArray[$x][$y] = 1;
                $picArray[$x + 1][$y] = 1;
                $picArray[$x][$y + 1] = 1;
                $picArray[$x + 1][$y + 1] = 1;
            }
            if ($bandPicResult[1] == "horizontal_rectangle") {
//	echo "<br>Found a horizontal_rectangle";
                $picArray[$x][$y] = 1;
                $picArray[$x + 1][$y] = 1;
            }
            if ($bandPicResult[1] == "vertical_rectangle") {
//	echo "<br>Found a vertical_rectangle";
                $picArray[$x][$y] = 1;
                $picArray[$x][$y + 1] = 1;
            }

            /*
            $pgdisp =			"<a href=\"";
            $pgdisp .= $basepage."?disp=view_band&band=".$chosen."\"><img title = \"".getBname($main, $chosen);
            $pgdisp .= "\" class = \"bandgridpic\" src=\"".$basepage;
            $pgdisp .= "includes/content/blocks/getPicture3.php?pic=";
            $pgdisp .= $bandPicResult[0]."\" alt=\"band pic\" /></a>";
            echo $pgdisp;
            */
            displayPic3($chosen, $bandPicResult[0], getBname($chosen));


            while ($picArray[$x][$y] == 1) {
                $x = $x + 1;
                if ($x > 3) {
                    $x = 0;
                    $y = $y + 1;
                }
            }
            if (($y > 2)) $arrayComplete = true;

        }
    }


    //Fill the 12X12 grid bands to display


    //End of picture row


    ?>

</div><!-- End #bandgrid -->
