<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/variety.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>查询品种信息</div>";

$sql = "SELECT * FROM variety 
       WHERE active='在产' ORDER BY variety_code;";

$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
echo "<div id='right'>";
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	printTableHead();
	printTableBody($result);
}
echo "</div>";

require("menu.php");
require("../public/footer.php");

function printTableHead(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>品种编号</th>
	<th>品种名称</th>
	<th>颜色</th>
	<th>厂商</th>
	<th>备注</th>
	<th>状态</th>
	</tr>";
}
function printTableBody($result){
	$i = 1;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . $recrow['variety_code'] . "</td>";
		echo "<td>" . $recrow['variety_name'] . "</td>";
		echo "<td>" . $recrow['color'] . "</td>";
		echo "<td>" . $recrow['supplier'] . "</td>";
		echo "<td>" . $recrow['comm'] . "</td>";
		echo "<td>" . $recrow['active'] . "</td>";
		echo "</tr>";
		$i = $i+1;
	}
	echo "</table>";
}
?>
