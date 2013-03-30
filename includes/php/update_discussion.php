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


session_start(); 

include('../../variables/variables.php');

$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");

function isInteger($input){
    return(ctype_digit(strval($input)));
}

If(isset($_GET['fest']) && isInteger($_GET['fest'])) {
	$_SESSION['fest'] = $_GET['fest'];
} 
 include('../../includes/check_rights.php');
 include('../../includes/content/blocks/database_functions.php'); 
include('../../includes/content/blocks/other_functions.php'); 


If(!empty($_SESSION['fest'])){

include('../../variables/fest_variables.php');
//	echo "host=$dbhost user=$master_dbuser2 pw=$master_dbpw2 dbname=$dbname sitename =$sitename<br />";

$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");
}

$user = $_POST['user'];
$comment = $_POST['comment'];



//Check to see if the user is current on this discussion. If not, make it happen
$sql = "select * from comments where id='$comment' AND discuss_current LIKE '%--$user--%'";
$result = mysql_query($sql, $main);
//IF the user is not current on this discussion, set user to be current
If(mysql_num_rows($result) == 0){
	$query = "UPDATE comments SET  discuss_current=CONCAT(discuss_current,'--$user--') where id=$comment";
	$upd = mysql_query($query, $main);
} //Closes If(mysql_num_rows($result) == 0)


?>
