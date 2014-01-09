<?php
/*
//Copyright (c) 2013-2014 Jason Kennaly.
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

if (!empty($_POST['displayNumSelect'])) {
    setUserSetting($user, 74, $_POST['displayNumSelect']);
}

if (!empty($_POST['displayTiersSelect'])) {
    setUserSetting($user, 76, $_POST['displayTiersSelect']);
}

$displayNumSetting = getUserSetting($user, 74);
$displayNumOptions = getPermValuesForUserSetting(74);

$displayRatedSetting = getUserSetting($user, 75);
$displayRatedOptions = getPermValuesForUserSetting(75);

$displayTiersSetting = getUserSetting($user, 76);
$displayTiersOptions = getPermValuesForUserSetting(76);

?>

<div id="bandOptions">

    <form id="displayNumForm" method="POST" class="band-option">
        <select name="displayNumSelect" id="displayNumSelect" class="band-option">
            <?php
            foreach ($displayNumOptions as $o) {
                if ($displayNumSetting == $o['value']) {
                    ?>
                    <option selected="selected" value="<?php echo $o['value']; ?>">Show <?php echo $o['item']; ?>
                        bands
                    </option>
                <?php
                } else {
                    ?>
                    <option value="<?php echo $o['value']; ?>">Show <?php echo $o['item']; ?> bands</option>
                <?php
                }
            }
            ?>
        </select>
    </form>

    <form id="displayRatedForm" method="POST" class="band-option">
        <?php
        if ($displayRatedSetting == 1) {
            ?>
            <input type="submit" name="submitShowRated" value="Show bands I've rated" class="band-option"/>
        <?php
        } else {
            ?>
            <input type="submit" name="submitHideRated" value="Hide bands I've rated" class="band-option"/>
        <?php
        }
        ?>
    </form>

    <form id="displayTiersForm" method="POST" class="band-option">
        <select name="displayTiersSelect" id="displayTiersSelect" class="band-option">
            <?php
            foreach ($displayTiersOptions as $o) {
                if ($displayTiersSetting == $o['value']) {
                    ?>
                    <option selected="selected" value="<?php echo $o['value']; ?>"><?php echo $o['item']; ?></option>
                <?php
                } else {
                    ?>
                    <option value="<?php echo $o['value']; ?>"><?php echo $o['item']; ?></option>
                <?php
                }
            }
            ?>
        </select>
    </form>

</div>