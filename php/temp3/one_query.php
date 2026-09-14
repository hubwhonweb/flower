<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_query.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询数据</div>";
echo "<div id='right'>";

$indirect_cost = print_indirect_cost();
$direct_cost = print_direct_cost();
$income = print_income();
print_to_pay();
print_profit($indirect_cost,$direct_cost,$income);

echo "</div>";

require("menu.php");
require("../public/footer.php");

function print_indirect_cost(){
	$sql = "select sum(one_amount) as amount from one_cost;";
	$result = WHDBmysql_query($sql);
	$recrow = mysqli_fetch_assoc($result);
    echo "<span style='font-size:25px'>间接成本：";
	echo round($recrow['amount'],2) . "元";
	echo "</span><br />";
	return round($recrow['amount'],2);
}

function print_direct_cost(){
	$sql = "select sum(one_amount) as amount from one_in;";
	$result = WHDBmysql_query($sql);
	$recrow = mysqli_fetch_assoc($result);
    echo "<span style='font-size:25px'>直接成本：";
	echo round($recrow['amount'],2) . "元";
	echo "</span><br />";
	return round($recrow['amount'],2);
}
function print_income(){
	$sql = "select sum(one_amount) as amount from one_out;";
	$result = WHDBmysql_query($sql);
	$recrow = mysqli_fetch_assoc($result);
    echo "<span style='font-size:25px'>销售收入：";
	echo round($recrow['amount'],2) . "元";
	echo "</span><br />";
	return round($recrow['amount'],2);
}

function print_to_pay(){
	$sql = "select sum(one_amount) as amount from one_out where one_flag='未付款';";
	$result = WHDBmysql_query($sql);
	$recrow = mysqli_fetch_assoc($result);
    echo "<span style='font-size:25px'>未付款：";
	echo round($recrow['amount'],2) . "元";
	echo "</span><br />";
	return round($recrow['amount'],2);
}

function print_profit($indirect_cost,$direct_cost,$income){
	echo "<span style='font-size:25px'>利润（收入-间接成本-直接成本）：";
	echo $income-$indirect_cost-$direct_cost . "元";
	echo "</span><br />";
}

?>
