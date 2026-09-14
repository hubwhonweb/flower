<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/assess.php") == FALSE){
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
        $sql = "UPDATE batch
		SET event_code = '" . $_POST['event_code'] ."' WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "process_rose/assessAndComment.php");
}
//没有确认提交按钮，从数据库中找到指定记录
else{
	$sql = "SELECT * FROM batch WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "process_rose/assessAndComment.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
	mysqli_free_result($result);
}
?>

<div id='title'>批次评估</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/assess.php?id=" . $id; ?>"
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">


<tr>

<td>
<?php
    echo "品种编号：" . $recrow['variety_code'];
?>
</td>
</tr>
<tr>
<td>
<?php
    echo "扦插日期：" . $recrow['batch_date'];
?>
</td>
</tr>
<tr>
<td>评估分数</td>
<td>
<?php
    echo "<input type='number' name='event_code' value='" . $recrow['event_code'] . "'/>";
    ?>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="提交"></td>
</tr>	
	
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
