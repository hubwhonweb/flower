<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/plant_move_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $in_date = $_POST['in_date'];
    $house_name = $_POST['house_name'];
    $bed = $_POST['bed'];

    $variety1 = $_POST['variety_code1'];
    $plant_date1 = $_POST['plant_date1'];
	$pot1 = $_POST['pot1'];
    
    $variety2 = $_POST['variety_code2'];
    $plant_date2 = $_POST['plant_date2'];
    $pot2 = $_POST['pot2'];
    
    $variety3 = $_POST['variety_code3'];
    $plant_date3 = $_POST['plant_date3'];
    $pot3 = $_POST['pot3'];
    
    $variety4 = $_POST['variety_code4'];
    $plant_date4 = $_POST['plant_date4'];
    $pot4 = $_POST['pot4'];
    
    $variety5 = $_POST['variety_code5'];
    $plant_date5 = $_POST['plant_date5'];
    $pot5 = $_POST['pot5'];
    
    plant_move_new_recorder($in_date,$house_name,$bed,$variety1,$plant_date1,$pot1,"");
    plant_move_new_recorder($in_date,$house_name,$bed,$variety2,$plant_date2,$pot2,"");
    plant_move_new_recorder($in_date,$house_name,$bed,$variety3,$plant_date3,$pot3,"");
    plant_move_new_recorder($in_date,$house_name,$bed,$variety4,$plant_date4,$pot4,"");
    plant_move_new_recorder($in_date,$house_name,$bed,$variety5,$plant_date5,$pot5,"");

	header("Location: " . $BASE_DIR . "process_rose/plant_move.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>移苗记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/plant_move_new.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>移苗日期：</td>
<td>
<?php
echo "<input type='date' style='width:180px' name='in_date' value='" .date('Y-m-d',(time()-24*60*60)) . "' />";
?>
</td>
<td>进苗温室：</td>
<td>
<select name="house_name" style="width:150px">
   <option value='D'>D温室</option>
   <option value='C'>C温室</option>
   <option value='B'>B温室</option>
   <option value='A'>A温室</option>
   <option value='E'>E温室</option>
</select>
</td>
<td>进苗床号：</td>
<td>
<select name="bed" style="width:150px">
<?php
for( $i=1; $i<=59;$i++){
    echo "<option value='" . $i . "'>" .$i. "</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td>品种:</td>
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
    echo "<input type='date' style='width:160px' name='plant_date1' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>

<td>数量:</td>
<td>
<input type='number' style='width:60px' name='pot1' value='0' />
</td>

</tr>
<tr>
<td>品种:</td>
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
    echo "<input type='date' style='width:160px' name='plant_date2' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>

<td>数量:</td>
<td>
<input type='number' style='width:60px' name='pot2' value='0' />
</td>

</tr>
<tr>
<td>品种:</td>
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
    echo "<input type='date' style='width:160px' name='plant_date3' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>

<td>数量:</td>
<td>
<input type='number' style='width:60px' name='pot3' value='0' />
</td>

</tr>
<tr>
<td>品种:</td>
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
    echo "<input type='date' style='width:160px' name='plant_date4' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>

<td>数量:</td>
<td>
<input type='number' style='width:60px' name='pot4' value='0' />
</td>

</tr>
<tr>
<td>品种:</td>
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
    echo "<input type='date' style='width:160px' name='plant_date5' value='" .date('Y-m-d',time()-42*12*3600) . "' />";
    ?>
</td>

<td>数量:</td>
<td>
<input type='number' style='width:60px' name='pot5' value='0' />
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
function plant_move_new_recorder($in_date,$house_name,$bed,$variety_code,$plant_date,$pot,$state){
    if($pot>0){
        $sql = "INSERT INTO plant_move (
         move_date,target_house,target_bed,variety,plant_date,state,plants)
            VALUES( '" . $in_date . "',
                   '" .$house_name  ."',
                   '" .$bed  ."',
                   '" .$variety_code . "',
                   '" .$plant_date  ."',
                   '" .$state  ."',
                   '" .$pot  ."');";
        //echo $sql;
            WHDBmysql_query($sql);
    }
}
?>
