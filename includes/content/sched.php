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
date_default_timezone_set('UTC');
$festDays = getAllDays();
$festStages = getAllStages();
$stageWidth = 95 / (1 + count($festStages));
$heightFactor = 4;
$textHeight = $heightFactor * 2.5;
$borderWidth = 1;
?>

<div id="content">
    <style>
        div.sidebar {
            display: none;
        }

        div#content {
            width: 100%;
        }
    </style>
    <?php

    foreach ($festDays as $fD) {
        ?>
        <div id="day-<?php echo $fD['id']; ?>" class="festSchedDay">
            <h3><?php echo $fD['name']; ?></h3>

            <div id="day-<?php echo $fD['id']; ?>-time" class="festSchedStage"
                 style="border:2px solid;width:<?php echo $stageWidth; ?>%;">
                <div class="stageName">Time</div>
                <!-- end .stageName -->
                <?php
                $maxTime = $header['start_time'] + $header['length'];
                for ($i = $header['start_time']; $i < $maxTime; $i = $i + 3600) {
                    $setHeight = ((60 * $heightFactor) / 5) - 2 * $borderWidth;
                    ?>
                    <div id="time-<?php echo $i; ?>; ?>" class="festSchedSet"
                         style="border:<?php echo $borderWidth; ?>px solid;height:<?php echo $setHeight; ?>px;">
                        <?php
                        echo strftime('%l:%M %p', $i);
                        ?>
                    </div> <!--end #time-<?php echo $i; ?> -->
                <?php
                }
                ?>
            </div>
            <!--end #day-<?php echo $fD['id']; ?>-time -->

            <?php

            foreach ($festStages as $fS) {
                $setList = getBandsByDayAndStage($fD['id'], $fS['id']);
                $setCount = count($setList);
                ?>
                <div id="day-<?php echo $fD['id']; ?>-stage-<?php echo $fS['id']; ?>" class="festSchedStage"
                     style="border:2px solid;width:<?php echo $stageWidth; ?>%;">
                    <div class="stageName"><?php echo $fS['name']; ?></div>
                    <!-- end .stageName -->
                    <?php
                    for ($i = 0; $i < $setCount; $i++) {
                        $set = $setList[$i];

                        if ($i == 0) {
                            $spaceTime = $set['start'] / 60;
                        } else {
                            $iPrev = $i - 1;
                            $spaceTime = ($set['start'] - $setList[$iPrev]['end']) / 60;
                        }
                        $spaceHeight = ($spaceTime * $heightFactor) / 5;

                        $minInSet = ($set['end'] - $set['start']) / 60;
                        $setHeight = ($minInSet * $heightFactor) / 5 - 2 * $borderWidth;
                        $score = uscoref2($set['band'], $user);
                        if ($score > 4){
                            $scoreClass = "score-green";
                            $scoreColor = "green";
                        }
                        elseif ($score < 3 && $score > 0) {
                            $scoreClass = "score-red";
                            $scoreColor = "red";
                        }
                        elseif ($score > 0 ) {
                            $scoreClass = "score-yellow";
                            $scoreColor = "yellow";
                        }
                        else {
                            $scoreClass = "score-unknown";
                            $scoreColor = "LightBlue";
                        }
                        ?>
                        <div class="spacer" style="border:none;height:<?php echo $spaceHeight; ?>px;">
                        </div> <!--end .spacer -->
                        <a href="<?php echo $basepage; ?>?disp=view_band&band=<?php echo $set['band']; ?>&fest=<?php echo $fest; ?>">
                        <a href="<?php echo $basepage; ?>"?disp=view_band&band=>
                        <div id="set-<?php echo $set['id']; ?>; ?>" class="festSchedSet"
                             style="border:<?php echo $borderWidth; ?>px solid;height:<?php echo $setHeight; ?>px;background-color:<?php echo $scoreColor; ?>;">
                            <span
                                style="font-size:<?php echo $textHeight; ?>px;vertical-align:middle;line-height: <?php echo $setHeight / 2; ?>px;text-align: center;width:100%;">
                            <?php
                            echo getBname($set['band']);
                            ?>
                                </span>
                        </div> <!--end #set-<?php echo $set['id']; ?> --></a>
                    <?php
                    }
                    ?>
                </div> <!--end #day-<?php echo $fD['id']; ?>-stage-<?php echo $fS['id']; ?> -->
            <?php
            }
            ?>
        </div> <!--end #day-<?php echo $fD['name']; ?> -->
    <?php
    }

    ?>
</div> <!-- end #content -->
