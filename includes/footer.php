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

<div id="footer">

    <p>Copyright &copy 2013-2014 Jason Kennaly</p>

    <p>This software operating this site is licensed under the AGPL v3. <br/><a
            href="<?php echo $basepage; ?>?disp=license">Click here for more information on copyright and licensing.</a>
    </p>

    <p><?php
        if (session_id() && !empty($_SESSION['user'])) {
            echo "User " . $_SESSION['user'] . " is currently logged in.<br>";
            echo "Current access level is " . $_SESSION['level'] . ".<br>";
            ?>
            Having trouble with the site? <a href="mailto:festivaltime.us@gmail.com">Send an email to admin</a>
        <?php
        } else {
            echo "No user is currently logged in.";
        }

        ?></p>
</div> <!-- end #footer -->

