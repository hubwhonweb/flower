<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/lose2_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "process_rose/menu.php");
}
else{
	$id = $_GET['id'];
}


//提交了确认按钮，进行数据库修改
if(isset($_POST['submit'])){
        $batch_code = date('ymd',strtotime($_POST['plant_date']));
        $batch_code = $batch_code.$_POST['variety_code'];
    
        $sql = "UPDATE lose
		SET lose_date = '" . $_POST['lose_date'] ."' ,
		plant_date = '" . $_POST['plant_date'] ."' ,
		variety_code = '" . $_POST['variety_code'] ."' ,
        batch_code = '" . $batch_code ."' ,
		pot_in = '" . $_POST['pot_in'] ."' ,
        pot_out = '" . $_POST['pot_out'] ."' ,
		mname = '" . $_SESSION['WHOAMI'] . "', 
		mtime = '" . date('Y-m-d h:i:sa',time()) . "' WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "process_rose/lose2.php");
}
//没有确认提交按钮，从数据库中找到指定记录
else{
	$sql = "SELECT * FROM lose WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "process_rose/lose2.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
	mysqli_free_result($result);
}
?>

<div id='title'>修改组盆记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/lose2_modify.php?id=" . $id; ?>"
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>磕盆日期</td>
<td>
<?php
echo "<input type='date' name='lose_date' value='" . $recrow['lose_date'] . "'/>";
?>
</td>
</tr>
<tr>
<td>扦插日期</td>
<td>
<?php
    echo "<input type='date' name='plant_date' value='" . $recrow['plant_date'] . "'/>";
    ?>
</td>
</tr>
<tr>
<td>品种编号</td>
<td>
<select name="variety_code">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    echo "<option value='" . $recrow['variety_code'] . "'>" . $recrow['variety_code']."</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
</tr>
<tr>
<td>组盆数量</td>
<td>
<?php
    echo "<input type='number' name='pot_in' value='" . $recrow['pot_in'] . "'/>";
    ?>
</td>
</tr>
<tr>
<td>损失数量</td>
<td>
<?php
    echo "<input type='number' name='pot_out' value='" . $recrow['pot_out'] . "'/>";
    ?>
</td>
</tr>


<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="修改"></td>
</tr>	
	
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
