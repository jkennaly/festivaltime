<div id="content">

<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){



	mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");

//Sets the target for all POST actions
$post_target=$basepage."?disp=inclusion_test";

//echo $post_target;

include "includes/content/blocks/band_selector.php";

include "includes/content/blocks/scoring_functions.php";

include "variables/page_variables.php";

//echo "<br>My score for this band is $uscore.<br>";

echo "<br>Average rating is $avg_rating.<br>";

$uscoreall[] = NULL;
for ($i=1; $i<=168; $i++)
  {
	$sql="select name from bands where id='$i'";
	$res = mysql_query($sql);
	$arr[$i] = mysql_fetch_assoc($res);
	$uscoreall[] = uscoref($i, $user, $avg_rating);
  
  }


arsort($uscoreall);

//var_dump ($uscoreall);

echo "<table>";

reset($uscoreall);

for ($i=1; $i<=168; $i++)
  {
//	echo "<tr><th>".$arr[(key($uscoreall))]["name"]."</th><td>".current($uscoreall)."</td></tr>";
	echo "<tr><th><a href=\"".$basepage."?disp=view_band&band=".key($uscoreall)."\">".$arr[(key($uscoreall))]["name"]."</a></th><td>".current($uscoreall)."</td></tr>";
	next($uscoreall);

//<a href=\"".$basepage."?disp=view_band&band=".key($uscoreall)."\">".$arr[(key($uscoreall))]["name"]."</a>
  
  }
echo "</table>";
mysql_close();
}
else{
?>
<p>

You do not have sufficient access rights to view this page.

</p>

<?php 
}

?>
</div> <!-- end #content -->
