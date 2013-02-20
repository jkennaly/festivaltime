<div id="content">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//Sets the target for all POST actions
$post_target=$basepage."?disp=band_scores";

include $baseinstall."includes/content/blocks/user_selector.php";
$uscoreall[] = NULL;

$sql="select max(id) as rows from bands";
$res = mysql_query($sql);
$num = mysql_fetch_assoc($res);

If(!empty($_POST['user'])) $scoreuser = $_POST['user'];
If(empty($_POST['user'])) $scoreuser = $user;

$sql = "select avg(rating) as average from ratings where ratings.user='$scoreuser'";

$res = mysql_query($sql);
$arr = mysql_fetch_assoc($res);

$useravgrating = $arr['average'];

$sql = "select username from Users where id='$scoreuser'";
$res = mysql_query($sql);
$user_row = mysql_fetch_array($res);
$scoreusername = $user_row['username'];

echo "Showing band scores for user ".$scoreusername."<br>";
echo "The average rating for this user is ".$useravgrating."<br>";
echo "The average rating for all bands by all users is ".$avg_rating."<br>";
echo "Your average rating for all bands is ".$uavg_rating."<br>";

for ($i=1; $i<=$num["rows"]; $i++)
  {
	$sql="select name from bands where id='$i'";
	$res = mysql_query($sql);
	$arr[$i] = mysql_fetch_assoc($res);
	$uscoreall[] = uscoref($i, $scoreuser, $avg_rating);
  	$j=$i;
  }


arsort($uscoreall);

echo "<table>";

reset($uscoreall);

for ($i=1; $i<=$j; $i++)
  {
	echo "<tr><th>$i</th><th><a href=\"".$basepage."?disp=view_band&band=".key($uscoreall)."\">".$arr[(key($uscoreall))]["name"]."</a></th><td>".current($uscoreall)."</td></tr>";
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
