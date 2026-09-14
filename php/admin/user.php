<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/user.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");

$sql = "SELECT * FROM user;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
echo "<div id='title'>查询用户信息</div>";
echo "<div id='right'>";
if($numrow == 0) {
 	echo "没有记录";	 
 }
else{
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>用户名</th>
	<th>密码</th>
	<th>备注</th>
	<th>状态</th>
	<th>动作</th>
	</tr>";
while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
		<td>" . $recrow['name'] . "</td>
		<td>" . $recrow['password'] . "</td>
		<td>" . $recrow['comm'] . "</td>
		<td>" . $recrow['active'] . "</td>
		<td>" . "<a href='user_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>
		</tr>";
}
echo "</table>";
}
echo "</div>";
?>

<div id="new">
	<a href='user_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>