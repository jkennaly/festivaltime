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
If( $festtype = 1) $right_required = "EditFest";
If( $festtype = 2 && $user == $festcreator) $right_required = "SimFest";
If( $festtype = 3 && in_group($simfestgroup, $user, $master)) $right_required = "SimFest";
If( $festtype = 4) $right_required = "SimFest";
If(empty($right_required)) $right_required = "Admin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){


//Once the information is submitted, store it in the database
If($_POST){

//Escape entered info

	$escapedDay = mysql_real_escape_string($_POST['day']);
	$escapedDate = mysql_real_escape_string($_POST['date']);
    $simcapped = 0;
    If($right_required == "SimFest") {
        $query="SELECT name, date FROM days ORDER BY date ASC";
        $mem_result = mysql_query($query, $main);
        $num = mysql_num_rows($mem_result);
        $dayofmonth=1+$num;
        
        If($num>9) $simcapped = 1;
        $escapedDate = "2010-01-".$dayofmonth;
    }

//Verify that the day name is not already taken

	$query = "select * from days where name='$escapedDay'";
	$pwq = mysql_query($query, $main);
	$num = mysql_num_rows($pwq);

	If($num>0 || $simcapped == 1){
		echo "That day name is not unique, or cap has been reached. Day not created.";
	}
	else{

		$query = "insert into days (name, date) values ('$escapedDay', '$escapedDate'); ";
		$upd = mysql_query($query, $main);

		
	}

}

//First, find all current days

	$query="SELECT name, date FROM days ORDER BY date ASC";
	$mem_result = mysql_query($query, $main);

?>
<p>

This page allows for adding new days to the festival.

</p>
<form action="index.php?disp=add_days" method="post">
<table border="1">
<tr>
<th>day</th>
<th>date</th>
</tr>
<tr>
<td>
<input type="text" autofocus name="day" maxlength="25" size ="25">
</td>
<td>
<input type="date" name="date">
</td>
</tr>
</table>
<input type="submit">
</form>

<p>
The following days have been added for this festival.
</p>

<table border="1">
<tr>
<th>day</th>
<th>date</th>
</tr>

<?php 
while($row = mysql_fetch_array($mem_result)) {
	echo "<tr><td>".$row["name"]."</td><td>".$row["date"]."</td></tr>";

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
