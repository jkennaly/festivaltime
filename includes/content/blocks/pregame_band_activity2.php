<?php

//Get current users comment, if they have one
$sql = "select id, comment, user from comments where user='$user' and band='$band'";
$res=mysql_query($sql, $main);

If(mysql_num_rows($res)>0) {
	$row=mysql_fetch_array($res); 
	$discuss_table= "discussion_".$row['id'];
	$sql = "select d.id, d.response as reply, d.created as time, d.user as user from $discuss_table as d";
	$res1 = mysql_query($sql, $main);
	If(mysql_num_rows($res1)>0) {
		echo "<h2>".getUname($master, $row['user'])."</h2>";
		echo"<h3><a id=\"displayText$user\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$user."', 'displayText".$user."');return false;\">show discussion</a>".$row['comment']."</h3><div id=\"toggleText$user\" style=\"display: none;\">";
		while($row1 = mysql_fetch_array($res1)) {
			echo "<p class=\"responder\">".getUname($master, $row['user'])." at ".$row1['time']."<p><p id=\"reply\">".$row1['reply']."</p>";
		} //Closes while($row = mysql_fetch_array($res))
		echo "<br /><a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Reply to this discussion</a></div>";
	} else {
		echo "<h2>".getUname($master, $row['user'])."</h2>";
		echo "<h3>".$row['comment']."</h3>";
		echo "<br /><a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Start a discussion</a></div>";
	}
}
//Get user comments from group members




//Get all other user comments
//Get current users comment, if they have one
$sql = "select id, comment, user from comments where user!='$user' and band='$band'";
$res=mysql_query($sql, $main);

If(mysql_num_rows($res)>0) {
	while($row=mysql_fetch_array($res)) { 
		$discuss_table= "discussion_".$row['id'];
		$sql = "select d.id, d.response as reply, d.created as time, d.user as user from $discuss_table as d";
		$res1 = mysql_query($sql, $main);
		If(mysql_num_rows($res1)>0) {
			$i=0;
			while($row1 = mysql_fetch_array($res1)) {
				If($i==0) {
					echo "<h2>".getUname($master, $row['user'])."</h2>";
					echo"<h3><a id=\"displayText".$row['user']."\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$row['user']."', 'displayText".$row['user']."');return false;\">show discussion</a>".$row['comment']."</h3><div id=\"toggleText".$row['user']."\" style=\"display: none;\">";
				}
				echo "<p class=\"responder\">".getUname($master, $row1['user'])." at ".$row1['time']."<p><p>".$row1['reply']."</p>";
				$i++;
			} //Closes while($row = mysql_fetch_array($res))
			echo "<br /><a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Reply to this discussion</a></div>";
		} else  {
			echo "<h2>".getUname($master, $row['user'])."</h2>";
			echo "<h3>".$row['comment']."</h3>";
			echo "<br /><a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Start a discussion</a></div>";
		}
	}
}

?>