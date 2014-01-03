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

if($festivaltimeContext != 1) die('This page load was out of context');

//This page pulls the fest-specific data from the appropriate info table
If(!empty($_SESSION['fest'])){ $fest = $_SESSION['fest'];}
//	echo "fest_var ".$fest;
$header = getFestHeader($fest);
$sitename=$header['sitename'];
$festSeries = $header['series'];
	
?>