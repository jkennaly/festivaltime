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


//Get format for link


include('includes/content/blocks/new_genre.php');

$sets = getBandSetsByFestival($band);
date_default_timezone_set('UTC');

foreach ($sets as $set) {
    $startSec = $header['start_time'] + $set['start'];
    $endSec = $header['start_time'] + $set['end'];

    $startString = strftime('%l:%M %p', $startSec);
    $endString = strftime('%l:%M %p', $endSec);

    $timeLine[] = getDname($set['day']) . " " . getPname($set['stage']) . " " . $startString . "-" . $endString;
}


If ($_GET['disp'] == "view_band") $bandlink = searchlink($band, $user);
else $bandlink = "<a href=\"" . $basepage . "?disp=view_band&band=" . $band . "\">" . $name . "</a>";

If ($_GET['disp'] == "pic_band") $piclink = $basepage . "?disp=band_gallery&band=" . $band;
else $piclink = $basepage . "?disp=pic_band&band=" . $band;

//	echo "Clicking the band name will open a new window and search for the band. Change search engine from My Account -> User Settings.";


$query = "select name, id from genres order by name asc";
$query_genre = mysql_query($query, $master);

$genreList = genreList($user);
$bandGenreID = getBandGenreID($band, $user);
$rating = getAverageRatingForBandByUsersFollowers($user, $band);

?>
<h1 id="bandtitle">
    <?php
    echo $bandlink;
    ?></h1>

<div id=bandvitals">

    <a href="<?php echo $piclink ?>"><img id="band_pic_home"
                                          src="includes/content/blocks/getPicture.php?band=<?php echo $band; ?>&fest=<?php echo $_SESSION['fest']; ?>"
                                          alt="click to add a picture of the band"/></a>

    <?php
    foreach ($timeLine as $t) {
        ?>
        <p class="band_info"><?php echo $t; ?></p>
    <?php
    }
    ?>

    <p class="band_info">

    <form action="index.php?disp=view_band&band=<?php echo $band; ?>" method="post"><select name="genre">
            <?php
            foreach ($genreList as $g) {
                If ($g['id'] == $bandGenreID) echo "<option selected=\"selected\" value=" . $g["id"] . ">" . $g["name"] . "</option>";
                else echo "<option value=" . $g["id"] . ">" . $g["name"] . "</option>";
            }
            ?>
        </select><input type="submit" value="Change Genre"/></form>
    </p>
    <p class="band_info"><?php echo "Group average rating: " . round($rating, 1); ?></p>
</div> <!-- end #bandvitals -->
