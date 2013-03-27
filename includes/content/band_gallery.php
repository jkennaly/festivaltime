<?php

$bandlink = "<a href=\"".$basepage."?disp=view_band&band=".$band."\">".$name."</a>";
?>
<h1 id="bandtitle">
<?php 
echo $bandlink; ?></h1>

<div id=bandgallery">

<?php

//Get all the pics of the band
$query="select id from pics where band='$band'";
$result = mysql_query($query, $main);
If(mysql_num_rows($result)>0){
	while($row=mysql_fetch_array($result)){
		?>
		<img class="gallery_pic" src="includes/content/blocks/getSpecPic.php?picid=<?php echo $row['id']; ?>&fest=<?php echo $_SESSION['fest']; ?>" alt="gallery pic" />
		<?php
	}
}


?>
</div> <!-- end #bandgallery -->