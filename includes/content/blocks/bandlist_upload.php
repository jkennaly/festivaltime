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

<div id="content">

    <?php
    $right_required = "EditFest";
    If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
        die("You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "\">FestivalTime</a>");
    }

    if (empty($_POST['submitFinal'])) unset($_SESSION['bandul']);
    If (!empty($_POST['submitFile']) || !empty($_POST['submitSingleBand'])) {
        If (!empty($_POST['submitFile'])) {
            if ($_FILES["file"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
                die('File upload error');
            }
            echo "Upload: " . $_FILES["file"]["name"] . "<br>";
            echo "Type: " . $_FILES["file"]["type"] . "<br>";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];

            $file = $_FILES["file"]["tmp_name"];
            // you might want to escape it just in case
            $lines = file($file, FILE_IGNORE_NEW_LINES);
        } else $lines = array($_POST['name']);
        foreach ($lines as $name) {
            $bandid = getBandIDFromName($name);
            if ($bandid) {
                $bandMatch['id'] = $bandid;
                $bandMatch['name'] = $name;
                $_SESSION['bandul']['bandMatch'][] = $bandMatch;
            } else {

                $possibleMatch = getSimilarBandNameFromName($name);
                if ($possibleMatch) {
                    foreach ($possibleMatch as $pM) $_SESSION['bandul']['possibleMatch'][$name][] = $pM;
                } else $_SESSION['bandul']['noMatch'][] = $name;

            }

        }

    }

    If (!empty($_POST['submitFinal'])) {
        $defpri = getDefaultBandPriority();

        //First figure out whether to add possible matches or use existing
        foreach ($_SESSION['bandul']['possibleMatch'] as $uploadedName => $b) {
            $matched = 0;
            foreach ($b as $possib) {
                if ($_POST[$uploadedName] == $possib['id']) {
                    $_SESSION['bandul']['bandMatch'][] = $possib;
                    $matched = 1;
                    break;
                }
            }
            if ($matched == 0) {
                $_SESSION['bandul']['noMatch'][] = $uploadedName;
            }
        }

        //Now add all the matched bands into the band list
        foreach ($_SESSION['bandul']['bandMatch'] as $matchBand) {
            $table = "band_list";
            $band = $matchBand['id'][0];
            //var_dump($matchBand);

            // Insert into database
            $cols = array("festival", "festival_series", "band", "priority");
            $vals = array($fest, $festSeries, $band, $defpri);
            insertRow($table, $cols, $vals);
        }

        //Add all unmatched bands into the database as bands and then in the list for this fest
        foreach ($_SESSION['bandul']['noMatch'] as $unmatchedBandName) {
            $table = "bands";

            // Insert into database
            $cols = array("name");
            $vals = array($unmatchedBandName);
            $band = insertRow($table, $cols, $vals);

            $table = "band_list";

            // Insert into database
            $cols = array("festival", "festival_series", "band", "priority");
            $vals = array($fest, $festSeries, $band, $defpri);
            insertRow($table, $cols, $vals);

        }
        unset($_SESSION['bandul']);

        $table = "festivals";
        $cols = array("band_list", "band_list_v");
        $vals = array($user, 0);
        $where = "`id`=$fest";
        updateRow($table, $cols, $vals, $where);
        $_SESSION['bandsAdded'] = 1;
    }


    if (isset($_SESSION['bandul'])) {
        ?>
        <form method="post" enctype="multipart/form-data">
            <input type="submit" name="submitFinal" value="Add Bands">

            <p>After clicking on the Add Bands button, the bands below labelled Not Matched will be added to the
                database. Bands labelled as Possible Matches
                will be individually either added or matched to existing bands. All bands will then be added to the band
                list for festival <?php echo $sitename; ?>.</p>

            <h2> Matched Bands:</h2>

            <?php
            foreach ($_SESSION['bandul']['bandMatch'] as $b) {
                echo $b['name'] . "<br />";
            }
            ?>

            <h2>Possible Matches:</h2>

            <?php
            foreach ($_SESSION['bandul']['possibleMatch'] as $uploadedName => $b) {


                echo "Given name: <b>" . $uploadedName . "</b><br />";
                echo '<input type="radio" name="' . $uploadedName . '" value="none">None of these match<br />';
                foreach ($b as $possib) {
                    echo '<input type="radio" name="' . $uploadedName . '" value="' . $possib['id'] . '">' . $possib['name'] . '<br />';
                }

            }
            ?>
        </form>
        <h2>Not Matched:</h2>

        <?php
        foreach ($_SESSION['bandul']['noMatch'] as $b) {
            echo $b . "<br />";
        }
    }

    if (!isset($_SESSION['bandul'])) {
        ?>

        <p>

            This page allows for uploading a band list for the festival. Band lists may be added by uploading a simple
            text
            file that contains one band name per line. No other information can be in the file, or the system will read
            it
            as part of a band name! Alternatively, yu can type in the name of a single band in the text box, and submit
            that.

        </p>


        <form method="post" enctype="multipart/form-data">
            <label for="file">Filename:</label><br/>
            <input type="file" name="file" id="file"><br/>
            <input type="submit" name="submitFile" value="Submit Band List File">
        </form>
        <br/><br/>
        <form method="post" enctype="multipart/form-data">
            <input size="30" type="text" name="name" value="Enter band name here"><br/>
            <input type="submit" name="submitSingleBand" value="Submit Single Band Name">
        </form>
    <?php
    }
    ?>

</div> <!-- end #content -->
