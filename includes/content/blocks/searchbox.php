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
    <div id="searchbox">
        <form action="<?php echo $basepage . "?disp=search"; ?>" method="post">
            <input type="submit" value="Search">
            <input type="text" size="15" name="search_query" autofocus="autofocus">
            <input type="hidden" name="bands" value="true">
            <input type="hidden" name="comments" value="true">
            <?php
            $temp_right = "CreateNotes";
            If (!empty($_SESSION['level'])) {
                If (CheckRights($_SESSION['level'], $temp_right)) echo "<input type=\"hidden\" name=\"discussions\" value=\"true\">";
            }
            ?>

        </form>
    </div> <!-- end #searchbox -->

<?php
//End of searchbox

