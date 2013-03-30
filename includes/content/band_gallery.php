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

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
    
If(!empty($_POST['flagpic'])){
    $clicker = "UPDATE pics SET clicks=clicks+1 WHERE id='".$_POST['pic']."'";
    $query = mysql_query($clicker, $master);
}

$temp_right = "EditFest";
If(CheckRights($_SESSION['level'], $temp_right) && !empty($_POST['delpic'])){
//    echo "Del logic entered band_gallery<br />";
    $sql = "DELETE FROM pics WHERE id = '".$_POST['pic']."'";
    $upd = mysql_query($sql, $master);
}

$bandlink = "<a href=\"".$basepage."?disp=view_band&band=".$band."\">".$name."</a>";



?>
<h1 id="bandtitle"><?php echo $bandlink; ?></h1>

<div id=bandgallery">

<?php

//Get all the pics of the band
$bandsql="select master_id from bands where id='$band'";
$bandres=mysql_query($bandsql, $main);
$row=mysql_fetch_array($bandres);
$mband=$row['master_id'];
$query="select id, clicks from pics where mas_id='$mband'";
$result = mysql_query($query, $master);
If(mysql_num_rows($result)>0){
	while($row=mysql_fetch_array($result)){
	    $temp_right = "EditFest";
        If(CheckRights($_SESSION['level'], $temp_right)) {
            $rmvbutton= "<form action=".$basepage."?disp=band_gallery&band=";
            $rmvbutton.=$band."\" method=\"post\"><input type=\"submit\" name=\"delpic\" value=\"Remove this picture\"></input>";
            $rmvbutton.="<input type=\"hidden\" name=\"pic\" value=\"".$row['id']."\"></input></form>";
            If($row['clicks']>0) $rmvbutton.="<br />This pic has been flagged ".$row['clicks']." times";
        }
            else {
            $rmvbutton= "<form action=".$basepage."?disp=band_gallery&band=";
            $rmvbutton.=$band."\" method=\"post\"><input type=\"submit\" name=\"flagpic\" value=\"Flag this picture\"></input>";
            $rmvbutton.="<input type=\"hidden\" name=\"pic\" value=\"".$row['id']."\" this picture\"></input></form>";    
        }
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
