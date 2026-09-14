<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/variety_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	$sql = "INSERT INTO variety (product_code,variety_code,variety_name,supplier,color,comm,active,mname, mtime) 
			VALUES( 
				'" . $_POST['product_code'] . "',
				'" . $_POST['variety_code'] . "',
				'" . $_POST['variety_name'] . "',
				'" . $_POST['supplier'] . "',
				'" . $_POST['color'] . "',
				'" . $_POST['comm'] . "',
				'在产',
				'" . $_SESSION['WHOAMI'] . "', 
				'" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/variety.php");
}
else{
	require("../public/header.php");
}
?>

<div id='title'>增加产品信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/variety_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return varietyDataCheck();">
<table class="inputtable">

<tr>
<td>所属产品：</td>
<td>
<select name='product_code' id='productcode'>
<?php
$sql = "select * from products where active='在产'";
$result = WHDBmysql_query($sql);
while( $records = mysqli_fetch_array($result)){
	echo "<option value='" .$records['product_code']."'>" . $records['product_name'] . "</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td>品种编号：</td>
<td>
<input type="text" name="variety_code" placeholder="两位数字" id="varietycode" onblur="varietyCodeAjax(this.value)">
<span id="varietycodemsg" style="color:red">*</span>
</td>
</tr>
<tr>
<td>品种名称：</td>
<td>
<input type="text" name="variety_name" value="" />
</td>
</tr>

<tr>
<td>供应商：</td>
<td>
<select name='supplier'>
<?php
foreach ($PRODUCT_SUPPLIER as $li) {
	echo "<option value='" .$li."'>" . $li . "</option>";
}
?>
</select>
</td>
</tr>

<tr>
<td>颜色：</td>
<td>
<input type="text" name="color" value="" />
</td>
</tr>
<tr>
<td>品种描述:</td>
<td>
<textarea  name="comm" rows="3" cols="40"></textarea>
</td>
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