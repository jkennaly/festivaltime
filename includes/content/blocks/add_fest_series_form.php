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
$right_required = "EditFest";
If(!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)){
	die("You do not have rights to access this page. You can login or register here: <a href=\"".$basepage."\">FestivalTime</a>");
}

?>

<form method="post" enctype="multipart/form-data" id="add_fest_series_form">
<input size="100" type="text" name="name" value="Replace this text with a name of the festival series"><br>
<input size="100" type="text" name="descrip" value="Replace this text with a description of the festival series"><br>
<input type="submit" name="submitFestSeries" value="Submit">
<button type="button" id="cancel">Cancel</button>
</form>


