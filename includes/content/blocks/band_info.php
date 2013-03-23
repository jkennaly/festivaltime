<?php


/* In order for this block to work, page_variables.php must be included.
*/

//Get format for link

include('includes/content/blocks/accept_rating.php');

$starttime=substr($stime, 11, 5);
$endtime=substr($etime, 11, 5);
If($_GET['disp']=="view_band") $bandlink = searchlink($band, $user, $main, $master); 
else $bandlink = "<a href=\"".$basepage."?disp=view_band&band=".$band."\">".$name."</a>";

//	echo "Clicking the band name will open a new window and search for the band. Change search engine from My Account -> User Settings.";
?>
<h1 id="bandtitle">
<?php 
echo $bandlink; 
echo " ".ratingStars($band, $user, $main, "searchratingstars", $basepage."includes/images", $basepage, $post_target); 
echo "<a href=\"".$basepage."?disp=comment_band&band=".$band."\"><img class=\"searchratingstars\" src=\"".$basepage."includes/images/comments.jpg\"></a>";
echo "<a href=\"".$basepage."?disp=link_band&band=".$band."\"><img class=\"searchratingstars\" src=\"".$basepage."includes/images/link.jpg\"></a>";  
?></h1>
<a href="<?php echo $basepage."disp=pic_band&band=".$band; ?>"><img id="band_pic_home" src="includes/content/blocks/getPicture.php?band=<?php echo $band; ?>&fest=<?php echo $_SESSION['fest']; ?>" alt="click to add a picture of the band" /></a>


<p class="band_info"><?php echo $dayname; ?></p>
<p class="band_info"><?php echo $stagename; ?></p>
<p class="band_info"><?php echo $genrename; ?></p>
<p class="band_info"><?php echo "Group average rating: ".round($rating, 1); ?></p>
<p class="band_info"><?php echo $starttime."-".$endtime; ?></p>
<br />
