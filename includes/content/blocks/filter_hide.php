<div id="filter">

<?php
//Set up the fields that will be used for the query

$select = "SELECT bands.name AS name, bands.id AS id, bands.start as stime, bands.end as etime ";
$from = "FROM bands ";
$where = "WHERE ";
$order = " ORDER BY ";
$where_active = 0;
$sort_active = 0;

?>

<p>
Filters:<a class="helplink" href="<?php echo $basepage; ?>?disp=about#filters">Click here for help with this section</a>
</p>
<form action="<? echo $post_target; ?>" method="get">
<input type="submit" name="filter_disp" value="Change filter">
</form>
<form action="<? echo $post_target; ?>#bandlist" method="post">
<input type="submit" name="all_bands" value="Show all bands">
</form>

</div><!-- End #filter -->
