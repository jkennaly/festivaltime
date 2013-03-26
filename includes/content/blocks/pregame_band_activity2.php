<?php

//Get current users comment, if they have one
$sql = "select id, comment from comments where user='$user' and band='$band'";
$res=mysql_query($sql, $main);

If(mysql_num_rows($res)>0) {
	$row=mysql_fetch_array($res); 
	$discuss_table= "discussion_".$row['id'];
	echo"<h3><a id=\"displayText\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle(toggleText, displayText);return false;\">show discussion</a>".$row['comment']."</h3><div id=\"toggleText\" style=\"display: none;\">";
	$sql = "select d.id, d.response as reply, d.created as time, d.user as user from $discuss_table as d";
	$res = mysql_query($sql, $main);
while($row = mysql_fetch_array($res)) {
	echo "<p class=\"responder\">".getUname($master, $row['user'])." at ".$row['time']."<p><p id=\"reply\">".$row['reply']."</p>";
} //Closes while($row = mysql_fetch_array($res))
	echo "</div>";
}
//Get user comments from group members




//Get all other user comments


?>