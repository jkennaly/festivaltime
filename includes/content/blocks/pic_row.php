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
	echo "<a class=\"pic_row_pic\" href=\"".$basepage."?disp=view_band&band=".$picband."\"><img src=\"".$basepage."includes/content/blocks/getPicture.php?band=$picband\" alt=\"band pic\" /></a>";
}


//End of picture row

?>


</div><!-- End #pic_row -->




