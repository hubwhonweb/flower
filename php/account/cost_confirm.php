<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/cost_confirm.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>查询支出记录</div><div id='right'>";
// 提交查询表单后处理
$sql= "SELECT * FROM cost WHERE confirm='未复核'";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
 	echo "没有记录";	 
}
else{
	print_table_head();
	print_table_body($result);
}
mysqli_free_result($result);
echo "</div>";
 
function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>科目</th>
	<th>分类</th>
	<th>子项</th>
	<th>金额</th>
	<th>说明</th>
	<th>经手人</th>
	<th>状态</th>
	<th>修改人</th>
	<th>修改</th>
	<th>确认</th>
	</tr>";
}
function print_table_body($result){
	$tt = 0;
	while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
	<td>" . $recrow['date'] . "</td>
	<td>" . $recrow['item'] . "</td>
	<td>" . $recrow['category'] . "</td>
	<td>" . $recrow['name'] . "</td>
	<td>" . $recrow['amount'] . "</td>
	<td>" . $recrow['comm'] . "</td>
	<td>" . $recrow['handler'] . "</td>
	<td>" . $recrow['confirm'] . "</td>
	<td>" . $recrow['mname'] . "</td>
	<td>" . "<a href='cost_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>
	<td>" . "<a href='cost_confirm_do.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>复核" ."</td>
	</tr>";
	$tt = $tt + $recrow['amount'];
	}
	echo"<tr><td></td><td></td><td></td><td></td>";
	echo "<td><span style='color:red' >" . $tt . "</span></td>";
	echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	echo "</table>";
}
?>

<div id="new">
	<a href='cost_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>