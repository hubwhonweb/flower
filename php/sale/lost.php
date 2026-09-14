<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/lost.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>报损补货查询</div>";
echo "<div id='right'>";
	
if(isset($_POST['submit'])){
	$agent_code = $_POST['agent_code'];
	$startd = $_POST['startdate']; 
	$endd = $_POST['enddate'];
	$sql = make_sql($agent_code,$startd,$endd);	
 	$result = WHDBmysql_query($sql);
 	$numrow = mysqli_num_rows($result);
	print_title($_POST['startdate'],$_POST['enddate'],$_POST['agent_code']);
	if($numrow == 0) {
 		echo "没有记录";	 
 	}
	else{
		print_table_head();
		$total = print_table_body($result);
		print_table_foot($total);
	}
 }
 else{
	$agent_code = "99999";
	$startd = date('Y-m-d',monthFirstDate(time())); 
	$endd = date('Y-m-d',time());
	$sql = make_sql($agent_code,$startd,$endd);
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	print_title($startd,$endd,$agent_code);
	if($numrow == 0) {
		echo "没有记录";	 
	}
	else{
		print_table_head();
		$total = print_table_body($result);
		print_table_foot($total);
	}
}
echo "</div>";
mysqli_free_result($result);

function print_title($startd,$endd,$agent_code){
	echo "<span style='font-size:25px'>查询条件：";
	echo "经销商：" . getAgentName($agent_code);
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
}

function make_sql($agent_code,$startd,$endd){
	if( $agent_code == "99999" ){
		$sqlmid = "";
	} 
	else{
		$sqlmid = " AND agent_code = " . $agent_code;
	}
	$sql = "SELECT * FROM lost 
	WHERE flag='已复核' AND date >= '" . $startd . "' 
	AND date <= '" . $endd . "'" . $sqlmid . " ORDER BY date DESC;";
	return $sql;
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>产品</th>
	<th>经销商</th>
	<th>金额</th>
	<th>备注</th>
	<th>状态</th>
	</tr>";
}

function print_table_body($result){
	$total = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . $recrow['date'] . "</td>";
		echo "<td>" . getProductName($recrow['product_code']) . "</td>";
		echo "<td>" . getAgentName($recrow['agent_code']) . "</td>";
		echo "<td>" . $recrow['amount'] . "</td>";
		echo "<td>" . $recrow['comm'] . "</td>";
		echo "<td>" . $recrow['flag'] . "</td>";
		echo "</tr>";
		$total = $total + $recrow['amount'];
	}
	return $total;
}
function print_table_foot($total){
	echo "<tr>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>合计</td>";
	echo "<td>" . $total . "</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="new">
	<a href='lost_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/lost.php"; ?>" method="post">
<select name="agent_code">
<?php
   $catsql = "SELECT * FROM agent where active='YES' order by order_date DESC;";
   $catres = WHDBmysql_query($catsql);
   echo "<option value='99999' selected>'所有经销商'</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
    }
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
