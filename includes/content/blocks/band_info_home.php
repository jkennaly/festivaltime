<?php


/* In order for this block to work, page_variables.php must be included.
*/



	$starttime=substr($stime, 11, 5);
	$endtime=substr($etime, 11, 5);

//	echo "Clicking the band name will take you to the details page for that band.";
?>
<img src="includes/content/blocks/getPicture.php?band=<?php echo $band; ?>&fest=<?php echo $_SESSION['fest']; ?>" alt="band pic" />
<table class="bandinfotable">
<tr>
<th>band name</th>
<th>day</th>
<th>stage</th>
<th>genre</th>
<th>average rating</th>
<th>start time</th>
<th>end time</th>
</tr>
<tr>
<td><?php 
echo "<a href=\"".$basepage."?disp=view_band&band=".$band."\">".$name."</a>"; 
?></td>
<td><?php echo $dayname; ?></td>
<td><?php echo $stagename; ?></td>
<td><?php echo $genrename; ?></td>
<td><?php echo round($rating, 1); ?></td>
<td><?php echo $starttime; ?></td>
<td><?php echo $endtime; ?></td>
</tr>
</table>

