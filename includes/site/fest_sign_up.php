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

$right_required = "ViewNotes";
If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
    die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
}
?>
<div id="content">
    <?php

    if (!empty($_POST['purchaseFest'])) {
        $purchased = purchaseFest($user, $fest);
        if ($purchased) {
            echo "Sign up successful! Click the button to load the festival.<br />";
            echo '<button id="reload">Load the Festival</button>';
        } else {
            echo "Sign up unsuccessful! Click the button to return to home page.<br />";
            echo '<button id="cancelPurchase">Return to home page</button>';
        }
    }

    if (empty($_POST['purchaseFest'])) {
        ?>


        <h2>You are not currently signed up for this festival</h2>
        <h4>Festival Name: </h4><?php echo $header['sitename']; ?><br/>
        This festival costs <b><?php echo $header['cost']; ?></b> credits.<br/>
        You currently have <b><?php echo getCurrentCredits($user); ?></b> credits available.<br/>
        <form method="post">
            <button name="purchaseFest" type="submit" value="go">Sign up for festival</button>
        </form>
        <button id="cancelPurchase">Cancel</button>
    <?php
    }
    ?>


</div> <!-- end #content -->
<script type="text/javascript">
    <!--
    var basepage = "<?php echo $basepage; ?>";
    //-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/fest-purchase.js"></script>