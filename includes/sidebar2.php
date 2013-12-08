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


ob_start();
?>   


<div id="sidebar2" class="sidebar">

<aside id="popular-bands-widget" class="widget">
<h3 class="wideget-title">Popular Bands</h3>
<ul class="popular-list">
<li class="popular-item">Nine Inch Nails</li>
</ul>
</aside>

<aside id="popular-users-widget" class="widget">
<h3 class="wideget-title">Popular Users</h3>
<ul class="popular-list">
<li class="popular-item">Eric</li>
</ul>
</aside>

<aside id="popular-users-widget" class="widget">
<h3 class="wideget-title">Popular Festivals</h3>
<ul class="popular-list">
<li class="popular-item">Coachella Weekend 1 2014</li>
</ul>
</aside>



</div> <!-- end #sidebar2 -->

<?php
$output = ob_get_contents();
$file = $baseinstall."external/cache-sidebar2.txt";
file_put_contents($file, $output);
ob_flush();


?>



