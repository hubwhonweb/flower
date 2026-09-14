<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/products.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");
echo "<div id='title'>查询产品信息</div>";
echo "<div id='right'>";

$sql = "SELECT * FROM products order by product_code;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
 	echo "没有记录";	 
 }
else{
	printTableHead();
	printTableBody($result);
}
mysqli_free_result($result);
echo "</div>";

function printTableBody($result){
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . $recrow['product_code'] . "</td>";
		echo "<td>" . $recrow['product_name'] . "</td>";
		echo "<td>" . $recrow['active'] . "</td>";
		echo "<td>" . "<a href='products_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>";
		echo "</tr>";
}
echo "</table>";
}
function printTableHead(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>产品编号</th>
	<th>产品名称</th>
	<th>状态</th>
	<th>动作</th>
	</tr>";
}
?>

<div id="new">
	<a href='products_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
