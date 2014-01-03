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


//echo "<p><a href=\"".$basepage."?disp=view_band\">Click here to choose from a list of all the bands</a> or select Home from the Nav bar up top to use the filters</p>";


$post_target = $basepage . "?disp=view_band&band=$band";

// Collect data display comments, etc.
//If $band is defined

include $baseinstall . "includes/content/blocks/band_info.php";


//include "includes/content/blocks/recommendations.php";

//include "includes/content/blocks/liveranked.php";

