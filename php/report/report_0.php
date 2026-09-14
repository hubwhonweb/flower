<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_000.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>月报表</div>";
echo "<div id='right'>";

//根据提交搜索条件生成搜索时间
if(isset($_POST['submit'])){
	$queryYear = $_POST['year'];
	//$queryMonth = $_POST['month'];
}
else{
	$queryYear = date('Y',monthFirstDate(time()));
	//$queryMonth = date('m',monthFirstDate(time()));
}
$queryStartDate = date('Y-m-d',strtotime($queryYear."-1-1"));
$queryEndDate = date('Y-m-d',strtotime($queryYear."-12-31"));
//打印名称
echo "<br/>";
print_search($queryYear);
//打印成本支出表
echo "<br/>";
print_cost($queryStartDate,$queryEndDate);
echo "</div>";

?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "report/report0.php"; ?>" method="post">
查询：
选择年份：
<?php
echo "<select name='year'>";
foreach ($YEARS as $li) {
	if(date('Y',monthFirstDate(time()))==$li){
		echo "<option value='" . $li . "' selected>" . $li . "</option>";
	}
	else{
		echo "<option value='" . $li . "'>" . $li . "</option>";
	}
	
}
echo "</select>";
?>


<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>

<?php
function print_search($sy){
	echo "<span>".$sy."年成本</span><br/>";
}


function print_cost($queryStartDate,$queryEndDate){
	require("../public/config.php");
	//print title
	echo "<span>成本支出情况</span><br/>";
	//print table head
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>支出分类</th>";
	echo "<th>支出科目</th>";
	echo "<th>支出金额</th>";
	echo "</tr>";
	//print table body
	$total = 0;//cost total counter
	//print 生产成本，包括生产成本、财务成本、管理成本
	//$sql = "select * from cost_category 
	//where category='生产成本' or category='管理成本' or category='财务成本';";
	$sql = "select * from cost_category 
	where active='YES' and category='生产成本';";
	$result = WHDBmysql_query($sql);
	$subtotal = 0;//cost category total counter
	while ($record = mysqli_fetch_array($result)) {
		$sql = "select sum(amount) as amount from cost 
		WHERE date <='".$queryEndDate."' AND date >='".$queryStartDate . "' 
		AND name='".$record['name']."';";
		$result1 = WHDBmysql_query($sql);
		$record1 = mysqli_fetch_array($result1);
		echo "<tr>";
		echo "<td>生产成本</td>";
		echo "<td>".$record['name']."</td>";
		if( $record1['amount']==NULL or $record1['amount']==0){
			$p = 0;
		}
		else{
			$p = round($record1['amount'],2);
		}
		$subtotal = $subtotal + $p;
		echo "<td>".$p."</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<th>".$cName."</th>";
	echo "<th>合计</th>";
	echo "<th>".round($subtotal,2)."</th>";
	echo "</tr>";
	$total = $total + $subtotal;
	//print 固定资产
	$sql = "select * from cost_category where active='YES' and category='固定资产';";
	$result = WHDBmysql_query($sql);
	$subtotal = 0;//cost category total counter
	while ($record = mysqli_fetch_array($result)) {
		$sql = "select sum(amount) as amount from cost 
		WHERE date <='".$queryEndDate."' AND date >='".$queryStartDate . "' AND name='".$record['name']."';";
		$result1 = WHDBmysql_query($sql);
		$record1 = mysqli_fetch_array($result1);
		echo "<tr>";
		echo "<td>固定资产</td>";
		echo "<td>".$record['name']."</td>";
		if( $record1['amount']==NULL or $record1['amount']==0){
			$p = 0;
		}
		else{
			$p = round($record1['amount'],2);
		}
		$subtotal = $subtotal + $p;
		echo "<td>".$p."</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<th>".$cName."</th>";
	echo "<th>合计</th>";
	echo "<th>".round($subtotal,2)."</th>";
	echo "</tr>";
	$total = $total + $subtotal;
	//print 其他成本
	$sql = "select * from cost_category where active='YES' and category='其他成本';";
	$result = WHDBmysql_query($sql);
	$subtotal = 0;//cost category total counter
	while ($record = mysqli_fetch_array($result)) {
		$sql = "select sum(amount) as amount from cost 
		WHERE date <='".$queryEndDate."' AND date >='".$queryStartDate . "' AND name='".$record['name']."';";
		$result1 = WHDBmysql_query($sql);
		$record1 = mysqli_fetch_array($result1);
		echo "<tr>";
		echo "<td>".$cName."</td>";
		echo "<td>".$record['name']."</td>";
		if( $record1['amount']==NULL or $record1['amount']==0){
			$p = 0;
		}
		else{
			$p = round($record1['amount'],2);
		}
		$subtotal = $subtotal + $p;
		echo "<td>".$p."</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<th>".$cName."</th>";
	echo "<th>合计</th>";
	echo "<th>".round($subtotal,2)."</th>";
	echo "</tr>";
	$total = $total + $subtotal;
	
	//print table foot
	echo "<tr>";
	echo "<th>合计</th>";
	echo "<th></th>";
	echo "<th>".round($total,2)."</th>";
	echo "</tr>";
	echo "</table>";
}
?>
