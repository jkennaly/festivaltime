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
    $right_required = "ViewNotes";
    If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
        die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
    }
    include($baseinstall . 'includes/content/blocks/accept_follow.php');
    If (!empty($_REQUEST["profileUser"])) {
        $_SESSION['profileUser'] = $_REQUEST["profileUser"];
    }

    if (!empty($_SESSION['profileUser'])) $profileUser = $_SESSION['profileUser'];
    $userList = getVisibleUsers($user);
    ?>
    <form action="index.php?disp=user_profile" method="post">
        Display a different user profile:
        <select name="profileUser">
            <?php
            foreach ($userList as $row) {
                echo "<option value=" . $row . ">" . getUname($row) . "</option>";
            }

            ?>
        </select>
        <input type="submit">
    </form>
    <?php
    if (!empty($profileUser)) {
        ?>
        <h2><?php echo getUname($profileUser); ?></h2>
        <?php
        displayScaledUserPic($profileUser);


        if ($profileUser == $user) {
            ?>
            <div class="picUpload">
                Upload a new user profile picture:
                <?php
                include $baseinstall . "includes/content/blocks/pic_user.php";
                ?>
            </div> <!-- end .picUpload -->
        <?php
        } else {

            ?>
            <?php
            drawFollowButtons($user, $profileUser);
            ?>

        <?php
        }
    }
    ?>


</div> <!-- end #content -->
