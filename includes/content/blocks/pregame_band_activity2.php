<?php

//Get current users comment, if they have one
$sql = "select id, comment, user from comments where user='$user' and band='$band'";
$res=mysql_query($sql, $main);

If(mysql_num_rows($res)>0) {
	$row=mysql_fetch_array($res); 
	$discuss_table= "discussion_".$row['id'];
	$sql = "select d.id, d.response as reply, d.created as time, d.user as user from $discuss_table as d";
	$res1 = mysql_query($sql, $main);
	$leftcell= "<h2>".getUname($master, $row['user'])."</h2>";
	If(mysql_num_rows($res1)>0) {
		$leftcell.="<a id=\"displayText$user\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$user."', 'displayText".$user."', $user, ".$row['comment'].");return false;\">show discussion</a>";
		$rightcell = "<h3>".$row['comment']."</h3>";
		$discuss ="<div id=\"toggleText$user\" style=\"display: none;\">";
		while($row1 = mysql_fetch_array($res1)) {
			$discuss .= "<p class=\"responder\">".getUname($master, $row1['user'])." at ".$row1['time']."<p><p id=\"reply\">".$row1['reply']."</p>";
		} //Closes while($row = mysql_fetch_array($res))
		$discuss .= "<br /><a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Reply to this discussion</a></div>";
	} else {
		$rightcell = "<div><h3>".$row['comment']."</h3>";
		$leftcell.= "<a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Start a discussion</a></div>";
		$discuss="";
	}
	echo "<table class=\"commentstable\" id=\"comment".$row['user']."\"><tr><th>$leftcell</th><td>$rightcell</td></tr></table>";
	echo $discuss;
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
					echo "<h2><br />".getUname($master, $row['user'])." ".displayStars($band, $row['user'], $main, "displaystars", $basepage."includes/images")."</h2>";
		If(mysql_num_rows($res1)>0) {
			$i=0;
			while($row1 = mysql_fetch_array($res1)) {
				If($i==0) {
					echo"<h3><a id=\"displayText".$row['user']."\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$row['user']."', 'displayText".$row['user']."', '".$row['user']."', '".$row['comment']."');return false;\">show discussion</a>".$row['comment']."</h3><div id=\"toggleText".$row['user']."\" style=\"display: none;\">";
				}
				echo "<p class=\"responder\">".getUname($master, $row1['user'])." at ".$row1['time']."<p><p>".$row1['reply']."</p>";
				$i++;
			} //Closes while($row = mysql_fetch_array($res))
			echo "<br /><a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Reply to this discussion</a></div>";
		} else  {
			echo "<div><h3>".$row['comment']."</h3>";
			echo "<a href=\"$basepage?disp=discussion&comment=".$row['id']."\">Start a discussion</a></div>";
		}
	}
}

?>