
<?php
//This page pulls the fest-specific data from the appropriate info table
If(!empty($_SESSION['fest'])){ $fest = $_SESSION['fest'];}
	echo $fest;
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