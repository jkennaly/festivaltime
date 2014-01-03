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


$right_required = "ModifySelf";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}
?>

<div id="content">
    <form method="post">
        <input type="submit" name="submitUpdateSettings" value="Update All Settings">
        <?php



        //If there is a POST['chg_setting'], change the value in the user_setting_template

        If (!empty($_POST['submitUpdateSettings'])) {
            foreach ($_POST['setting'] as $setting => $value) setUserSetting($user, $setting, $value);
        }

        $userSettings = getAllUserSettings();
        foreach ($userSettings as $uS) {
            echo "<h3>" . $uS['name'] . "</h3>";
            $currentSetting = getUserSetting($user, $uS['id']);
            $permItems = getPermValuesForUserSetting($uS['id']);
            echo "<h5>Current selection is " . getUSItem($uS['id'], $currentSetting) . "</h5>";
            ?>
            <select name="setting[<?php echo $uS['id']; ?>]">
                <?php
                foreach ($permItems as $row) {
                    if ($currentSetting != $row['value']) echo "<option value=" . $row['value'] . ">" . $row['item'] . "</option>";
                    else echo "<option selected=\"selected\" value=" . $row['value'] . ">" . $row['item'] . "</option>";
                }

                ?>
            </select>

        <?php
        }

        ?>
        <br/><input type="submit" name="submitUpdateSettings" value="Update All Settings">
    </form>
</div> <!-- end #content -->


