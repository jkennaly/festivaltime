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

$activeBands = getActiveBands();
$bandList = "";
foreach ($activeBands as $b) {
    $fests = getFestivalsBandIsIn($b);
    $bandList .= '<li class="popular-item">';
    $bandList .= '<a href="' . $basepage . '?disp=view_band&band=' . $b . '&fest=' . $fests['0'] . '" >' . getBname($b) . '</a>';
    $bandList .= '</li>';
}

$newFests = getNewFestivals();


ob_start();
?>


<div id="sidebar2" class="sidebar">

    <aside id="account-management-widget" class="widget">
        <h3 class="wideget-title">My Account</h3>
        <ul class="popular-list">
            <li class="popular-item"><a href="<?php echo $basepage; ?>?disp=login">Log In</a></li>
            <li class="popular-item"><a href="<?php echo $basepage; ?>?disp=logout">Log Out</a></li>
            <li class="popular-item"><a href="<?php echo $basepage; ?>?disp=change_password">Change Password</a></li>
            <li class="popular-item"><a href="<?php echo $basepage; ?>?disp=user_settings">User Settings</a></li>
            <li class="popular-item"><a href="<?php echo $basepage; ?>?disp=user_profile">My Profile</a></li>
        </ul>
        <!-- end #account-management-widget -->


        <aside id="popular-bands-widget" class="widget">
        <h3 class="wideget-title">Popular Bands</h3>
        <ul class="popular-list">
            <?php echo $bandList; ?>
        </ul>
    </aside>

    <aside id="popular-users-widget" class="widget">
        <?php
        $followingUsers = getFollowedBy($user);
        $visibleUsers = getVisibleUsers($user);
        $followerCount = array();
        foreach ($visibleUsers as $u) {
            if (empty($followingUsers) || !in_array($u, $followingUsers)) {
                $followerCount[$u] = count(getUsersFollowing($u, $user));
            }
        }
        arsort($followerCount);
        $i = 0;

        ?>
        <h3 class="wideget-title">Popular Users</h3>
        <ul class="popular-list">
            <?php
            foreach ($followerCount as $u => $count) {
                if ($u == $user) continue;
                ?>
                <li class="popular-item">
                    <a href="<?php echo $basepage; ?>?disp=user_profile&profileUser=<?php echo $u; ?>">
                    <?php echo getUname($u); ?></li>
                </a>
                <?php

                $i++;
                if ($i > 2) break;
            }
            ?>
        </ul>
    </aside>

    <aside id="recent-fests-widget" class="widget">
        <h3 class="wideget-title">Newly Added Fests</h3>
        <ul class="popular-list">
            <?php
            foreach ($newFests as $c) {
                echo "<li class=\"popular-item\"><a href=\"" . $basepage . '?disp=home&fest=' . $c['id'] . "\">" . $c['sitename'] . "</a></li>";
            }
            ?>
        </ul>
    </aside>

    <aside id="bands-need-pics-widget" class="widget">
        <?php
        if (!checkIfAllBandsHavePics()) {
            ?>
            <a href="<?php echo $basepage; ?>?disp=find_band_pics">Bands missing pictures</a>
        <?php
        }
        ?>
    </aside>

    <aside id="bands-pics-need-review-widget" class="widget">
        <?php
        if (!checkIfAllBandPicsReviewed()) {
            ?>
            <a href="<?php echo $basepage; ?>?disp=crop_image">Bands pictures require cropping</a>
        <?php
        }
        ?>
    </aside>


</div> <!-- end #sidebar2 -->

<?php
$output = ob_get_contents();
$file = $baseinstall . "external/cache-sidebar2.txt";
file_put_contents($file, $output);
ob_flush();


?>



