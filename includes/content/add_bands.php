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


?>

<div id="content">

<?php
If( $festtype == 1) $right_required = "AddBands"; else $right_required = "SimFest";
/*
If( $festtype == 2 && $user == $festcreator) $right_required = "SimFest";
If( $festtype == 3 && in_group($simfestgroup, $user, $master)) $right_required = "SimFest";
If( $festtype == 4) $right_required = "SimFest";
*/
If(empty($right_required)) $right_required = "Admin";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){
    
    $post_target=$basepage."?disp=add_bands";



//Once the information is submitted, store it in the database
If(!empty($_POST)){
    
    If(isset($_POST['new_band']) && $right_required == "AddBands"){

    //Escape entered info
    
    	$escapedName = mysql_real_escape_string($_POST['new_band']);
    
    //Verify that the band name is not already taken
    
    	$query = "select * from bands where name='$escapedName'";
//        echo $query;
    	$pwq = mysql_query($query, $main);
    	$num = mysql_num_rows($pwq);
    
    	If($num>0){
    		echo "That band name is not unique. Band not created.";
    	}
    	else{
    
    		$query = "insert into bands (name) values ('$escapedName'); ";
    		$upd = mysql_query($query, $main);
            echo mysql_error();
    	}
    }
    If(isset($_POST['existing']) && empty($_POST['new_band'])){
        $query="select * from bands where id='".$_POST['existing']."'";
//        echo $query;
        $result_master = mysql_query($query, $master);
        $res_master=mysql_fetch_array($result_master);
        $num1 = mysql_num_rows($result_master);
        
        If($num1 > 0){
            $query = "select * from bands where name='".$res_master['name']."'";
            $pwq = mysql_query($query, $main);
            $num = mysql_num_rows($pwq);
        
            If($num>0){
                echo "That band name is not unique or is invalid. Band not created.";
            }
            else{
        
                $query = "insert into bands (name, master_id) values ('".$res_master['name']."', '".$res_master['id']."'); ";
                $upd = mysql_query($query, $main);
                $query = "select id from bands where name='".$res_master['name']."'";
                $pwq = mysql_query($query, $main);
                $id_row = mysql_fetch_array($pwq);
                $info_sql = "select * from info";
                $info_res= mysql_query($info_sql, $main);
                while($row=mysql_fetch_array($info_res)){
                    switch ($row['item']){
                        case "Festival id":
                            $festaddid=$row['value'];
                            break;
                        case "Festival Identifier Begin":
                            $festprefix=$row['value'];
                            break;
                        case "Festival Identifier End":
                            $festsuffix=$row['value'];
                            break;
                    }
                }
                $mainsql="select id from bands where name='".$res_master['name']."'";
                $main_res= mysql_query($mainql, $main);
                $row=mysql_fetch_array($main_res);
                $feststring="--".$festaddid."--".$festprefix.$id_row['id'].$festsuffix;
                
                $query = "update bands set festivals=CONCAT(festivals, '$feststring') where id='".$res_master['id']."'";
                $upd = mysql_query($query, $master);
                
            }
        }
    }
}


//Get data to make the pick lists
    $query="select name, id from bands order by name asc";
    $query_band = mysql_query($query, $master);

?>
<p>

This page allows for adding bands to the festival. You can either select a band from the list of bands already entered, or enter a new band.

</p>
<form action="index.php?disp=add_bands" method="post">
<select name="existing">
<?php 
while($row = mysql_fetch_array($query_band)) {
	echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>

<?php
If($right_required == "AddBands"){
?>
<input type="text" name="new_band"></input>
<?php
}
?>
<input type="submit" value="Add to festival"></input>
</form>
<?php


}
else{
echo "This page requires a higher level access than you currently have.";

include $baseinstall."includes/site/login.php";
}

?>
</div> <!-- end #content -->
