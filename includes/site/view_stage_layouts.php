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
$right_required = "EditFest";
If(!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)){
	die("You do not have rights to access this page. You can login or register here: <a href=\"".$basepage."\">FestivalTime</a>");
}

?>


<div id="content">
<?php 

/*
 <p id='position'>Unclicked</p>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/layout.js"></script>
*/

?>
<p>

This page displays the current stage layouts.
</p>



<?php 
$layouts = getAllStageLayouts($user, $master);
foreach($layouts as $l){
	displayStageLayoutPic($basepage, $l, $master );
}
?>

</div> <!-- end #content -->

