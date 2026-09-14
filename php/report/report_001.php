<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_001.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>财务情况同比表</div>";
echo "<div id='right'>";

print_table_head();
print_table_body();

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
	$queryDate = yearFirstDate(time());
	
	for($i = 0; $i < 5; $i++){
		$income = print_income($queryDate);
	    $cost = print_cost($queryDate);
	    print_profit($queryDate,$income,$cost);
	    $queryDate = yearFirstDate($queryDate-10*24*60*60);
	}
	
	echo "</table>";
	echo "</div>";
}

function print_income($queryDate){
	echo "<tr>";
	echo "<td>" . date('Y',$queryDate) ."收入</td>";
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
		$income[$i] = $record['amount']-$record1['amount'];
		$queryDate = getNextMonthFirstDate($queryDate);
		$total = $total + round(($record['amount']-$record1['amount']),0);
	}
	echo "<td>" . numberToMoney($total) . "</td>";
	echo "</tr>";
	return $income;
}

function print_cost($queryDate){
	echo "<tr>";
	echo "<td>" . date('Y',$queryDate) ."成本</td>";
	$total = 0;
	for ($i = 0; $i < 12; $i++) {
		$startDate = date('Y-m-d',$queryDate);
		$endDate = date('Y-m-d',getNextMonthFirstDate($queryDate));
		$sql = "select sum(amount) as amount from cost 
		where date >= '". $startDate ."' and date < '" . $endDate . "' 
		and (category='生产成本' or category='管理成本' or category='财务成本');";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
		echo "<td>" . numberToMoney($record['amount']) . "</td>";
		$cost[$i] = $record['amount'];
		$queryDate = getNextMonthFirstDate($queryDate);
		$total = $total + round($record['amount'],0);
	}
	echo "<td>" . numberToMoney($total) . "</td>";
	echo "</tr>";
	return $cost;
}

function print_profit($queryDate,$income,$cost){
	echo "<tr>";
	echo "<td><span style='color:red'>" . date('Y',$queryDate) ."利润</span></td>";
	$total = 0;
	for ($i = 0; $i < 12; $i++) {
		$profit = $income[$i] - $cost[$i];
		echo "<td><span style='color:red'>" . numberToMoney($profit) . "</span></td>";
		$total = $total + $profit;
	}
	echo "<td><span style='color:red'>" . numberToMoney($total) . "</span></td>";
	echo "</tr>";
}
?>