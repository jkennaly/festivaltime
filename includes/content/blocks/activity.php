<?php
//Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
?>


<div id=\"activity\">

<?php

/* This block displays links to recent activity
*  
*/

echo "<div id=\"flagwrapper\">";
include "includes/content/blocks/flagged.php";
echo "</div><!-- End #flagwrapper -->";

echo "<div class=\"clearfloat\"></div>";

echo "<div id=\"discusswrapper\">";
include "includes/content/blocks/discussed.php";
echo "</div><!-- End #discusswrapper -->";

echo "<div class=\"clearfloat\"></div>";

//If(empty($discuss_pics)) include "includes/content/blocks/pic_row.php";

echo "<div id=\"genreswrapper\">";
include "includes/content/blocks/initial.php";
echo "</div><!-- End #genreswrapper -->";

echo "<div class=\"clearfloat\"></div>";

/*
echo "<div id=\"commentwrapper\">";
include "includes/content/blocks/commented.php";
echo "</div><!-- End #commentwrapper -->";
*/
echo "<div id=\"recommendwrapper\">";
include "includes/content/blocks/recommended.php";
echo "</div><!-- End #recommendwrapper -->";

echo "<div class=\"clearfloat\"></div>";

//End of active bands section

?>

</div><!-- End #activity -->
