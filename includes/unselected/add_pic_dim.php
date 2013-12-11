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


$right_required = "Admin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

	$sql = "SELECT * FROM `pics`";
	$res = mysql_query($sql, $master);
	while ($row = mysql_fetch_array($res)){
//		echo $row['id'];
		file_put_contents('image.jpg', $row['pic']);

		$image = new SimpleImage();
		$image->load('image.jpg');
		switch ($row['shape']){
			case "large_square":
				$image->resize(410,410);
				break;
			case "small_square":
				$image->resize(205,205);
				break;
			case "horizontal_rectangle":
				$image->resize(410,205);
				break;
			case "vertical_rectangle":
				$image->resize(205,410);
				break;
			default:
				break;
		}
		
		$image->save('image.jpg');
		$data = mysql_real_escape_string(file_get_contents('image.jpg'));
		$query = "UPDATE `pics` SET scaled_pic='$data' WHERE id='".$row['id']."'";
		$result = mysql_query($query, $master);
//		echo "<br>".$query;
		unlink('image.jpg');
	}

}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
