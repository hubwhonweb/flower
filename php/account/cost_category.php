<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/cost_category.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

$sql = "SELECT * FROM cost_category order by item;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
echo "<div id='title'>增加支出类型</div>";
echo "<div id='right'>";
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>编号</th>
	<th>支出大类</th>
	<th>分类名称</th>
	<th>描述</th>
	<th>活跃状态</th>
	<th>动作</th>
	</tr>";
	while($recrow = mysqli_fetch_assoc($result)){
		echo"<tr>
		<td>" . $recrow['item'] . "</td>
		<td>" . $recrow['category'] . "</td>
		<td>" . $recrow['name'] . "</td>
		<td>" . $recrow['comm'] . "</td>
		<td>" . $recrow['active'] . "</td>
		<td>" . "<a href='cost_category_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>
		</tr>";
	}
	echo "</table>";
}
echo "</div>";
?>

<div id="new">
	<a class="newform" href='cost_category_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<?php
mysqli_free_result($result);
require("menu.php");
require("../public/footer.php");
?>