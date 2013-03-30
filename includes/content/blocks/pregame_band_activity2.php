/*
*Copyright (c) 2013 Jason Kennaly.
*All rights reserved. This program and the accompanying materials
*are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
*http://www.gnu.org/licenses/agpl.html
*
*Contributors:
*    Jason Kennaly - initial API and implementation
*/


<?php

include "includes/content/blocks/accept_discuss_reply.php";
$post_target=$basepage."?disp=view_band&band=".$band;

//Get current users comment, if they have one
$sql = "select id, comment, user from comments where user='$user' and band='$band'";
$res=mysql_query($sql, $main);

If(mysql_num_rows($res)>0) {
	$row=mysql_fetch_array($res); 
	
	//Check to see if the user is current on this discussion. 
	$sqlcurrent = "select id from comments where id='".$row['id']."' AND discuss_current LIKE '%--$user--%'";
	$resultcurrent = mysql_query($sqlcurrent, $main);
	//IF the user is not current on this discussion
	If(mysql_num_rows($resultcurrent) == 0){
		$stat = "New discussion!";
	} else $stat = "Show discussion";	
	
	$discuss_table= "discussion_".$row['id'];
	$sql = "select d.id, d.response as reply, d.created as time, d.user as user from $discuss_table as d";
	$res1 = mysql_query($sql, $main);
	$leftcell= "<h2>".getUname($master, $row['user'])."<br />".displayStars($band, $row['user'], $main, "displaystars", $basepage."includes/images")."</h2>";
	
	$linksql="select id as link, descrip from links where band='$band' and user='".$row['user']."'";
	$linkres=mysql_query($linksql, $main);
	If(mysql_num_rows($linkres)>0) {
		$link_row=mysql_fetch_array($linkres);
		$rightcell = "<div class=\"linkdisplay\"><a href=\"".$basepage."links.php?linkid=".$link_row['link']."\" target=\"_blank\">".$link_row['descrip']."</a></div>";
	} else $rightcell = "";
	
	If(mysql_num_rows($res1)>0) {
					$rightcell .= "<div class=\"commentdisplay\">".$row['comment']."</div>";
           $rightcell.="<a id=\"displayText$user\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$user."', 'displayText".$user."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">$stat</a>";

				$discuss ="<div id=\"toggleText$user\" class=\"discussionreply\" style=\"display: none;\">";
		while($row1 = mysql_fetch_array($res1)) {
			$discuss .= "<p class=\"responder\">".getUname($master, $row1['user'])." at ".$row1['time']."</p><p class=\"reply\">".$row1['reply']."</p>";
		} //Closes while($row = mysql_fetch_array($res))
			$discuss .= "<br /><form action=\"$post_target\" method=\"post\"><textarea rows=\"6\" cols=\"64\" name=\"new_reply\"></textarea>";
			$discuss .= "<input type=\"submit\" value=\"Send response\" id=\"reply".$row['id']."\"><input type=\"hidden\" name=\"comment\" value=\"".$row['id']."\"><input type=\"hidden\" name=\"discuss_table\" value=\"$discuss_table\"></form><a id=\"displayText$user\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$user."', 'displayText".$user."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">Hide Discussion</a></div>";
	} else {
        //Check to see if the current user is current on the comment
        If($stat == "New discussion!") {      
            $query = "UPDATE comments SET discuss_current=CONCAT(discuss_current,'--$user--') where id='".$row['id']."'";
            $upd = mysql_query($query, $main);
            $leftcell .= "New Comment!<br />";
        }
		$rightcell .= "<div class=\"commentdisplay\">".$row['comment']."</div>";
		$rightcell.= "<a id=\"displayText$user\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$user."', 'displayText".$user."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">Start a discussion</a>";
		$discuss ="<div id=\"toggleText$user\" class=\"discussionreply\" style=\"display: none;\">";
        $discuss .= "<br /><form action=\"$post_target\" method=\"post\"><textarea rows=\"6\" cols=\"64\" name=\"new_reply\"></textarea>";
        $discuss .= "<input type=\"submit\" value=\"Send response\" id=\"reply".$row['id']."\"><input type=\"hidden\" name=\"comment\" value=\"".$row['id']."\"><input type=\"hidden\" name=\"discuss_table\" value=\"$discuss_table\"></form><a id=\"displayText$user\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$user."', 'displayText".$user."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">Hide Discussion</a></div>";
        
	}
	$tablerow[$row['user']]= "<tr id=\"comment".$row['user']."\"><th>$leftcell</th><td>$rightcell$discuss</td></tr>";
//	echo $discuss;
}
//Get user comments from group members

//Get all other user comments
$sql = "select id, comment, user from comments where user!='$user' and band='$band'";
$res=mysql_query($sql, $main);

If(mysql_num_rows($res)>0) {
	while($row=mysql_fetch_array($res)) {
		
		
	//Check to see if the user is current on this discussion. 
	$sqlcurrent = "select id from comments where id='".$row['id']."' AND discuss_current LIKE '%--$user--%'";
	$resultcurrent = mysql_query($sqlcurrent, $main);
	//IF the user is not current on this discussion
	If(mysql_num_rows($resultcurrent) == 0){
		$stat = "New discussion!";
	} else $stat = "Show discussion";

		$discuss_table= "discussion_".$row['id'];
		$sql = "select d.id, d.response as reply, d.created as time, d.user as user from $discuss_table as d";
		$res1 = mysql_query($sql, $main);
		$leftcell= "<h2>".getUname($master, $row['user'])."<br />".displayStars($band, $row['user'], $main, "displaystars", $basepage."includes/images")."</h2>";	
		
		$linksql="select id as link, descrip from links where band='$band' and user='".$row['user']."'";
		$linkres=mysql_query($linksql, $main);
		If(mysql_num_rows($linkres)>0) {
			$link_row=mysql_fetch_array($linkres);
			$rightcell = "<div class=\"linkdisplay\"><a href=\"".$basepage."links.php?linkid=".$link_row['link']."\" target=\"_blank\">".$link_row['descrip']."</a></div>";
		} else $rightcell = "";
		If(mysql_num_rows($res1)>0) {
			$i=0;
			while($row1 = mysql_fetch_array($res1)) {
				If($i==0) {
					$rightcell .= "<div class=\"commentdisplay\">".$row['comment']."</div>";
                    $rightcell.="<a id=\"displayText".$row['user']."\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$row['user']."', 'displayText".$row['user']."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">$stat</a>";
					$discuss = "<div id=\"toggleText".$row['user']."\" class=\"discussionreply\" style=\"display: none;\">";
				}
				$discuss .= "<p class=\"responder\">".getUname($master, $row1['user'])." at ".$row1['time']."</p><p class=\"reply\">".$row1['reply']."</p>";
				$i++;
			} //Closes while($row = mysql_fetch_array($res))
			$discuss .= "<br /><form action=\"$post_target\" method=\"post\"><textarea rows=\"6\" cols=\"64\" name=\"new_reply\"></textarea>";
			$discuss .= "<input type=\"submit\" value=\"Send response\" id=\"reply".$row['id']."\"><input type=\"hidden\" name=\"comment\" value=\"".$row['id']."\"><input type=\"hidden\" name=\"discuss_table\" value=\"$discuss_table\"></form><a id=\"displayText".$row['user']."\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$row['user']."', 'displayText".$row['user']."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">Hide Discussion</a></div>";
		} else  {
		    //Check to see if the current user is current on the comment
		    If($stat == "New discussion!") {      
                $query = "UPDATE comments SET discuss_current=CONCAT(discuss_current,'--$user--') where id='".$row['id']."'";
                $upd = mysql_query($query, $main);
                $leftcell .= "New Comment!<br />";
		    }
			$rightcell .= "<div class=\"commentdisplay\">".$row['comment']."</div>";
			$rightcell .= "<a id=\"displayText".$row['user']."\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$row['user']."', 'displayText".$row['user']."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">Start a discussion</a>";
			$discuss ="<div id=\"toggleText".$row['user']."\" class=\"discussionreply\" style=\"display: none;\">";
            $discuss .= "<br /><form action=\"$post_target\" method=\"post\"><textarea rows=\"6\" cols=\"64\" name=\"new_reply\"></textarea>";
            $discuss .= "<input type=\"submit\" value=\"Send response\" id=\"reply".$row['id']."\"><input type=\"hidden\" name=\"comment\" value=\"".$row['id']."\"><input type=\"hidden\" name=\"discuss_table\" value=\"$discuss_table\"></form><a id=\"displayText".$row['user']."\" title=\"Click to toggle discussion\" href=\"#\" onclick=\"toggle('toggleText".$row['user']."', 'displayText".$row['user']."', '$user', '".$row['id']."', 'reply".$row['id']."');return false;\">Hide Discussion</a></div>";
 
		}
    $tablerow[$row['user']]= "<tr id=\"comment".$row['user']."\"><th>$leftcell</th><td>$rightcell$discuss</td></tr>";
//	echo "<table class=\"commentstable\" id=\"comment".$row['user']."\"><tr><th>$leftcell</th><td>$rightcell$discuss</td></tr></table>";
//	echo $discuss;
	}
}
echo "<table class=\"commentstable\">";
foreach($tablerow as $v) echo $v;
echo "</table>";

?>
