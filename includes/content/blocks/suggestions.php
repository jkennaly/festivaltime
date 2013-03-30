#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<div id=\"suggestions\">

<?php

/* This block displays links to suggested bands
*  
*/

//If(empty($_SESSION['suggested'])) {
//If no current suggestion is active, show bands everyone else liked
	//Bands I have not rated
	$where = ExternalExcludeFilter("id", "bands", "band", "ratings", "user", $userid, $main);
	If($where != ")" ) {$where .= " AND ";} else {$where = "";}
	//Bands with an average rating over 3.5
	$where .= ExternalMinimumFilter("id", "bands", "band", "ratings", "avg(rating)", "3.5", $main);
	$_SESSION['suggested'] = "select id, name from bands where $where limit 0,9";
	$_SESSION['suggest_descrip'] = "Bands you should check out";
//	echo $_SESSION['suggested'];
//}

//echo "Suggestion sql is <br>";
//echo $_SESSION['suggested'];

$sug_result=mysql_query($_SESSION['suggested'], $main);

//display results
If(mysql_num_rows($sug_result)>0) {

echo "<p class=\"activehead\">".$_SESSION['suggest_descrip']."</p>";
echo "<div id=\"suggestedlist\" class=\"bandlist\"><ul>";

while($row = mysql_fetch_array($sug_result)) {
	echo "<li><a href=\"".$basepage."?disp=view_band&band=".$row["id"]."\">".$row["name"]."</a></li>";
}

echo "</p></ul></div><!--End #suggestedlist-->";
} //Closes If(!empty(mysql_num_rows($result)))


//End of suggested bands section

?>

</div><!-- End #suggestions -->
