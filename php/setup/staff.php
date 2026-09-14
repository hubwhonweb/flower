<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/staff.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");
echo "<div id='title'>查询员工信息信息</div>";
echo "<div id='right'>";

$sql = "SELECT * FROM staff ORDER BY active;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
 	echo "没有记录";	 
}
else{
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>姓名</th>
	<th>联系电话</th>
	<th>出生年月</th>
	<th>住址</th>
	<th>身份证号</th>
	<th>部门</th>
	<th>职务</th>
	<th>入职时间</th>
	<th>状态</th>
	<th>动作</th>
	</tr>";
while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
		<td>" . $recrow['name'] . "</td>
		<td>" . $recrow['tel'] . "</td>
		<td>" . $recrow['birthday'] . "</td>
		<td>" . $recrow['address'] . "</td>
		<td>" . $recrow['id_no'] . "</td>
		<td>" . $recrow['department'] . "</td>
		<td>" . $recrow['title'] . "</td>
		<td>" . $recrow['begin_date'] . "</td>";
	if( $recrow['active']=="YES" ){
		echo "<td>在职</td>";
	}
	else{
		echo "<td>离职</td>";
	}
	echo "<td>" . "<a href='staff_modify.php?id=" . $recrow['id'] . "'>修改" ."</td></tr>";
}
echo "</table>";
}
echo "</div>";
?>

<div id="new">
	<a href='staff_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>