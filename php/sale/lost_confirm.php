<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/lost_confirm.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>报损补货查询</div>";
echo "<div id='right'>";
$sql = "SELECT * FROM lost WHERE flag='未复核' ORDER BY date DESC;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	print_table_head();
	$total = print_table_body($result);
	print_table_foot($total);
}
echo "</div>";
mysqli_free_result($result);


function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>产品</th>
	<th>经销商</th>
	<th>金额</th>
	<th>备注</th>
	<th>修改</th>
	<th>复核</th>
	</tr>";
}

function print_table_body($result){
	$total = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . $recrow['date'] . "</td>";
		echo "<td>" . getProductName($recrow['product_code']) . "</td>";
		echo "<td>" . getAgentName($recrow['agent_code']) . "</td>";
		echo "<td>" . $recrow['amount'] . "</td>";
		echo "<td>" . $recrow['comm'] . "</td>";
		echo "<td>" . "<a href='lost_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>";
		echo "<td>" . "<a href='lost_confirm_do.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>复核</a></td>";
		echo "</tr>";
		$total = $total + $recrow['amount'];
	}
	return $total;
}
function print_table_foot($total){
	echo "</table>";
}
?>

<div id="new">
		<a href='lost_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
