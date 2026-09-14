<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/ac.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>一目了然</div><div id='right'>";
//当年的情况
$queryDate = date('Y-m-d',yearFirstDate(time()));
echo "<span style='font-size:20px;color:red;'>当年财务情况</span>";
//get income
$sql = "SELECT sum(subtotal) as total FROM orders WHERE date >='" . $queryDate . "' AND flag='已付款';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$income = $rec['total'];
mysqli_free_result($result);
//get lost
$sql = "SELECT sum(amount) as amount FROM lost WHERE date >='" . $queryDate . "' AND flag='已复核';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$lost = $rec['amount'];
mysqli_free_result($result);
//get cost
$sql = "SELECT  sum(amount) as amount FROM cost WHERE date >='" . $queryDate . "' AND confirm='已复核';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$cost = $rec['amount'];
mysqli_free_result($result);
//get income need pay
$sql = "SELECT  sum(subtotal) as total FROM orders WHERE date >='" . $queryDate . "' AND flag='已发货';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$topay = $rec['total'];
mysqli_free_result($result);

print_this_year($income,$lost,$cost,$topay,$acChange);

//历年累计情况
echo "<span style='font-size:20px; color:red;'>历年累计财务情况</span>";
//get income
$sql = "SELECT sum(subtotal) as total FROM orders WHERE flag='已付款';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$income = $rec['total'];
mysqli_free_result($result);
//get lost
$sql = "SELECT sum(amount) as amount FROM lost WHERE flag='已复核';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$lost = $rec['amount'];
mysqli_free_result($result);
//get cost
$sql = "SELECT  sum(amount) as amount FROM cost WHERE confirm='已复核';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$cost = $rec['amount'];
mysqli_free_result($result);
//get income need pay
$sql = "SELECT  sum(subtotal) as total FROM orders WHERE flag='已发货';";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$topay = $rec['total'];
mysqli_free_result($result);
//get account change
$sql = "SELECT  sum(amount) as amount FROM account;";
$result = WHDBmysql_query($sql);
$rec = mysqli_fetch_array($result);
$acChange = $rec['amount'];
mysqli_free_result($result);
print_all_years($income,$lost,$cost,$topay,$acChange);

echo "</div>";
require("menu.php");
require("../public/footer.php");

function print_all_years($income,$lost,$cost,$topay,$acChange){
	echo "<table width='700' border='2' cellspacing='5'>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>已收款：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($income,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>应收款：</td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($topay,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>报损：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($lost,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>总收入（已付款+未付款-报损）：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($topay+$income-$lost,2) . "</span></td>";
	echo "</tr>";
		
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>总支出：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($cost,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>毛利润（总收入-总支出）：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($topay+$income-$lost-$cost,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>银行帐户交易合计：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($acChange,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>公司现金余额（已付款-报损-总支出+银行交易）：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($income-$lost-$cost+$acChange,2) . "</span></td>";
	echo "</tr>";
	
	echo "</table>";
}
function print_this_year($income,$lost,$cost,$topay){
	echo "<table width='700' border='2' cellspacing='5'>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>已收款：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($income,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>应收款：</td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($topay,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>报损：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($lost,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>总收入（已付款+未付款-报损）：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($topay+$income-$lost,2) . "</span></td>";
	echo "</tr>";
		
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>总支出：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($cost,2) . "</span></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><span style='font-size:20px;color:red;'>毛利润（总收入-总支出）：</span></td>";
	echo "<td><span style='font-size:25px;color:red;'>". number_format($topay+$income-$lost-$cost,2) . "</span></td>";
	echo "</tr>";
	
	echo "</table>";

}
?>