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
$right_required = "EditFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Once the information is submitted, store it in the database
If($_POST){

//Escape entered info

	$escapedStage = mysql_real_escape_string($_POST['stage']);

//Verify that the day name is not already taken

	$query = "select * from stages where name='$escapedStage'";
	$pwq = mysql_query($query, $main);
	$num = mysql_num_rows($pwq);

	If($num){
		echo "That stage name is not unique. Stage not created.";
	}
	else{

		$query = "insert into stages (name) values ('$escapedStage'); ";
		$upd = mysql_query($query, $main);

		
	}

}

//First, find all current days

mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname) or die( "Unable to select database");
	$query="SELECT name FROM stages ORDER BY id ASC";
	$mem_result = mysql_query($query, $main);

?>
<p>

This page allows for adding new stages to the festival.

</p>
<form action="index.php?disp=add_stages" method="post">
<table border="1">
<tr>
<th>stage</th>
</tr>
<tr>
<td>
<input type="text" autofocus name="stage" maxlength="100" size ="100">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following stages have been added for this festival.
</p>

<table border="1">
<tr>
<th>stage</th>
</tr>

<?php 
while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["name"]."</td></tr>";

}
?>

</table>

<?php



mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
