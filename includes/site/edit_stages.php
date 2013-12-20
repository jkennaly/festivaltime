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

$stages = getAllStages();

If(!empty($_POST)){

	foreach($stages as $updated){
		$post_edit = "update-".$updated['id'];
		$post_delete = "delete-".$updated['id'];
//		echo $post_edit."<br />";
		if (!empty($_POST[$post_edit])){
			// Insert into database
			$table = "stages";
			$cols = array("name", "priority", "layout");
			$vals = array($_POST['name'], $_POST['level'], $_POST['layout']);
			$where = "`id`='".$updated['id']."'";
			updateRow($table, $cols, $vals, $where);
		}
		if (!empty($_POST[$post_delete])){
		
			// Insert into database
			$table = "stages";
			$cols = array("deleted");
			$vals = array(1);
			$where = "`id`='".$updated['id']."'";
			updateRow($table, $cols, $vals, $where);
		}
		
	}
	
	If(!empty($_POST['submitNewStage'])){
	
		// Insert into database
		$table = "stages";
		$cols = array("name", "priority", "layout");
		$vals = array($_POST['name'], $_POST['priority'], $_POST['layout']);
		insertRow($table, $cols, $vals);
	
	}
	
	$stages = getAllStages();
}

?>


<div id="content">
<?php 


?>
<p>

This page allows for editing of stage information.

</p>



<?php 
$availPrior = getStagePriorities();

$layouts = getAllStageLayouts($master);
if($stages){
		foreach ($stages as $s){
		$priority = getPriorityInfoFromID($master, $s['priority']);
		?>
		<br /><br />
		<div class="stagewrapper">
		<form action="<?php echo $basepage."?disp=edit_stages"; ?>" method="post" enctype="multipart/form-data">
		<div class="stagename">
			Stage Name: 
			<?php echo $s['name'] ?><br />
		<input size="30" type="text" name="name" value="<?php echo $s['name']; ?>"><br />
		</div> <!-- end .stagename -->
		
		<div class="stagepriority">
		Stage Priority Name: 
		<?php echo $priority['name']; ?>
		<br />
		Stage Priority Description: 
		<?php echo $priority['description']; ?><br />
		<select name="level">
		<?php 
		foreach($availPrior as $a){
			if ($priority['name'] != $a['name'] ) echo "<option value=\"".$a['id']."\">".$a['name']."</option>";
			else echo "<option selected=\"selected\" value=\"".$a['id']."\">".$a['name']."</option>";
		}
		?>
		</select>
		</div> <!-- end .stagepriority -->
		
		<div class="stagelayout">
		Stage Layout: 
		<a href="includes/content/blocks/getPicStageLayout.php?layout=<?php echo $s['layout'] ?>" class="thickbox">
		<?php echo getStageLayoutName($s['layout'], $master ) ?>
		</a><br />
			<select name="layout">
		<?php 
		foreach($layouts as $l){
			if ($l['id'] != $s['layout'] ) echo "<option value=\"".$l['id']."\">".$l['description']."</option>";
			else echo "<option selected=\"selected\" value=\"".$l['id']."\">".$l['description']."</option>";
		}
		?>
		</select>
		</div> <!-- end .stagelayout -->
		
			<input type="submit" name="update-<?php echo $s['id']; ?>" value="Update <?php echo $s['name']; ?>" />
			<input type="submit" name="delete-<?php echo $s['id']; ?>" value="Delete <?php echo $s['name']; ?>" />
		
		</form>	
		</div> <!-- end .stagewrapper -->

	<?php 
		}
}



?>
<form action="<?php echo $basepage."?disp=edit_stages"; ?>" method="post" enctype="multipart/form-data">
<input size="30" type="text" name="name" value="Name of the stage here"><br />
Select Priority: 
<select name="priority">
<?php 
	foreach($availPrior as $a){
		if (1 != $a['default'] ) echo "<option value=\"".$a['id']."\">".$a['name']."</option>";
		else echo "<option selected=\"selected\" value=\"".$a['id']."\">".$a['name']."</option>";
	}
?>
Select Layout: 
</select><br />
		<select name="layout">
	<?php 
	foreach($layouts as $l){
		if ($l['default'] != 1 ) echo "<option value=\"".$l['id']."\">".$l['description']."</option>";
		else echo "<option selected=\"selected\" value=\"".$l['id']."\">".$l['description']."</option>";
	}
	?>
	</select>
<input type="submit" name="submitNewStage" value="Submit">
</form>

<script src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/thickbox.js"></script>
</div> <!-- end #content -->

