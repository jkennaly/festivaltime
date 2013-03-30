<div id="content">

<p>

This page allows for editing existing bands.

</p>

<?php
$right_required = "AddBands";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

$post_target=$basepage."?disp=edit_bands";


include "blocks/band_info_edit.php";


include "blocks/band_selector.php";

mysql_close();
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
