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


<div id="sidebar-left">

    <div id="new-comments" class="widget">
        <?php
        $newCommentBands = getNewPregameCommentBands($user, $fest, 5);
        if (!empty($newCommentBands)) {
            ?>
            <h3 class="widget-title">New Comments</h3>
            <?php
            $displayed = array();
            foreach ($newCommentBands as $nCB) {
                if (!in_array($nCB, $displayed)) {
                    ?>
                    <a href="<?php echo $basepage; ?>?disp=view_band&band=<?php echo $nCB; ?>"><?php echo getBname($nCB); ?></a>
                    <br/>
                    <?php
                    $displayed[] = $nCB;
                }
            }
        }
        ?>

    </div>
    <!-- end #new-comments -->

    <div id="new-discussion" class="widget">
        <?php
        $newDiscussionBands = getNewPregameDiscussionBands($user, $fest, 5);
        if (!empty($newDiscussionBands)) {
            ?>
            <h3 class="widget-title">New Discussion</h3>
            <?php
            $displayed = array();
            foreach ($newDiscussionBands as $nCB) {
                if (!in_array($nCB, $displayed)) {
                    ?>
                    <a href="<?php echo $basepage; ?>?disp=view_band&band=<?php echo $nCB; ?>"><?php echo getBname($nCB); ?></a>
                    <br/>
                    <?php
                    $displayed[] = $nCB;
                }
            }
        }
        ?>

    </div>
    <!-- end #new-discussion -->
</div> <!-- end #sidebar -->