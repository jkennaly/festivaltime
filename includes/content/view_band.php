<?php
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
?>


<div id="content">



<?php
$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
If(empty($band)) $band="";
/*/
?>
<p>

This page allows for viewing the bands and comments.<a class="helplink" href="<?php echo $basepage."?disp=about&band=$band#viewband"; ?>">Click here for help with this section</a>

</p>

<?php
*/

//Process displaying band info
	If (!empty($_REQUEST["band"])) {



		include('blocks/pregame_band.php');

} else {
//If no band is passed through GET or POST, display a selector

	$query="select name, id from bands";
	$query_band = mysql_query($query, $main);
?>
<form action="index.php?disp=view_band" method="post">
<select name="band">
<?php 
while($row = mysql_fetch_array($query_band)) {
	echo "<option value=".$row['id'].">".$row['name']."</option>";
}
	
?>
</select>
<input type="submit">
</form>
<?php
	}

}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
