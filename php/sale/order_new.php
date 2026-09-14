<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");

echo "<div id='title'>查询订单记录</div>";
echo "<div id='right'>";
if(isset($_POST['submit'])){
	$sql = make_sql($_POST['product_code'],$_POST['class'],$_POST['agent_code']);
}
else{
	$sql = make_sql("99999","99999","99999");
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

function make_sql($product_code,$class,$agent_code){
	$msql = "";
	if ($product_code != "99999"){
		$msql = $msql . " AND product_code = '" . $product_code . "'";
	}
	if ($class != "99999"){
		$msql = $msql . " AND class = '" . $class . "'";
	}
	if ($agent_code != "99999"){
		$msql = $msql . " AND agent_code = '" . $agent_code . "'";
	}
	$sql = "SELECT * FROM orders WHERE flag='新订单' ". $msql . " ORDER BY order_id DESC;";
	return $sql;
}
function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>订单号</th>";
	echo "<th>日期</th>";
	echo "<th>经销商</th>";
	echo "<th>产品</th>";
	echo "<th>等级</th>";
	echo "<th>箱数</th>";
	echo "<th>每箱盆数</th>";
	echo "<th>价格</th>";
	echo "<th>合计</th>";
	echo "<th>备注</th>";
	echo "<th>修改人</th>";
	echo "<th>操作</th>";
	echo "<th>操作</th>";
	echo "<th>操作</th>";
	echo "</tr>";
}
function print_table_body($result){
	$boxes = 0;
	while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>";
	echo "<td>" . $recrow['order_id'] ."</td>";
	echo "<td>" . $recrow['date'] . "</td>";
	echo "<td>" . getAgentName($recrow['agent_code']) . "</td>";
	echo "<td>" . getProductName($recrow['product_code']) . "</td>";
	echo "<td>" . $recrow['class'] . "</td>";
	echo "<td>" . $recrow['boxes'] . "</td>";
	echo "<td>" . $recrow['plants_in_box'] . "</td>";
	echo "<td>" . $recrow['price'] . "</td>";
	echo "<td>" . $recrow['subtotal'] . "</td>";
	echo "<td>" . $recrow['comment'] . "</td>";
	echo "<td>" . $recrow['mname'] . "</td>";
	echo "<td>" . "<a href='order_new_modify.php?id=" . $recrow['id'] . "'>修改</a></td>";
	echo "<td>" . "<a href='order_new_delete.php?id=" . $recrow['id'] . "' onclick='return deleteConfirm()'>删除</a></td>";
	echo "<td>" . "<a href='order_new_confirm.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>确认</a></td>";
	echo "</tr>";
	$boxes = $boxes + $recrow['boxes'];
	}
	echo "<tr><td></td><td></td><td></td><td></td><td></td><td>" . $boxes . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	echo "</table>";
}
?>

<div id="new">
		<a href='order_new_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/order_new.php"; ?>" method="post">
产品：
<select name="product_code">
<?php
echo "<option value='99999' selected>'全部'</option>";
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</select>
等级：
<select name="class">
<?php
echo "<option value='99999' selected>'全部'</option>";
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
经销商：
<select name="agent_code">
<?php
echo "<option value='99999' selected>'全部'</option>";
$catsql = "SELECT * FROM agent WHERE active='YES' order by code;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
