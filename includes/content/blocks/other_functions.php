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
  if(is_array($needle)) {
    foreach ($needle as $n) {
      if (strpos($haystack, $n) !== false) {$i=1; break;}
    }
  }
  else If (strpos($needle, $haystack) !== false) $i=1;
  return $i;
} 

function email_bad($email) {
    $i=0;
    $atloc = strpos($email, '@');
    $dotloc = strpos($email, '.', $atloc);
    If( $atloc < 1) {$i=1; echo $atloc."at loc<br />";}
    If( $dotloc < $atloc + 1) {$i=1; echo $dotloc."dot loc<br />";}
    If( strlen($email) < 6) {$i=1; echo 3;}
    
  return $i;
}  

function email_bad2($email) {
    $i=0;
    $atloc = strpos($email, '@');
    $dotloc = strpos($email, '.', $atloc);
    If( $atloc < 1) {$i=1;}
    If( $dotloc < $atloc + 1) {$i=1;}
    If( strlen($email) < 6) {$i=1;}
    
  return $i;
}  

function total_ratings($user, $main) {
    $sql="select count(id) as total from ratings where user='$user'";
//    echo $sql;
    $result = mysql_query($sql, $main);
    $row=mysql_fetch_array($result);
    return $row['total'];
}

function rating_count($user, $rating, $main) {
    $sql="select count(id) as total from ratings where user='$user' and rating='$rating'";
//    echo $sql;
    $result = mysql_query($sql, $main);
    $row=mysql_fetch_array($result);
    If(empty($row['total'])) $row['total'] = 0;
    return $row['total'];
}

?>
