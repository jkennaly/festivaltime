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


?>

<div id="content">
    
<p>This page allows for transferring comments and ratings from one festival to another. Warning: any comment/rating in the target festival will be overwritten by the comment/rating from the source festival.</p>

<?php

$source_comments_sql = "select c.comment as com, b.master_id as mas, c.discuss_current as cur from comments as c join bands as b on c.band=b.id where c.user='$user'";
$source_rating_sql = "select r.rating as rat, b.master_id as mas from ratings as r join bands as b on r.band=b.id where r.user='$user'";


If(!empty($_POST)){
    
//First grab the data from the source db

$dbname = $_POST['source'];    
$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");

$sres = mysql_query($source_comments_sql, $main);
$rres = mysql_query($source_rating_sql, $main);

while($row = mysql_fetch_array($sres)){
    $scommarr[] = $row;
}

while($row = mysql_fetch_array($rres)){
    $sratarr[] = $row;
}

//Now that all the applicable data has been pulled, switch to the target db

$dbname = $_POST['target'];    
$main = mysql_connect($dbhost,$dbuser,$dbpw);
@mysql_select_db($dbname, $main) or die( "Unable to select main database");

foreach($scommarr as $v){
    $v['com'] = mysql_real_escape_string($v['com']);
    $sql = "select id from bands where master_id='".$v['mas']."'";
    $res = mysql_query($sql, $main);
    If(mysql_num_rows($res) > 0){
        $rowb = mysql_fetch_array($res);
        $new_sql = "select id from comments where user='$user' and band='".$rowb['id']."'";
        $new_res = mysql_query($new_sql, $main);
        If(mysql_num_rows($new_res) > 0){
            $row = mysql_fetch_array($new_res);
            $upd_sql = "update comments set comment='".$v['com']."' where id='".$row['id']."'";
            $upd_res = mysql_query($upd_sql, $main);
        } else {
            $ins_sql = "insert into comments (band, user, comment, discuss_current) values ('".$rowb['id']."', '$user', '".$v['com']."', '".$v['cur']."')";
            echo $ins_sql."<br />";
            $ins_res = mysql_query($ins_sql, $main);
        }
    }
}

foreach($sratarr as $v){
    $sql = "select id from bands where master_id='".$v['mas']."'";
    $res = mysql_query($sql, $main);
    If(mysql_num_rows($res) > 0){
        $rowb = mysql_fetch_array($res);
        $new_sql = "select id from ratings where user='$user' and band='".$rowb['id']."'";
        $new_res = mysql_query($new_sql, $main);
        If(mysql_num_rows($new_res) > 0){
            $row = mysql_fetch_array($new_res);
            $upd_sql = "update ratings set rating='".$v['rat']."' where id='".$row['id']."'";
            $upd_res = mysql_query($upd_sql, $main);
        } else {
            $ins_sql = "insert into ratings (band, user, rating) values ('".$rowb['id']."', '$user', '".$v['rat']."')";
            $ins_res = mysql_query($ins_sql, $main);
        }
    }
}

}
$post_target=$basepage."?disp=comment_xfer";

    $query="select CONCAT(name, \" \", year) as name, dbname from festivals order by id asc";
//    echo $query;
    $query_fest = mysql_query($query, $master);
    $query_fest1 = mysql_query($query, $master);

?>
<form action="<?php echo $post_target; ?>" method="post">
Source Festival:
<select name="source">
<?php 
while($row = mysql_fetch_array($query_fest)) {
    echo "<option value=".$row['dbname'].">".$row['name']."</option>";
}
    
?>
</select>
<br />
Target Festival:
<select name="target">
<?php 
while($row = mysql_fetch_array($query_fest1)) {
    echo "<option value=".$row['dbname'].">".$row['name']."</option>";
}
    
?>
</select>
<br />
<input type="submit">
</form>

</div> <!-- end #content -->
