<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report1.php") == FALSE){
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
	$qdate = time() - 180*24*60*60;  //缺省6个月查询
}

//打印表头
print_table_head();
print_real($qdate);
//结束块
echo "</table>";
echo "</div>";

function print_real($qdate){
	$today = time();
	while( ($qdate+5*24*60*60) <= $today ){
		$dateStart = date('Y-m-d',$qdate);
		$dateFinish = date('Y-m-d',$qdate+5*24*60*60);
		$sql = "select sum(plants) as pots,sum(subtotal) as total from orders
		where date<'" .$dateFinish. "' and date>='".$dateStart."';";
		$result = WHDBmysql_query($sql);
		$record =  mysqli_fetch_array($result); 
		
		if($record['pots']!=0){
		echo "<tr>";		
		echo "<td>" .$dateFinish . "</td>";
		echo "<td>" .$record['pots'] . "</td>";
        echo "<td>" .numberToMoney($record['total']/$record['pots']) . "</td>";
		echo "</tr>";
		}
		$qdate = $qdate + 5*24*60*60;
	}

}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>盆数</th>
    <th>均价</th>
	</tr>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "report/report1.php"; ?>" method="post">
	
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
