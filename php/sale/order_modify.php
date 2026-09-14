<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>修改订单记录</div>";
echo "<div id='right'>";

//设置付款标志，查询的起止日期
$w_startdate = date('Y-m-d',time()-48*60*60);
$w_enddate = date('Y-m-d',time());
//生成查询的条件语句并查询
$sql = "SELECT * FROM orders WHERE date >='" . $w_startdate . "' AND date <='" . $w_enddate . "' ORDER BY date DESC;";
	
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
//处理查询结果
if($numrow == 0) {
 	echo "没有记录";	 
}
else{
	print_table_head();
	print_table_body($result);
}
echo "</div>";
mysqli_free_result($result);

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>订单号</th>";
	echo "<th>日期</th>";
	echo "<th>经销商</th>";
	echo "<th>箱数</th>";
	echo "<th>单价</th>";
	echo "<th>合计</th>";
	echo "<th>备注</th>";
	echo "<th>状态</th>";
	echo "<th>修改</th>";
	echo "</tr>";
}

function print_table_body($result){
	while($recrow = mysqli_fetch_assoc($result)){
		echo"<tr>";
		echo "<td>" . $recrow['order_id'] ."</td>";
		echo "<td>" . $recrow['date'] . "</td>";
		echo "<td>" . getAgentName($recrow['agent_code']) . "</td>";
		echo "<td>" . $recrow['boxes'] . "</td>";
		echo "<td>" . $recrow['price'] . "</td>";
		echo "<td>" . floor($recrow['subtotal']) . "</td>";
		echo "<td>" . $recrow['comment'] . "</td>";
		echo "<td>" . $recrow['flag'] . "</td>";
		echo "<td>" . "<a href='order_modify_do.php?id=" . $recrow['id'] . "'>"."修改</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>

<?php
require("menu.php");
require("../public/footer.php");
?>
