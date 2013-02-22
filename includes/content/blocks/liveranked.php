<?php

/* This block displays live ratings and comments for current band
*  
*/
If(!empty($band)){

echo "<div id=\"liveratings\">";
//First display any live ratings from this festival for this band
$sql = "select * from live_rating where band='$band' order by user";
echo $sql;
$res = mysql_query($sql, $main);
If(mysql_num_rows($res)>0) {
	echo "<table><tr><th>Time</th><th>User</th><th>Band</th><th>Rating</th><th>Comment</th>";
	while($row=mysql_fetch_array($res)) {
		$live_rater = getUname($master, $row['user']);
		$live_band = getBname($main, $row['band']);
		echo "<tr><td>".$row['time']."</td><td>".$live_rater."</td><td>".$live_band."</td><td>".$row['rating']."</td><td>".$row['comment']."</td></tr>";
	} 
	echo "</table>";
}



echo "<br /></div><!-- End #liveratings -->";

}
?>
