<?php


/* In order for this block to work, page_variables.php must be included.
*/

//Get format for link

include('includes/content/blocks/accept_rating.php');
include('includes/content/blocks/accept_comment.php');
include('includes/content/blocks/accept_link.php');

$starttime=substr($stime, 11, 5);
$endtime=substr($etime, 11, 5);
If($_GET['disp']=="view_band") $bandlink = searchlink($band, $user, $main, $master); 
else $bandlink = "<a href=\"".$basepage."?disp=view_band&band=".$band."\">".$name."</a>";

//	echo "Clicking the band name will open a new window and search for the band. Change search engine from My Account -> User Settings.";
?>
<h1 id="bandtitle">
<?php 
echo $bandlink; ?></h1>

<div id=bandvitals">

<a href="<?php echo $basepage."?disp=pic_band&band=".$band; ?>"><img id="band_pic_home" src="includes/content/blocks/getPicture.php?band=<?php echo $band; ?>&fest=<?php echo $_SESSION['fest']; ?>" alt="click to add a picture of the band" /></a>


<p class="band_info"><?php echo $dayname; ?></p>
<p class="band_info"><?php echo $stagename; ?></p>
<p class="band_info"><?php echo $genrename; ?></p>
<p class="band_info"><?php echo "Group average rating: ".round($rating, 1); ?></p>
<p class="band_info"><?php echo $starttime."-".$endtime; ?></p>
</div> <!-- end #bandvitals -->
<div id="iconrow">
<?php

$sql="select comment from comments where band='$band' and user='$user'";
$res=mysql_query($sql, $main);
If(mysql_num_rows($res)>0) {$row=mysql_fetch_array($res); $defcomment=$row['comment'];} else $defcomment="";
$commententry ="<div id=\"commententry\" style=\"display: none;\">";
$commententry .="<form action=\"index.php?disp=view_band&band=$band\" method=\"post\">";
$commententry .="<textarea rows=\"16\" cols=\"64\" name=\"new_comment\">$defcomment</textarea>";
$commententry .="<input type=\"submit\" value=\"Save comment\"></input>";
$commententry .="</form></div>";

$sql="select link, descrip from links where band='$band' and user='$user'";
$res=mysql_query($sql, $main);
If(mysql_num_rows($res)>0) {$row=mysql_fetch_array($res); $deflink=$row['link']; $defdescrip=$row['descrip'];} else {$deflink=""; $defdescrip="";}
$linkentry ="<div id=\"linkentry\" style=\"display: none;\">";
$linkentry .="<form action=\"index.php?disp=view_band&band=$band\" method=\"post\">";
$linkentry .="<textarea rows=\"4\" cols=\"64\" name=\"new_link\">$deflink</textarea>";
$linkentry .="<input type=\"text\" maxlength=\"25\" name=\"new_descrip\" value =\"$defdescrip\"></input>";
$linkentry .="<input type=\"submit\" value=\"Save link\"></input>";
$linkentry .="</form></div>";

echo " ".ratingStars($band, $user, $main, "searchratingstars", $basepage."includes/images", $basepage, $post_target); 
echo "<a href=\"#\" onclick=\"simpleToggle('commententry', 'commententry');return false;\"><img class=\"searchratingstars\" title=\"Comment on the band\" src=\"".$basepage."includes/images/comments.jpg\"></a>";
echo "<a href=\"#\" onclick=\"simpleToggle('linkentry', 'linkentry');return false;\"><img class=\"searchratingstars\" title=\"Provide a link to the band\" src=\"".$basepage."includes/images/link.jpg\"></a>";  


echo "</div><!--End #iconrow -->";
echo $commententry;
?>
