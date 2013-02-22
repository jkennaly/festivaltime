<?php


/* In order for this block to work, page_variables.php must be included.
*/

//Get format for link



	$starttime=substr($stime, 11, 5);
	$endtime=substr($etime, 11, 5);

	echo "Clicking the band name will open a new window and search for the band. Change search engine from My Account -> User Settings.";
?>
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
<td><?php searchlink($band, $user, $main, $master); ?></td>
<td><?php echo $dayname; ?></td>
<td><?php echo $stagename; ?></td>
<td><?php echo $genrename; ?></td>
<td><?php echo round($rating, 1); ?></td>
<td><?php echo $starttime; ?></td>
<td><?php echo $endtime; ?></td>
</tr>
</table>

