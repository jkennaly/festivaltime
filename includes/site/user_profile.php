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
        die("You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "\">FestivalTime</a>");
    }
    if (!empty($_POST['submitFollow'])) {
        $followee = $_POST['submitFollow'];
        $follower = $user;
        followUser($follower, $followee);
    }
    if (!empty($_POST['submitUnfollow'])) {
        $followee = $_POST['submitUnfollow'];
        $follower = $user;
        unFollowUser($follower, $followee);
    }
    if (!empty($_POST['submitBlock'])) {
        $blockee = $_POST['submitBlock'];
        $blocker = $user;
        blockUser($blocker, $blockee);
    }
    if (!empty($_POST['submitUnblock'])) {
        $blockee = $_POST['submitUnblock'];
        $blocker = $user;
        unBlockUser($blocker, $blockee);
    }
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
                echo "<option value=" . $row['id'] . ">" . $row['username'] . "</option>";
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
            <form action="index.php?disp=user_profile" method="post">
                <?php
                if (!userFollowsUser($user, $profileUser)) {
                    ?>
                    <button type="submit" name="submitFollow" value="<?php echo $profileUser; ?>">
                        Follow <?php echo getUname($profileUser); ?></button>
                <?php
                } else {
                    ?>
                    <button type="submit" name="submitUnfollow" value="<?php echo $profileUser; ?>">Stop
                        following <?php echo getUname($profileUser); ?></button>
                <?php
                }
                if (!userBlocksUser($user, $profileUser)) {
                    ?>
                    <button type="submit" name="submitBlock" value="<?php echo $profileUser; ?>">
                        Block <?php echo getUname($profileUser); ?></button>
                <?php
                } else {
                    ?>
                    <button type="submit" name="submitUnblock" value="<?php echo $profileUser; ?>">Stop
                        blocking <?php echo getUname($profileUser); ?></button>
                <?php
                }
                ?>

            </form>
        <?php
        }
    }
    ?>


</div> <!-- end #content -->
