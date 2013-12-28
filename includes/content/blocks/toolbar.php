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

global $fest;

include('includes/content/blocks/accept_rating.php');
include('includes/content/blocks/accept_comment.php');
include('includes/content/blocks/accept_link.php');

?>

<div id="iconrow">
    <?php

    $mode = 1;
    $defcomment = getUserCommentOnBandForFest($user, $band, $fest, $mode);
    $link = getUserLinkOnBandForFest($user, $band, $fest, $mode);
    $commententry = "<div id=\"commententry\" style=\"display: none;\">";

    $priors = getFestivalsBandIsIn($band);
    $priorshown = 0;
    If (count($priors) > 1) {
        foreach ($priors as $v) {
            $festComment = getUserCommentOnBandForFest($user, $band, $v, $mode);
            $festHeader = getFestHeader($v);
            $festRating = getUserRatingOnBandForFest($user, $band, $v, $mode);
            If (!empty($festComment)) {
                If ($priorshown == 0) $commententry .= "Select a previous festival to copy the comment from that show.<br />";
                $priorshown = 1;

                If (empty($festRating)) $rateline = "(Unrated)";
                else {
                    $rate_row = mysql_fetch_array($resr);
                    $rateline = " (" . $festRating . " Stars)";
                }

                $priorname = $festHeader['sitename'];
                $oldcomment = $priorname . "$rateline: " . $festComment;
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
    If (!empty($link)) {
        $deflink = $link['link'];
        $defdescrip = $link['descrip'];
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

    echo " " . ratingStars($band, $user, "searchratingstars", $basepage . "includes/images", $post_target, 1);
    echo "<a href=\"#\" onclick=\"simpleToggle('commententry', 'commententry');return false;\"><img class=\"searchratingstars\" title=\"Comment on the band\" src=\"" . $basepage . "includes/images/comments.jpg\"></a>";
    echo "<a href=\"#\" onclick=\"simpleToggle('linkentry', 'linkentry');return false;\"><img class=\"searchratingstars\" title=\"Provide a link to the band\" src=\"" . $basepage . "includes/images/link.jpg\"></a>";


    echo "</div><!--End #iconrow -->";
    echo $commententry;
    echo $linkentry;
    ?>
