<div id="content">

<?php
$right_required = "AddBands";
If(isset($_SESSION['level']) && CheckRights($_SESSION['level'], $right_required)){

//Get data to make the pick lists
	$query="select name, id from bands order by name asc";
	$query_band = mysql_query($query, $master);


//Once the information is submitted, store it in the database
If(!empty($_POST)){
    
    If(isset($_POST['new_band'])){

    //Escape entered info
    
    	$escapedName = mysql_real_escape_string($_POST['name']);
    
    //Verify that the band name is not already taken
    
    	$query = "select * from bands where name='$escapedName'";
    	$pwq = mysql_query($query, $main);
    	$num = mysql_num_rows($pwq);
    
    	If($num>0){
    		echo "That band name is not unique. Band not created.";
    	}
    	else{
    
    		$query = "insert into bands (name) values ('$escapedName'); ";
    		$upd = mysql_query($query, $main);
    	}
    }
    If(isset($_POST['existing'])){
        $query="select * from bands where id='".$_POST['existing']."'";
        $res_master = mysql_query($query, $master);
        $num1 = mysql_num_rows($res_master);
        
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
                $feststring="--".$festaddid."--".$festprefix.$row['id'].$festsuffix;
                
                $query = "update bands set festivals='$feststring' ";
                $upd = mysql_query($query, $master);
                
            }
        }
    }
}

?>
<p>

This page allows for adding bands to the festival.

</p>
<form action="index.php?disp=add_bands" method="post">
<select name="existing">
<?php 
while($row = mysql_fetch_array($query_band)) {
	echo "<option value=".$row["id"].">".$row["name"]."</option>";
}
?>
</select>


<input type="Add to festival">
</form>
<?php


}
else{
echo "This page requires a higher level access than you currently have.";

include "login.php";
}

?>
</div> <!-- end #content -->
