<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/finish_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $finish_date = $_POST['finish_date'];
	
    $variety1 = $_POST['variety_code1'];
    $batch_date1 = $_POST['batch_date1'];
    
    $variety2 = $_POST['variety_code2'];
    $batch_date2 = $_POST['batch_date2'];
    
    $variety3 = $_POST['variety_code3'];
    $batch_date3 = $_POST['batch_date3'];
    
    $variety4 = $_POST['variety_code4'];
    $batch_date4 = $_POST['batch_date4'];
    
    $variety5 = $_POST['variety_code5'];
    $batch_date5 = $_POST['batch_date5'];
    
    new_finish_recorder($finish_date,$variety1,$batch_date1);
    new_finish_recorder($finish_date,$variety2,$batch_date2);
    new_finish_recorder($finish_date,$variety3,$batch_date3);
    new_finish_recorder($finish_date,$variety4,$batch_date4);
    new_finish_recorder($finish_date,$variety5,$batch_date5);
    
	header("Location: " . $BASE_DIR . "process_rose/finish.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>上市记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/finish_new.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>上市日期：</td>
<td>
<?php
echo "<input type='date' name='finish_date' max='".date('Y-m-d',time())."' value='" .date('Y-m-d',time()) . "' />";
?>
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code1">
<option value="999">无</option>
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
    echo "<input type='date' name='batch_date1' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code2">
<option value="999">无</option>
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
    echo "<input type='date' name='batch_date2' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code3">
<option value="999">无</option>
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
    echo "<input type='date' name='batch_date3' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code4">
<option value="999">无</option>
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
    echo "<input type='date' name='batch_date4' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code5">
<option value="999">无</option>
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
    echo "<input type='date' name='batch_date5' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>

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

function new_finish_recorder($finish_date,$variety,$batch_date){
    if( $variety != "999") {
        $sql = "UPDATE batch SET finish_date='".$finish_date."'
                WHERE variety_code='".$variety."'
                AND batch_date='".$batch_date."'";
        WHDBmysql_query($sql);
    }
}
?>
