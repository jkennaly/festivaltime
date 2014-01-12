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


    If (!isset($_SESSION['level'])) {

//Once the information is submitted, store it in the database
        If ($_POST) {

//Escape entered info

            $escapedName = mysql_real_escape_string($_POST['username']);
            $escapedName = $str = preg_replace('/\s+/', '', $escapedName);
            //        $escapedPW = mysql_real_escape_string($_POST['password']);
            $escapedEmail = mysql_real_escape_string($_POST['email']);
            $credentials_version = "2"; //Credentials v1 is the first version to incorporate keys and the credited field

//Verify that the username is not already taken

            $query = "select * from Users where username='$escapedName' OR email='$escapedEmail'";
            $pwq = mysql_query($query, $master);
            $num = mysql_num_rows($pwq);


//Validation
            $failedValidation = 0;
            If ($num != 0) {
                echo "That username or email address is not unique. User not created. Try logging in or resetting your password if you forgot it.";
                $failedValidation = 1;
            }
            If (strlen($escapedName) < 4) {
                echo "Please choose a username that is at least 4 characters.";
                $failedValidation = 1;
            }
            /*
            If (strlen($escapedPW) < 4) {
                echo "Please choose a password that is at least 4 characters.";
                $failedValidation = 1;
            }
            */
            If (in_string($outlawcharacters, $escapedName)) {
                echo "You may not use special characters in your username.";
                $failedValidation = 1;
            }
            If (email_bad($escapedEmail)) {
                echo "$escapedEmail email is not valid.";
                $failedValidation = 1;
            }

            if (!$failedValidation) {
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

                $query = "insert into Users (username, hashedpw, salt, level, email, credits)";
                $query .= " values ('$escapedName', '$hashedPW', '$salt', 'member', '$escapedEmail', 25); ";
//        echo $query;
                $id = mysql_query($query, $master);

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

        <form action="index.php?disp=register" method="post">
            <table border="1">
                <tr>
                    <th>username</th>
                </tr>

                <tr>
                    <td>
                        <input type="text" name="username" maxlength="60" size="30"/>
                    </td>
                </tr>

                <tr>
                    <th>email</th>
                </tr>

                <tr>
                    <td>
                        <input type="email" name="email" maxlength="100" size="45"/>
                    </td>
                </tr>

            </table>

            <input type="submit">
        </form>
    <?php
        } else {
            ?>
            <div id="wrapper">
                An email with your password has been sent to the email address you gave. Enter your email/password when
                you get it.
            </div>
            <?php
            include $baseinstall . "includes/site/login.php";
        }

    } else {
        echo "You are already logged in!";
    }
    ?>
</div> <!-- end #content -->

