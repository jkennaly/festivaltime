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

include('includes/content/blocks/accept_rating.php');
include('includes/content/blocks/accept_comment.php');
include('includes/content/blocks/accept_link.php');

?>

<div id="iconrow">
    <?php

    $sql = "select comment from comments where band='$band' and user='$user'";
    $res = mysql_query($sql, $main);
    If (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $defcomment = $row['comment'];
    } else $defcomment = "";
    $commententry = "<div id=\"commententry\" style=\"display: none;\">";

    $priors = getFestivalsBandIsIn($band, $main, $master);
    $priorshown = 0;
    If (count($priors) > 1) {
        foreach ($priors as $v) {
            $sql = "select comment from comments where festival='" . $v . "' and user='" . $user . "' and band='" . $band_master_id . "' ";
            $res = mysql_query($sql, $master);
            $sqlr = "select rating from ratings where festival='" . $v . "' and user='" . $user . "' and band='" . $band_master_id . "' ";
            $resr = mysql_query($sqlr, $master);
            If (mysql_num_rows($res) > 0) {
                If ($priorshown == 0) $commententry .= "Select a previous festival to copy the comment from that show.<br />";
                $priorshown = 1;
                $comment_row = mysql_fetch_array($res);
                If (mysql_num_rows($resr) == 0) $rateline = "(Unrated)";
                else {
                    $rate_row = mysql_fetch_array($resr);
                    $rateline = " (" . $rate_row['rating'] . " Stars)";
                }

                $priorname = $header['sitename'];;
                $oldcomment = $priorname . "$rateline: " . $comment_row['comment'];
                $priorband = getFestBandIDFromMaster($band_master_id, $v, $master);
                $commententry .= "<div id=\"hid" . $v . "\" class=\"hiddentext\">$oldcomment</div>";
                If ($v != $fest) $commententry .= "<a href=\"#\" onclick=\"addText('commentarea', 'hid" . $v . "')\">" . $priorname . "</a><br />";
            }
        }
    }
    If ($priorshown == 1) $commententry .= "<div id=\"blank\" class=\"hiddentext\"></div><a href=\"#\" onclick=\"addText('commentarea', 'blank')\">Clear Text</a><br />";

    $commententry .= "<form action=\"index.php?disp=view_band&band=$band\" method=\"post\">";
    $commententry .= "<textarea rows=\"16\" cols=\"64\" name=\"new_comment\" id=\"commentarea\">$defcomment</textarea>";
    $commententry .= "<input type=\"submit\" value=\"Save comment\" />";
    $commententry .= "</form></div>";

    $sql = "select link, descrip from links where band='$band' and user='$user'";
    $res = mysql_query($sql, $main);
    If (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_array($res);
        $deflink = $row['link'];
        $defdescrip = $row['descrip'];
    } else {
        $deflink = "Link here";
        $defdescrip = "Description here";
    }
    $linkentry = "<div id=\"linkentry\" style=\"display: none;\">";
    $linkentry .= "<form action=\"index.php?disp=view_band&band=$band\" method=\"post\">";
    $linkentry .= "<textarea rows=\"4\" cols=\"64\" name=\"new_link\">$deflink</textarea>";
    $linkentry .= "<input type=\"text\" maxlength=\"25\" name=\"new_descrip\" value =\"$defdescrip\"/>";
    $linkentry .= "<input type=\"submit\" value=\"Save link\"/>";
    $linkentry .= "</form></div>";

    echo " " . ratingStars($band, $user, $main, "searchratingstars", $basepage . "includes/images", $basepage, $post_target);
    echo "<a href=\"#\" onclick=\"simpleToggle('commententry', 'commententry');return false;\"><img class=\"searchratingstars\" title=\"Comment on the band\" src=\"" . $basepage . "includes/images/comments.jpg\"></a>";
    echo "<a href=\"#\" onclick=\"simpleToggle('linkentry', 'linkentry');return false;\"><img class=\"searchratingstars\" title=\"Provide a link to the band\" src=\"" . $basepage . "includes/images/link.jpg\"></a>";


    echo "</div><!--End #iconrow -->";
    echo $commententry;
    echo $linkentry;
    ?>
