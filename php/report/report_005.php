<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_005.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>月销售情况</div>";
echo "<div id='right'>";
print_table_head();
$queryYear = date('Y',time())-3;
print_table_body($queryYear);
$queryYear = date('Y',time())-2;
print_table_body($queryYear);
$queryYear = date('Y',time())-1;
print_table_body($queryYear);
$queryYear = date('Y',time());
print_table_body($queryYear);
echo "</table>";
echo "</div>";



function print_table_body($queryYear){
	//计算查询年份的每月1日时间，及下一年度的一月一日
	$queryDate[0] = date('Y-m-d',strtotime($queryYear."-01-01"));
	$queryDate[1] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[0])));
	$queryDate[2] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[1])));
	$queryDate[3] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[2])));
	$queryDate[4] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[3])));
	$queryDate[5] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[4])));
	$queryDate[6] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[5])));
	$queryDate[7] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[6])));
	$queryDate[8] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[7])));
	$queryDate[9] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[8])));
	$queryDate[10] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[9])));
	$queryDate[11] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[10])));
	$queryDate[12] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[11])));
	
	echo "<tr>";
	echo "<td>".$queryYear."</td>"; 
	for( $i = 0; $i < 12; $i++ ){
		$sql = "SELECT SUM(plants) as pots,SUM(subtotal) as totals FROM orders 
		WHERE date <'".$queryDate[$i+1]."' AND date >='".$queryDate[$i] . "' 
		AND product_code='0001';";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
		if( $record['pots']==NULL or $record['pots']==0){
			$plants = 1;
		}
		else{
			$plants = $record['pots'];
		}
		$rtotal = round($plants/10000,1);
		echo "<td>".round($record['totals']/$plants,2)."（".$rtotal."）"."</td>";
		
	}
	echo "</tr>";
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th></th>
	<th>1月</th>
	<th>2月</th>
	<th>3月</th>
	<th>4月</th>
	<th>5月</th>
	<th>6月</th>
	<th>7月</th>
	<th>8月</th>
	<th>9月</th>
	<th>10月</th>
	<th>11月</th>
	<th>12月</th>
	</tr>";
}
?>

<?php
require("menu.php");
require("../public/footer.php");
?>