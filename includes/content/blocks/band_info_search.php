<?php


/* In order for this block to work, page_variables.php must be included.
*/

//Get format for link



	$starttime=substr($stime, 11, 5);
	$endtime=substr($etime, 11, 5);

//	echo "Clicking the band name will open a new window and search for the band. Change search engine from My Account -> User Settings.";
?>
<h1 id="bandtitle"><?php searchlink($band, $user, $main, $master); echo " ".ratingStars($band, $user, $main, "searchratingstars", $basepage."includes/images", $basepage); ?></h1>
<img id="band_pic_home" src="includes/content/blocks/getPicture.php?band=<?php echo $band; ?>&fest=<?php echo $_SESSION['fest']; ?>" alt="band pic" />


<p class="band_info"><?php echo $dayname; ?></p>
<p class="band_info"><?php echo $stagename; ?></p>
<p class="band_info"><?php echo $genrename; ?></p>
<p class="band_info"><?php echo "Group average rating: ".round($rating, 1); ?></p>
<p class="band_info"><?php echo $starttime."-".$endtime; ?></p>
<br />
