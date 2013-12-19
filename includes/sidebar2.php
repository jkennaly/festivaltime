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

$bandActivitySQL= "SELECT * FROM( SELECT name, band as id, sum(hits) FROM(
SELECT bands.name as name, band, count(band) as hits FROM `live_rating` left join bands on bands.id=live_rating.band group by band
UNION ALL
SELECT bands.name as name, band, count(band) as hits FROM `comments` left join bands on bands.id=comments.band group by band
UNION ALL
SELECT bands.name as name, band, count(band) as hits FROM `ratings` left join bands on bands.id=ratings.band group by band
) as inter where band!='0' group by band order by sum(hits) desc limit 25) as intera order by rand() limit 3";

$res = mysql_query($bandActivitySQL, $master);
echo mysql_error($master);
$maxi = mysql_num_rows($res);
for($i = 0; $i < $maxi; $i++ ){
    $row=mysql_fetch_array($res);
    $bandl[$i] = $row;
    $bandl[$i]["fest"] = max(getFestivalsMaster($bandl[$i]["id"], $master));
    $bandl[$i]["id"] = getFestBandIDFromMaster($bandl[$i]["id"], $bandl[$i]["fest"], $master);
}
$bandList = "";
foreach($bandl as $b){
    $bandList .= '<li class="popular-item">';
    $bandList .= '<a href="'.$basepage.'?disp=view_band&band='.$b["id"].'&fest='.$b["fest"].'" >'.$b["name"].'</a>';
    $bandList .= '</li>'; 
}

$newFests = getNewFestivals($master);
ob_start();
?>   


<div id="sidebar2" class="sidebar">

<aside id="popular-bands-widget" class="widget">
<h3 class="wideget-title">Popular Bands</h3>
<ul class="popular-list">
<?php echo $bandList; ?>
</ul>
</aside>

<aside id="popular-users-widget" class="widget">
<h3 class="wideget-title">Popular Users</h3>
<ul class="popular-list">
<li class="popular-item">Eric</li>
</ul>
</aside>

<aside id="recent-fests-widget" class="widget">
<h3 class="wideget-title">Newly Added Fests</h3>
<ul class="popular-list">
<?php 
foreach ($newFests as $c){
	echo "<li class=\"popular-item\"><a href=\"".$c['website']."\">".$c['sitename']."</a></li>";
}
?>
</ul>
</aside>



</div> <!-- end #sidebar2 -->

<?php
$output = ob_get_contents();
$file = $baseinstall."external/cache-sidebar2.txt";
file_put_contents($file, $output);
ob_flush();


?>



