<?php

//display results
If(mysql_num_rows($result)>0) {
echo "<div id=\"bandlist\" class=\"bandlist\"><p><ul>";
echo "<p>Displaying ".mysql_num_rows($result)." results</p>";

while($row = mysql_fetch_array($result)) {
	echo "<li><a href=\"".$basepage."?disp=view_band&band=".$row["id"]."\">".$row["name"]."</a></li>";
}

echo "</p></ul></div>";
} //Closes If(!empty(mysql_num_rows($result)))

?>
