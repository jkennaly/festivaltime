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

<?php

//Show all groups the user is a member of
$membersof = in_groups($user, $master);
foreach($membersof as $v) {
    echo "User is in group #".$v['id']." called ".$v['name'].".<br />";
}

//Show all groups the user is elligible to join (including by invitation)

//Show groups the user has permission to invite members to, along with an invite interface

//Show groups the user can invite nonmembers to, along with a reg code and hyperlink

$sql="select * from special_keys where credit='$user'";
$result=mysql_query($sql, $master);
while($row=mysql_fetch_array($result)){
    echo "You can invite people using the key called ".$row['descrip'].", effective beiginning ".$row['effective']." and for ".$row['duration']." days thereafter.<br />";
    echo "The registration code to give for this key is ".$row['key'].", or you can just give this link: ".$basepage."?regcode=".$row['key']."<br /><br />";
}



?>
</div> <!-- end #content -->
