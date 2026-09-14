<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/finishPick_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $in_date = $_POST['in_date'];
    
    $variety1 = $_POST['variety_code1'];
    $plant_date1 = $_POST['plant_date1'];
	$pot1 = $_POST['pot1'];
    $merge_pot1 = $_POST['merge_pot1'];
   
    $variety2 = $_POST['variety_code2'];
    $plant_date2 = $_POST['plant_date2'];
    $pot2 = $_POST['pot2'];
    $merge_pot2 = $_POST['merge_pot2'];
    
    $variety3 = $_POST['variety_code3'];
    $plant_date3 = $_POST['plant_date3'];
    $pot3 = $_POST['pot3'];
    $merge_pot3 = $_POST['merge_pot3'];
    
    $variety4 = $_POST['variety_code4'];
    $plant_date4 = $_POST['plant_date4'];
    $pot4 = $_POST['pot4'];
    $merge_pot4 = $_POST['merge_pot4'];
    
    $variety5 = $_POST['variety_code5'];
    $plant_date5 = $_POST['plant_date5'];
    $pot5 = $_POST['pot5'];
    $merge_pot5 = $_POST['merge_pot5'];
    
    pick_new_recorder($in_date,$variety1,$plant_date1,"成品组盆",$pot1,$merge_pot1);
    Pick_new_recorder($in_date,$variety2,$plant_date2,"成品组盆",$pot2,$merge_pot2);
    Pick_new_recorder($in_date,$variety3,$plant_date3,"成品组盆",$pot3,$merge_pot3);
    Pick_new_recorder($in_date,$variety4,$plant_date4,"成品组盆",$pot4,$merge_pot4);
    Pick_new_recorder($in_date,$variety5,$plant_date5,"成品组盆",$pot5,$merge_pot5);

	header("Location: " . $BASE_DIR . "process_rose/finishPick.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>成品组盆</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/finishPick_new.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>组盆日期：</td>
<td>
<?php
echo "<input type='date' style='width:180px' name='in_date' value='" .date('Y-m-d',time()) . "' />";
?>
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code1" style="width:150px">
<?php
$catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
}
mysqli_free_result($catres);
?>
</select>
</td>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' style='width:180px' name='plant_date1' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>

<td>磕盆数量:</td>
<td>
<input type='number' style='width:80px' name='pot1' value='0' />
</td>
<td>组盆数量:</td>
<td>
<input type='number' style='width:80px' name='merge_pot1' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code2" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' style='width:180px' name='plant_date2' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>
<td>磕盆数量:</td>
<td>
<input type='number' style='width:80px' name='pot2' value='0' />
</td>
<td>组盆数量:</td>
<td>
<input type='number' style='width:80px' name='merge_pot2' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code3" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' style='width:180px' name='plant_date3' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>
<td>磕盆数量:</td>
<td>
<input type='number' style='width:80px' name='pot3' value='0' />
</td>
<td>组盆数量:</td>
<td>
<input type='number' style='width:80px' name='merge_pot3' value='0' />
</td>
</tr>
<tr>

<td>品种编号:</td>
<td>
<select name="variety_code4" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' style='width:180px' name='plant_date4' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>
<td>磕盆数量:</td>
<td>
<input type='number' style='width:80px' name='pot4' value='0' />
</td>
<td>组盆数量:</td>
<td>
<input type='number' style='width:80px' name='merge_pot4' value='0' />
</td>
</tr>
<tr>

<td>品种编号:</td>
<td>
<select name="variety_code5" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' style='width:180px' name='plant_date5' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>
<td>磕盆数量:</td>
<td>
<input type='number' style='width:80px' name='pot5' value='0' />
</td>
<td>组盆数量:</td>
<td>
<input type='number' style='width:80px' name='merge_pot5' value='0' />
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="增加" ></td>
</tr>
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
<?php
function pick_new_recorder($in_date,$variety_code,$plant_date,$stage,$pickPots,$mergePots){
    if($pickPots>0){
        $sql = "INSERT INTO plant_merge (
         merge_date,variety,plant_date,stage,pick_pots,merge_pots)
            VALUES( '" . $in_date . "',
                   '" .$variety_code  ."',
                   '" .$plant_date  ."',
                   '" .$stage . "',
                   '" .$pickPots ."',
                   '" .$mergePots ."');";
        //echo $sql;
        WHDBmysql_query($sql);
    }
}
?>
