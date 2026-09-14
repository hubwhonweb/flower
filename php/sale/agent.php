<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/agent.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");
echo "<div id='title'>查询经销商信息</div>";
echo "<div id='right'>";

$sql = "SELECT * FROM agent order by order_date DESC;";
if(isset($_POST['submit'])){
   $sql = "SELECT * FROM agent where name like '".$_POST['name_select']."%';";
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
mysqli_free_result($result);
echo "</div>";

function print_table_body($result){
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . $recrow['code'] . "</td>";
		echo "<td>" . $recrow['name'] . "</td>";
		echo "<td>" . $recrow['city'] . "</td>";
        echo "<td>" . $recrow['tel'] . "</td>";
		echo "<td>" . $recrow['active'] . "</td>";
		echo "<td>" . $recrow['order_times'] . "</td>";
		echo "<td>" . $recrow['order_date'] . "</td>";
		echo "<td>" . "<a href='agent_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>";
		echo "</tr>";
}
echo "</table>";
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>经销商编号</th>
	<th>经销商名称</th>
    <th>电话</th>
	<th>市场</th>
	<th>活跃</th>
	<th>订货次数</th>
	<th>订货时间</th>
	<th>动作</th>
	</tr>";
}
?>

<div id="new">
	<a href='agent_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/agent.php"; ?>" method="post">
<input type='text' name='name_select' value="">
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>


<?php
require("menu.php");
require("../public/footer.php");
?>
