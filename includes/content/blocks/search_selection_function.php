<?php
//Retunrs a string, formatted according to the users spec'd search engine
function searchlink($band, $user, $mysql_link, $mysql_link2) {
//Get user's search engine

$sql = "select value from user_settings_$user where item='Search Engine'";
$res = mysql_query($sql, $mysql_link2);
$val = mysql_fetch_array($res);

//Get band name
$sql = "select name from bands where id='$band'";
$res = mysql_query($sql, $mysql_link);
$value = mysql_fetch_array($res);
$name=$value['name'];

switch ($val['value'])
{
case "1":
//Youtube
  $link = "<a href=\"http://www.youtube.com/results?search_query=".str_replace(" ", "+", $name)."\" target=\"_blank\">".$name."</a>"; 
  break;
case "2":
//Rdio
  $link = "<a href=\"http://www.rdio.com/artist/".str_replace(" ", "%20", $name)."\" target=\"_blank\">".$name."</a>";
  break;
case "3":
//Google
  $link = "<a href=\"https://www.google.com/search?q=".str_replace(" ", "%20", $name)."\" target=\"_blank\">".$name."</a>";
  break;
/*
case "4":
//Spotify
  $link = "<a href=\"http://ws.spotify.com/search/1/artist?q=".str_replace(" ", "+", $name)."\" target=\"_blank\">".$name."</a>";
  break;
*/
case "5":
//Yahoo Music
  $link = "<a href=\"http://music.yahoo.com/search/?m=artist&p=".str_replace(" ", "-", $name)."\" target=\"_blank\">".$name."</a>";
  break;
case "6":
//Soundcloud
  $link = "<a href=\"https://soundcloud.com/search?q=".str_replace(" ", "%20", $name)."\" target=\"_blank\">".$name."</a>";
  break;
case "7":
//Myspace
  $link = "<a href=\"http://www.myspace.com/search/music?q=".str_replace(" ", "%20", $name)."\" target=\"_blank\">".$name."</a>";
  break;
case "8":
//AOL Music
  $link = "<a href=\"http://music.search.aol.com/search?q=".str_replace(" ", "+", $name)."&s_it=header_form\" target=\"_blank\">".$name."</a>";
  break;
case "9":
//Grooveshark
  $link = "<a href=\"http://html5.grooveshark.com/#!/search/".str_replace(" ", "%20", $name)."\" target=\"_blank\">".$name."</a>";
  break;
case "10":
//eMusic
  $link = "<a href=\"http://www.emusic.com/search/music/?s=".str_replace(" ", "+", $name)."\" target=\"_blank\">".$name."</a>";
  break;
default:
//  echo "Youtube";
  $link = "<a href=\"http://www.youtube.com/results?search_query=".str_replace(" ", "+", $name)."\" target=\"_blank\">".$name."</a>"; 
}




echo $link;

return 1;
}

?>
