<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_mention_do.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "sale/menu.php");
}
else{
	$agent_code = $_GET['id'];
}

echo "<div id='right'>";
//通知抬头
$sql = "select * from agent where code = '" . $agent_code . "';";
$agentRes = WHDBmysql_query($sql);
$agentRec = mysqli_fetch_array($agentRes);
$sql = "select sum(subtotal) as subt,sum(boxes) as box from orders where agent_code = '" . $agent_code . "' and flag='已发货';";
$subRes = WHDBmysql_query($sql);
$subRec = mysqli_fetch_array($subRes);
echo $agentRec['name'] . "您好。截至" . date('Y-m-d',time(oid))."，您共有未付款：".$subRec['subt']."元。共：".$subRec['box']."箱</br>明细如下：</br>";
mysqli_free_result($subRes);
mysqli_free_result($agentRes);
//打印通知列表项
echo "<table cellpadding='5'>";
print_table_header();
//打印通知列表内容

$sql = "select DISTINCT(`order_id`) as order_id from orders where agent_code = '" . $agent_code . "' and flag='已发货';";
$orderRes = WHDBmysql_query($sql);


while ($orderRec = mysqli_fetch_array($orderRes)) {
	
	$sql = "select * from orders where order_id = '".$orderRec['order_id']."'";
	$recs = WHDBmysql_query($sql);
	$recc = mysqli_fetch_array($recs);
	echo "<tr>";
	echo "<td>" . $recc['date']. "</td>";
	echo "<td>" . $recc['order_id']. "</td>";
	echo "<td>" . getProductName($recc['product_code']). "</td>";

	$sql = "select sum(plants) as plant,sum(boxes) as box,sum(subtotal) as subtotal from orders
            where order_id ='".$orderRec['order_id']."'";
	$recs = WHDBmysql_query($sql);
	$recc = mysqli_fetch_array($recs);
	echo "<td>" . $recc['box']. "箱</td>";
	echo "<td>" . $recc['plant']. "盆</td>";
	echo "<td>" . $recc['subtotal']. "元</td>";
	echo "</tr>";
	
}
mysqli_free_result($orderRes);

echo "</table></div>";
require("menu.php");
require("../public/footer.php");

function print_table_header(){
	echo "<tr>";
	echo "<td>发货日期</td>";
	echo "<td>订单号</td>";
	echo "<td>产品</td>";
	echo "<td>箱数</td>";
	echo "<td>盆数</td>";
	echo "<td>金额</td>";
	echo "</tr>";
}
?>