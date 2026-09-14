<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/lose1_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $lose_date = $_POST['lose_date'];
	
    $variety1 = $_POST['variety_code1'];
    $plant_date1 = $_POST['plant_date1'];
	$pot_in1 = 0;
    $pot_out1 = $_POST['pot_out1'];
    
    $variety2 = $_POST['variety_code2'];
    $plant_date2 = $_POST['plant_date2'];
    $pot_in2 = 0;
    $pot_out2 = $_POST['pot_out2'];
    
    $variety3 = $_POST['variety_code3'];
    $plant_date3 = $_POST['plant_date3'];
    $pot_in3 = 0;
    $pot_out3 = $_POST['pot_out3'];
    
    $variety4 = $_POST['variety_code4'];
    $plant_date4 = $_POST['plant_date4'];
    $pot_in4 = 0;
    $pot_out4 = $_POST['pot_out4'];
    
    $variety5 = $_POST['variety_code5'];
    $plant_date5 = $_POST['plant_date5'];
    $pot_in5 = 0;
    $pot_out5 = $_POST['pot_out5'];
    
    newLoseRecorder($lose_date,$variety1,$plant_date1,$pot_in1,$pot_out1,"1");
    newLoseRecorder($lose_date,$variety2,$plant_date2,$pot_in2,$pot_out2,"1");
    newLoseRecorder($lose_date,$variety3,$plant_date3,$pot_in3,$pot_out3,"1");
    newLoseRecorder($lose_date,$variety4,$plant_date4,$pot_in4,$pot_out4,"1");
    newLoseRecorder($lose_date,$variety5,$plant_date5,$pot_in5,$pot_out5,"1");
	
	header("Location: " . $BASE_DIR . "process_rose/lose1.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>苗期磕盆记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/lose1_new.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>磕盆日期：</td>
<td>
<?php
echo "<input type='date' name='lose_date' value='" .date('Y-m-d',time()) . "' />";
?>
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code1">
<?php
$catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
}
mysqli_free_result($catres);
?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='plant_date1' value='" .date('Y-m-d',(time()-15*24*60*60)) . "' />";
    ?>
</td>

</td>
<td>磕盆数量:</td>
<td>
<input type='number' name='pot_out1' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code2">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='plant_date2' value='" .date('Y-m-d',(time()-15*24*60*60)) . "' />";
    ?>
</td>

</td>
<td>磕盆数量:</td>
<td>
<input type='number' name='pot_out2' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code3">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='plant_date3' value='" .date('Y-m-d',(time()-15*24*60*60)) . "' />";
    ?>
</td>

</td>
<td>磕盆数量:</td>
<td>
<input type='number' name='pot_out3' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code4">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='plant_date4' value='" .date('Y-m-d',(time()-15*24*60*60)) . "' />";
    ?>
</td>

</td>
<td>磕盆数量:</td>
<td>
<input type='number' name='pot_out4' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code5">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='plant_date5' value='" .date('Y-m-d',(time()-15*24*60*60)) . "' />";
    ?>
</td>

</td>
<td>磕盆数量:</td>
<td>
<input type='number' name='pot_out5' value='0' />
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
