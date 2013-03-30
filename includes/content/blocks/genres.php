#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<div id="genrebands">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

/* This block displays bands by genre
*  
*/


//Find three genres to display, each with at least three bands
$sql="select genre from bands group by genre order by rand()";
$res=mysql_query($sql, $main);

//For each genre, find 3 bands to display
//$j is how many genres have been displayed
$j=0;

while($row=mysql_fetch_array($res)) {
	$query="select bands.id as id, bands.name as name from ratings left join bands on bands.genre='".$row['genre']."' group by bands.id order by rand()";
//	echo $query;
	$result=mysql_query($query, $main);
	$gname = getGname($master, $row['genre']);
	If(empty($gname)) $gname = "Bands that need a genre";
	$genredisp = "<h2>".$gname."</h2>";
	$i=0;	
	//$k is whether the current genre will be displayed
	$k=0;
	while ($band_row=mysql_fetch_array($result)) {
		$rated_sql="select id from ratings where user='$user' and band='".$band_row['id']."'";
//		echo $rated_sql;
		$rated_res=mysql_query($rated_sql, $main);
		//Only display bands that have not been rated
		If(mysql_num_rows($rated_res)==0) {
			$genredisp .= "<table class=\"bandcap\"><caption align=\"bottom\">".$band_row['name']."</caption><tr><td class=\"pic_cell\"><a class=\"pic_row_pic\" href=\"".$basepage."?disp=view_band&band=".$band_row['id']."\"><img src=\"".$basepage."includes/content/blocks/getPicture.php?band=".$band_row['id']."&fest=".$_SESSION['fest']."\" alt=\"band pic\" /></a></td></tr></table>";
			If($i>1) break;
			$i++;
			If($i==1) {$j++; $k=1;}
		} // Clsoes If(mysql_num_rows($rated_res)==0)
		
	} //Closes while ($band_row=mysql_fetch_array($result))
	If($k==1) echo $genredisp;
	If($j>2) break;
} //Closes while($row=mysql_fetch_array($res))


//End of picture row




//End of genre section
}

?>

</div><!-- End #genrebands -->
