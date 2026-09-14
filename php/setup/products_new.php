<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/product_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
       $sql = "INSERT INTO products (product_code,product_name,pot_code,bag_code,box_code,active, mname, mtime) 
			VALUES( '" . $_POST['code'] . "',
				'" .$_POST['name']  ."', 
				'在产',
				'" . $_SESSION['WHOAMI'] . "', 
				'" . date('Y-m-d H:i:s',time()) . "');";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "setup/products.php");
}
else{
	require("../public/header.php");
}

?>
<div id='title'>增加产品信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/products_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return productsDataCheck();">
<table class="inputtable">

<tr>
<td>产品编码：</td>
<td>
<input type="text" name="code" placeholder="两位产品码+两位盆径" id="productcode" onblur="productCodeAjax(this.value)">
<span id="productcodemsg" style="color:red">*</span>
</td>
</tr>

<tr>
<td>产品名称:</td>
<td>
<input type="text" name="name" id="name" value="">
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
