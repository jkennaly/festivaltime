#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<div id="content">

<?php
$right_required = "AddFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(empty($_POST['newfest'])) {
	
$utc = new DateTimeZone('UTC');
$dt = new DateTime('now', $utc);
$post_target=$basepage."?disp=add_fest";
$outlawcharacters = array(" ", "`", "~", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "_", "+", "=", "{", "}", "|", "\\", "[", "]", ":", ";", "\"", "'", "<", ">", "?", ",", ".", "/");

echo "<form id=\"new_fest\" action=\"".$post_target."\" method=\"post\">";
?>
<h3>Please enter the following information to start with:</h3>

<p>Timezone:
<?php

echo '<select name="userTimeZone">';
$timezone_identifiers = DateTimeZone::listIdentifiers();
foreach($timezone_identifiers as $tz) {
    $current_tz = new DateTimeZone($tz);
    $offset =  $current_tz->getOffset($dt);
    $transition =  $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
    $abbr = $transition[0]['abbr'];

    echo '<option value="' .$tz. '">' .$tz. ' [' .$abbr. ' '. formatOffset($offset). ']</option>';
}
echo '</select>';
?>
</p>

<p>Festival Start Time (make sure this includes the start of the show. Earlier is fine, but it cannot be later than the first band.)
<input type="time" name="start" ></input></p>
<p>Festival Length (The number of hours between the start time and when the last band walks off stage. Longer is OK.)
<input type="number" name="length" min="1" max="24"></input></p>
<p>Festival Name (e.g., Bonnaroo)
<input type="text" name="festname"></input></p>
<p>Festival Year (e.g., 2013)
<input type="text" name="festyear"></input></p>
<input type="submit" value="Create Show" name="newfest"></input>
</form>
<?php
} else {
	echo "Attempting to add festival...";
	$timezone=$_POST['userTimeZone'];
	$feststart=$_POST['start'];
	$festlen=mysql_real_escape_string($_POST['length']);
	$festname=mysql_real_escape_string($_POST['festname']);
	$festyear=mysql_real_escape_string($_POST['festyear']);
	$festnamelower=str_replace($outlawcharacters, "", strtolower($festname));
	$festyearlower=str_replace($outlawcharacters, "", strtolower($festyear));
	$newdb="festival_".$festnamelower."_".$festyearlower;
	$prefest = "";
	$postfest = "";
	for($i=0;$i<=2;$i++){
		$prefest .= randLetter();
	}
	for($i=0;$i<=2;$i++){
		$postfest .= randLetter();
	}
	$newsite=$festname." ".$festyear;
	//Verify that the dbname is unique
	$query="select id from festivals where dbname='$newdb'";
	$result=mysql_query($query, $master);
	If(mysql_num_rows($result) != 0) die;
	//Add the festival into the main table
	$query="insert into festivals (name, year, dbname) VALUES ('$festname', '$festyear', '$newdb')";
	$result=mysql_query($query, $master);
	//Get the id for the festival
	$query="select id from festivals where dbname='$newdb'";
//	echo $query;
	$result=mysql_query($query, $master);
	$row=mysql_fetch_array($result);
	$fest=$row['id'];
	//Create the festival info table in the master db
	$query="CREATE TABLE IF NOT EXISTS `info_$fest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;";
	$result=mysql_query($query, $master);
	//Populate the master info table
	$query="insert into `info_$fest` (item, value) VALUES ('timezone', '$timezone')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival Start Time (24hr time)', '$feststart')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival Length (hours)', '$festlen')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival Name', '$festname')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival Year', '$festyear')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival id', '$fest')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival Identifier Begin', '$prefest')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('Festival Identifier End', '$postfest')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('sitename', '$newsite')";
	$result=mysql_query($query, $master);
	$query="insert into `info_$fest` (item, value) VALUES ('dbname', '$newdb')";
	$result=mysql_query($query, $master);
	
	//Create the database
	$main = mysql_connect($dbhost,$dbuser,$dbpw);
	$query="CREATE DATABASE $newdb";
	$result=mysql_query($query, $main);
	@mysql_select_db($newdb, $main) or die( "Unable to select main database");
	$query="GRANT ALL ON $newdb TO `$dbuser`@`localhost`";
//	echo $query."<br />";
	$result=mysql_query($query, $main);
	//Populate the tables into the new database
	$command = "mysql -u$dbuser -p$dbpw " . "-h $dbhost -D $newdb < ".$baseinstall."install/festival_template.sql";
	$output = shell_exec($command);
	//Fill in the info table
	$query="insert into `info` (item, value) VALUES ('timezone', '$timezone')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival Start Time (24hr time)', '$feststart')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival Length (hours)', '$festlen')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival Name', '$festname')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival Year', '$festyear')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival id', '$fest')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival Identifier Begin', '$prefest')";
	$result=mysql_query($query, $main);
	$query="insert into `info` (item, value) VALUES ('Festival Identifier End', '$postfest')";
	$result=mysql_query($query, $main);
}

}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
