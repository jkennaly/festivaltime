<div id="content">

<?php
$right_required = "EditFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

$post_target= $basepage."?disp=update_master";


//Process any POST data
If(!empty($_POST['upd'])) {
//	var_dump($_POST);
	foreach($_POST as $k => $v){
		$v=mysql_real_escape_string($v);
		$fest_tag = substr($k, 4);
		$str_digits = 4+count_digit($fest_id);
		$fest_tag_str = substr($fest_tag, $str_digits);
		If(substr($k, 0, 4) == "upd_") {
			$sql = "UPDATE bands SET festivals=CONCAT(festivals, '$fest_tag') WHERE name='$v' AND festivals NOT LIKE '%$fest_tag%'";
			$upd = mysql_query($sql, $master);
		} //Closes If(substr($k, 0, 4) == "upd_")
		If(substr($k, 0, 4) == "add_") {
			$sql = "INSERT INTO bands (name, festivals) VALUES ('$v', '$fest_tag')";
			$upd = mysql_query($sql, $master);
		} // Closes If(substr($k, 0, 4) == "add_")
		$pre_str=strlen($fest_id_start);
		$post_str=-1*strlen($fest_id_end);
		$main_band_id_temp = substr($fest_tag_str, $pre_str);
		$main_band_id = substr($main_band_id_temp, 0, $post_str);
		$sql="select id from bands where festivals like '%$fest_tag%'";
		$res = mysql_query($sql, $master);
		$id= mysql_fetch_array($res);
		$mas_id=$id['id'];
		$sql = "UPDATE bands SET master_id='$mas_id' WHERE id='$main_band_id'";
		$upd = mysql_query($sql, $main);
		
	} // Closes foreach($_POST as $k => $v)
} // Closes If(!empty($_POST['upd']))

/*
$ttable = "info_".$fest_id;

If(!empty($_POST['info_upd'])) {

UpdateTable($main, $master, "info", $dbuser, $dbpw, $dbhost, $dbname, $master_dbuser, $master_dbpw, $dbhost, $master_db, $baseinstall);

$sql_rename = "RENAME TABLE `info` TO `$ttable`";
$upd=mysql_query($sql_rename, $master);
}

//Get info on current festival
$sql_info_get="select * from info";
$res_info=mysql_query($sql_info_get, $main);
echo "<h3>This is the info table for festival #$fest_id</h3>";
echo "<table>";
while ($row=mysql_fetch_array($res_info)) {
	echo "<tr><th>".$row['id']."</th><td>".$row['item']."</td><td>".$row['value']."</td></tr>";
} // Closes while ($row=mysql_fetch_array($res))
echo "</table><br />";

If(checkTable($main, $master, "info", $ttable)) {
	echo "The info table in this festival matches the table in the master database.<br>";
} else {
	echo "The info table in this database does not match the master. The button below will update the master to match this database's info. This will destroy any existing info table in the master!<br>";
?>
	<form action="<?php echo $post_target; ?>" method="post">
	<input type="submit" name="info_upd" value="Update Master Info table">
	</form>
<?php 
} //Closes eles If(checkTable($ma...

*/




//Get list of bands from current festival
$sql_bands="select id, name, master_id from bands order by id asc";
$res_bands=mysql_query($sql_bands, $main);

echo "If submit is pressed at the bottom of this form, all bands with no possible match will be added to the database as new bands. All bands with a checked box will be associated with the band that is checked. Make sure only checkbox is checked per band! If there are more than one checkbox that is appropriate, use the one furthest up the list. This will avoid creating duplicates in the database.";

echo "<form action=\"$post_target\" method=\"post\"><table><th>Fest ID</th><th>Band name</th><th>Master code</th><th>Master ID</th><th>Possible Matches</th>";
$i=0;
while ($row=mysql_fetch_array($res_bands)) {
	$bandmain[]=$row;
	$fest_tag = "--".$fest_id."--".$fest_id_start.$row['id'].$fest_id_end;
	$bandmain[$i]['fest_tag']=$fest_tag;
	echo "<tr><th>".$row['id']."</th><td>".$row['name']."</td><td>".$fest_tag."</td><td>".$row['master_id']."</td><td><ul>";
	If(empty($row['master_id'])) {
		$namesize = strlen($row['name']);
		If($namesize<6) $testname=$row['name'];
		If($namesize>=6 && $namesize<10) $testname=substr($row['name'], 4);
		If($namesize>=10) $testname=substr($row['name'], 4, 6);
		
		$match_sql = "select id, name from bands where name like '%".$testname."%'";
		$res_match=mysql_query($match_sql, $master);
		$match_found=0;
		$box_checked=0;
		while($match_names=mysql_fetch_array($res_match)) {
			$match_found=1;
			If($match_names['name'] == $row['name']) {echo "<li><input type=\"checkbox\" checked=\"checked\" name=\"upd_$fest_tag\" value=\"".$match_names['name']."\">".$match_names['name']."</input></li>"; $box_checked=1;}
			If($match_names['name'] != $row['name']) echo "<li><input type=\"checkbox\" name=\"upd_$fest_tag\" value=\"".$match_names['name']."\">".$match_names['name']."</input></li>"; 
			}
		If($match_found==0) {
			echo "<input type=\"hidden\" name=\"add_$fest_tag\" value=\"".$row['name']."\">";
		} //Closes If($match_found==0)
		If($box_checked==0) echo "<li><input type=\"checkbox\" checked=\"checked\" name=\"add_$fest_tag\" value=\"".$row['name']."\">".$row['name']."</input></li>";
	} //Closes If(empty($row['master_id']))
	echo "</ul></td></tr>";
	$i++;
} // Closes while ($row=mysql_fetch_array($res))
echo "</table><input type=\"submit\" name=\"upd\" value=\"Update Master\"></form>";

//Find any bands in the current festival that are not registered in master
$sql = "select id, n";




} else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
