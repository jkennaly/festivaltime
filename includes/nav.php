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

<div id="navwrapper">


    <?php
    include $baseinstall . "includes/content/blocks/searchbox.php";
    ?>

    <ul id="nav" class="nav">

        <li><a href="<?php echo $basepage; ?>?disp=home&fest=0">Home</a>

            <?php
            $right_required = "EditFest";
            If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {
            ?>
        <li><a href="<?php echo $basepage; ?>?disp=festival_status">Manage Festivals</a></li>
        <?php
        }
        ?>

        <li><a href="<?php echo getForumLink($user, $mainforum, $forumblog); ?>">Forum</a></li>
        <?php
        //}
        ?>

    </ul>
    <!-- end #nav -->

</div> <!-- end #navwrapper -->