<?php



//echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to choose from a list of all the bands</a> or select Home from the Nav bar up top to use the filters</p>";



$post_target = $basepage."?disp=view_band&band=$band";

// Collect data display comments, etc.
//If $band is defined
If(empty($_POST['edit']) && empty($_POST['edits']))	{
	include $baseinstall."includes/content/blocks/band_info.php";
	$priors = getFestivals($band, $main, $master);
	If(count($priors)>2) {
		foreach($priors as $v) {
			$priorname=getFname($master, $v['fest']);
			$priorband=$v['band'];
			If( $v['fest'] != $fest) echo "<a href=\"".$basepage."?disp=view_band&band=".$priorband."&fest=".$v['fest']."\">".$priorname."</a><br />";
		}
	}
?>

<form id="edit_band_button" action="<?php echo $basepage."?disp=view_band&band=$band"; ?>" method="post">
<input type="submit" value="Edit Band Info" name="edit">
</form>

<?php
} //Closes If(empty($_POST['edit']))

If(!empty($_POST['edit']) || !empty($_POST['edits']))	{
	include $baseinstall."includes/content/blocks/band_info_edit.php";
?>

<form id="edit_band_button" action="<?php echo $basepage."?disp=view_band&band=$band"; ?>" method="post">
<input type="submit" value="Done Editing" name="done">
</form>

<?php
} //Closes If(!empty($_POST['edit']))
	//query to pull basic data



	//If the page viewer was referred by a recommendation, set it to followed
	If(!empty($_GET["recomm"])) {
		$sql = "UPDATE recommendations SET followed='1' WHERE touser='$user' AND band='$band'";
		$res = mysql_query($sql, $main);
	}  //Closes If($_GET["recomm"])
	//end recommedations section


	include "includes/content/blocks/recommendations.php";

	include "includes/content/blocks/liveranked.php";

