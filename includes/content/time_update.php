<div id="content">
<?php
$right_required = "SiteAdmin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
?>

<p>

Once all set times have been loaded into the system, they need to be converted for use during gametime.

Pressing the button below will take all the readable set times that have been entered, and convert them into the values the system will use during gametime to calculate which bands to display.

Edit the file itself to enable the script.

</p>

<?php
/*
mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");

    $query = "SELECT id, start, end, name, day FROM bands";
    $result = mysql_query($query); 
    if($result){
        $num = mysql_numrows($result);
        $i=0;
        while ($i < $num) {
		echo "<br>Start time for ".mysql_result($result,$i,"name")." is ".mysql_result($result,$i,"start")."<br>";
		$sec_start = strtotime(mysql_result($result,$i,"start"));
		echo "Seconds start time for ".mysql_result($result,$i,"name")." is ".$sec_start."<br>";
		echo "End time for ".mysql_result($result,$i,"name")." is ".mysql_result($result,$i,"end")."<br>";
		$sec_end = strtotime(mysql_result($result,$i,"end"));
		echo "Seconds end time for ".mysql_result($result,$i,"name")." is ".$sec_end."<br>";
		$ae = array("Thursday", "2013-04-12 20:00", "2013-04-13 20:00", "2013-04-14 20:00");
		$as = array("Thursday", "2013-04-12 19:00", "2013-04-13 19:00", "2013-04-14 19:00");
		$j = mysql_result($result,$i,"day");
		echo "Projected start time for ".mysql_result($result,$i,"name")." is ".$as[$j]."<br>";
		echo "Projected end time for ".mysql_result($result,$i,"name")." is ".$ae[$j]."<br><br>";
		$id = mysql_result($result,$i,"id");
//		mysql_query("UPDATE bands SET start = '".$as[$j]."', end = '".$ae[$j]."' WHERE id='$id'");
		mysql_query("UPDATE bands SET sec_start = '$sec_start', sec_end = '$sec_end' WHERE id='$id'");
		
		$i++;
       }
    }
*/


}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>



</div> <!-- end #content -->
