<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_all_agent.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>查询订单记录</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	 //设置付款标志，查询的起止日期
	 $w_startdate = $_POST['startdate'];
	 $w_enddate = $_POST['enddate'];
	 //生成查询的条件语句并查询
	 if($_POST['agent_code']=="99999"){
		 $sql = "SELECT * FROM orders WHERE date >='" . $w_startdate . "' AND date <='" . $w_enddate . "' ORDER BY date DESC;";
	 }
	 else{
		 $sql = "SELECT * FROM orders WHERE agent_code='" .$_POST['agent_code']. "' AND date >='" . $w_startdate . "' AND date <='" . $w_enddate . "' ORDER BY date DESC;";
		 }
}
else{
	 //设置查询的起止日期
	 $w_startdate = date('Y-m-d',monthFirstDate(time()));
	 $w_enddate = date('Y-m-d',time());
	 //生成查询的条件语句并查询
	 $sql = "SELECT * FROM orders WHERE date >='" . $w_startdate . "' AND date <='" . $w_enddate . "' ORDER BY date DESC;";
}
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
//处理查询结果
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
	echo "<tr>";
	echo "<th>订单号</th>";
	echo "<th>日期</th>";
	echo "<th>经销商</th>";
	echo "<th>产品名</th>";
	echo "<th>箱数</th>";
	echo "<th>每箱盆数</th>";
	echo "<th>总盆数</th>";
    echo "<th>等级</th>";
	echo "<th>单价</th>";
	echo "<th>合计</th>";
	echo "<th>备注</th>";
	echo "<th>状态</th>";
	echo "</tr>";
}

function print_table_body($result){
	$stt = 0;
	$ptt = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		echo"<tr>";
		echo "<td>" . "<a href='order_view.php?id=" . $recrow['order_id'] . "'>". $recrow['order_id'] ."</td>";
		echo "<td>" . $recrow['date'] . "</td>";
		echo "<td>" . getAgentName($recrow['agent_code']) . "</td>";
		echo "<td>" . getProductName($recrow['product_code']) . "</td>";
		echo "<td>" . $recrow['boxes'] . "</td>";
		echo "<td>" . $recrow['plants_in_box'] . "</td>";
		echo "<td>" . $recrow['plants'] . "</td>";
        echo "<td>" . $recrow['class'] . "</td>";
		echo "<td>" . $recrow['price'] . "</td>";
		echo "<td>" . floor($recrow['subtotal']) . "</td>";
		echo "<td>" . $recrow['comment'] . "</td>";
		echo "<td>" . $recrow['flag'] . "</td>";
		echo "</tr>";
		$stt = $stt + floor($recrow['subtotal']);
		$ptt = $ptt + $recrow['plants'];
	}
	echo"<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><span style='color:red' >" . $ptt . "</span></td>";
	echo "<td></td>";
	echo "<td><span style='color:red' >" . $stt . "</span></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/order_all_agent.php"; ?>" method="post">
	
<select name="agent_code">
<?php
echo "<option value='99999'>全部经销商</option>";
$catsql = "SELECT * FROM agent WHERE active='YES' order by order_times DESC, name_pinyin;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>
<?php
//查询的时间条件，起始时间为月初，结束时间为当日
echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>"; 
echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
?>

<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
