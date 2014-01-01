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

if (!empty($_POST['submitNewParent'])) {
    $newParent = $_POST['newParent'];
    setParentGenre($newParent, $newParent);
    addParentGenre($newParent);
}

if (!empty($_POST['submitNewGenre'])) {
    $newGenre = $_POST['newGenre'];
    $parentGenre = $_POST['parentGenre'];
    addGenre($newGenre, $parentGenre);
}

if (!empty($_POST['submitSelectedParent'])) {
    foreach ($_POST['selectedParent'] as $g => $p) {
        setParentGenre($g, $p);
    }
}

$genreList = getAllGenres();
$parentGenreList = getParentGenres();

?>


<div id="content">

    Placeholder for managing genres.

    Current parent genres:<br/>
    <?php
    foreach ($parentGenreList as $p) {
        echo getGname($master, $p) . "<br />";
    }

    //Add a new genre
    ?>
    <div id="addNewGenre">
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="newGenre" value="New genre name"/>
            <select name="parentGenre">
                <?php
                foreach ($parentGenreList as $pG) {
                    echo '<option value="' . $pG . '">' . getGname($master, $pG) . '</option>';
                }
                ?>
            </select>
            <input type="submit" name="submitNewGenre" value="Submit new genre"/>
        </form>
    </div>
    <?php

    //Set a genre as a parent
    ?>
    <div id="setParentGenre">
        <form method="post" enctype="multipart/form-data">
            <select name="newParent">
                <?php
                foreach ($genreList as $g) {
                    if (!in_array($g['id'], $parentGenreList)) echo '<option value="' . $g['id'] . '">' . $g['name'] . '</option>';
                }
                ?>
            </select>
            <input type="submit" name="submitNewParent" value="Submit new parent genre"/>
        </form>

    </div>
    <?php

    //Change existing parent genres

    ?>
    <div id="setParents">
        <form method="post" enctype="multipart/form-data">
            <?php
            foreach ($genreList as $g) {
                echo $g['name'];
                ?>

                <select name="selectedParent[<?php echo $g['id'] ?>]">
                    <?php
                    foreach ($parentGenreList as $pG) {
                        if ($g['parent'] != $pG) echo '<option value="' . $pG . '">' . getGname($master, $pG) . '</option>';
                        else echo '<option selected="selected" value="' . $pG . '">' . getGname($master, $pG) . '</option>';
                    }
                    ?>
                </select><br/>
            <?php
            }
            ?>
            <input type="submit" name="submitSelectedParent" value="Submit all parent genres"/>
        </form>

    </div>
    <?php



    ?>
</div> <!-- end #content -->


<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>