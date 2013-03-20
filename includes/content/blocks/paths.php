
<?php

//Set some variables for use
$banddecay=0.25; //$banddecay is the rate at which the score drops for a band you are at; there is no decay for the last 5 min
$traveltime = 2; //Traveltime is the number of 5min blocks it takes to go from one placeto another
$mintime = 20; //$mintime is the minimum amount of time the user will stay at a show once committing
$thirstiness = 0.04; //$thristiness affects how fast score for beer tent accumulates



$uscoreall[] = NULL;

If(!empty($_POST['user'])) $scoreuser = $_POST['user'];
If(empty($_POST['user'])) $scoreuser = $user;

$sql="select max(id) as rows from bands";
$res = mysql_query($sql, $main);
$num = mysql_fetch_assoc($res);

$sql = "select avg(rating) as average from ratings where ratings.user='$scoreuser'";

$res = mysql_query($sql, $main);
$arr = mysql_fetch_assoc($res);

$useravgrating = $arr['average'];

$sql = "select username from Users where id='$scoreuser'";
$res = mysql_query($sql, $master);
$user_row = mysql_fetch_array($res);
$scoreusername = $user_row['username'];
//echo "Showing best path for user ".$scoreusername."<br>";


for ($i=1; $i<=$num["rows"]; $i++)
  {
	$sql="select name from bands where id='$i'";
	$res = mysql_query($sql, $main);
	$arr[$i] = mysql_fetch_assoc($res);
	$uscoreall[] = uscoref($i, $scoreuser, $avg_rating, $main);
  	$j=$i;
  }


arsort($uscoreall);

reset($uscoreall);

for ($i=1; $i<=$j; $i++)
  {
	If( $arr[(key($uscoreall))]["name"] ) {

//	echo "<tr><th>$i</th><th><a href=\"".$basepage."?disp=view_band&band=".key($uscoreall)."\">".$arr[(key($uscoreall))]["name"]."</a></th><td>".current($uscoreall)."</td></tr>";
	$bandscore[key($uscoreall)] = current($uscoreall);
	} else {
	$i = $i-1;
	$j=$j-1;
	}
	next($uscoreall);
  
  }


//First draw a grid for Day 1

//Get fest start time and length
$sql="select value from info where item like 'Festival Start Time%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_start_time = $row['value'];

$sql="select value from info where item like 'Festival Length%'";
$res=mysql_query($sql, $main);
$row=mysql_fetch_array($res);
$fest_length = $row['value'];

//$fest_start_time = "10:00";
//fest length must be specified in hours
//$fest_length = 15;

//We will not be using the standard values for these variables
unset($stage);
unset($day);

//Get the list of stages
$sql = "select name, id from stages where name!='Undetermined'";
$res = mysql_query($sql, $main);
while($row=mysql_fetch_array($res)){
	$stage[] = $row;
}
//Get list of days
$sql1 = "select id, name, date from days where name!='Undetermined'";
$res1 = mysql_query($sql1, $main);
while($row=mysql_fetch_array($res1)){
	$day[] = $row;
}

//Loop for each day
for($i=0;$i<mysql_num_rows($res1);$i++) {
unset($currentstage);

$fest_start_time_sec = strtotime($day[$i]['date']." ".$fest_start_time);

//echo "<h3>".$day[$i]['name']."</h3>";
$fest_end_time_sec = $fest_start_time_sec + $fest_length * 3600;

//Loop for each stage
for($j=0;$j<mysql_num_rows($res);$j++) {
	
//Loop for each 5 min increment to collect band data
for ($k=$fest_start_time_sec;$k<($fest_end_time_sec+600);$k=$k+300) {

$band_end = $k+300;
//See if a band is playing at the current time block and pull info if it does
$sql_band = "select id, name, sec_start, sec_end, start, end, stage, genre from bands where sec_end>'$band_end' AND sec_start<='$k' AND stage='".$stage[$j]['id']."'";
$res_band = mysql_query($sql_band, $main);
If(mysql_num_rows($res_band)>0) {
	$band_row=mysql_fetch_array($res_band);
	//Find number of blocks
	$set_time = $band_row['sec_end'] - $band_row['sec_start'];
	$blocks = $set_time/300;
	$rat_sql = "select rating from ratings where user='$scoreuser' and band='".$band_row['id']."'";
	$res_rat = mysql_query($rat_sql, $main);
	$rat_row=mysql_fetch_array($res_rat);
	//Lay down the band name
//	echo "<td class=\"rating".$rat_row['rating']."\">"."<a href=\"".$basepage."?disp=view_band&band=".$band_row['id']."\">".$band_row['name']."\n".getGname($master, $band_row['genre'])."\n".$bandscore[$band_row['id']]."</a></td>";
	$bestpath[$k][$stage[$j]['id']]['band']=$band_row['id'];
	$bestpath[$k][$stage[$j]['id']]['score']=$bandscore[$band_row['id']];
	$bestpath[$k][$stage[$j]['id']]['rating']=$rat_row['rating'];
	$bestpath[$k][$stage[$j]['id']]['name']=$band_row['name'];
	$bestpath[$k][$stage[$j]['id']]['sec_end']=$band_row['sec_end'];
	$bestpath[$k][$stage[$j]['id']]['sec_start']=$band_row['sec_start'];
	$bestpath[$k][$stage[$j]['id']]['stage']=$stage[$j]['id'];
}
}
}

unset($currentshow);
unset($currentbest);
unset($conline);
$travelling=0;
$looking=0;
$moving=1;
$beertent['band']=-1;
$beertent['score']=0;
$beertent['rating']=0;
$beertent['name']="Beer Tent";
$beertent['sec_end']=$fest_end_time_sec;
$beertent['sec_start']=$fest_start_time_sec;
$beertent['stage']=-1;
//echo "var divday".$day[$i]['id']." = document.getElementById('day".$day[$i]['id']."');\n";
$curdiv="divday".$day[$i]['id'];
$showday=$day[$i]['id'];
$curpc=0;
for ($k=$fest_start_time_sec;$k<$fest_end_time_sec;$k=$k+300) {
$beertent['score']=$beertent['score']+$thirstiness;
If(empty($targetset)) $target['score']=-10;
	If(isset($bestpath[$k+600]))  {
		//Find the best 10 min band
		$tenmin = $beertent;
		foreach($bestpath[$k+600] as $v) {
			If($v['score'] > $tenmin['score']) $tenmin = $v;
		}
		//Find the best 20 min band
		$twentymin = $beertent;
		foreach($bestpath[$k+1200] as $v) {
		 	If($v['score'] > $twentymin['score']) $twentymin = $v;
		}
		//Find the best 30 min band
		$thirtymin = $beertent;
		foreach($bestpath[$k+1800] as $v) {
			If($v['score'] > $thirtymin['score']) $thirtymin = $v;
		}
		//Find the best 40 min band
		$fortymin = $beertent;
		foreach($bestpath[$k+2400] as $v) {
			If($v['score'] > $fortymin['score']) $fortymin = $v;
		}
		//Find the best 50 min band
		$fiftymin = $beertent;
		foreach($bestpath[$k+3000] as $v) {
			If($v['score'] > $fiftymin['score']) $fiftymin = $v;
		}
		//Find the best 60 min band
		$sixtymin = $beertent;
		foreach($bestpath[$k+3600] as $v) {
			If($v['score'] > $sixtymin['score']) $sixtymin = $v;
		}
		//Find the best 70 min band
		$seventymin = $beertent;
		foreach($bestpath[$k+4200] as $v) {
			If($v['score'] > $seventymin['score']) $seventymin = $v;
		}
		If ($looking ==1 || $moving ==1) {
			If($looking == 1) {
				/* Old looking logic
				If(($v['score'] > $currentbest['score'] ) && ($v['score'] > $target['score'] ) && ( $v['name'] != $currentbest['name'])) {				
					$target = $v;
					$travelling=1;
					$targetset=1;
				}
				 */
				If($tenmin['name'] != $currentbest['name'] && $tenmin==$thirtymin && $tenmin['score'] >= $currentbest['score'] && !($tenmin != $twentymin && $twentymin == $fortymin)) {
					$target = $tenmin;
					$travelling=1;
					$targetset=1;
					$traveltimeactual=$traveltime;
				}
			}
			If($moving == 1){
					/* Old Moving Logic
					If( $v['score'] > $target['score'] ) $target = $v;
					$travelling=1;
					$targetset=1;
					 */
				If($tenmin==$thirtymin) {
					$target = $tenmin;
					$travelling=1;
					$targetset=1;
					$traveltimeactual=$traveltime;
				}
				If($targetset !=1 && $tenmin['sec_end']>$k+1800) {
					If ($tenmin==$twentymin) {
					$target = $tenmin;
					$travelling=1;
					$targetset=1;
					$traveltimeactual=$traveltime;
					}
					If ($twentymin==$thirtymin && $twentymin==$fortymin && $twentymin==$fiftymin && $twentymin==$sixtymin) {
					$target = $tenmin;
					$travelling=1;
					$targetset=1;
					$traveltimeactual=$traveltime;
					}
					If($targetset !=1) {
					$target = $twentymin;
					$travelling=1;
					$targetset=1;
					If($target['sec_start'] > $k+300) $traveltimeactual=($target['sec_start']-$k)/300; else $traveltimeactual=$traveltime;
					}
				}
			}
		}
		If(($looking ==1 || $moving ==1) && $travelling==1) {
			If($beertent['score'] > $target['score']) $target = $beertent;
			
		}
	};
	If(isset($currentshow)) $pcgone=round(($k-$currentshowstart)*100/($currentshowend-$currentshowstart), 2);
	If (!empty($targetset) && isset($currentshow) && $travelling==1) {
//		echo "var divband$currentshow$pcgone = document.getElementById('band$currentshow');\n";
		$prevdiv=$curdiv;
		$prevpc=$curpc;
		If($currentshow < 0 ) $currentshow="beer";
		$curpc=$pcgone;
		$curdiv = "divband$currentshow";
		$conline .="var $curdiv = document.getElementById('band$currentshow');\n";
		$conline.="connect($prevdiv, $curdiv, $prevpc, $curpc, \"#0F0\", 5);\n";
		If($currentshow =="beer" ) $currentshow=-1;
		
	}
	If($travelling==0 && $moving == 0) {
	//First show of the day
	If(!isset($currentshow)) {
		$currentshow = $currentbest['band'];
		$currentshowstart = $currentbest['sec_start'];
		$currentshowend = $currentbest['sec_end'];
		$status="First show of the day";
		$minhere=0;
		$pcgone=round(($k-$currentshowstart)*100/($currentshowend-$currentshowstart), 2);
//		echo "var divband$currentshow = document.getElementById('band$currentshow');\n";
		$prevdiv=$curdiv;
		$prevpc=$curpc;
		If($currentshow < 0 ) $currentshow="beer";
		$curpc=$pcgone;
		$curdiv = "divband$currentshow";
		$conline ="var $prevdiv = document.getElementById('day$showday');\n";
		$conline .="var $curdiv = document.getElementById('band$currentshow');\n";
		$conline.="connect($prevdiv, $curdiv, $prevpc, $curpc, \"#0F0\", 5);\n";
		$prenode=$currentshow;
		If($currentshow =="beer" ) $currentshow=-1;
		
	}
	//First block seen, but not first show of day
	
	
	//Been at the show more than 20 min
	
	If($minhere>=$mintime) {
		
			If($currentbest['sec_end']>$k+300) {
				$status="Still the best option";
				$currentbest['score']=$currentbest['score']-$banddecay;
				If($currentbest['name']=="Beer Tent") $beertent['score']=$thirstiness;
				$looking=1;
				$moving=0;
			} else {
			$changing=1;
			$status="Finishing up ".$currentbest['name'];
			$looking=0;
			$moving=1;
			
			}
		
	//First 20 min of show
	}
		If($currentbest['sec_end']>$k+300 && $minhere>0 && $minhere<$mintime) {
			$status="Still at ".$currentbest['name'];
			$currentbest['score']=$currentbest['score']-$banddecay;
				If($currentbest['name']=="Beer Tent") $beertent['score']=$thirstiness;
			If($minhere==($mintime-5)) $looking=1; else $looking=0;
			$moving=0;
		} elseif($currentbest['sec_end']<=$k+300 && $minhere>0 && $minhere<$mintime) {
			$status="Finishing up ".$currentbest['name'];
			$looking=0;
			$moving=1;
			
		}
		If($minhere == 0 && $status != "First show of the day") {
				$currentshow = $currentbest['band'];
				$currentshowstart = $currentbest['sec_start'];
				$currentshowend = $currentbest['sec_end'];
				$status="At a new show"; 
				$minhere=0;
				$looking=0;
				$moving=0;
				$pcgone=round(($k-$currentshowstart)*100/($currentshowend-$currentshowstart), 2);
//				echo "var divband$currentshow = document.getElementById('band$currentshow');\n";
		$prevdiv=$curdiv;
		$prevpc=$curpc;
		If($currentshow < 0 ) $currentshow="beer$showday";
		$curpc=$pcgone;
		$curdiv = "divband$currentshow";
		$conline .="var $prevdiv = document.getElementById('band$prenode');\n";
		$conline .="var $curdiv = document.getElementById('band$currentshow');\n";
		$conline.="connect($prevdiv, $curdiv, $prevpc, $curpc, \"#0F0\", 5);\n";
		$prenode=$currentshow;
		If($currentshow =="beer" ) $currentshow=-1;
		} 
	

	$prevshow = $currentshow;
	}
	

	If(isset($currentshow)) $minhere=$minhere+5;
	 
	If($travelling > 0) {
//		If(isset($currentshow))echo "Travelling to ".$target['name']."\n";
//		If(!isset($currentshow)) echo "Travelling to first show\n";
		$looking=0;
		$moving=0;
		$travelling = $travelling+1;
		$prevshow=-1;
		If($travelling > $traveltime) {
			$currentbest = $target;
			$targetset=0;
			$minhere=0;
			$travelling =0;
		}
	}
}

echo $conline;

}
