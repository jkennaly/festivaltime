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


	UpdateTable($master, $main, "Users", $master_dbuser, $master_dbpw, $dbhost, $master_db, $dbuser, $dbpw, $dbhost, $dbname, $baseinstall);

	$query="SELECT Users.username AS username, Users.username AS name, rating, comment, descrip, links.id as link, comments.id as comid FROM Users LEFT JOIN ratings ON Users.id=ratings.user AND ratings.band='$band' LEFT JOIN comments ON Users.id=comments.user AND comments.band='$band' LEFT JOIN links ON Users.id=links.user AND links.band='$band' GROUP BY Users.id";
	
	$query_comment = mysql_query($query, $main);

	$i = 1;
	while ($comment_row = mysql_fetch_assoc($query_comment)) {

//This If statement ensures that there is data to display
		If( $comment_row['rating'] || $comment_row['comment'] || $comment_row['link'] ) {

			If( $comment_row['username'] == $_SESSION['user'] ) {
				$i_ret = $i;
				$i = 0;
			} //Closes If( $comment_row['username']...
		$temp_right = "CreateNotes";
		If(!empty($comment_row['comid']) && CheckRights($_SESSION['level'], $temp_right)) {
			$table[$i] = "<form action=\"".$basepage."?disp=discussion&comment=".$comment_row['comid']."\" method=\"post\"><input class=\"responsebutton\" type=\"submit\" value=\"Discuss this comment\"></form><table class=\"commenttable\"><tr><th>User:</th><td>";
		} else {
			$table[$i] = "<table class=\"commenttable\"><tr><th>User:</th><td>";
		} //Closes else If($comment_row['comid'])
		$table[$i] .= $comment_row['name'];

		If( $comment_row['username'] == $_SESSION['user'] ) {	
			$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
		} else {
			$table[$i] .= "</td></tr><tr><th>Rating:</th><td>";
		} //Closes else If( $comment_row['username']...

		$table[$i] .= $comment_row['rating'];

		If( $comment_row['username'] == $_SESSION['user'] ) {	
			$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
		} else {
			$table[$i] .= "</td></tr><tr><th>Link:</th><td>";
		} //Closes else If( $comment_row['username']...

		$table[$i] .= "<a href=\"".$basepage."links.php?linkid=".$comment_row['link']."\"  target=\"_blank\">".$comment_row['descrip']."</a>";	


		If( $comment_row['username'] == $_SESSION['user'] ) {	
			$table[$i] .= "</td></tr><tr><th colspan=2 class=\"commentrow\">Comment:</th></tr><tr><td colspan=2>";
		} else {
			$table[$i] .= "</td></tr><tr><th colspan=2 class=\"commentrow\">Comment:</th></tr><tr><td colspan=2>";
		} //Closes else If( $comment_row['username']...

		$table[$i] .= $comment_row['comment'];
		$table[$i] .= "</td></tr></table>";

		If( $comment_row['username'] == $_SESSION['user'] ) {
			$i = $i_ret;
		} //Closes If( $comment_row['username']...
		$i_max = $i;
		$i = $i +1;
		//Closes the If loop preventing users with no data from being displayed
		} // Closes  If( $comment_row['rating'] ||...

		
	}//Closes while ($comment_row = mysql_fetch_assoc($query_comment))


?>
