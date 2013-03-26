<?php

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