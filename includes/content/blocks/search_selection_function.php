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


//Return a string, formatted according to the users specified search engine
function searchLink($band, $user)
{
//Get user's search engine

    global $master;

    $val = getUserSetting($user, 1);

//Get band name
    $name = getBname($band);

    switch ($val) {
        case "1":
//Youtube
            $link = "<a href=\"http://www.youtube.com/results?search_query=" . urlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on Youtube</a>";
            break;
        case "2":
//Rdio
            $link = "<a href=\"http://www.rdio.com/artist/" . rawurlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on Rdio</a>";
            break;
        case "3":
//Google
            $link = "<a href=\"https://www.google.com/search?q=" . rawurlencode($name) . "\" target=\"_blank\">Search for " . $name . " on Google</a>";
            break;

        case "4":
//Spotify
            $link = "<a href=\"https://play.spotify.com/search/" . rawurlencode($name) . "\" target=\"_blank\">" . $name . "</a>";
            break;

        case "5":
//Yahoo Music
            $link = "<a href=\"http://music.yahoo.com/search/?m=artist&p=" . urlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on Yahoo</a>";
            break;
        case "6":
//Soundcloud
            $link = "<a href=\"https://soundcloud.com/search?q=" . rawurlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on Soundcloud</a>";
            break;
        case "7":
//Myspace
            $link = "<a href=\"http://www.myspace.com/search/music?q=" . rawurlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on MySpace</a>";
            break;
        case "8":
//AOL Music
            $link = "<a href=\"http://music.search.aol.com/search?q=" . urlencode($name) . "&s_it=header_form\" target=\"_blank\">Listen to " . $name . " on AOL</a>";
            break;
        case "9":
//Grooveshark
            $link = "<a href=\"http://html5.grooveshark.com/#!/search/" . rawurlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on Grooveshark</a>";
            break;
        case "10":
//eMusic
            $link = "<a href=\"http://www.emusic.com/search/music/?s=" . urlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on eMusic</a>";
            break;
        default:
//  echo "Youtube";
            $link = "<a href=\"http://www.youtube.com/results?search_query=" . urlencode($name) . "\" target=\"_blank\">Listen to " . $name . " on Youtube</a>";
    }

    return $link;
}

?>
