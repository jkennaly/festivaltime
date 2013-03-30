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

/*
*  This block requires the following variables: $userid (must contain 
*  the id of the currently logged in user).
*/

//Get data for display of recommended bands



	$query="SELECT bands.id as id, bands.name as name, recommendations.id as recomm from recommendations, bands where touser='$userid' AND followed='0' AND bands.id=recommendations.band limit 0,9";
	$result=mysql_query($query, $main);

//Do not display anything if there are no recommendations to show
	If (0 != mysql_num_rows($result)) {
		echo "<div id=\"recommended\" class=\"activelist\"><p class=\"activehead\">Bands that have been recommended to you:<a class=\"helplink\" href=\"".$basepage."?disp=about#recommended\">Click here for help with this section</a></p><p><ul>";
	while($row = mysql_fetch_array($result)) {
		echo "<li><a href=\"".$basepage."?disp=view_band&band=".$row["id"]."&recomm=".$row["recomm"]."\">".$row["name"]."</a></li>";
echo "</p></ul><br /></div><!-- End #recommended -->";	
		}  //Closes while($row = mysql_fetch_array($result))
	} //Closes If (0 != mysql_num_rows($result))
//End of recommended bands


?>
