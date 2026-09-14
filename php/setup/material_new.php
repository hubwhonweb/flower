<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/material_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	$sql = "INSERT INTO material (code,name, category,unit, warning_limit, price,detail, active, mname, mtime) 
			VALUES( '" . $_POST['code'] . "',
			'" . $_POST['name'] . "',
			'" .$_POST['categories']  ."', 
			'" .$_POST['unit']  ."', 
			'" .$_POST['warning_limit']  ."', 
			'" .$_POST['price']  ."', 
			'" .$_POST['detail']  ."', 
			'YES',
			'" . $_SESSION['WHOAMI'] . "', 
			'" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/material.php");
}
else{
require("../public/header.php");
}
?>
<div id='title'>增加生产资料列表信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/material_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return materialDataCheck();">
<table class="inputtable">

<tr>
<td>名称:</td>
<td>
<input type="text" name="name" value="" />
</td>
</tr>
<tr>
<td>生产资料编码:</td>
<td>
<input type="text" name="code" id="materailcode" onblur="materialCodeAjax(this.value)">
<span id="materialcodemsg" style="color:red">*</span>
</td>
</tr>

<td>分类:</td>
<td>
<?php
echo "<select name='categories'>";
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
<input type="text" name="unit" value="" />
</td>
</tr>
<tr>
<td>库存警告数量:</td>
<td>
<input type="number" name="warning_limit" value="1" />
</td>
</tr>

<tr>
<td>参考价格：</td>
<td>
<input type="number" step="0.01" name="price" value="" />
</td>
</tr>

<tr>
<td>描述：</td>
<td><textarea  name="detail" rows="3" cols="50"></textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="增加"></td>
</tr>
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>