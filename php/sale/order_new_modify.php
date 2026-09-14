<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_new_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "sale/order_new.php");
}
else{
	$id = $_GET['id'];
}
//如果提交订单
if(isset($_POST['submit'])){
	$sql = "UPDATE orders set
		product_code = '" . $_POST['product_code']. "',
		class = '" . $_POST['class']. "',
		boxes = '" . $_POST['boxes']. "',
		plants_in_box = '" . $_POST['plantsinbox']. "',
		plants = '" . $_POST['plants']. "',
		price = '" . $_POST['price']. "',
		subtotal = '" . $_POST['subtotal']. "',
		mname = '" . $_SESSION['WHOAMI']. "',
		mtime = '" . date('Y-m-d h:i:sa',time()) . "'
		where id='" .$id . "';";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "sale/order_new.php");
}
else{
	//在数据库中查找订单信息
	$sql = "SELECT * FROM orders WHERE id='" . $id . "';";
	$result = WHDBmysql_query($sql);
	$orderrow = mysqli_fetch_assoc($result);
    require("../public/header.php");
}
?>

<div id='title'>修改订单</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "sale/order_new_modify.php?id=" . $id; ?>" 
	  method="post" 
	  onkeydown ="if(event.keyCode==13) return false;"
>

<span id="whtext">发生日期：
<?php
echo $orderrow['date']."</span>"; 
?>
<br>
<span id="whtext">经销商:
<?php
echo getAgentName($orderrow['agent_code'])."</span>"; 
?>

<table class="hovertable">
<tr>
<th>产品名</th>
<th>等级</th>
<th>箱数</th>
<th>每箱数量</th>
<th>盆数</th>
<th>单价</th>
<th>金额</th>
</tr>

<tr>
<td>
<select name="product_code">
<?php
echo "<option value='" . $orderrow['product_code'] . "'>" . getProductName($orderrow['product_code']) . "</option>";
$catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class">
<?php
echo "<option value='" . $orderrow['class'] . "'>" . $orderrow['class'] . "</option>";
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<?php
echo "<input type='number' style='width:50px' name='boxes' id='boxes' value='" .$orderrow['boxes'] ."'>";
?>
</td>
<td>
<?php
echo "<input type='number' style='width:50px' name='plantsinbox' id='plantsinbox' value='" .$orderrow['plants_in_box'] ."'>";
?>
</td>
<td>
<?php
echo "<input type='number' style='width:50px' name='plants' id='plants' style='color:red' value='" .$orderrow['plants'] ."'>";
?>
</td>
<td>
<?php
echo "<input type='number' style='width:50px' step='0.01' name='price' id='price' value='" .$orderrow['price'] ."'>";
?>
</td>
<td>
<?php
echo "<input type='number' step='0.01' name='subtotal' id='subtotal' style='color:red' value='" .$orderrow['subtotal'] ."'>";
?>
</td>
</tr>
</table>
<input type="submit" name="submit" id="sub" value="修改">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>