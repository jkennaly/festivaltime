<div id="content">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//Sets the target for all POST actions
$post_target=$basepage."?disp=band_scores";

include $baseinstall."includes/content/blocks/user_selector.php";
$uscoreall[] = NULL;

If(!empty($_POST['user'])) $scoreuser = $_POST['user'];
If(empty($_POST['user'])) $scoreuser = $user;

//Genre-based averages

$sql_user_genre = "select avg(ruser.rating) as useraverage, count(ruser.rating) as usercount, genre from bands left join ratings as ruser on bands.id=ruser.band and user='$scoreuser' group by genre order by useraverage desc";

$sql_all_genre = "select avg(rating) as average, count(rating) as count, genre from bands left join ratings on bands.id=ratings.band group by genre";

$res_user_genre = mysql_query($sql_user_genre, $main);
$res_all_genre = mysql_query($sql_all_genre, $main);

//echo $sql_user_genre;


while($row=mysql_fetch_array($res_all_genre)) {
	$all_genre[$row['genre']]['avg']=$row['average'];
	$all_genre[$row['genre']]['cnt']=$row['count'];	
}

?>

<table>
<tr>
<th>Genre</th>
<th>Group rating</th>
<th>Total ratings in this genre</th>
<th><?php echo getUname($master, $scoreuser); ?>'s rating</th>
<th><?php echo getUname($master, $scoreuser); ?>'s ratings in this genre</th>
</tr>

<?php
while($row=mysql_fetch_array($res_user_genre)) {
echo "<tr><td>".getGname($master, $row['genre'])."</td><td>".$all_genre[$row['genre']]['avg']."</td><td>".$all_genre[$row['genre']]['cnt']."</td><td>".$row['useraverage']."</td><td>".$row['usercount']."</td></tr>";
}
echo "</table>";

$sql="select max(id) as rows from bands";
$res = mysql_query($sql, $main);
$num = mysql_fetch_assoc($res);

$sql = "select avg(rating) as average from ratings where ratings.user='$scoreuser'";

$res = mysql_query($sql, $main);
$arr = mysql_fetch_assoc($res);

$useravgrating = $arr['average'];

$sql = "select username from Users where id='$scoreuser'";
$res = mysql_query($sql, $master);
$user_row = mysql_fetch_array($res);
$scoreusername = $user_row['username'];

echo "Showing band scores for user ".$scoreusername."<br>";
echo "The average rating for this user is ".$useravgrating."<br>";
echo "The average rating for all bands by all users is ".$avg_rating."<br>";
echo "Your average rating for all bands is ".$uavg_rating."<br>";

for ($i=1; $i<=$num["rows"]; $i++)
  {
	$sql="select name from bands where id='$i'";
	$res = mysql_query($sql, $main);
	$arr[$i] = mysql_fetch_assoc($res);
	$uscoreall[] = uscoref($i, $scoreuser, $avg_rating, $main);
  	$j=$i;
  }


arsort($uscoreall);

echo "<table>";

reset($uscoreall);

for ($i=1; $i<=$j; $i++)
  {
	If( $arr[(key($uscoreall))]["name"] ) echo "<tr><th>$i</th><th><a href=\"".$basepage."?disp=view_band&band=".key($uscoreall)."\">".$arr[(key($uscoreall))]["name"]."</a></th><td>".current($uscoreall)."</td></tr>"; else {
	$i = $i-1;
	$j=$j-1;
	}
	next($uscoreall);
  
  }
echo "</table>";

mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
