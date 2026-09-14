<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("order/order_report2.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>销售情况</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	$sdate = strtotime($_POST['startdate']);
	$edate = strtotime($_POST['enddate']);
}
else{
	$edate = time();
	$sdate =monthFirstDate(time());
}

//打印表头
print_table_head();
print_real($sdate,$edate);
//结束块
echo "</table>";
echo "</div>";

function print_real($sdate,$edate){
	$Apot = 0;
	$Bpot = 0;
	$Cpot = 0;
	$Tpot = 0;
	$queryDateStart = date('Y-m-d',$sdate);
	$queryDateEnd = date('Y-m-d',$edate);
	
	$sql = "select sum(plants) as pots from orders 
		where class = 'A级' and date >='" .$queryDateStart. "' 
		and date <='" .$queryDateEnd."';";
	$result = WHDBmysql_query($sql);
	$record =  mysqli_fetch_array($result); 
	$Apot = $record['pots'];
	
	$sql = "select sum(plants) as pots from orders 
		where class = 'B级' and date >='" .$queryDateStart. "' 
		and date <='" .$queryDateEnd."';";
	$result = WHDBmysql_query($sql);
	$record =  mysqli_fetch_array($result); 
	$Bpot = $record['pots'];
	
	$sql = "select sum(plants) as pots from orders 
		where class = 'C级' and date >='" .$queryDateStart. "' 
		and date <='" .$queryDateEnd."';";
	$result = WHDBmysql_query($sql);
	$record =  mysqli_fetch_array($result); 
	$Cpot = $record['pots'];
	
	$Tpot = $Apot+$Bpot+$Cpot;
	
	echo $queryDateStart." to ".$queryDateEnd;
	
	echo "<tr>";		
	echo "<td>" .$Apot . "(". number_format(($Apot/$Tpot)*100,2) ."%)</td>";
	echo "<td>" .$Bpot . "(". number_format(($Bpot/$Tpot)*100,2) ."%)</td>";
	echo "<td>" .$Cpot . "(". number_format(($Cpot/$Tpot)*100,2) ."%)</td>"; 
	echo "<td>" .$Tpot ."</td>";
	echo "</tr>";
	
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>A级盆数占比</th>
	<th>B级盆数占比</th>
	<th>C级盆数占比</th>
	<th>销售总数</th>
	</tr>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/report2.php"; ?>" method="post">
	
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
