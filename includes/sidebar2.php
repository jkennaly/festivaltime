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
            if (!in_array($u, $followingUsers)) {
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


</div> <!-- end #sidebar2 -->

<?php
$output = ob_get_contents();
$file = $baseinstall . "external/cache-sidebar2.txt";
file_put_contents($file, $output);
ob_flush();


?>



