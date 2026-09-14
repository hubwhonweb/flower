<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/report001.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>销售三年同比（扣除客户报损）</div>";
echo "<div id='right'>";
print_table_head();
print_table_body();
echo "</div>";
require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>年度</th>";
	for ($i = 0; $i < 12; $i++) {
		echo "<th>" . ($i+1) ."月</th>";
	}
	echo "<th>合计</th>";
	echo "</tr>";
}

function print_table_body(){
	$queryDate3 = yearFirstDate(time());
	$queryDate2 = yearFirstDate($queryDate3-10*24*60*60);
	$queryDate1 = yearFirstDate($queryDate2-10*24*60*60);
	print_income($queryDate1);
	print_income($queryDate2);
	print_income($queryDate3);
	echo "</table>";
}

function print_income($queryDate){
	echo "<tr>";
	echo "<td>" . date('Y',$queryDate) ."年度每月收入</td>";
	$total = 0;
	for ($i = 0; $i < 12; $i++) {
		$startDate = date('Y-m-d',$queryDate);
		$endDate = date('Y-m-d',getNextMonthFirstDate($queryDate));
		//收入
		$sql = "select sum(subtotal) as amount from orders where date >= '". $startDate ."' and date < '" . $endDate . "';";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
		//报损
		$sql = "select sum(amount) as amount from lost where date >= '". $startDate ."' and date < '" . $endDate . "';";
		$result1 = WHDBmysql_query($sql);
		$record1 = mysqli_fetch_array($result1);
		//收入减去报损
		echo "<td>" . numberToMoney($record['amount']-$record1['amount']) . "</td>";
		$queryDate = getNextMonthFirstDate($queryDate);
		$total = $total + round(($record['amount']-$record1['amount']),2);
	}
	echo "<td>" . numberToMoney($total) . "</td>";
	echo "</tr>";
}
?>