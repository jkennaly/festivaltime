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

/* This block displays links to the bands with nine most recent comments.
*  This block requires the following variables: none
*/

//Get data for the most recently commented bands
	$query="SELECT comments.band as id, bands.name as name FROM comments, bands WHERE comments.band=bands.id GROUP BY comments.band ORDER BY MAX(comments.id) desc limit 0,9";
	$result=mysql_query($query, $main);
	echo "<div id=\"comments\" class=\"activelist\"><p class=\"activehead\">Bands that have recent comments:<a class=\"helplink\" href=\"".$basepage."?disp=about#comments\">Click here for help with this section</a></p><p><ul>";
while($row = mysql_fetch_array($result)) {
	echo "<li><a href=\"".$basepage."?disp=view_band&band=".$row["id"]."\">".$row["name"]."</a></li>";
}

echo "</p></ul><br /></div><!-- End #comments -->";
//End of most recently commented bands

?>
