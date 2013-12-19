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

function drawFestStatus($fest, $statustypes){
	global $master, $basepage;
	?>
		<div class="feststatus">
		<h4><?php echo $fest['sitename']; ?></h4>
		<a href="<?php echo $fest['website']; ?>">Festival Website</a><br />
		<?php 
		foreach ($statustypes as $st){
			?>
			<div class="feststatusrow">
			<?php
			echo "<div class=\"feststatusname\">".$st[1]."</div><!-- end .feststatusname -->"; 
			if($fest[$st[0]] > 0){
				echo '<div class="feststatususer">Entered by '.getUname($master, $fest[$st[0]])."</div><!-- end .feststatususer -->";
				if($fest[$st[2]] > 0) echo '<div class="feststatususer">Verified by '.getUname($master, $fest[$st[2]])."</div><!-- end .feststatususer -->";
				else {
	?>
	 <div class="feststatususer"><button type="button" data-target="<?php echo $basepage."?disp=verify_".$st[0]."&fest=".$fest['id']; ?>">Verify information</button></div><!-- end .feststatususer -->
	 <?php
	 }
			}
			else {
	?>
	 <div class="feststatususer"><button type="button" data-target="<?php echo $basepage."?disp=edit_".$st[0]."&fest=".$fest['id']; ?>">Enter information</button></div><!-- end .feststatususer -->
	 <div class="feststatususer"></div><!-- end .feststatususer -->
	 <?php
	 }
			if($fest[$st[0]] > 0 && $fest[$st[2]] > 0){
			?>
			<button type="button" id="unlock-<?php echo $fest['id']."-".$st[0]; ?>">Unlock for edits</button>
			<?php } ?>
			</div><!-- end .feststatusrow -->
	<?php
		}
	echo "</div><!-- end .feststatus -->";
}
?>
