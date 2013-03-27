<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

$bandlink = "<a href=\"".$basepage."?disp=view_band&band=".$band."\">".$name."</a>";

$temp_right = "EditFest";
If(CheckRights($_SESSION['level'], $temp_right)) $rmvbutton="<form action=".$basepage."?disp=band_gallery&band=".$band."\" method=\"post\"><input type=\"submit\" name=\"delpic\" value=\"Remove this picture\"></input></form>";	
else $rmvbutton="<form action=".$basepage."?disp=band_gallery&band=".$band."\" method=\"post\"><input type=\"submit\" name=\"flagpic\" value=\"Flag this picture\"></input></form>";
?>
<h1 id="bandtitle"><?php echo $bandlink; ?></h1>

<div id=bandgallery">

<?php

//Get all the pics of the band
$query="select id from pics where band='$band'";
$result = mysql_query($query, $main);
If(mysql_num_rows($result)>0){
	while($row=mysql_fetch_array($result)){
		?>
		<table class="bandcap"><caption align="bottom"><?php echo $rmvbutton; ?></caption><tr><td class=\"pic_cell\">
		<img class="gallery_pic" src="includes/content/blocks/getSpecPic.php?picid=<?php echo $row['id']; ?>&fest=<?php echo $_SESSION['fest']; ?>" alt="gallery pic" />
        </tr></table>
		<?php
	}
}


?>
</div> <!-- end #bandgallery -->
<?php
} else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
