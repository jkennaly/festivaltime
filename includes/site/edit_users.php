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
    $right_required = "EditUsers";
    If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {


//First, find all users

        If (!empty($_POST["delete_user"])) {

            deleteUser($_POST["delete_user"]);
        }

        If (!empty($_POST["acl_radio"])) {
            $usermoded = "new_acl" . $_POST["acl_radio"];
            $sql = "UPDATE Users SET level='" . $_POST[$usermoded] . "' WHERE id = '" . $_POST["acl_radio"] . "'";
//		echo $sql;
            $upd = mysql_query($sql, $master);
        }

        If (!empty($_POST["change_password"])) {
            if (!empty($_POST["new_password"])) {

                $escapedPW = mysql_real_escape_string($_POST["new_password"]);
                $escapedID = mysql_real_escape_string($_POST["change_password"]);
                # generate a random salt to use for this account
                $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

                $saltedPW = $escapedPW . $salt;

                $hashedPW = hash('sha256', $saltedPW);

                $query = "UPDATE `Users` SET `hashedpw`='$hashedPW', `salt`='$salt' WHERE `id` = '" . $escapedID . "'";

                $upd = mysql_query($query, $master);
            } else echo "Password not changed; no new password entered.";
        }

        $query = "SELECT id, username, level FROM Users ORDER BY level ASC";
        $mem_result = mysql_query($query, $master);
        $acl_sql = "select * from access_levels";
        $acl_res = mysql_query($acl_sql, $master);
        ?>
        <p>

            This page shows all users who currently have access to the site, except for the currently logged in user.
            Only other users may be modified through this page.

        </p>
        <form action="index.php?disp=edit_users" method="post">
            <p>
                <input type="radio" name="delete_user" value="0" checked="checked">Do not delete any users
            </p>

            <p>
                <input type="radio" name="acl" value="0" checked="checked">Do not change any user access levels</p>

            <p>
                <input type="radio" name="change_password" value="0" checked="checked">Do not change any user passwords
            </p>

            <table border="1">
                <tr>
                    <th>username</th>
                    <th>access level</th>
                    <th>Change user access level</th>
                    <th>delete user</th>
                    <th>change password</th>
                </tr>

                <?php

                while ($row = mysql_fetch_array($mem_result)) {
                    echo "<tr><td>" . $row["username"] . "</td><td><select name=\"new_acl" . $row["id"] . "\">";
                    mysql_data_seek($acl_res, 0);
                    while ($acl_row = mysql_fetch_array($acl_res)) {
                        If ($acl_row['value'] == $row["level"]) echo "<option selected=\"selected\" value=\"" . $acl_row['value'] . "\">" . $acl_row['name'] . "</option>";
                        else echo "<option value=\"" . $acl_row['value'] . "\">" . $acl_row['name'] . "</option>";
                    }

                    echo "</td><td><input type=radio name=\"acl_radio\" value=" . $row["id"] . "></td>";

                    If ($row["username"] != $_SESSION['user']) {
                        echo '<td><input type="radio" name="delete_user" value="' . $row["id"] . '"></td>';
                    } else {
                        echo "<td></td>";
                    } // Closes If($row["username"] != $_SESSION['user'])

                    If ($row["username"] != $_SESSION['user']) {
                        echo '<td><input type="radio" name="change_password" value="' . $row["id"] . '"></td></tr>';
                    } else {
                        echo "<td></td></tr>";
                    } // Closes If($row["username"] != $_SESSION['user'])
                } //Closes while($row = mysql_fetch_array($mem_result))
                ?>

            </table>
            New password:
            <input size="30" type="text" name="new_password"><br/>
            <input type="submit">
        </form>
        <?php

        mysql_close();
    } else {
        echo "This page requires a higher level access than you currently have.";

        include $baseinstall . "includes/site/login.php";
    }

    ?>
</div> <!-- end #content -->
