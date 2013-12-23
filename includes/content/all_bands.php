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


$right_required = "ViewNotes";
If (isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)) {
    ?>

    <div id="content">
        <?php


        include "includes/content/blocks/filter_functions.php";

        //Sets the target for all POST actions
        $post_target = $basepage . "?disp=home";

        //Find genre of every band in main
        $sql = "select id, name from bands order by rand()";
        $res = mysql_query($sql, $main);
        while ($row = mysql_fetch_array($res)) {
            $bandgenreid[$row['id']] = getBandGenreID($row['id'], $user);
            $bandscore[$row['id']] = uscoref2($row['id'], $user);
            $bandgenrename[$row['id']] = getBandGenre($row['id'], $user);
            $bandname[$row['id']] = $row['name'];
            $bandid[$row['id']] = $row['id'];
        }

        for ($i = 0; $i <= max($bandgenreid); $i++) {
            foreach ($bandgenreid as $k => $v) {
                If ($i == $v) {
                    $genreheading[$i] = "<h2>" . $bandgenrename[$k] . "</h2>";
                    $banddisplayname[$i][] = $bandname[$k];
                    $banddisplayscore[$i][] = $bandscore[$k];
                    $banddisplayid[$i][] = $bandid[$k];
                }
            }
        }

        foreach ($genreheading as $k => $v) {
            echo "<div class=\"clearfloat\"></div>";
            echo $v;
            foreach ($banddisplayname[$k] as $key => $name) {
                $genredisp = "<table class=\"bandcap\"><caption align=\"bottom\">" . $name;
                $genredisp .= "</caption><tr><td class=\"pic_cell\"><a class=\"pic_row_pic\" href=\"";
                $genredisp .= $basepage . "?disp=view_band&band=" . $banddisplayid[$k][$key] . "\"><img src=\"" . $basepage . "includes/content/blocks/getPicture.php?band=";
                $genredisp .= $banddisplayid[$k][$key] . "&fest=" . $_SESSION['fest'] . "\" alt=\"band pic\" /></a></td></tr></table>";

                echo $genredisp;
            }
        }

        //Display options for sorting bands
        //Show only bands I haven't rated
        //Show only unrated bands
        //Show bands by other festivals
        //By genre
        //By rating
        //


        ?>
    </div> <!-- end #content -->
    <script type="text/javascript">
        $(function () {
            screenWidth('content');
            leftMargin('wrapper');
            leftMargin('header');
        });
    </script>
<?php
} else {
    echo "This page requires a higher level access than you currently have.";

    include $baseinstall . "includes/site/login.php";
}

?>

