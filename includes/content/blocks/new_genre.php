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


$right_required = "ViewNotes";
If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {

    If (!empty($_POST['genre'])) {

        //handle genres

        $gsql = "select id from bandgenres where band='$band_master_id' and user='$user'";
        $gres = mysql_query($gsql, $master);
        If (mysql_num_rows($gres) > 0) $query = "update bandgenres set genre='" . $_POST['genre'] . "' where band='$band_master_id' and user='$user'";
        else $query = "insert into bandgenres (band, user, genre) values ('$band_master_id', '$user', '" . $_POST['genre'] . "')";
//      echo $query."<br />";
        $gupd = mysql_query($query, $master);
        $genre = $_POST['genre'];
        $genrename = getBandGenre($band, $user);

    }
}