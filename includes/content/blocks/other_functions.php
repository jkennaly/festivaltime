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


function formatOffset($offset) {
        $hours = $offset / 3600;
        $remainder = $offset % 3600;
        $sign = $hours > 0 ? '+' : '-';
        $hour = (int) abs($hours);
        $minutes = (int) abs($remainder / 60);

        if ($hour == 0 AND $minutes == 0) {
            $sign = ' ';
        }
        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');

}

function randLetter() {
    $int = rand(0,51);
    $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rand_letter = $a_z[$int];
    return $rand_letter;
}

function randAlphaNum() {
    $int = rand(0,35);
    $a_z = "012345679ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rand_letter = $a_z[$int];
    return $rand_letter;
}

function ratingStars($band, $user, $main, $class, $imgpath, $basepage, $rate_target) {
//This function returns the html text for a string of 5 rating stars, with the number of filled stars equal to rating
    $sql = "select rating from `ratings` where band='$band' and user='$user'";
	$res = mysql_query($sql, $main);
	$rate="";
	If(mysql_num_rows($res) == 0) {
		for($i=1;$i<=5;$i++){
			$rate.="<a href=\"$rate_target&rateband=$i\"><img class=\"$class\" title=\"Rate the band a $i\" src=\"$imgpath/estar.jpg\"></a>";
		}
	} else {
		$row=mysql_fetch_array($res);
		$empty=5-$row['rating'];
		$filled=$row['rating'];
		for($i=1;$i<=$filled;$i++){
			$rate.="<a href=\"$rate_target&rateband=$i\"><img class=\"$class\" title=\"Rate the band a $i\" src=\"$imgpath/fstar.jpg\"></a>";
		}
		for($j=1;$j<=$empty;$j++){
			$k=$i+$j-1;
			$rate.="<a href=\"$rate_target&rateband=$k\"><img class=\"$class\" src=\"$imgpath/estar.jpg\"></a>";
		}
	}	
	return $rate;
}

function displayStars($band, $user, $main, $class, $imgpath) {
//This function returns the html text for a string of 5 rating stars, with the number of filled stars equal to rating
    $sql = "select rating from `ratings` where band='$band' and user='$user'";
	$res = mysql_query($sql, $main);
	$rate="";
	If(mysql_num_rows($res) == 0) {
		
	} else {
		$row=mysql_fetch_array($res);
		$empty=5-$row['rating'];
		$filled=$row['rating'];
		for($i=1;$i<=$filled;$i++){
			$rate.="<img class=\"$class\" src=\"$imgpath/fstar.jpg\">";
		}
	}	
	return $rate;
}

function in_string($needle, $haystack) {
    $i=0;
        echo "Testing $needle with $haystack<br />";
  if(is_array($needle)) {
    foreach ($needle as $n) {
        echo "Testing $n with $haystack<br />";
      if (strpos($haystack, $n) != false) $i=1;
    }
  }
  else If (strpos($needle, $haystack) != false) $i=1;
} 
  If($i !== 0) return true; return false;

?>
