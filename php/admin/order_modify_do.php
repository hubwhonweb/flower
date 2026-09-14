<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/order_modify_do.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "admin/order_modify.php");
}
else{
	$id = $_GET['id'];
}

//如果提交订单
if(isset($_POST['submit'])){
	//获取经销商名称
	$sql = "SELECT * FROM agent WHERE code='" . $_POST['agent_code']. "';";
	$result = WHDBmysql_query($sql);
	$agentrow = mysqli_fetch_assoc($result);
	$agent_name = $agentrow['name'];
	//修改数据库中订单记录
	$sql = "UPDATE orders set
		product_code = '" . $_POST['product_code']. "',
		date = '" . $_POST['mdate']. "',
		agent_code = '" . $_POST['agent_code']. "',
		class = '" . $_POST['class']. "',
		boxes = '" . $_POST['boxes']. "',
		plants_in_box = '" . $_POST['plantsinbox']. "',
		plants = '" . $_POST['plants']. "',
		price = '" . $_POST['price']. "',
		subtotal = '" . $_POST['subtotal']. "',
		flag = '" . $_POST['flag']. "',
		mname = '" . $_SESSION['WHOAMI']. "',
		mtime = '" . date('Y-m-d h:i:sa',time()) . "'
		where id='" .$id . "';";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "admin/order_modify.php");
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
<form action="<?php echo $BASE_DIR . "admin/order_modify_do.php?id=" . $id; ?>" 
	  method="post" 
	  onkeydown ="if(event.keyCode==13) return false;"
>


发生日期:
<?php
echo "<input type='date' name='mdate' id='mdate' value='" .$orderrow['date'] ."'>";
?>
</br>

经销商:
<select name="agent_code">
<?php
echo "<option value='" . $orderrow['agent_code'] . "'selected>" . getAgentName($orderrow['agent_code']) . "</option>";
$catsql = "SELECT * FROM agent WHERE active='YES' order by order_times DESC, name_pinyin;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>
</br>

产品名:
<select name="product_code">
<?php
echo "<option value='" . $orderrow['product_code'] . "'selected>" . getProductName($orderrow['product_code']) . "</option>";
$catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</select>
</br>

付款状态:
<select name="flag">
<?php
echo "<option value='" . $orderrow['flag'] . "'>" . $orderrow['flag'] . "</option>";
echo "<option value='已发货'>已发货</option>";
echo "<option value='已付款'>已付款</option>";
?>
</select>
</br>

等级:
<select name="class">
<?php
echo "<option value='" . $orderrow['class'] . "'>" . $orderrow['class'] . "</option>";
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</br>

箱数:
<?php
echo "<input type='number' name='boxes' id='boxes' value='" .$orderrow['boxes'] ."'>";
?>
</br>
每箱数量:
<?php
echo "<input type='number' name='plantsinbox' id='plantsinbox' value='" .$orderrow['plants_in_box'] ."'>";
?>
</br>
盆数:
<?php
echo "<input type='number' name='plants' id='plants' style='color:red' value='" .$orderrow['plants'] ."'>";
?>
</br>
单价:
<?php
echo "<input type='number' step='0.01' name='price' id='price' value='" .$orderrow['price'] ."'>";
?>
</br>
金额:
<?php
echo "<input type='number' step='0.01' name='subtotal' id='subtotal' style='color:red' value='" .$orderrow['subtotal'] ."'>";
?>
</br>

<input type="submit" name="submit" id="sub" value="修改">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>