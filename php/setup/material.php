<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/material.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

$sql = "SELECT * FROM material order by code;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);

echo "<div id='title'>增加生产资料信息</div>";
echo "<div id='right'>";

if($numrow == 0) {
	echo "没有记录";	 
}
else{
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>编号</th>
	<th>分类</th>
	<th>名称</th>
	<th>单位</th>
	<th>警告数量</th>
	<th>参考价格</th>
	<th>产品描述</th>
	<th>状态</th>
	<th>修改人</th>
	<th>动作</th>
	</tr>";
while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
		<td>" . $recrow['code'] . "</td>
		<td>" . $recrow['category'] . "</td>
		<td>" . $recrow['name'] . "</td>
		<td>" . $recrow['unit'] . "</td>
		<td>" . $recrow['warning_limit'] . "</td>
		<td>" . $recrow['price'] . "</td>
		<td>" . $recrow['detail'] . "</td>
		<td>" . $recrow['active'] . "</td>
		<td>" . $recrow['mname'] . "</td>
		<td>" . "<a href='material_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>
		</tr>";
}
echo "</table>";
}
echo "</div>";
?>

<div id="new">
	<a class="newform" href='material_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>