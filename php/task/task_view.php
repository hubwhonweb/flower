<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("task/task_task.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询工作信息</div>";
echo "<div id='right'>";
	
if(isset($_POST['submit'])){
	$sql = make_sql($_POST['startdate'],$_POST['enddate']);
	print_title($_POST['startdate'],$_POST['enddate']);
}
 else{
	$startd = date('Y-m-d',monthFirstDate(time())); 
	$endd = date('Y-m-d',time());
	$sql = make_sql($startd,$endd);
	print_title($startd,$endd);
}
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	print_table_head();
	$total = print_table_body($result);
	print_table_foot($total);
}
echo "</div>";
mysqli_free_result($result);

function print_title($startd,$endd,$task_code){
	echo "<span style='font-size:25px'>查询条件：";
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
	mysqli_free_result($result);
}

function make_sql($startd,$endd){
	$sql = "SELECT task_code,task_price,task_name,task_unit, SUM(task_count) as count 
	from task_log   
	where task_date>='".$startd."'  and task_date<='".$endd."' 
	GROUP BY task_code;";
	return $sql;
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>工作单元</th>
	<th>数量</th>
	<th>单价</th>
	<th>合计</th>
	</tr>";
}

function print_table_body($result){
	$total = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		$subtotal = round($recrow['task_price']*$recrow['count'],2);
		echo "<tr>";
		echo "<td>" . $recrow['task_name'] . "</td>";
		echo "<td>" . round($recrow['count'],2).$recrow['task_unit'] . "</td>";
		echo "<td>" . $recrow['task_price'] . "</td>";
		echo "<td>" . $subtotal . "元</td>";
		echo "</tr>";
		$total = $total + $subtotal;
	}
	return $total;
}

function print_table_foot($total){
	echo "<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>" . $total . "</td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="new">
	<a href='task_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "task/task_view.php"; ?>" method="post">
<?php
//查询的时间条件，起始时间为年初，结束时间为当日
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
