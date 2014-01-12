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

?>
<div id="content">

    <?php


    If (!isset($_SESSION['level'])) {


        if (!empty($_POST['submitPWReset'])) {
            //check that the username and password is a valid combination
            $escapedEmail = mysql_real_escape_string($_POST['email']);
            $escapedName = mysql_real_escape_string($_POST['username']);
            $sql = "SELECT `id` FROM `Users` WHERE `email`='$escapedEmail' AND `username`='$escapedName' AND `deleted`!='1'";
            $res = mysql_query($sql, $master);
            if (mysql_num_rows($res) == 1) {
                $row = mysql_fetch_array($res);
                $id = $row['id'];


                //Generate an initial password
                $escapedPW = "";
                for ($i = 0; $i < 8; $i++) {
                    $escapedPW .= randAlphaNum();
                }

// generate a random salt to use for this account
                $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

//$salt = bin2hex(76);

                $saltedPW = $escapedPW . $salt;

                $hashedPW = hash('sha256', $saltedPW);

                $table = "Users";
                $cols = array("hashedpw", "salt");
                $vals = array($hashedPW, $salt);
                $where = "`id`='$id'";
                updateRow($table, $cols, $vals, $where);

                if ($id > 0) {
                    $to = $escapedEmail;
                    $subject = 'Festival Time Registration';
                    $message = "You have been registered for Festival Time! To login, just visit https://www.festivaltime.us and enter the username you used to register along with the password below. Once you log in, you can change your password by clicking on Change Password in the upper right.\r\n Your username is: " . $escapedName . "\r\n Your password is " . $escapedPW;
                    $headers = 'From: webmaster@festivaltime.us' . "\r\n" .
                        'Reply-To: festivaltime.us@gmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                    mail($to, $subject, $message, $headers);
                }

            }
        }


        if (empty($id)) {
            ?>

            <form method="post">
                username: <input type="text" name="username" maxlength="60" size="30"/>
                <br/>email: <input type="email" name="email" maxlength="100" size="45"/>
                <br/><input type="submit" name="submitPWReset"/>
            </form>
        <?php
        } else {
            ?>
            An email with your password has been sent to the email address you gave. Enter your email/password when you get it.
            If you don't get the email, send an email to <a
                href="mailto:festivaltime.us@gmail.com?subject=Login%20Trouble&body=User%20<?php echo $id; ?>">festivaltime.us@gmail.com</a>.
            <?php
            include $baseinstall . "includes/site/login.php";
        }

    } else {
        echo "You are already logged in!";
    }
    ?>
</div> <!-- end #content -->

