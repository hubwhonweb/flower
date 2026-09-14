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
	$staff_name = $_POST['staff_name'];
	$startd = $_POST['startdate']; 
	$endd = $_POST['enddate'];
	print_title($_POST['startdate'],$_POST['enddate'],$_POST['staff_name']);
}
else{
	$staff_name = "99999";
	$startd = date('Y-m-d',monthFirstDate(time())); 
	$endd = date('Y-m-d',time());
	print_title($startd,$endd,$staff_name);
}

print_table_head();
$total = print_table_body($staff_name,$startd,$endd);
print_table_foot($total);

echo "</div>";

function print_title($startd,$endd,$staff_name){
	echo "<span style='font-size:25px'>查询条件：";
	echo "姓名：" . $staff_name;
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
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
	<th>工作内容</th>
	<th>合计</th>
	</tr>";
}

function print_table_body($staffName,$startD,$endD){
	$total = 0;
	$startD = strtotime($startD);
	$endD = strtotime($endD);
	while( $startD <= $endD ){
		$subtotal = print_body($staffName,$startD);
		$startD = $startD + 24*60*60;
		$total = $total + $subtotal;
	}
	return $total;
}

function print_body($staffName,$startD){
	$startD = date('Y-m-d',$startD);
	$sTotal =0;
	$sBody = "";
	$sql = "SELECT * FROM task_log 
		 WHERE task_date = '" . $startD . "' 
		 AND staff_name = '" . $staffName . "';";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0) {
		$sBody =  "空";	 
	}
	else{
	    while( $recrow = mysqli_fetch_assoc($result) ){
	    	$sBody = $sBody . $recrow['task_name'].
	    				$recrow['task_count']."*".$recrow['task_price'].",";
	    	$sTotal = $sTotal + $recrow['task_count'] * $recrow['task_price']; 
	    }
	}
	echo "<tr>";
	echo "<td>".$startD."</td>";
	echo "<td>".$sBody."</td>";
	echo "<td>".$sTotal."元</td>";
	echo "</tr>";

	return $sTotal;
}

function print_table_foot($total){
	echo "<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>" . $total . "</td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "task/task_month.php"; ?>" method="post">

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