<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("task/task_staff.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询员工工作信息</div>";
echo "<div id='right'>";
	
if(isset($_POST['submit'])){
	$sql = make_sql($_POST['staff_name'],$_POST['startdate'],$_POST['enddate']);
	print_title($_POST['startdate'],$_POST['enddate'],$_POST['staff_name']);
}
 else{
	$staff_name = "99999";
	$startd = date('Y-m-d',monthFirstDate(time())); 
	$endd = date('Y-m-d',time());
	$sql = make_sql($staff_name,$startd,$endd);
	print_title($startd,$endd,$staff_name);
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

function print_title($startd,$endd,$staff_name){
	echo "<span style='font-size:25px'>查询条件：";
	echo "姓名：" . $staff_name;
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
	mysqli_free_result($result);
}

function make_sql($staff_name,$startd,$endd){
	if( $staff_name == "99999" ){
		$sqlmid = "";
	} 
	else{
		$sqlmid = " AND staff_name = '" . $staff_name."'";
	}
	$sql = "SELECT * FROM task_log 
		 WHERE task_date >= '" . $startd . "' 
		 AND task_date <= '" . $endd . "'" .$sqlmid .
		" ORDER BY task_date DESC;";
	return $sql;
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>姓名</th>
	<th>工作单元</th>
	<th>数量</th>
	<th>单价</th>
	<th>合计</th>
	<th>备注</th>
	<th>修改</th>
	<th>复核</th>
	</tr>";
}

function print_table_body($result){
	$total[0] = 0;
	$total[1] = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		$subtotal = round($recrow['task_price']*$recrow['task_count'],2);
		echo "<tr>";
		echo "<td>" . $recrow['task_date'] . "</td>";
		echo "<td>" . $recrow['staff_name'] . "</td>";
		echo "<td>" . $recrow['task_name'] . "</td>";
		echo "<td>" . $recrow['task_count'].$recrow['task_unit'] . "</td>";
		echo "<td>" . $recrow['task_price'] . "</td>";
		echo "<td>" . $subtotal . "</td>";
		echo "<td>" . $recrow['comm'] . "</td>";
		if( $recrow['flag']=='待复核' ){
			echo "<td>" . "<a href='task_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>";
			echo "<td>" . "<a href='task_check.php?id=" . $recrow['id'] . "'>复核" ."</td>";
		}
		else{
		    echo "<td></td>";
		    echo "<td></td>";
		}
		echo "</tr>";
		$total[0] = $total[0] + $recrow['task_count'];
		$total[1] = $total[1] + $subtotal;
	}
	return $total;
}
function print_table_foot($total){
	echo "<tr>";
	echo "<td></td>";
	echo "<td>合计</td>";
	echo "<td></td>";
	echo "<td>" . $total[0] . "</td>";
	echo "<td></td>";
	echo "<td>" . $total[1] . "</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="new">
	<a href='task_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "task/task_staff.php"; ?>" method="post">

<select name="staff_name">
<?php
   $catsql = "SELECT * FROM staff where active='YES';";
   $catres = WHDBmysql_query($catsql);
   echo "<option value='99999' selected>'所有员工'</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['name'] . "'>" . $catrow['name'] ."</option>";
    }
	mysqli_free_result($catres);
?>
</select>
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