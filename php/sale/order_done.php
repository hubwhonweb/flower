<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_done.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>查询已付款订单记录</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	 //设置付款标志，查询的起止日期
	 $w_startdate = $_POST['startdate'];
	 $w_enddate = $_POST['enddate'];
	 //生成查询的条件语句并查询
	 $sql = "SELECT distinct(order_id) FROM orders WHERE flag='已付款' AND bank_date >='" . $w_startdate . "' AND bank_date <='" . $w_enddate . "' ORDER BY bank_date DESC;";
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
}
else{
	 //设置查询的起止日期
	 $w_startdate = date('Y-m-d',monthFirstDate(time()));
	 $w_enddate = date('Y-m-d',time());
	 //生成查询的条件语句并查询
	 $sql = "SELECT distinct(order_id) FROM orders WHERE flag='已付款' AND bank_date >='" . $w_startdate . "' AND bank_date <='" . $w_enddate . "' ORDER BY bank_date DESC;";
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
}
echo "</div>";
mysqli_free_result($result);

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>订单号</th>";
	echo "<th>日期</th>";
	echo "<th>经销商</th>";
	echo "<th>订单金额</th>";
	echo "<th>发货日期</th>";
	echo "<th>银行入账日期</th>";
	//echo "<th>银行对应金额</th>";
	//echo "<th>状态</th>";
	//echo "<th>修改人</th>";
	echo "<th>操作</th>";
	echo "</tr>";
}

function print_table_body($result){
	$stt = 0;
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
		echo "<td>" . "<a href='order_view.php?id=" . $recrow['order_id'] . "'>". $recrow['order_id'] ."</td>";
		echo "<td>" . $orderrec['date'] . "</td>";
		echo "<td>" . getAgentName($orderrec['agent_code']) . "</td>";
		echo "<td>" . floor($totalrow['subt']) . "</td>";
		echo "<td>" . $orderrec['send_date'] . "</td>";
		echo "<td>" . $orderrec['bank_date'] . "</td>";
		//echo "<td>" . $orderrec['bank_rec'] . "</td>";
		//echo "<td>" . $orderrec['flag'] . "</td>";
		//echo "<td>" . $orderrec['mname'] . "</td>";
        if( $orderrec['bank_date'] > date('Y-m-d',time()-48*60*60) ) {
            echo "<td>" . "<a href='order_pay_modify.php?id=" . $recrow['order_id'] . "'>未付款</td>";
        }
		echo "<td></td>";
		echo "</tr>";
		$stt = $stt + $totalrow['subt'];
	}
	mysqli_free_result($totalrec);
	mysqli_free_result($orderresult);
	echo"<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><span style='color:red' >" . $stt . "</span></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
    echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/order_done.php"; ?>" method="post">
<?php
//查询的时间条件，起始时间为月初，结束时间为当日
echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>"; 
echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
?>

<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
