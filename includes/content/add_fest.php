<div id="content">

<?php
$right_required = "AddFest";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

?>
<h3>Please enter the following information to start with:</h3>

<p>Timezone:
<?php
$utc = new DateTimeZone('UTC');
$dt = new DateTime('now', $utc);
$post_target=$basepage."?disp=add_fest";

echo "<form id=\"new_fest\" action=\".$post_target.\" method=\"post\"";
echo '<select name="userTimeZone">';
foreach(DateTimeZone::listIdentifiers() as $tz) {
    $current_tz = new DateTimeZone($tz);
    $offset =  $current_tz->getOffset($dt);
    $transition =  $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
    $abbr = $transition[0]['abbr'];

    echo '<option value="' .$tz. '">' .$tz. ' [' .$abbr. ' '. formatOffset($offset). ']</option>';
}
echo '</select>';
?>
</p>

<p>Festival Start Time (make sure this includes the start of the show. Earlier is fine, but it cannot be later than the first band.)
<input type="time" name="start" value="<?php echo substr($stime, 11, 5) ?>"></p>
<p>Festival Length (The number of hours between the start time and when the last band walks off stage. Longer is OK.)
<input type="number" name="length" min="1" max="24"></p>
<p>Festival Name (e.g., Bonnaroo)
<input type="text" name="festname"></p>
<p>Festival Year (e.g., 2013)
<input type="text" name="festyear"></p>

<?php
}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
