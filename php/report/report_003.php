<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
$productCode = "0001";

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_003.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>10公分玫瑰生产量</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	$queryYear = $_POST['year'];
}
else{
	$queryYear = date('Y',time());
}
echo "查询：" .$queryYear . "年10公分玫瑰生产量<br>";
//计算查询年份的每月1日时间，及下一年度的一月一日
$queryDate[0] = date('Y-m-d',strtotime($queryYear."-01-01"));
$queryDate[1] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[0])));
$queryDate[2] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[1])));
$queryDate[3] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[2])));
$queryDate[4] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[3])));
$queryDate[5] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[4])));
$queryDate[6] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[5])));
$queryDate[7] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[6])));
$queryDate[8] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[7])));
$queryDate[9] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[8])));
$queryDate[10] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[9])));
$queryDate[11] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[10])));
$queryDate[12] = date('Y-m-d',getNextMonthFirstDate(strtotime($queryDate[11])));
//打印表头
print_table_head();
//查询批次表中玫瑰种类
/*
$sql = "select distinct(variety_code) as variety_code from batch 
where product_code='".$productCode."'
AND batch_date>='" .$queryDate[0] . "' 
AND batch_date<'" .$queryDate[12] . "' order by variety_code;";
*/
$sql = "select distinct(variety_code) as variety_code from batch 
where batch_date>='" .$queryDate[0] . "' 
AND batch_date<'" .$queryDate[12] . "' order by variety_code;";
$result = WHDBmysql_query($sql);
//计算并打印每一个品种每个月的扦插数量
while ($record = mysqli_fetch_array($result)) {
	print_stick_variety($record['variety_code'],$queryDate);
}
//计算并打印最后合计
print_table_end($queryDate,$productCode);
//结束块
echo "</div>";

function print_stick_variety($variety,$queryDate){
	echo "<tr>";
	echo "<td>10公分玫瑰</td>"; 
	echo "<td>" .$variety . "</td>"; 
	for( $i = 0; $i < 12; $i++ ){
		$sql = "select sum(batch_pots) as pots from batch 
		where variety_code='" . $variety ."' 
		AND batch_date>='" .$queryDate[$i] . "' 
		AND batch_date<'" .$queryDate[$i+1] . "';";
		$result = WHDBmysql_query($sql);
		$record =  mysqli_fetch_array($result);  
		echo "<td>" .$record['pots'] . "</td>"; 
	}
	echo "</tr>";
}
function print_table_end($queryDate,$productCode){
	echo "<tr>";
	echo "<td></td>"; 
	echo "<td></td>"; 
	for( $i = 0; $i < 12; $i++ ){
		/*
		$sql = "select sum(batch_pots) as pots from batch 
		where product_code='".$productCode."' 
		AND batch_date>='" .$queryDate[$i] . "' 
		AND batch_date<'" .$queryDate[$i+1] . "';";
		*/
		$sql = "select sum(batch_pots) as pots from batch 
		where batch_date>='" .$queryDate[$i] . "' 
		AND batch_date<'" .$queryDate[$i+1] . "';";
		$result = WHDBmysql_query($sql);
		$record =  mysqli_fetch_array($result);  
		echo "<td>" .$record['pots'] . "</td>"; 
	}
	echo "</tr>";
	echo "</table>";

}
function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>盆径</th>
	<th>品种</th>
	<th>1月</th>
	<th>2月</th>
	<th>3月</th>
	<th>4月</th>
	<th>5月</th>
	<th>6月</th>
	<th>7月</th>
	<th>8月</th>
	<th>9月</th>
	<th>10月</th>
	<th>11月</th>
	<th>12月</th>
	</tr>";
}

?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "report/report_003.php"; ?>" method="post">
选择年份：
<?php
echo "<select name='year'>";
foreach ($YEARS as $li) {
	echo "<option value='" . $li . "'>" . $li . "</option>";
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