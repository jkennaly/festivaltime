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
		<ul><li><a href="<?php echo $basepage; ?>?disp=home&fest=0">Site</a></li></ul></li>
	<li><a href="<?php echo $basepage; ?>?disp=about">About</a></li>
<!--		<ul><li><a href="<?php echo $basepage; ?>?disp=guide">Guide</a></li></ul></li> -->
	<li><a href="<?php echo $basepage; ?>?disp=home">Festivals</a>  
                <ul>
                    <li><a href="<?php echo $basepage; ?>?disp=create_festival">Create New Festival</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=festival_status">Festival Status</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=add_venue">Add Festival Venue</a></li>
                </ul>
                </li> 
	<li><a href="<?php echo $basepage; ?>?disp=home">Bands</a>  
                <ul>
                    <li><a href="<?php echo $basepage; ?>?disp=bands_by_genre">Bands by Genre</a></li>
                </ul>
                </li> 
	<li><a href="<?php echo $basepage; ?>?disp=home">Stages</a>  
                <ul>
                    <li><a href="<?php echo $basepage; ?>?disp=add_stage">Add Stage</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=add_stage_priority">Add Stage Priority</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=add_stage_layout">Add Stage Layout</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=edit_stage_layouts">Edit Stage Layouts</a></li>
                    <li><a href="<?php echo $basepage; ?>?disp=edit_stages">Edit Stages</a></li>
                </ul>
    </li> 
	<li><a href="<?php echo getForumLink($master, $user, $mainforum, $forumblog); ?>">Forum</a></li>
	</ul>
<?php
//}
?>

</ul> <!-- end #nav -->

</div> <!-- end #navwrapper -->

