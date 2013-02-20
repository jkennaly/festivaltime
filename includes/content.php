<?php

If(!empty($_GET["disp"])) $disp =  htmlspecialchars($_GET["disp"]);
If(empty($_GET["disp"])) $disp = "home";

//Find all content files that can be displayed
$old_path = getcwd();
chdir($baseinstall."includes/content/");
include $baseinstall."variables/page_variables.php";
$content_files = glob("*.php");
chdir($old_path);

If($disp){
	//If there is a specific type of content requested, and there is a file with that name, display it
	If(in_array($disp . ".php", $content_files)){
		include $baseinstall."includes/content/" . $disp . ".php";
	}
	//If a content file is requested that does not exist, return error
	else{
		include $baseinstall."includes/content/error.php";
	}
}
//If no specific file is requested, return the home content
else{
	include "includes/content/home.php";
}

?>
