<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("order/order_report1.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>销售情况</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	$qdate = strtotime($_POST['querydate']);
}
else{
	$qdate = time();
}

//打印表头
print_table_head();
print_real($qdate);
//结束块
echo "</table>";
echo "</div>";

function print_real($qdate){
	
	$queryDateStart = monthFirstDate($qdate);
	$queryDateEnd = monthFinalDate($qdate);
	$tbox = 0;
	$tpot = 0;
	while( $queryDateStart <= $queryDateEnd ){
		$queryDate = date('Y-m-d',$queryDateStart);
		$sql = "select sum(boxes)as box,sum(plants) as pots,sum(subtotal) as total from orders
		where date='" .$queryDate. "';";
		$result = WHDBmysql_query($sql);
		$record =  mysqli_fetch_array($result); 
		if($record['box']!=0){
		$tbox = $tbox + $record['box'];
		$tpot = $tpot + $record['pots'];
        $ttotal = $ttotal + $record['total'];
		echo "<tr>";		
		echo "<td>" .$queryDate . "</td>";
		echo "<td>" .$record['box'] . "</td>";
		echo "<td>" .$record['pots'] . "</td>";
        echo "<td>" .numberToMoney($record['total']) . "</td>";
        echo "<td>" .numberToMoney($record['total']/$record['pots']) . "</td>";
		echo "</tr>";
		}
		$queryDateStart = $queryDateStart+24*60*60;
	}
	echo "<tr>";		
		echo "<td>本月共销售</td>";
		echo "<td>" .$tbox . "箱</td>";
		echo "<td>" .$tpot . "盆</td>";
        echo "<td>" .numberToMoney($ttotal) . "元</td>";
	    echo "<td>" .numberToMoney($ttotal/$tpot) . "元</td>";
        echo "</tr>";
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>箱数</th>
	<th>盆数</th>
    <th>金额</th>
    <th>均价</th>
	</tr>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "sale/report1.php"; ?>" method="post">
	
<?php 
echo "<input type='date' name='querydate' value='" . date('Y-m-d',time()) . "'/>"; 
?>

<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>


<?php
require("menu.php");
require("../public/footer.php");
?>
