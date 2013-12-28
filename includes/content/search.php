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
    If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {


        include $baseinstall . "includes/content/blocks/searchbox.php";
        /*
        ?>
        <div id="searchbox">
        <form action="<?php echo $basepage."?disp=search"; ?>" method="post">
        <input type="text" size="64" name="search_query" autofocus="autofocus"></textarea>
        <br>
        <input type="checkbox" checked="checked" name="bands" value="true">Bands</input>
        <input type="checkbox" checked="checked" name="comments" value="true">Comments</input>
        <?php
        $temp_right = "CreateNotes";
        If(CheckRights($_SESSION['level'], $temp_right)) echo "<input type=\"checkbox\" checked=\"checked\" name=\"discussions\" value=\"true\">Discussions</input>";
        ?>
        <br>
        <input type="submit" value="Search">
        </form>
        </div> <!-- end #searchbox -->

        <?php
        */
        If (!empty($_POST['search_query'])) {
            $escapedQuery = mysql_real_escape_string($_POST['search_query']);

//Update the tracking columns in the comment table to reflect the activity
            If (!empty($_POST['bands'])) {

                $sql = "select `id`, `name` from `bands` where `name` like '%$escapedQuery%'";
                $result = mysql_query($sql, $master);
                If (mysql_num_rows($result) > 0) {
                    echo "<h4>The following bands were found like \"<i>$escapedQuery</i>\":</h4>";
                    include $baseinstall . "includes/content/blocks/display_bands.php";

                }
                If (mysql_num_rows($result) == 0) echo "No bands found like \"$escapedQuery\"<br>";
            } //Closes If($_POST['bands'])

            If (!empty($_POST['comments'])) {

                $sql = "select `messages`.`id` as id, `bands`.`id` as band, `content` as comment, `Users`.`username`, `bands`.`name` from `messages` left join `Users` on `messages`.`fromuser`=`Users`.`id` left join `bands` on `messages`.`band`=`bands`.`id` where comment like '%$escapedQuery%' and `remark`='2'";
                $result = mysql_query($sql, $master);
                If (mysql_num_rows($result) > 0) {
                    echo "<div id=\"commentlist\"><h4>The following comments were found like \"<i>$escapedQuery</i>\":</h4>";
                    while ($row = mysql_fetch_array($result)) {
                        echo "<p>Comment from " . $row['username'] . " about <a href=\"" . $basepage . "?disp=view_band&band=" . $row['band'] . "\">" . $row['name'] . "</a><br>" . $row['comment'] . "</p>";
                    } //Closes while($row=mysql_fetch_array($result))
                    echo "</div><!-- end #commentlist -->";
                } // Closes If(mysql_num_rows($result) > 0 )
                If (mysql_num_rows($result) == 0) echo "<div id=\"commentlist\"><p>No comments found like \"$escapedQuery\"</p></div><!-- end #commentlist -->";
            } //Closes If($_POST['comments'])


        } //Closes If($_POST['search_query'])


    } else {
        echo "This page requires a higher level access than you currently have.";

        include $baseinstall . "includes/site/login.php";

    }

    ?>
</div> <!-- end #content -->
