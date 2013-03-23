<div id="content">



<?php
$right_required = "CreateNotes";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

If(!empty($_POST)){
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];

  
 $file = $_FILES["file"]["tmp_name"]; 
 // you migh want to escape it just in case
 $data = mysql_real_escape_string(file_get_contents($file)); 
 // Insert into database
 $sql = "INSERT INTO pics (`pic`, `user`, `band`, `name`, `type`, `size`) VALUES ('$data', '$user', '$band', '".$_FILES["file"]["name"]."', '".$_FILES["file"]["type"]."', '".($_FILES["file"]["size"] / 1024)."');";

 $upd = mysql_query($sql, $main);
//echo "<br>".$sql."<br>";
echo mysql_error(); 

  }

}

?>
<p>

This page allows for adding a picture of a band.

</p>

<?php

include $baseinstall."includes/content/blocks/band_info.php";

$link = "<a href=\"http://www.google.com/search?q=".str_replace(" ", "%20", $name)."&tbm=isch\" target=\"_blank\">Search Google for band pics</a>";

?>
<form action="<?php echo $basepage."?disp=pic_band&band=".$band; ?>" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>



<?php
}else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
