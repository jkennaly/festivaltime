#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<?php



//echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to choose from a list of all the bands</a> or select Home from the Nav bar up top to use the filters</p>";



$post_target = $basepage."?disp=view_band&band=$band";

// Collect data display comments, etc.
//If $band is defined
If(!empty($band)) {


	include $baseinstall."includes/content/blocks/pregame_band_vitals.php";
	include $baseinstall."includes/content/blocks/pregame_band_activity2.php";

} else { //closes If(!empty($band))
	echo "Band is empty.<br>";
}
?>
<div id="userinfo">

<?php

//echo "<br>User comments, ratings, and links for this band:<a class=\"helplink\" href=\"$basepage?disp=about&band=$band#commenting\">Click here for help with this section</a><br>";


If(!isset($i_ret)){
//Execute this logic if the user has not rated, commented or linked the band	


	If(!empty($table)) {
		foreach ($table as $val) {
			echo "<br>".$val."<br>";
		} //Closes foreach ($table as $val)
	} //Closes If(!empty($table) 
} else {
//Execute this logic if the user has rated, commented or linked the band
	for ($i=0; $i<=$i_max; $i++) {
		If(isset($table[$i])) {
			echo "<br>".$table[$i]."<br>";
		} //Closes If(isset($table[$i]))
	} //Closes for ($i=0; $i<=$i_max; $i++)

}//Closes If(!isset($i_ret)) else
rmTable($main, "Users");
echo "</div> <!-- end #userinfo -->";

?>
