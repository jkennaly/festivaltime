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

    If (!empty($_POST['new_comment'])) {
        acceptComment($main, $master, $user, $band, $fest_id, $_POST['new_comment']);


    }
}
