<div id="genrebands">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

/* This block displays bands by genre
*  
*/
//Find genre of every band in master
$sql="select id, name from bands";
$res=mysql_query($sql, $master);
$genrecount=0;
while($row=mysql_fetch_array($res)) {
    $bandgenre[$row['id']] = getBandGenreID($master, $master, $row['id'], $user);
    $bandscore[$row['id']] = uscoref2($row['id'], $user, $muavg_rating, $master);
    echo "genre: ".$bandgenre[$row['id']]."-".getBandGenre($master, $master, $row['id'], $user)." score: ".$bandscore[$row['id']]." band: ".$row['name']."<br />";
    $test = $bandgenre[$row['id']];
    $used = 0;
    
    //Count # of genres in master
    foreach ($bandgenre as $v){
        If($test == $v) $used =$used + 1;
    }
    If($used == 1) {
        $genrecount=$genrecount + 1;
        $genresused[] = $bandgenre[$row['id']];
    }
}



//Find average rating for each genre
foreach($genresused as $k=>$v) {
    $count=0;
    $total=0;
    foreach($bandscore as $key=>$val) {
        If($bandgenre[$key] == $v) {
            $count=$count + 1;
            $total=$total + $val;
        }
    }
    $genrescore[$k] = $total/$count;
    If($genrescore[$k] >= 4) {$genrelove[] = $v; echo "loved genre $v: with score $genrescore[$k] ".getGname($master, $v)."<br />";}
    If($genrescore[$k] < 4 && $genrescore[$k] >= 3) {$genrelike[] = $v; echo "liked genre: with score $genrescore[$k] ".getGname($master, $v)."<br />";}
}

//Rank genres by score: >=4 love, >=3 and <4 like,




//Find nine bands to display

$where = ExternalExcludeFilter("id", "bands", "band", "ratings", "user", $user, $main);

If(empty($where)) $where = "1=1";

$sql="select id, name from bands where $where order by rand()";
$res=mysql_query($sql, $main);


//$j is how many genres have been displayed
$j=1;
$n = 0;
for($i=1;$i<=3;$i++){
    while($row=mysql_fetch_array($res)) {
        If($i==1) {
            If(in_array(getBandGenreID($main, $master, $row['id'], $user), $genrelove) ) $bandpasses = 1; else $bandpasses = 0;
        }
        If($i==2) {
            If(in_array(getBandGenreID($main, $master, $row['id'], $user), $genrelike) ) $bandpasses = 1; else $bandpasses = 0;
        }
        If($i==3) {
            $bandpasses = 1;
        }
        If($bandpasses == 1) {
        	$genredisp = "<table class=\"bandcap\"><caption align=\"bottom\">i: $i j: $j n: $n<br />".$row['name']."<br />";
        	$genredisp .= getBandGenre($main, $master, $row['id'], $user)."</caption><tr><td class=\"pic_cell\"><a class=\"pic_row_pic\" href=\"";
        	$genredisp .= $basepage."?disp=view_band&band=".$row['id']."\"><img src=\"".$basepage."includes/content/blocks/getPicture.php?band=";
        	$genredisp .= $row['id']."&fest=".$_SESSION['fest']."\" alt=\"band pic\" /></a></td></tr></table>";
        	
        	echo $genredisp;
        	If(($n + 1) % 3 == 0) echo "<div class=\"clearfloat\"></div>";
        	$j++;
            $n++;
        }
        If(($j==4 && $i<3) || $n >= 9) break;
    } //Closes while($row=mysql_fetch_array($res))
    $j=1;
    If($n >= 9) break;
    mysql_data_seek($res, 0);
}


//End of picture row

}

?>

</div><!-- End #genrebands -->
