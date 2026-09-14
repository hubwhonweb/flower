<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/access.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>用户使用情况查询</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	$sql = "SELECT * FROM access_log where user_name like '" .$_POST['user_name']. "%' order by access_time DESC limit 100;";
}
else{
	$sql = "SELECT * FROM access_log order by access_time DESC limit 100;";
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
	<th>时间</th>
	<th>功能列表</th>
	</tr>";
}
function print_table_body($result){
	while($recrow = mysqli_fetch_array($result)){
		echo "<tr>";
		echo "<td>" . $recrow['user_name'] . "</td>";
		echo "<td>" . $recrow['access_time'] . "</td>";
		echo "<td>" . $recrow['function_name'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "admin/access.php"; ?>" method="post">
查询用户：
<input type="text" name="user_name" value="" />
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>