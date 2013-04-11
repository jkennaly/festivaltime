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

<div id="content">

<?php

$right_required = "ViewNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//Sets the target for all POST actions
$post_target=$basepage."?disp=band_scores";

//Find all users that share a group with the current user

//First get all the groups the user is in
$groups_in = in_groups($user, $master);

//Find all the users in the system


foreach($groups_in as $v) {
    echo "<h2>".$v['name']."</h2>";
    
}


}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
