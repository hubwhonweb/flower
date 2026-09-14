<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_002.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>年度经营情况</div>";
echo "<div id='right'>";

print_table_head();
print_table_body();

echo "</div>";

require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>年度</th>
	<th>扦插量</th>
	<th>销售量</th>
	<th>损失盆数</th>
	<th>收入</th>
	<th>平均价格</th>
	<th>A级率</th>
	<th>人力成本</th>
	<th>每盆人力成本</th>
	</tr>";
}

function print_table_body(){
	$queryDate = yearFirstDate(time());
	for($i = 0; $i < 5; $i++){
		$liners = get_year_liners($queryDate);
	    $sold = get_year_sold($queryDate);
	    $income = get_year_income($queryDate);
		$averagePrice = round($income/$sold,2);
		$classA = get_year_class_a($queryDate);
		$classARate = toPercentage($classA/$sold);
		$humanResource = get_year_human($queryDate);
		$humanResource = round($humanResource,0);
		$averageHumanCost = round($humanResource/$sold,2);
		echo "<tr>";
		echo "<td>" . date('Y',$queryDate) . "</td>";
		echo "<td>" . $liners . "</td>";
		echo "<td>" . $sold . "</td>";
		echo "<td>" . ($liners - $sold) . "</td>";
		echo "<td>" . numberToMoney($income) . "</td>";
		echo "<td>" . $averagePrice . "</td>";
		echo "<td>" . $classARate . "</td>";
		echo "<td>" . $humanResource . "</td>";
		echo "<td>" . $averageHumanCost . "</td>";
		echo "</tr>";
	    $queryDate = yearFirstDate($queryDate-10*24*60*60);
	}
	echo "</table>";
	echo "</div>";
}

function get_year_human($queryDate){
	$year = date('Y',$queryDate);
	$queryStartDate = $year."-01-01";
	$queryEndDate = $year."-12-31";
	$sql = "SELECT SUM(amount) as amount FROM cost
		WHERE item = '1001' AND date <='".$queryEndDate."' AND date >='".$queryStartDate . "';";
	$res = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($res);
	return $rec['amount'];
}

function get_year_liners($queryDate){
	$year = date('Y',$queryDate);
	$queryStartDate = $year."-01-01";
	$queryEndDate = $year."-12-31";
	$sql = "SELECT SUM(batch_pots) as pots FROM batch 
		WHERE batch_date <='".$queryEndDate."' AND batch_date >='".$queryStartDate . "';";
	$res = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($res);
	return $rec['pots'];
}

function get_year_sold($queryDate){
	$year = date('Y',$queryDate);
	$queryStartDate = $year."-01-01";
	$queryEndDate = $year."-12-31";
	$sql = "SELECT SUM(plants) as pots FROM orders 
		WHERE date <='".$queryEndDate."' AND date >='".$queryStartDate . "';";	
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	return $rec['pots'];
}

function get_year_class_a($queryDate){
	$year = date('Y',$queryDate);
	$queryStartDate = $year."-01-01";
	$queryEndDate = $year."-12-31";
	$sql = "SELECT SUM(plants) as pots FROM orders 
		WHERE class = 'A级' AND date <='".$queryEndDate."' AND date >='".$queryStartDate . "';";	
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	return $rec['pots'];
}
function get_year_income($queryDate){
	$year = date('Y',$queryDate);
	$queryStartDate = $year."-01-01";
	$queryEndDate = $year."-12-31";
	//收入
		$sql = "select sum(subtotal) as amount from orders where date >= '". $queryStartDate ."' and date <= '" . $queryEndDate . "';";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
	//报损
		$sql = "select sum(amount) as amount from lost where date >= '". $queryStartDate ."' and date <= '" . $queryEndDate . "';";
		$result1 = WHDBmysql_query($sql);
		$record1 = mysqli_fetch_array($result1);
	//收入减去报损
		return ($record['amount']-$record1['amount']);
	
}
?>