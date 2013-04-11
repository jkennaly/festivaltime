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


?>

<div id="navwrapper">


<?php
include $baseinstall."includes/content/blocks/searchbox.php";
?>

<ul id="nav">

	<li><a href="<?php echo $basepage; ?>?disp=home">Home</a>
		<ul><li><a href="<?php echo $basepage; ?>?disp=home&fest=0">Site Home</a></li></ul></li>
	<li><a href="<?php echo $basepage; ?>?disp=about">About</a>
		<ul><li><a href="<?php echo $basepage; ?>?disp=guide">Site Guide</a></li></ul></li>
	<li><a href="<?php echo $basepage; ?>?disp=all_bands">Bands</a></li>
        <ul><li><a href="<?php echo $basepage; ?>?disp=sched">Schedule</a></li></ul></li>
	<li><a href="<?php echo $basepage; ?>mobile/mobile.php">Gametime</a></li>
	<li><a href="<?php echo $forumbase; ?>">Forum</a></li>

<?php
//Restricts the other menu to users that have the CreateNotes right.
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>
	<li><a href="#">Other</a>
		<ul><li><a href="#">Manage</a>
			<ul><li><a href="#">Users</a>		
				<ul>
					<li><a href="<?php echo $basepage; ?>?disp=edit_users">Edit Users</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=view_users">View Users</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=add_users">Add Users</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=edit_user_settings">Edit User Settings</a></li>
				</ul>
			</li>
			<li><a href="#">Festival</a>
		
				<ul>
					<li><a href="<?php echo $basepage; ?>?disp=add_genres">Add Genres</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=add_days">Add Days</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=add_stages">Add Stages</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=add_bands">Add Bands</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=edit_bands">Edit Bands</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=update_master">Update Master</a></li>
					<li><a href="<?php echo $basepage; ?>?disp=add_locations">Add Locations</a></li>
				</ul>
			</li>
			<li><a href="#">Site</a>
		
				<ul>
					<li><a href="<?php echo $basepage; ?>?disp=add_groups">Add Groups</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=add_grouptypes">Add Group Types</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=add_special_key">Add Special Keys</a></li>
				</ul>
			</li></ul>
			
		</li>
		<li><a href="#">Social</a>
			<ul><li><a href="<?php echo $basepage; ?>?disp=discuss_index">Discussion Index</a></li></ul>
		</li>
		<li><a href="#">Stats</a>
			<ul>
			      <li><a href="<?php echo $basepage; ?>?disp=band_scores">Band Scores</a></li>
		          <li><a href="<?php echo $basepage; ?>?disp=user_stats">User Stats</a></li>
                  <li><a href="<?php echo $basepage; ?>?disp=group_stats">Group Stats</a></li>
		    </ul>
	</li>
<?php
}
?>

</ul> <!-- end #nav -->

</div> <!-- end #navwrapper -->

