/*
*Copyright (c) 2013 Jason Kennaly.
*All rights reserved. This program and the accompanying materials
*are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
*http://www.gnu.org/licenses/agpl.html
*
*Contributors:
*    Jason Kennaly - initial API and implementation
*/


<div id="pic_row">

<?php

/* This block displays links to the bands with nine most recent comments.
*  This block requires the following variables: none
*/


//Display header for picture row

//Find each picture
$sql="select band from `pics`";
$res=mysql_query($sql, $main);
$i=0;
while($row=mysql_fetch_array($res)) {
	$pics[$i]=$row['band'];
	$i++;
}
for ($j=0;$j<3;$j++) {
	$picband=$pics[array_rand($pics)];
	echo "<a class=\"pic_row_pic\" href=\"".$basepage."?disp=view_band&band=".$picband."\"><img src=\"".$basepage."includes/content/blocks/getPicture.php?band=$picband&fest=".$_SESSION['fest']."\" alt=\"band pic\" /></a>";
}


//End of picture row

?>


</div><!-- End #pic_row -->




