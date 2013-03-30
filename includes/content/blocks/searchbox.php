<div id=\"searchbox\">

<?php

/* This block displays a search box
*  
*/


?>
<div id="searchbox">
<form action="<?php echo $basepage."?disp=search"; ?>" method="post">
<input type="submit" value="Search">
<input type="text" size="40" name="search_query" autofocus="autofocus"></textarea>
<input type="hidden"name="bands" value="true">
<input type="hidden" name="comments" value="true">
<?php
$temp_right = "CreateNotes";
If(!empty($_SESSION['level'])) {If(CheckRights($_SESSION['level'], $temp_right)) echo "<input type=\"hidden\" name=\"discussions\" value=\"true\">";}
?>

</form>
</div> <!-- end #searchbox -->

<?php
//End of searchbox

?>

</div><!-- End #searchbox -->
