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
	$batch_mother1 = $_POST['batch_mother1'];
	$pots1 = $_POST['batch_pots1'];
	$variety2 = $_POST['variety_code2'];
	$pots2 = $_POST['batch_pots2'];
	$batch_mother2 = $_POST['batch_mother2'];
	$variety3 = $_POST['variety_code3'];
	$pots3 = $_POST['batch_pots3'];
	$batch_mother3 = $_POST['batch_mother3'];
	$variety4 = $_POST['variety_code4'];
	$pots4 = $_POST['batch_pots4'];
	$batch_mother4 = $_POST['batch_mother4'];
	$variety5 = $_POST['variety_code5'];
	$pots5 = $_POST['batch_pots5'];
	$batch_mother5 = $_POST['batch_mother5'];
	$variety6 = $_POST['variety_code6'];
	$pots6 = $_POST['batch_pots6'];
	$batch_mother6 = $_POST['batch_mother6'];
	$variety7 = $_POST['variety_code7'];
	$pots7 = $_POST['batch_pots7'];
	$batch_mother7 = $_POST['batch_mother7'];
	$variety8 = $_POST['variety_code8'];
	$pots8 = $_POST['batch_pots8'];
	$batch_mother8 = $_POST['batch_mother8'];
	$variety9 = $_POST['variety_code9'];
	$pots9 = $_POST['batch_pots9'];	
	$batch_mother9 = $_POST['batch_mother9'];
    
    newBatchRecorder($batch_date,$variety1,$pots1,$batch_mother1);
	newBatchRecorder($batch_date,$variety2,$pots2,$batch_mother2);
	newBatchRecorder($batch_date,$variety3,$pots3,$batch_mother3);
	newBatchRecorder($batch_date,$variety4,$pots4,$batch_mother4);
	newBatchRecorder($batch_date,$variety5,$pots5,$batch_mother5);
	newBatchRecorder($batch_date,$variety6,$pots6,$batch_mother6);
	newBatchRecorder($batch_date,$variety7,$pots7,$batch_mother7);
	newBatchRecorder($batch_date,$variety8,$pots8,$batch_mother8);
	newBatchRecorder($batch_date,$variety9,$pots9,$batch_mother9);
	
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
echo "<input type='date' name='batch_date' value='" .date('Y-m-d',(time()-24*60*60)) . "' />";
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother1' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother2' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother3' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother4' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother5' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother6' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother7' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother8' value='' />
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
<td>母本情况:</td>
<td>
<input type='text' name='batch_mother9' value='' />
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
