<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/can.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>用户权限查询</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	$sql = "SELECT * FROM can where user_name like '" .$_POST['user_name']. "%';";
}
else{
	$sql = "SELECT * FROM can order by user_name;";
} 
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
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
	echo "<tr>
	<th>用户</th>
	<th>功能代码</th>
	<th>操作</th>
	</tr>";
}
function print_table_body($result){
	while($recrow = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>" . $recrow['user_name'] . "</td>";
		echo "<td>" . $recrow['function_code'] . "</td>";
		echo "<td>" . "<a href='can_delete.php?id=" . $recrow['id'] . "'>删除" ."</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
<div id="new">
	<a href='can_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "admin/can.php"; ?>" method="post">
查询用户：
<input type="text" name="user_name" value="" />
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>