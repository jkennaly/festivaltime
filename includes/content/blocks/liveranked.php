<?php

/* This block displays live ratings and comments for current band
*  
*/
If(!empty($band)){

echo "<div id=\"liveratings\">";
//First display any live ratings from this festival for this band
$sql = "select * from live_rating where band='$band' order by user";
$res = mysql_query($sql, $main);
$sql2 = "select * from live_rating where band='$band_master_id' and user='$user' order by time";
$res2 = mysql_query($sql2, $master);
If(mysql_num_rows($res)>0 || mysql_num_rows($res2)>0) {
	echo "<table><tr><th>Festival</th><th>Time</th><th>User</th><th>Band</th><th>Rating</th><th>Comment</th>";
	while($row=mysql_fetch_array($res)) {
		$live_rater = getUname($master, $row['user']);
		$live_band = getBname($main, $row['band']);
		echo "<tr><td>$fest_name $fest_year</td><td>".$row['time']."</td><td>".$live_rater."</td><td>".$live_band."</td><td>".$row['rating']."</td><td>".$row['comment']."</td></tr>";
	} 
	while($row=mysql_fetch_array($res2)) {
		$live_rater = getUname($master, $row['user']);
		$live_band = getBname($main, $row['band']);
		$fest = getFname($master, $row['festival']);
		echo "<tr><td>$fest</td><td>".$row['time']."</td><td>".$live_rater."</td><td>".$live_band."</td><td>".$row['rating']."</td><td>".$row['comment']."</td></tr>";
	} 
	echo "</table>";
}



echo "<br /></div><!-- End #liveratings -->";

}
?>
