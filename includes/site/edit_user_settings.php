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


$right_required = "SiteAdmin";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}

if (!empty($_POST['submitChangedSettings'])) {
    foreach ($_POST['default'] as $setting => $value) setDefaultUserSettingValue($setting, $value);
    foreach ($_POST['newValue'] as $setting => $value) {
        if (!empty($value)) createPermissibleUserSettingValue($setting, $value);
    }
}
if (!empty($_POST['submitNewSetting'])) {
    createUserSetting($_POST['newSettingName'], $_POST['newSettingDescription'], $_POST['newSettingValueName']);
}
if (!empty($_POST['submitDeletedSetting'])) {
    deleteUserSetting($_POST['deleteSetting']);
}

?>

<div id="content">
    <form method="post">
        <input type="submit" name="submitChangedSettings" value="Update Current Settings">
        <?php
        $userSettings = getAllUserSettings();
        foreach ($userSettings as $uS) {
            echo "<h3>" . $uS['id'] . "-" . $uS['name'] . "</h3>";

            $currentDefault = getDefaultValueForUserSetting($uS['id']);
            $permItems = getPermValuesForUserSetting($uS['id']);
            echo "<h5>Current default is " . getUSItem($uS['id'], $currentDefault) . "</h5>";
            ?>
            Change default:
            <select name="default[<?php echo $uS['id']; ?>]">
                <?php
                foreach ($permItems as $row) {
                    if ($currentDefault != $row['value']) echo "<option value=" . $row['value'] . ">" . $row['item'] . "</option>";
                    else echo "<option selected=\"selected\" value=" . $row['value'] . ">" . $row['item'] . "</option>";
                }

                ?>
            </select><br/>
            <h5>Current Permissible values for this setting:</h5>
            <?php
            foreach ($permItems as $row) echo "Value " . $row['value'] . " is " . $row['item'] . "<br />";
            ?>
            Add a new value by typing it's name here:
            <input type="text" name="newValue[<?php echo $uS['id']; ?>]" size="30"/>
        <?php
        }

        ?>
        <br/><input type="submit" name="submitChangedSettings" value="Update Current Settings">
    </form>
    <br/>

    <form method="post">
        <h3>Add new setting</h3>
        New setting name: <input type="text" name="newSettingName" size="30"/><br/>
        New setting description: <input type="text" name="newSettingDescription" size="30"/><br/>
        New value name: <input type="text" name="newSettingValueName" size="30"/><br/>
        <input type="submit" name="submitNewSetting" value="Add New Setting">
    </form>
    <br/>

    <form method="post">
        <h3>Delete setting</h3>
        <select name="deleteSetting">
            <?php
            foreach ($userSettings as $uS) {
                echo "<option value=" . $uS['id'] . ">" . $uS['name'] . "</option>";
            }
            ?>
        </select>
        <br/><input type="submit" name="submitDeletedSetting" value="Delete Setting">
    </form>

</div> <!-- end #content -->