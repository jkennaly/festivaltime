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


$right_required = "CreateNotes";
If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {

    If (!empty($_POST['discuss_table']) && !empty($_POST['new_reply']) && !empty($_POST['comment'])) {

        acceptDiscussReply($main, $master, $user, $band, $fest_id, $_POST['discuss_table'], $_POST['new_reply'], $_POST['comment']);
        /*    $discuss_table=$_POST['discuss_table'];

            $query = "show tables like '$discuss_table'";
            $result = mysql_query($query, $main);

            If((mysql_num_rows($result) == 0)) {
        //table did not exist, so create it
                $sql = "CREATE TABLE $discuss_table (id int NOT NULL AUTO_INCREMENT, user int, response varchar(4096), viewed varchar(4096), created TIMESTAMP DEFAULT NOW(), PRIMARY KEY (id))";
                $res = mysql_query($sql, $main);
            }
        }

        If(!empty($_POST['new_reply'])){
            $comment=$_POST['comment'];
            $discuss_table=$_POST['discuss_table'];
            $escapedReply = mysql_real_escape_string($_POST['new_reply']);

            $sql = "INSERT INTO $discuss_table (user, response) VALUES ('$user', '$escapedReply')";
            $result = mysql_query($sql, $main);
        //Update the tracking columns in the comment table to reflect the activity
            $query = "UPDATE comments SET discuss_current='--$user--' where id=$comment";

            $upd = mysql_query($query, $main);
            */
    } //Closes If($_POST['new_reply'])

}

