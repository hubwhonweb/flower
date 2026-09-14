<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/task.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");
echo "<div id='title'>查询工作任务信息</div>";
echo "<div id='right'>";

$sql = "SELECT * FROM task order by task_code;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result); 
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>编号</th>
	<th>名称</th>
    <th>组别</th>
	<th>指标</th>
	<th>单价</th>
	<th>描述</th>
	<th>动作</th>
	</tr>";
	while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
		<td>" . $recrow['task_code'] . "</td>
		<td>" . $recrow['task_name'] . "</td>
        <td>" . $recrow['task_group'] . "</td>
		<td>" . $recrow['task_number'] . "</td>
		<td>" . $recrow['task_price'] . "</td>
		<td>" . $recrow['task_describe'] . "</td>
		<td>" . "<a href='task_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>
		</tr>";
	}
	echo "</table>";
}
echo "</div>";
?>

<div id="new">
	<a class="newform" href='task_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>
