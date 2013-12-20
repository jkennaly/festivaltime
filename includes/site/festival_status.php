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
$complete = getCompletedFestivals($master);
$incomplete = getIncompleteFestivals($master);
$verifreq = getVerifReqFestivals($master);

?>


<div id="content">
<button type="button" id="show-complete">Show/Hide Completed Festivals</button>
<button type="button" id="show-incomplete">Show/Hide Festivals Requiring More Information</button>
<button type="button" id="show-verifreq">Show/Hide Festivals Requiring Verification</button>

<div id="festivalstatuscompleted" class="festivalstatuswrapper">
<h2>Complete Festivals</h2>
<?php 
$statustypes = array(
				array('header', 'Header', 'header_v'), 
				array('dates', 'Dates and Venues', 'dates_v'),
				array('days_venues', 'Days', 'days_venues_v'),
				array('stages', 'Stages', 'stages_v'),
				array('band_list', 'Band List', 'band_list_v'),
				array('band_stages', 'Band Stages', 'band_stages_v'),
				array('set_times', 'Set Times', 'set_times_v'),
				);
foreach ($complete as $c){

drawFestStatus($c, $statustypes);
}
?>
</div><!-- end #festivalstatuscompleted -->

<div id="festivalstatusincomplete" class="festivalstatuswrapper">
<h2>Festivals that need more information added</h2>
<?php 
foreach ($incomplete as $inc){

drawFestStatus($inc, $statustypes);
}
?>
</div><!-- end #festivalstatusincomplete -->

<div id="festivalstatusverifreq" class="festivalstatuswrapper">
<h2>Festivals that need information verified</h2>
<?php 
foreach ($verifreq as $vr){
drawFestStatus($vr, $statustypes);
}
?>
</div><!-- end #festivalstatusverifreq -->

</div> <!-- end #content -->


<script type="text/javascript">
<!--
var basepage = "<?php echo $basepage; ?>";
//-->
</script>
<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/create.js"></script>