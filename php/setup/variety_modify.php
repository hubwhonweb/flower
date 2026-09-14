<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/variety_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "setup/variety.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE variety SET  
		variety_name = '" .$_POST['variety_name']  ."', 
		supplier = '" .$_POST['supplier']  ."', 
		color = '" .$_POST['color']  ."', 
		comm = '" .$_POST['comm']  ."', 
		active = '" .$_POST['active']  ."', 
		mname = '" . $_SESSION['WHOAMI'] . "', 
		mtime = '" . date('Y-m-d h:i:sa',time()) . "' WHERE id = " . $id . ";";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/variety.php");
}
else{
	$sql = "SELECT * FROM variety WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "setup/variety.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
require("../public/header.php");
}
?>

<div id='title'>修改产品信息内容</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/variety_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">
<tr>	
<td>所属产品：</td>
<td>
<?php
echo getProductName($recrow['product_code']);
?>
</td>
</tr>
<tr>	
<td>品种编号：</td>
<td>
<?php
echo $recrow['variety_code'];
?>
</td>
</tr>
<tr>
<td>品种名称：</td>
<td>
<?php
echo "<input type='text' name='variety_name' value='" . $recrow['variety_name'] . "'/>";
?>
</td>
</tr>
<tr>
<td>颜色：</td>
<td>
<?php
echo "<input type='text' name='color' value='" . $recrow['color'] . "'/>";
?>
</td>
</tr>
<tr>
<td>种苗供应商：</td>
<td>
<select name='supplier'>
<?php
echo "<option value='" . $recrow['supplier'] . "' selected>" . $recrow['supplier'] . "</option>";
foreach ($PRODUCT_SUPPLIER as $li) {
	echo "<option value='" . $li . "'>" . $li . "</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td>描述：</td>
<td>
<?php
echo "<textarea  name='comm' rows='3' cols='40'>".$recrow['comm']."</textarea>";
?>
</td>
</tr>

<tr>
<tr>
<td>状态：</td>
<td>
<select name='active'>
<?php
echo "<option value='" . $recrow['active'] . "' selected>" . $recrow['active'] . "</option>";
echo "<option value='在产'>在产</option>";
echo "<option value='停产'>停产</option>";
?>
</select>
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