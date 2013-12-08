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


function UpdateTable($source, $target, $table, $sourceuser, $sourcepassword, $sourcehost, $sourcedb, $targetuser, $targetpassword, $targethost, $targetdb, $path){
	//This function will copy table $table from $source database to $target, where $source and $target are the resource links to the databases. If $table already exists at $target, it will be wiped out

	//First find out if the table already exists at $target
	$sql = "select id from `".$table."`";
	$val = mysql_query($sql, $target);

	if($val !== FALSE)
	{
		mysql_query("DROP TABLE IF EXISTS `".$table."`", $target) or die(mysql_error());
	}

	exec("mysqldump --user=$sourceuser --password=$sourcepassword --host=$sourcehost $sourcedb $table > $path$table.sql");

	exec("mysql --user=$targetuser --password=$targetpassword --host=$targethost $targetdb < $path$table.sql");

	exec("rm $path$table.sql");

	return true;
}

function rmTable($target, $table){
	//This function remove table $table from $target database.


	mysql_query("DROP TABLE IF EXISTS `".$table."`", $target) or die(mysql_error());

	return true;
}

function checkTable($source, $target, $stable, $ttable){
	//This function checks to see if $stable in $source matches the $ttable in $target. It retunrs true if they match and false if they do not.

	$sql = "select * from `".$ttable."`";
	$valt = mysql_query($sql, $target);

	if($valt !== FALSE) {
		//Table exists in target

		$sql = "select * from `".$stable."`";
		$vals = mysql_query($sql, $source);
		if($vals !== FALSE) {
			//Table exists in source
			If(mysql_num_rows($valt) == mysql_num_rows($vals)) {
				//They have the same number of rows
				while($row=mysql_fetch_array($vals)) {
					If($row != mysql_fetch_array($valt) ) return false;
				}
				return true;
			}
		} //Closes if($vals !== FALSE)
	} //Closes if($valt !== FALSE)

	return false;
}

function getUname($source, $userid){
	//This function checks $source table Users for the username of $userid

	$sql = "select username from `Users` where id='$userid'";
	$res = mysql_query($sql, $source);
	$urow=mysql_fetch_array($res);
	$uname=$urow['username'];
	return $uname;

}

function getBname($source, $bandid){
	//This function checks $source table Users for the username of $userid

	$sql = "select name from `bands` where id='$bandid'";
	$res = mysql_query($sql, $source);
	$urow=mysql_fetch_array($res);
	$bname=$urow['name'];
	return $bname;

}

function getFname($source, $festid){
	//This function checks $source table Users for the username of $userid

	$sql = "select * from `info_$festid`";
	$res = mysql_query($sql, $source);


	while ($urow=mysql_fetch_array($res)) {
		If($urow['item']== "Festival Name") $fest_name=$urow['value'];
		If($urow['item']== "Festival Year") $fest_year=$urow['value'];
	}

	$fname=$fest_name." ".$fest_year;
	return $fname;

}

function getGname($source, $genreid){
	//This function checks $source table genres for the name of $genreid

	$sql = "select name from `genres` where id=$genreid";
	$res = mysql_query($sql, $source);


	$grow = mysql_fetch_array($res);
	$gname = $grow['name'];

	return $gname;

}

function getBandGenre($main, $master, $band, $user){
	//This function gets the name of a genre for a given user and band

	If($main == $master) {
		$mrow['master_id'] = $band;
	} else {
		//Get the band master_id
		$sql="select master_id from bands where id=$band";
		$res = mysql_query($sql, $main);
		$mrow = mysql_fetch_array($res);
	}

	//If the user has an entry in the genre table for that band, return that genre
	$sql="select genre from bandgenres where band='".$mrow['master_id']."' and user='$user'";
	$res = mysql_query($sql, $master);
	If(mysql_num_rows($res)>0) {
		$row = mysql_fetch_array($res);
		$gid = $row['genre'];
	} else {
		//If the user has no entry, return the genre with the highest count
		$sql1="select genre, count(user) as num from bandgenres where band='".$mrow['master_id']."' group by genre order by num desc limit 1";
		$res1 = mysql_query($sql1, $master);
		If(mysql_num_rows($res1)>0) {
			$row1 = mysql_fetch_array($res1);
			$gid = $row1['genre'];
		} else $gid = 0;
	}
	$gname = getGname($master, $gid);

	return $gname;
}

function getBandGenreID($main, $master, $band, $user){
	//This function gets the id of a genre for a given user and band

	If($main == $master) {
		$mrow['master_id'] = $band;
	} else {
		//Get the band master_id
		$sql="select master_id from bands where id=$band";
		$res = mysql_query($sql, $main);
		$mrow = mysql_fetch_array($res);
	}

	//If the user has an entry in the genre table for that band, return that genre
	$sql="select genre from bandgenres where band='".$mrow['master_id']."' and user='$user'";
	$res = mysql_query($sql, $master);
	If(mysql_num_rows($res)>0) {
		$row = mysql_fetch_array($res);
		$gid = $row['genre'];
	} else {
		//If the user has no entry, return the genre with the highest count
		$sql1="select genre, count(user) as num from bandgenres where band='".$mrow['master_id']."' group by genre order by num desc limit 1";
		$res1 = mysql_query($sql1, $master);
		If(mysql_num_rows($res1)>0) {
			$row1 = mysql_fetch_array($res1);
			$gid = $row1['genre'];
		} else $gid = 0;
	}

	return $gid;
}

function getSname($source, $stageid){
	//This function checks $source table stages for the name of $stageid

	$sql = "select name from `stages` where id=$stageid";
	$res = mysql_query($sql, $source);


	$srow = mysql_fetch_array($res);
	$sname = $srow['name'];

	return $sname;

}


function getFestivals($band, $main, $master){
	//This function returns an array containing the id of each festival the band is registered for
	$sql="select master_id from bands where id='$band'";
	$res = mysql_query($sql, $main);
	$row=mysql_fetch_array($res);
	$master_id = $row['master_id'];

	$sql="select festivals from bands where id='$master_id'";
	$res = mysql_query($sql, $master);
	$row=mysql_fetch_array($res);
	$raw = $row['festivals'];
	$working = explode ("-", $raw);
	$i=0;
	foreach($working as $v) {
		If(isInteger($v)) {
			$final[$i]=$v;
		}
		$i++;
	}



	If(isset($final)) return $final; else return false;

}


function getFollowedBy($user, $master){
	//This function returns an array containing the id of each festival the band is registered for
	$sql="select follows from Users where id='$user'";
	$res = mysql_query($sql, $master);
	$row=mysql_fetch_array($res);
	$raw = $row['follows'];

	$working = explode ("-", $raw);
	$i=0;
	foreach($working as $v) {
		If(isInteger($v)) {
			$final[$i]=$v;
		}
		$i++;
	}



	If(isset($final)) return $final; else return false;

}

function getFestBandIDFromMaster($band_master_id, $festid, $master) {
    $sql="select festivals from bands where id='$band_master_id'";
	$res = mysql_query($sql, $master);
	$row=mysql_fetch_array($res);
	$festString = $row['festivals'];
	
    $sql = "select * from `info_$festid`";
	$res = mysql_query($sql, $master);


	while ($urow=mysql_fetch_array($res)) {
		If($urow['item']== "Festival Identifier Begin") $fest_idb=$urow['value'];
		If($urow['item']== "Festival Identifier End") $fest_ide=$urow['value'];
	}
//	echo "feststring: ".$festString."<br>";
	$posb = strpos($festString, $fest_idb) + 3;
	$pose = strpos($festString, $fest_ide);
	$negpose = 0 - strlen($festString) + $pose;
	$rest = substr($festString, 0, $negpose);
//	echo "rest: ".$rest."<br>";
	$final = substr($rest, $posb);
//	echo "final: ".$final."<br>";
	return $final;
}

function genreList($main, $master, $user){
	//This function returns the genre for each band in main
	$sql = "select id from bands";
	$res = mysql_query($sql, $main);
	$i=0;
	while($row=mysql_fetch_array($res)) {
		$ret_genre[$i]['id'] = getBandGenreID($main, $master, $row['id'], $user);
		$ret_genre[$i]['name'] = getBandGenre($main, $master, $row['id'], $user);
		$ret_genre[$i]['bands'] = 1;
		$ret_genre[$i]['rating_total'] = act_rating($row['id'], $user, $main);
		If( $ret_genre[$i]['rating_total'] == 0) $ret_genre[$i]['rated'] = 0; else $ret_genre[$i]['rated'] = 1;

		$i_minus=0;
		for($j=0;$j<$i;$j++) {
			If($ret_genre[$j]['id'] == $ret_genre[$i]['id']) {
				$i_minus=1;
				$ret_genre[$j]['bands'] = $ret_genre[$j]['bands'] + 1;
				$ret_genre[$j]['rated'] = $ret_genre[$j]['rated'] + $ret_genre[$i]['rated'];
				$ret_genre[$j]['rating_total'] = $ret_genre[$j]['rating_total'] + $ret_genre[$i]['rating_total'];
			}
		}
		If($i_minus == 0 ) $i++;

	}
	return $ret_genre;

}

function acceptComment($main, $master, $user, $band, $fest_id, $comment){
	//This function returns the genre for each band in main
	$sql = "select `master_id` from `bands` where `id`='$band'";
	$res = mysql_query($sql, $main);
	$mas_id = mysql_fetch_assoc($res);
	$band_master_id = $mas_id['master_id'];

	$query="SELECT comment FROM comments WHERE band='$band' AND user='$user'";
	$query_comment = mysql_query($query, $main);
	$num = mysql_num_rows($query_comment);
		$comment = mysql_real_escape_string($comment);

	//New comments
	If ( $num == 0 ) {
		$sql = "INSERT INTO comments (band, user, comment, discuss_current) VALUES ('$band', '$user', '$comment', '--".$user."--')";
		$sql_run = mysql_query($sql, $main);
		//	echo $sql;
		$sql = "INSERT INTO comments (band, user, comment, festival) VALUES ('$band_master_id', '$user', '$comment', '$fest_id')";
		$sql_run = mysql_query($sql, $master);
		//Get id for new comment
		$sql = "select max(id) as disc from comments";
		$sql_run = mysql_query($sql, $main);
		$res = mysql_fetch_array($sql_run);

		//set $discuss_table for comment
		$discuss_table = "discussion_".$res['disc'];

		//Create a discussion table for the comment
		$sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
		$res = mysql_query($sql, $main);
	} else {
		$sql = "UPDATE comments SET comment='$comment' WHERE band='$band' AND user='$user'";
		$sql_run = mysql_query($sql, $main);
		$sql = "UPDATE comments SET comment='$comment' WHERE band='$band_master_id' AND user='$user' and `festival`='$fest_id'";
		$sql_run = mysql_query($sql, $master);
	}
}

function acceptRating($main, $master, $user, $band, $fest_id, $rating){
	$rating = mysql_real_escape_string($rating);
	$sql = "select `master_id` from `bands` where `id`='$band'";
	$res = mysql_query($sql, $main);
	$mas_id = mysql_fetch_assoc($res);
	$band_master_id = $mas_id['master_id'];
	//Find out if an exisiting rating is in
	$query="SELECT rating FROM ratings WHERE band='$band' AND user='$user'";
	$query_rating = mysql_query($query, $main);
	//	echo mysql_error();
	$rating_row = mysql_fetch_assoc($query_rating);

	If (!isset($rating_row['rating']) ) {
		$sql = "INSERT INTO ratings (band, user, rating) VALUES ('$band', '$user', '$rating')";
		$sql_run = mysql_query($sql, $main);
		$sql = "INSERT INTO ratings (band, user, rating, festival) VALUES ('$band_master_id', '$user', '$rating', '$fest_id')";
		$sql_run = mysql_query($sql, $master);
	} else {
		//	echo "With rating logic entered<br>";
		$sql = "UPDATE ratings SET rating='$rating' WHERE band='$band' AND user='$user'";
		$sql_run = mysql_query($sql, $main);
		$sql = "UPDATE ratings SET rating='$rating' WHERE band='$band_master_id' AND user='$user'";
		$sql_run = mysql_query($sql, $master);
	}
}

function acceptDiscussReply($main, $master, $user, $band, $fest_id, $discuss_table, $escapedReply, $commentid){
	$rating = mysql_real_escape_string($rating);
	$sql = "select `master_id` from `bands` where `id`='$band'";
	$res = mysql_query($sql, $main);
	$mas_id = mysql_fetch_assoc($res);
	$band_master_id = $mas_id['master_id'];
	$escapedReply = mysql_real_escape_string($escapedReply);
	//Find out if an exisiting rating is in

	$query = "show tables like '$discuss_table'";
	$result = mysql_query($query, $main);

	If((mysql_num_rows($result) == 0)) {
		//table did not exist, so create it
		$sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
		$res = mysql_query($sql, $main);
	}


	$comment=$commentid;
	$discuss_table=$_POST['discuss_table'];
	$escapedReply = mysql_real_escape_string($_POST['new_reply']);

	$sql = "INSERT INTO $discuss_table (user, response) VALUES ('$user', '$escapedReply')";
	$result = mysql_query($sql, $main);
	//Update the tracking columns in the comment table to reflect the activity
	$query = "UPDATE comments SET discuss_current='--$user--' where id=$comment";
	$upd = mysql_query($query, $main);
}

function submitPregame($main, $master, $submittedJSON){

	if(!empty($submittedJSON['pending_updates']['result'])){
		$serverUpdates = $submittedJSON['pending_updates']['result'];
		foreach($serverUpdates as $upd){
			switch ($upd['type']){
				case "addComment":
					acceptComment($main, $master, $upd['json']['uid'], $upd['json']['bandid'], $upd['json']['festid'], $upd['json']['comment']);
					$response['serverUpdated'][] = $upd['_id'];
					break;
				case "addRating":
					acceptRating($main, $master, $upd['json']['uid'], $upd['json']['bandid'], $upd['json']['festid'], $upd['json']['rating']);
					$response['serverUpdated'][] = $upd['_id'];
					break;
				case "addDiscussionReply":
					acceptDiscussReply($main, $master, $upd['json']['uid'], $upd['json']['bandid'], $upd['json']['festid'], $upd['json']['discussTable'], $upd['json']['discussReply'], $upd['json']['commentID']);
					$response['serverUpdated'][] = $upd['_id'];
					break;
				default:
					$response['error'] = 100;
					$response["error_msg"] = "Update type unknown.";
					break;
			}
		}
	}
	
	$sql = "SELECT DATABASE() as db";
	$res = mysql_query($sql, $main);
	$dbname = mysql_fetch_array($res);
	$maindb = $dbname['db'];

	$query = "SELECT table_name FROM information_schema.tables WHERE table_schema = '$maindb' AND table_name NOT LIKE 'discuss_%'";
	$result = mysql_query($query, $main);
	$submittedTableState = $submittedJSON['tableStates'];

	while($row=mysql_fetch_array($result)) {
		$table_name = $row['table_name'];
//		$debug['tableName'][] = $table_name;
		$tablesql = "SELECT * FROM `$table_name`";
		$tableres = mysql_query($tablesql, $main);
		if(empty($submittedTableState[$table_name])){
//			$debug[] = "Table $table_name submission is empty";
			If((mysql_num_rows($tableres) > 0)) {
				$i = 0;
				while($tablerow=mysql_fetch_assoc($tableres)) {
					$response['localUpdates'][$table_name][$i]['action'] = "add";
					$response['localUpdates'][$table_name][$i]['row'] = $tablerow;
					$i++;
				}
			}
		} else{
//			$debug[] = "Table $table_name submission is not empty";
			$i = 0;
			while($tablerow=mysql_fetch_assoc($tableres)) {
				if($table_name == "live_rating") $timestampCol = "time";
				else $timestampCol = "timestamp";
				$id = $tablerow['id'];
				$serverTimestamp = $tablerow[$timestampCol];
//				$debug[] = "Table $table_name checking id $id";
				unset($mobileTableRowKey);
				foreach($submittedTableState[$table_name]['result'] as $k=>$v){
					if($v['id'] == $id){
//						$debug[] = "Table $table_name checking id $id found key $k";
						$mobileTableRowKey = $k;
						break;
					}
				}
				if(!isset($mobileTableRowKey)){
					$response['localUpdates'][$table_name][$i]['action'] = "add";
					$response['localUpdates'][$table_name][$i]['row'] = $tablerow;
				} else {
					if($submittedTableState[$table_name]['result'][$mobileTableRowKey]['timestamp'] == $tablerow[$timestampCol]){
//						$debug[] = "Table $table_name checking id $id found key $mobileTableRowKey with matching timestamp ".$tablerow[$timestampCol];
						 
					} else {
						$response['localUpdates'][$table_name][$i]['action'] = "update";
						$response['localUpdates'][$table_name][$i]['row'] = $tablerow;
					}
				}
				$i++;
			}
		}
	}
	if(empty($response)) $response['localUpdates'] = "noChange";

	 
	      $response['debug'] = $debug;
	return $response;
}



?>
