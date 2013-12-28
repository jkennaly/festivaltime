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
    $right_required = "ModifySelf";
    If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {


        ?>
        <p>

            This page allows users to change their own password.

        </p>
        <form action="index.php?disp=change_password" method="post">
            <table border="1">
                <tr>
                    <th>password</th>
                    <th>re-enter password</th>
                </tr>

                <tr>
                    <td>
                        <input type="password" name="password1" maxlength="30" size="30">
                    </td>
                    <td>
                        <input type="password" name="password2" maxlength="30" size="30">
                    </td>
                </tr>

            </table>
            <input type="submit">
        </form>
<?php

//Once the information is submitted, store it in the database
        If ($_POST) {

//Verify that the password was correctly entered twice

            $escapedPW1 = mysql_real_escape_string($_POST['password1']);
            $escapedPW2 = mysql_real_escape_string($_POST['password2']);

            If ($escapedPW1 != $escapedPW2) {
                echo "The passwords do not match. Please try again.";
            } else {
                $escapedPW = $escapedPW1;
                $escapedName = $_SESSION['user'];


# generate a random salt to use for this account
                $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

                $saltedPW = $escapedPW . $salt;

                $hashedPW = hash('sha256', $saltedPW);

                $query = "UPDATE Users SET hashedpw='$hashedPW', salt='$salt' WHERE username = '$escapedName'";

                $upd = mysql_query($query, $master);

            }
        }

        mysql_close();
    } else {
        echo "This page requires a higher level access than you currently have.";

        include $baseinstall . "includes/site/login.php";
    }

    ?>
</div> <!-- end #content -->
