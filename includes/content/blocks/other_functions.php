<?php

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

function ratingStars($band, $user, $main, $class, $imgpath, $basepage, $rate_target) {
//This function returns the html text for a string of 5 rating stars, with the number of filled stars equal to rating
    $sql = "select rating from `ratings` where band='$band' and user='$user'";
	$res = mysql_query($sql, $main);
	$rate="";
	If(mysql_num_rows($res) == 0) {
		for($i=1;$i<=5;$i++){
			$rate.="<a href=\"$rate_target&rateband=$i\"><img class=\"$class\" src=\"$imgpath/estar.jpg\"></a>";
		}
	} else {
		$row=mysql_fetch_array($res);
		$empty=5-$row['rating'];
		$filled=$row['rating'];
		for($i=1;$i<=$filled;$i++){
			$rate.="<a href=\"$rate_target&rateband=$i\"><img class=\"$class\" src=\"$imgpath/fstar.jpg\"></a>";
		}
		for($j=1;$j<=$empty;$j++){
			$k=$i+$j;
			$rate.="<a href=\"$rate_target&rateband=$k\"><img class=\"$class\" src=\"$imgpath/estar.jpg\"></a>";
		}
	}	
	return $rate;
}

?>