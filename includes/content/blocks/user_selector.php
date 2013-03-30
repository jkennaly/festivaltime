#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
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
