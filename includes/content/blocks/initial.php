<div id="genrebands">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

/* This block displays bands by genre
*  
*/


//Find nine bands to display

$where = ExternalExcludeFilter("id", "bands", "band", "ratings", "user", $userid, $main);


$sql="select id, name from bands where $where order by rand() limit 9";
$res=mysql_query($sql, $main);

//For each genre, find 3 bands to display
//$j is how many genres have been displayed
$j=1;

while($row=mysql_fetch_array($res)) {
	$genredisp = "<table class=\"bandcap\"><caption align=\"bottom\">".$row['name']."<br />".getBandGenre($main, $master, $row['id'], $user)."</caption><tr><td class=\"pic_cell\"><a class=\"pic_row_pic\" href=\"".$basepage."?disp=view_band&band=".$row['id']."\"><img src=\"".$basepage."includes/content/blocks/getPicture.php?band=".$row['id']."&fest=".$_SESSION['fest']."\" alt=\"band pic\" /></a></td></tr></table>";
	
	echo $genredisp;
	If($j % 3 == 0) echo "<div class=\"clearfloat\"></div>";
	$j++;
} //Closes while($row=mysql_fetch_array($res))


//End of picture row

}

?>

</div><!-- End #genrebands -->
