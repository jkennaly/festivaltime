<?php

/* The user selector function draws a user selector, and POSTs the chosen band
*  to the $post_target page when submit is pressed.
*/

	$query="select username, id from Users";
	$query_user = mysql_query($query, $master);
?>
<form action="<?php echo $post_target; ?>" method="post">
<select name="user">
<?php 
while($row = mysql_fetch_array($query_user)) {
	echo "<option value=".$row['id'].">".$row['username']."</option>";
}
	
?>
</select>
<input type="submit">
</form>
