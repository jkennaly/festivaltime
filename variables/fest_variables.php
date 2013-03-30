/*
*Copyright (c) 2013 Jason Kennaly.
*All rights reserved. This program and the accompanying materials
*are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
*http://www.gnu.org/licenses/agpl.html
*
*Contributors:
*    Jason Kennaly - initial API and implementation
*/


<?php
//This page pulls the fest-specific data from the appropriate info table
If(!empty($_SESSION['fest'])){ $fest = $_SESSION['fest'];}
//	echo "fest_var ".$fest;
	$sql="select * from info_".$fest;
	$result = mysql_query($sql, $master);
	while($row=mysql_fetch_array($result)) {
		switch($row['item']) {
			case "sitename":
				$sitename=$row['value'];
				break;
			case "dbname":
				$dbname=$row['value'];
				break;
		}
	}
?>
