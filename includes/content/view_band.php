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
    If (empty($band)) $band = "";

    //Process displaying band info
    If (!empty($_REQUEST["band"])) {

        $post_target = $basepage . "?disp=view_band&band=$band&fest=$fest";

        //Band Header
        include $baseinstall . "includes/content/blocks/band_info.php";

        //Toolbar
        include('includes/content/blocks/toolbar.php');

        //Comments
        include('includes/content/blocks/pregame_remarks.php');


    } else {
//If no band is passed through GET or POST, display a selector

        $bandList = getAllBandsInFest();
        ?>
        <form action="index.php?disp=view_band" method="post">
            <select name="band">
                <?php
                foreach ($bandList as $row) {
                    echo "<option value=" . $row . ">" . getBname($row) . "</option>";
                }
                ?>
            </select>
            <input type="submit">
        </form>
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
<script type="text/javascript" src="includes/js/discussion.js"></script>
