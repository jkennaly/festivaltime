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

If(!empty($band)){

echo "<div id=\"liveratings\">";
//First display any live ratings from this festival for this band
$sql = "select * from live_rating where band='$band' order by user";
$res = mysql_query($sql, $main);
$sql2 = "select * from live_rating where band='$band_master_id' and user='$user' and festival!='$fest_id' order by time";
$res2 = mysql_query($sql2, $master);
If(mysql_num_rows($res)>0 || mysql_num_rows($res2)>0) {
	echo "<table><tr><th>Festival</th><th>Time</th><th>User</th><th>Band</th><th>Rating</th><th>Comment</th>";
	while($row=mysql_fetch_array($res)) {
		$live_rater = getUname($master, $row['user']);
		$live_band = getBname($main, $row['band']);
		echo "<tr><td>$fest_name $fest_year</td><td>".$row['time']."</td><td>".$live_rater."</td><td>".$live_band."</td><td>".$row['rating']."</td><td>".$row['comment']."</td></tr>";
	} 
	while($row2=mysql_fetch_array($res2)) {
		$live_rater = getUname($master, $row2['user']);
		$live_band = getBname($master, $row2['band']);
		$fest = getFname($master, $row2['festival']);
		echo "<tr><td>$fest</td><td>".$row2['time']."</td><td>".$live_rater."</td><td>".$live_band."</td><td>".$row2['rating']."</td><td>".$row2['comment']."</td></tr>";
	} 
	echo "</table>";
}



echo "<br /></div><!-- End #liveratings -->";

}
?>
