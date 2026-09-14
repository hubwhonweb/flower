<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_pay_do.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(checkUrlOrderID()==FALSE){
	header("Location: " . $BASE_DIR . "sale/menu.php");
}
else{
	$id = $_GET['id'];
}

//查询符合条件的订单
$sql = "SELECT * FROM orders WHERE order_id='" . $id . "';";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	//取得订单记录中的第一条
	$orderrec = mysqli_fetch_assoc($result);
	//查找订单总金额
	$sql = "SELECT sum(subtotal) as subt FROM orders WHERE order_id='" . $id . "';";
	$totalrec = WHDBmysql_query($sql);
	$total = mysqli_fetch_assoc($totalrec);
	mysqli_free_result($totalrec);
}

// 提交查询表单后处理
if(isset($_POST['submit'])){
	//修改订单信息
	$sql = "UPDATE orders set 
			flag='已付款', 
			bank_date='" . $_POST['bank_date'] . "',
			bank_rec='" . $_POST['bank_rec'] . "', 
			mname='" . $_SESSION['WHOAMI'] . "', 
			mtime='" . date('Y-m-d h:i:sa',time()) . "' 
			WHERE order_id='" . $id . "';";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "sale/order_pay.php");
}
else{
	require("../public/header.php");
	echo "<div id='title'>付款</div>";
	echo "<div id='right'>";
}
?>


<form action="<?php echo $BASE_DIR . "sale/order_pay_do.php?id=" .$id; ?>" 
	  method="post" 
	  onkeydown ="if(event.keyCode==13) return false;"
	  >
<table class='hovertable'>
<tr>
	<th>订单号</th>
	<th>日期</th>
	<th>经销商</th>
	<th>订单金额</th>
	<th>付款状态</th>
	<th>银行入账日期</th>
	<th>银行对应金额</th>
	<th>修改人</th>
</tr>
<tr>
<td><?php echo $orderrec['order_id']; ?></td>
<td><?php echo $orderrec['date']; ?></td>
<td><?php echo getAgentname($orderrec['agent_code']); ?></td>
<td><?php echo floor($total['subt']); ?></td>
<td><?php echo $orderrec['flag']; ?></td>

<td>
<?php 
echo "<input type='date' name='bank_date' value='" . date('Y-m-d',time()) . "'/>";
?>
</td>

<td>
<?php 
if($orderrec['bank_rec']==NULL){
	echo "<input type='number' step='0.01' name='bank_rec' value='0'/>";	
}
else{
	echo "<input type='number' step='0.01' name='bank_rec' value='" . $orderrec['bank_rec'] . "'/>";
}
?>
</td>
<td><?php echo $orderrec['mname'];?></td>
</table>
<input type="submit" name="submit" id="sub" value="付款"></form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>