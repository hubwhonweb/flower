<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_pay.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>查询未付款订单记录</div>";
echo "<div id='right'>";

$sql = "SELECT distinct(order_id),date FROM orders WHERE flag='已发货' ORDER BY date DESC;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	print_table_head();
	print_table_body($result);
}
mysqli_free_result($result);
echo "</div>";

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>订单号</th>";
	echo "<th>日期</th>";
	echo "<th>经销商</th>";
	echo "<th>订单金额</th>";
	//echo "<th>发货日期</th>";
	echo "<th>状态</th>";
	echo "<th>修改人</th>";
	echo "<th>操作</th>";
	echo "</tr>";
}
function print_table_body($result){
	$stt = 0 ;
	while($recrow = mysqli_fetch_assoc($result)){
	//查找同一订单号的第一条记录,
	$sql = "SELECT * from orders WHERE order_id = '" . $recrow['order_id'] . "';";
	$orderresult = WHDBmysql_query($sql);
	$orderrec = mysqli_fetch_assoc($orderresult);
	//查找订单总金额
	$sql = "SELECT sum(subtotal) as subt  from orders WHERE order_id = '" . $recrow['order_id'] . "';";
	$totalrec = WHDBmysql_query($sql);
	$totalrow = mysqli_fetch_assoc($totalrec);
	
	//打印表格内容
	echo"<tr>";
	echo "<td>" . "<a href='order_view.php?id=" . $recrow['order_id'] ."'>" . $recrow['order_id'] . "</td>";
	echo "<td>" . $orderrec['date'] . "</td>";
	echo "<td>" . getAgentName($orderrec['agent_code']) . "</td>";
	echo "<td>" . floor($totalrow['subt']) . "</td>";
	//echo "<td>" . $orderrec['send_date'] . "</td>";
	echo "<td>" . $orderrec['flag'] . "</td>";
	echo "<td>" . $orderrec['mname'] . "</td>";
	echo "<td>" . "<a href='order_pay_do.php?id=" . $recrow['order_id'] . "'>付款" ."</td>";
	echo "</tr>";
	$stt = $stt + $totalrow['subt'];
	}
	echo"<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><span style='color:red' >" . floor($stt) . "</span></td>";
	//echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>

<?php
require("menu.php");
require("../public/footer.php");
?>