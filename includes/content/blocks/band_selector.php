<?php

/* The band selector function draws a band selector, and POSTs the chosen band
*  to the $post_target page when submit is pressed.
*/

	$query="select name, id from bands";
	$query_band = mysql_query($query);
?>
<form action="<?php echo $post_target; ?>" method="post">
<select name="band">
<?php 
while($row = mysql_fetch_array($query_band)) {
	echo "<option value=".$row['id'].">".$row['name']."</option>";
}
	
?>
</select>
<input type="submit">
</form>
