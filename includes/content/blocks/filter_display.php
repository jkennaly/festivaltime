#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
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
<form name="filters" action="<? echo $post_target; ?>" method="post">
<div id="filterday" class="filtersection">
<p>
Filter by day:
<ul>
<?php
while($row = mysql_fetch_array($query_day)) {
	echo "<li><input type=\"checkbox\" name=\"day[]\" value=\"".$row["id"]."\">".$row["name"]."</li>";
}
?>
</ul>
</p>
</div><!-- End #filterday -->

<div id="filterstage" class="filtersection">
<p>
Filter by stage:
<ul>
<?php
while($row = mysql_fetch_array($query_stage)) {
	echo "<li><input type=\"checkbox\" name=\"stage[]\" value=\"".$row["id"]."\">".$row["name"]."</li>";
}
?>
</ul>
</p>
</div><!-- End #filterstage -->

<div id="filtergenre" class="dualfiltersection">
<p>
Filter by genre:
<ul>
<?php
while($row = mysql_fetch_array($query_genre)) {
	echo "<li><input type=\"checkbox\" name=\"genre[]\" value=\"".$row["id"]."\">".$row["name"]."</li>";
}
?>
</ul>
</p>
</div><!-- End #filtergenre -->

<div id="sortorder" class="filtersection">
<p>
Sort order:
<ul>
<li><input type="checkbox" name="sort[]" value="name">Alphabetically</li>
<li><input type="checkbox" name="sort[]" value="stime">By start time</li>
<li><input type="checkbox" name="sort[]" value="etime">By end time</li>
<li><input type="checkbox" name="sort[]" value="added">By date added</li>
<li><input type="checkbox" name="sort[]" value="invert">Reverse the selected sort order</li>
</ul>
</p>
</div><!-- End #sortorder -->

<div id="filtertime" class="filtersection">
<p>
Filter by time:
<ul>
<li><input type="checkbox" name="time[]" value="now">Playing now</li>
<li><input type="checkbox" name="time[]" value="onehour">In the next hour</li>
<li><input type="checkbox" name="time[]" value="twohour">In the next 2 hours</li>
</ul>
</p>
</div><!-- End #filtertime -->

<div id="filtercomment" class="filtersection">
<p>
Filter by comments:
<ul>
<li><input type="checkbox" name="comments[]" value="ihave">Bands I HAVE commented on</li>
<li><input type="checkbox" name="comments[]" value="ihavenot">Bands I HAVE NOT commented on</li>
<li><input type="checkbox" name="comments[]" value="none">Bands with NO comments</li>
<li><input type="checkbox" name="comments[]" value="someone">Bands SOMEONE has commented on</li>
<li><input type="checkbox" name="comments[]" value="many">Bands with several comments</li>
</ul>
</p>
</div><!-- End #filtercomment -->

<div id="filterrating" class="filtersection">
<p>
Filter by ratings:
<ul>
<li><input type="checkbox" name="ratings[]" value="ihave">Bands I HAVE rated</li>
<li><input type="checkbox" name="ratings[]" value="ihavenot">Bands I HAVE NOT rated</li>
<li><input type="checkbox" name="ratings[]" value="none">Bands NO ONE has rated</li>
<li><input type="checkbox" name="ratings[]" value="many">Bands many people have rated</li>
<li><input type="checkbox" name="ratings[]" value="high">Bands with a high rating</li>
<li><input type="checkbox" name="ratings[]" value="low">Bands with a low rating</li>
</ul>
</p>
</div><!-- End #filterrating -->

<div id="filterlink" class="filtersection">
<p>
Filter by links:
<ul>
<li><input type="checkbox" name="links[]" value="ihave">Bands I HAVE linked</li>
<li><input type="checkbox" name="links[]" value="ihavenot">Bands I HAVE NOT linked</li>
<li><input type="checkbox" name="links[]" value="none">Bands NO ONE has linked</li>
<li><input type="checkbox" name="links[]" value="someone">Bands SOMEONE has linked</li>
<li><input type="checkbox" name="links[]" value="many">Bands many people have linked</li>
</ul>
</p>
</div><!-- End #filterlink -->

<div id="empty1" class="filtersection">
<p>

</p>
</div><!-- End #empty1 -->

<div id="empty2" class="filtersection">
<p>

</p>
</div><!-- End #empty2 -->

<br />


<?php //If(isset($_SESSION['filter'])) echo "<input type=\"submit\" name=\"old\" value=\"Run my previous filter\">"; ?>
<input type="submit" name="new" value="Use selected filter">
</form>

</div><!-- End #filter -->
