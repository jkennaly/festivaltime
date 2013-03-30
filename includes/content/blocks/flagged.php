<?php

/* This block displays links to the bands with nine most recent comments.
*  This block requires the following variables: none
*/

$temp_right = "EditFest";
If(isset($_SESSION['level']) && !empty($user) && CheckRights($_SESSION['level'], $temp_right)){

$query="select * from pics where clicks>0";
$result = mysql_query($query, $master);
If(mysql_num_rows($result)>0){
    echo "<h2>The following pictures have been flagged for removal:</h2>";
    while($row=mysql_fetch_array($result)){
        echo "<a class=\"pic_row_pic\" href=\"".$basepage."?disp=band_gallery&band=".$row['band']."\">";
        echo "<img src=\"".$basepage."includes/content/blocks/getSpecPic.php?picid=".$row['id']."&fest=".$_SESSION['fest']."\" alt=\"flagged pic\" /></a>";
    }
}


//End of flagged pics
}

?>