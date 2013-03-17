<div id=\"activity\">

<?php

/* This block displays links to recent activity
*  
*/


echo "<div id=\"discusswrapper\">";
include "includes/content/blocks/discussed.php";
echo "</div><!-- End #discusswrapper -->";

//If(empty($discuss_pics)) include "includes/content/blocks/pic_row.php";

echo "<div id=\"genreswrapper\">";
include "includes/content/blocks/genres.php";
echo "</div><!-- End #genreswrapper -->";

/*
echo "<div id=\"commentwrapper\">";
include "includes/content/blocks/commented.php";
echo "</div><!-- End #commentwrapper -->";
*/
echo "<div id=\"recommendwrapper\">";
include "includes/content/blocks/recommended.php";
echo "</div><!-- End #recommendwrapper -->";


//End of active bands section

?>

</div><!-- End #activity -->
