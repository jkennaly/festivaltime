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
$right_required = "SendComms";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


$query="SELECT id, username FROM Users WHERE username='".$_SESSION['user']."'";
$query_user = mysql_query($query, $master);
$user_row = mysql_fetch_array($query_user);
$user = $user_row['id'];
$uname = $user_row['username'];

$sql = "select Max(id) as comm, fromuser from comms where (commtype='2' or commtype='5') group by fromuser order by comm desc";

$res=mysql_query($sql, $main);


?>
<!--<p>Last known locations. Color indicates message age. Blue less than 5 min, Green 5-15 min, Orange 15-30 min, White more than 30 min</p>-->
<table id="loctable">

<?php
//echo "<tr class=\"headings\"><th colspan=\"2\">Click on the band for details</th></tr>";
while($row=mysql_fetch_array($res)) {
	$query="select * from comms where id='".$row['comm']."'";
	$result=mysql_query($query, $main);
	$row_uname = getUname($master, $row['fromuser']);
	$comm_row = mysql_fetch_array($result);
	$commtype = $comm_row['commtype'];
	$time_msg_sent = strtotime($comm_row['timestamp']);
	$msg_age = (time()-$time_msg_sent)/60;
	$row_class = "white";
	If($msg_age <= 30.0) $row_class = "orange";
	If($msg_age <= 15.0) $row_class = "green";
	If($msg_age <= 5.0) $row_class = "blue";
	
	If($commtype==2) {
		$band_sql = "select name, stage from bands where id='".$comm_row['band']."'";
		$band_res = mysql_query($band_sql, $main);
		$band_row = mysql_fetch_array($band_res);
		$stage_sql = "select name from stages where id='".$band_row['stage']."'";
		$stage_res = mysql_query($stage_sql, $main);
		$stage_row = mysql_fetch_array($stage_res);
		echo "<tr><td class=\"$row_class\">$row_uname</td><td class=\"$row_class\"><a href=\"mobile_detail.php?band=".$comm_row['band']."&time=$basetime_s\">".$band_row['name']." at ".$stage_row['name']."</a></td></tr>";
	
	}
	If($commtype==5) {
		$loc_sql = "select name from locations where id='".$comm_row['location']."'";
		$loc_res = mysql_query($loc_sql, $main);
		$loc_row = mysql_fetch_array($loc_res);
		echo "<tr ><td class=\"$row_class\">$row_uname</td><td class=\"$row_class\">".$loc_row['name']."</td></tr>";
	}
}

?>
</table>

<?php 
}
else{
?>
<p>

You do not have sufficient access rights to view this page.

</p>

<?php 
}

?>

