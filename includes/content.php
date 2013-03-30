#-------------------------------------------------------------------------------
# Copyright (c) 2013 Jason Kennaly.
# All rights reserved. This program and the accompanying materials
# are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
# http://www.gnu.org/licenses/agpl.html
# 
# Contributors:
#     Jason Kennaly - initial API and implementation
#-------------------------------------------------------------------------------
<?php

If(!empty($_GET["disp"])) $disp =  htmlspecialchars($_GET["disp"]);
If(empty($_GET["disp"])) $disp = "home";

//Find all content files that can be displayed
$old_path = getcwd();
chdir($baseinstall."includes/content/");
$content_files = glob("*.php");
chdir($old_path);


	//If there is a specific type of content requested, and there is a file with that name, display it
	If(in_array($disp . ".php", $content_files)){
		include $baseinstall."includes/content/" . $disp . ".php";
	}
	//If a content file is requested that does not exist, return error
	else{
		include $baseinstall."includes/content/error.php";
	}



?>
