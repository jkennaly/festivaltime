
<?php


//Pull the POST data into regular arrays
if(!empty($_POST['day'])) {
	$where .= MatchFilter($_POST['day'], "day", "bands");
	$where_active = 1;
}

if(!empty($_POST['stage'])) {
    foreach($_POST['stage'] as $check) {
            $stage[]= $check;
    }
}

if(!empty($_POST['time'])) {
    foreach($_POST['time'] as $check) {
            $time[]= $check;
    }
}

if(!empty($_POST['comments'])) {
    foreach($_POST['comments'] as $check) {
            $comments[]= $check;
    }
}

if(!empty($_POST['ratings'])) {
    foreach($_POST['ratings'] as $check) {
            $ratings[]= $check;
    }
}

if(!empty($_POST['links'])) {
    foreach($_POST['links'] as $check) {
            $links[]= $check;
    }
}



//process filters

if(!empty($stage)) {
	If ($where_active == 1) $where .= " AND ";
	$where .= MatchFilter($stage, "stage", "bands");
	$where_active = 1;
}

if(!empty($time)) {
    foreach($time as $check) {
            echo "Time condition ".$check." is selected.<br>";
    }
}

if(!empty($comments)) {
    foreach($comments as $check) {

	If ($check == "ihave") {
	echo "Displaying bands I have commented on.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalIncludeFilter("id", "bands", "band", "comments", "user", $userid, $main);
		$where_active = 1;
	}


	If ($check == "ihavenot") {
	echo "Displaying bands I have not commented on.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalExcludeFilter("id", "bands", "band", "comments", "user", $userid, $main);
		$where_active = 1;
	}

	If ($check == "none") {
	echo "Displaying bands no one has commented on.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalExcludeFilter("id", "bands", "band", "comments", "'1'", "1", $main);
		$where_active = 1;

	}

	If ($check == "someone") {
	echo "Displaying bands someone has commented on.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalIncludeFilter("id", "bands", "band", "comments", "'1'", "1", $main);
		$where_active = 1;

	}

	If ($check == "many") {
	echo "Displaying bands many have commented on.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalMinimumFilter("id", "bands", "band", "comments", "count(*)", "2", $main);
		$where_active = 1;

	}
    }
}



if(!empty($ratings)) {

	foreach($ratings as $check) {
		If ($where_active == 1) $where .= " AND ";
		switch ($check)

{
case "ihave":
	echo "Displaying bands I have rated.<br>";
	$where .= ExternalIncludeFilter("id", "bands", "band", "ratings", "user", $userid, $main);
  break;
case "ihavenot":
	echo "Displaying bands I have not rated.<br>";
		$where .= ExternalExcludeFilter("id", "bands", "band", "ratings", "user", $userid, $main);
  break;
case "none":
	echo "Displaying bands no one has rated.<br>";
	$where .= ExternalExcludeFilter("id", "bands", "band", "ratings", "'1'", "1", $main);
  break;
case "someone":
	echo "Displaying bands someone has rated.<br>";
	$where .= ExternalIncludeFilter("id", "bands", "band", "ratings", "'1'", "1", $main);
  break;
case "many":
	echo "Displaying bands many have rated.<br>";
	$where .= ExternalMinimumFilter("id", "bands", "band", "ratings", "count(*)", "2", $main);
  break;
case "high":
	echo "Displaying bands with a high rating.<br>";
	$where .= ExternalMinimumFilter("id", "bands", "band", "ratings", "avg(rating)", "3.5", $main);
  break;
case "low":
	echo "Displaying bands with a low rating.<br>";
	$where .= ExternalMaximumFilter("id", "bands", "band", "ratings", "avg(rating)", "2.5", $main);
  break;
}
$where_active = 1;
	}
}

if(!empty($links)) {

    foreach($links as $check) {

	If ($check == "ihave") {
	echo "Displaying bands I have linked.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalIncludeFilter("id", "bands", "band", "links", "user", $userid, $main);
		$where_active = 1;
	}


	If ($check == "ihavenot") {
	echo "Displaying bands I have not linked.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalExcludeFilter("id", "bands", "band", "links", "user", $userid, $main);
		$where_active = 1;
	}

	If ($check == "none") {
	echo "Displaying bands no one has linked.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalExcludeFilter("id", "bands", "band", "links", "'1'", "1", $main);
		$where_active = 1;

	}

	If ($check == "someone") {
	echo "Displaying bands someone has linked.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalIncludeFilter("id", "bands", "band", "links", "'1'", "1", $main);
		$where_active = 1;

	}

	If ($check == "many") {
	echo "Displaying bands many have linked.<br>";

		If ($where_active == 1) $where .= " AND ";
		$where .= ExternalMinimumFilter("id", "bands", "band", "links", "count(*)", "2", $main);
		$where_active = 1;

	}
    }
}


if(!empty($_POST['sort'])) {



	foreach($_POST['sort'] as $check) {
		switch ($check)

		{
			case "name":
				If( $sort_active != 1) {
					echo "Bands sorted alphabetically.<br>";
					$order .= "name ";
					$sort_active = 1;
				}
  				break;
			case "stime":
				If( $sort_active != 1) {
					echo "Bands sorted by start time.<br>";
					$order .= "stime ";
					$sort_active = 1;
				}
				break;
			case "etime":
				If( $sort_active != 1) {
					echo "Bands sorted by end time.<br>";
					$order .= "etime ";
					$sort_active = 1;
				}
				break;
			case "added":
				If( $sort_active != 1) {
					echo "Bands sorted by date added to the system.<br>";
					$order .= "id ";
					$sort_active = 1;
				}
				break;
			case "invert":
				If( $sort_active == 1) {
					echo "Sort order is reversed.<br>";
					$order .= "desc";
				}
				break;
		} //Close Switch case
	} //Close foreach
}//Close If



//compose query

$sql = $select;
$sql .= $from;

If(strlen($where) > 6) $sql .= $where;
If($sort_active == 1) $sql .= $order;

// echo "<br>$sql<br>";

$result = mysql_query($sql, $main);


?>

