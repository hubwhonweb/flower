<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("material/material_warning.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");

echo "<div id='righttop'>查询库存信息</div>";
echo "<div id='right'>";

print_table_head();

//在生产资料表中查询有效的生产资料。把最少库存量和生产资料代码给打印函数进行打印
$sql ="SELECT * from material where active='YES' order by code;";
$result = WHDBmysql_query($sql);
while($rec = mysqli_fetch_array($result)){
	print_table_line($rec);
}
mysqli_free_result($result);

echo "</table>";
echo "</div>";

require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>--分类--</th>
	<th>--编号--</th>
	<th>--名称--</th>
	<th>--库存--</th>
	<th>--最少库存量--</th>
	<th>--单位--</th>
	</tr>";
}
//打印一种生产资料的剩余数量
function print_table_line($material){
	//入库信息
	$sql = "SELECT SUM(total) AS total FROM material_log WHERE flag='IN' AND material_code ='" . $material['code'] . "'";
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	$materialIn = $rec['total'];
	//出库信息
	$sql = "SELECT SUM(total) AS total FROM material_log WHERE flag='OUT' AND material_code ='" . $material['code'] . "'";
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	$materialOut = $rec['total'];
	//打印、合计等
	$total = $materialIn - $materialOut;
	if( $total <= $material['warning_limit']){
		echo "<tr>";
		echo "<td>" . $material['category'] . "</td>";
		echo "<td>" . $material['code'] . "</td>";
		echo "<td>" . $material['name'] . "</td>";
		echo "<td>" . $total . "</td>";
		echo "<td>" . $material['warning_limit'] . "</td>";
		echo "<td>" . $material['unit'] . "</td>";
		echo "</tr>";
	}
	mysqli_free_result($result);
}


?>