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
$right_required = "EditFest";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "\">FestivalTime</a>");
}

?>


<div id="content">
    <?php

    ?>

    <div class="messageform">
        <div class="messageformheader">
            <h2>Send Message</h2>
        </div>
        <!-- end .messageformheader -->
        <div class="messageformprivacy">
            Choose Privacy:
            <?php
            $privacies = getMessagePrivacies($master);
            echo "<select name=\"privacy\">";
            foreach ($privacies as $p) {
                echo "<option value=\"" . $p['id'] . "\">" . $p['name'] . "</option>";
            }
            echo "</select>";
            ?>
            <button id="setprivaybutton" type="button">Set Privacy</button>
        </div>
        <!-- end .messageprivacy -->
        <div class="messagecontexts">
            <div id="context-selector-1" class="messagecontextselector">
                Context Selector 1
            </div>
            <!-- end #context-selector-1 -->
            <div id="context-selector-2" class="messagecontextselector">
                Context Selector 2
            </div>
            <!-- end #context-selector-2 -->

        </div>
        <!-- end .messagecontexts -->

    </div>
    <!-- end .messageform -->

    <script src="includes/js/jquery-1.9.1.min.js"></script>
    <script>
        var basepage = "<?php echo $basepage; ?>";
    </script>
    <script type="text/javascript" src="includes/js/messages.js"></script>
</div> <!-- end #content -->

