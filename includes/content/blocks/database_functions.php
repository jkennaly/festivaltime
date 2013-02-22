<?php

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

?>
