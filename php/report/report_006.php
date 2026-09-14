<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_006.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");



if(isset($_POST['submit'])){
	$queryWeek = $_POST['week'];
}
else{
	$queryWeek = getYearAndWeeks(time())['week'];
}
echo "<div id='title'>第".$queryWeek."周关键数据同期对比</div>";
echo "<div id='right'>";


//打印表头
print_table_head();
print_4_years($queryWeek);
//结束块
echo "</table>";
echo "</div>";


function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>年份</th>
	<th>扦插盆数</th>
	<th>销售盆数</th>
	<th>销售金额</th>
	<th>平均价额</th>
	</tr>";
}

function print_4_years($queryWeek){
   $thisYear = date('Y',time());
   for($i = 0; $i < 4; $i++ ){
	  echo "<tr>";
      print_year_week($thisYear,$queryWeek);
	  $thisYear = $thisYear - 1;
	  echo "</tr>";
   }
}

function print_year_week($thisYear,$queryWeek){
	
    $startTime = getYearWeeksMonday($thisYear,$queryWeek);
	$endTime = $startTime + 7*24*60*60;
    $startDate = date('Y-m-d',$startTime);
	$endDate = date('Y-m-d',$endTime);
	
    $sql = "select sum(batch_pots) as pots from batch 
            where batch_date>='" .$startDate . "' 
            AND batch_date<'" .$endDate . "';";
    $result = WHDBmysql_query($sql);
	$plantRecord =  mysqli_fetch_array($result); 
	$sql = "select sum(plants) as plants,sum(subtotal) as subtotal from orders 
            where date>='" .$startDate . "' 
            AND date<'" .$endDate . "';";
    $result = WHDBmysql_query($sql);
	$saleRecord =  mysqli_fetch_array($result); 
	echo "<th>".$startDate."-".$endDate."</th>";
    echo "<th>".$plantRecord['pots']."</th>";
	echo "<th>".$saleRecord['plants']."</th>";
	echo "<th>".numberToMoney($saleRecord['subtotal'])."</th>";
	echo "<th>".numberToMoney($saleRecord['subtotal']/$saleRecord['plants'])."</th>";

}

?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "report/report_006.php"; ?>" method="post">
选择第几周：
<?php
echo "<select name='week'>";
for( $i=1; $i<=56;$i++){
    echo "<option value='" . $i . "'>" .$i. "</option>";
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