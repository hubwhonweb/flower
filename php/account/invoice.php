<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/invoice.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>invoice</div><div id='right'>";

$sql = "SELECT  * FROM invoice  
	 ORDER BY pay_date DESC LIMIT 50;";
$result = WHDBmysql_query($sql);
$num = mysqli_num_rows($result);
	// 打印表格
if( $num == 0 ) {
	echo "没有记录";
}
else{
	print_table_head();
	print_table_body($result);
}

echo "</div>";
mysqli_free_result($result);

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo " <th>付款日期</th>";
	echo " <th>项目</th>";
	echo " <th>金额</th>";
	echo " <th>发票情况</th>";
	echo " <th>操作</th>";
	echo "</tr>";
}
function print_table_body($result){
	while( $rec = mysqli_fetch_assoc($result)  ) {
		echo "<tr>";
		echo "<td>" . $rec['pay_date'] . "</td>";
		echo "<td>" . $rec['subject'] . "</td>";
		echo "<td>" . $rec['amount'] . "</td>";
		echo "<td>" . $rec['flag'] . "</td>";
		echo "<td>" ."<a href='invoice_get.php?id=" . $rec['id'] . "'>收到发票</td>";
		echo "</tr>";
	} 
	echo "</table>";
}
?>

<div id="new">
	<a href='invoice_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>