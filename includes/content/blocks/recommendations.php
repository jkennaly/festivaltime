<div id="recommender">

<?php

/* In order for this block to work, the variables $_SESSION['user'] must contain
*  the name of the currently logged in user, and $band must contain the id of the
* band to be recommended. The information will be POSTed back to view_band.php
*/

//Display a selector to make recommendations

$temp_right = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $temp_right)){

	$query="select id, username from Users where username!='".$_SESSION['user']."' AND username IS NOT NULL ";
	$query_users = mysql_query($query, $master);
?>
<form action="index.php?disp=view_band&band=<?php echo $band; ?>" method="post">
<input type="submit" value="Recommend this band to...">
<select name="recommend">
<?php 
while($row = mysql_fetch_array($query_users)) {
	echo "<option value=".$row['id'].">".$row['username']."</option>";
} // Closes while($row = mysql_fetch_array($query_users))
	
?>
</select>
</form>
<?php
} //Closes If(isset($_SESSION['level'])...
	
?>
</div> <!-- end #recommender -->
