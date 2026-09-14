<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/batch_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $batch_date = $_POST['batch_date'];
	$variety1 = $_POST['variety_code1'];
	$pots1 = $_POST['batch_pots1'];
	$variety2 = $_POST['variety_code2'];
	$pots2 = $_POST['batch_pots2'];
	$variety3 = $_POST['variety_code3'];
	$pots3 = $_POST['batch_pots3'];
	$variety4 = $_POST['variety_code4'];
	$pots4 = $_POST['batch_pots4'];
	$variety5 = $_POST['variety_code5'];
	$pots5 = $_POST['batch_pots5'];
	$variety6 = $_POST['variety_code6'];
	$pots6 = $_POST['batch_pots6'];
	$variety7 = $_POST['variety_code7'];
	$pots7 = $_POST['batch_pots7'];
	$variety8 = $_POST['variety_code8'];
	$pots8 = $_POST['batch_pots8'];
	$variety9 = $_POST['variety_code9'];
	$pots9 = $_POST['batch_pots9'];	

    newBatchRecorder($batch_date,$variety1,$pots1);
	newBatchRecorder($batch_date,$variety2,$pots2);
	newBatchRecorder($batch_date,$variety3,$pots3);
	newBatchRecorder($batch_date,$variety4,$pots4);
	newBatchRecorder($batch_date,$variety5,$pots5);
	newBatchRecorder($batch_date,$variety6,$pots6);
	newBatchRecorder($batch_date,$variety7,$pots7);
	newBatchRecorder($batch_date,$variety8,$pots8);
	newBatchRecorder($batch_date,$variety9,$pots9);
	
	header("Location: " . $BASE_DIR . "process_rose/batch.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>新建批次</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/batch_new.php"; ?>" 
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>生产日期：</td>
<td>
<?php
echo "<input type='date' name='batch_date' value='" .date('Y-m-d',time()) . "' />";
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
</td>
<td>数量:</td>
<td>
<input type='number' name='batch_pots1' value='' />
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
</td>
<td>数量:</td>
<td>
<input type='number' name='batch_pots2' value='' />
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
</td>
<td>数量:</td>
<td>
<input type='number' name='batch_pots3' value='' />
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
</td>
<td>数量:</td>
<td>
<input type='number' name='batch_pots4' value='' />
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
</td>
<td>数量:</td>
<td>
<input type='number' name='batch_pots5' value='' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code6">
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
<td>数量:</td>
<td>
<input type='number' name='batch_pots6' value='' />
</td>
</tr>
<tr>
<td>品种编号:</td>
<td>
<select name="variety_code7">
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
<td>数量:</td>
<td>
<input type='number' name='batch_pots7' value='' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code8">
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
<td>数量:</td>
<td>
<input type='number' name='batch_pots8' value='' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code9">
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
<td>数量:</td>
<td>
<input type='number' name='batch_pots9' value='' />
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
