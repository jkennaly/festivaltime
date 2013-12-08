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

    $myCachedFile = $baseinstall."external/cache-sidebar.txt";
    if(file_exists($myCachedFile))
    include_once($myCachedFile);
    else {
?>   


<div id="sidebar">

<ul id="sidebarnav">
	<li><a href="#">My Account</a>
		<ul><li><a href="<?php echo $basepage; ?>?disp=login">Log In</a></li></ul>
		<ul><li><a href="<?php echo $basepage; ?>?disp=logout">Log Out</a></li></ul>
		<ul><li><a href="<?php echo $basepage; ?>?disp=change_password">Change Password</a></li></ul>
		<ul><li><a href="<?php echo $basepage; ?>?disp=user_settings">User Settings</a></li></ul>
        <ul><li><a href="<?php echo $basepage; ?>?disp=my_groups">My Groups</a></li></ul>
	</li>

</ul> <!-- end #sidebarnav -->
</div> <!-- end #sidebar -->

<?php
}


?>



