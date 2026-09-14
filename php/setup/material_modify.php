<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/material_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber()==FALSE){
	header("Location: " . $BASE_DIR . "setup/material.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE material SET  
			name = '" .$_POST['name']  ."',
			category = '" .$_POST['categories']  ."',
			detail = '" .$_POST['detail']  ."',
			unit = '" .$_POST['unit']  ."',
			warning_limit = '" .$_POST['warning_limit']  ."',
			price = '" .$_POST['price']  ."',
			active = '" .$_POST['active']  ."',
			mname = '" . $_SESSION['WHOAMI'] . "', 
			mtime = '" . date('Y-m-d h:i:sa',time()) . "'     
			WHERE id = " . $id . ";";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/material.php");
}
else{
	$sql = "SELECT * FROM material WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "setup/material.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
require("../public/header.php");
}
?>

<div id='title'>修改生产资料列表</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/material_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return materialDataCheckModify();">
<table class="inputtable">

<tr>
<td>名称:</td>
<td>
<?php 
echo "<input type='text' name='name' id='materailname' value='" .$recrow['name'] . "'>";
?>
</td>
</tr>
<tr>
<td>编号:</td>
<td>
<?php 
echo $recrow['code'] ;
?>
</td>
</tr>
<tr>
<td>分类:</td>
<td>
<?php
echo "<select name='categories'>";
echo "<option value='" . $recrow['category'] . "'>" . $recrow['category'] . "</option>";
foreach ($MATERIAL_CAT as $fe) {
	echo "<option value='" . $fe . "'>" . $fe . "</option>";
}
echo "</select>";
?>
</td>
</tr>
<tr>
<td>单位:</td>
<td>
<?php 
echo "<input type='text' name='unit' value='" .$recrow['unit'] . "'>";
?>
</td>
</tr>
<tr>
<td>库存警告值：</td>
<td>
<?php 
echo "<input type='number' name='warning_limit' value='" .$recrow['warning_limit'] . "'>";
?>
</td>
</tr>
<tr>
<td>参考价格：</td>
<td>
<?php 
echo "<input type='number' step='0.01' name='price' value='" .$recrow['price'] . "'>";
?>
</td>
</tr>

<tr>
<td>描述：</td>
<td>
<?php
echo "<input type='text' name='detail' value='" .$recrow['detail'] . "'>";
?>
</td>
</tr>
<tr>
<td>状态：</td>
<td>
<select name='active'>
<?php
echo "<option value='" . $recrow['active'] . "'>" . $recrow['active'] . "</option>";
?>
<option value='YES'>YES</option>
<option value='NO'>NO</option>
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
