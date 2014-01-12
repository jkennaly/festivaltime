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

$festivaltimeContext = 1;

?>

<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <meta name="description" content=""/>

    <meta name="keywords" content=""/>

    <meta name="author" content=""/>

    <link rel="stylesheet" type="text/css" href="styles/style.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="styles/festival_style.css" media="screen"/>
    <link rel="stylesheet" href="styles/thickbox.css" type="text/css" media="screen"/>
    <?php

    session_start();
    if (!empty($_GET['disp']) && $_GET['disp'] == 'logout') unset($_SESSION);
    if (empty($_GET['disp']) && empty($_SESSION['level'])) $_GET['disp'] = 'login';
    if (empty($_GET['disp']) && !empty($_SESSION['level'])) $_GET['disp'] = 'home';

    include('variables/variables.php');

    $master = mysql_connect($dbhost, $master_dbuser, $master_dbpw);
    @mysql_select_db($master_db, $master) or die("Unable to select master database");


    include('includes/content/blocks/database_functions.php');


    If (!empty($_GET['fest']) && isInteger($_GET['fest'])) {
        $_SESSION['fest'] = $_GET['fest'];
    } elseif (isset($_GET['fest'])) unset($_SESSION['fest']);
    include('includes/check_rights.php');
    include('includes/content/blocks/other_functions.php');
    include('includes/content/blocks/SimpleImage.php');
    include $baseinstall . "includes/content/blocks/scoring_functions.php";
    include $baseinstall . "includes/content/blocks/search_selection_function.php";


    If (!empty($_SESSION['fest'])) {

        include('variables/fest_variables.php');


    }

    include('variables/page_variables.php');
    if (!empty($_SESSION['level']) && empty($user)) {
        die('Please login again.');
    }
    if (!empty($user)) $userFestivals = userFestivals($user);
    if (!empty($userFestivals) && !empty($fest)) $checkFest = in_array($fest, $userFestivals);
    else $checkFest = false;
    ?>

    <title><?php echo $sitedesignation ?></title>
    <script type="text/javascript" src="includes/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="includes/js/docflow.js"></script>
    <script src="includes/js/jquery.Jcrop.js"></script>
    <link rel="stylesheet" href="styles/jquery.Jcrop.css" type="text/css"/>

</head>

<body>

<div id="wrapper">
    <?php // var_dump($_SESSION);

    include('includes/header.php');

    if (!empty($user)) include('includes/nav.php');


    If (!empty($_SESSION['fest']) && $_SESSION['fest'] > 0) {

        include('includes/content.php');

    } else include('includes/unselected.php');

    if (!empty($user)) {

        include('includes/sidebar2.php');
    }

    include('includes/footer.php'); ?>

</div>
<!-- End #wrapper -->

</body>
<?php
If (!empty($master)) mysql_close($master);
?>
</html>

