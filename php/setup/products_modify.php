<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/products_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "setup/products.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE products SET  
	product_name = '" .$_POST['name']  ."',
	active = '" .$_POST['active']  ."',
	mname = '" . $_SESSION['WHOAMI'] . "',
	mtime = '" . date('Y-m-d H:i:s',time()) . "'
	WHERE id = " . $id . ";";
    WHDBmysql_query($sql);
    header("Location: " . $BASE_DIR . "setup/products.php");
}
else{
  	$sql = "SELECT * FROM products WHERE id = " . $id .";";
 	$result = WHDBmysql_query($sql);
	 $numrow = mysqli_num_rows($result);
 	 if($numrow == 0){
		header("Location: " . $BASE_DIR . "setup/products.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
    require("../public/header.php");
}
?>

<div id='title'>修改产品信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/products_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">

<tr>
<td>产品编码:</td>
<td>
<?php 
echo  $recrow['product_code'];
?>
</td>
</tr>

<tr>
<td>产品名称:</td>
<td>
<?php 
echo "<input type='text' name='name' id='name' value='". $recrow['product_name'] ."'>";
?>
</td>
</tr>

<td>状态:</td>
<td>
<select name="active">
<?php
echo "<option value='" . $recrow['active'] . "' selected>" . $recrow['active'] . "</option>";
?>
<option value='在产'>在产</option>
<option value='停产'>停产</option>
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
