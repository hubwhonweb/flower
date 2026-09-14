<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_view.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(checkUrlOrderID()==FALSE){
	header("Location: " . $BASE_DIR . "sale/menu.php");
}
else{
	$id = $_GET['id'];
}
require("../public/header.php");
echo "<div id='title'>订单</div>";
echo "<div id='right'>";

//合法的ID，从数据库中查询这个id的订单信息
$sql = "SELECT * FROM orders WHERE order_id='" . $id . "'";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);

if($numrow == 0) {
 	echo "没有记录";
}
else{
	//获取第一条记录
	$recrow = mysqli_fetch_assoc($result);
    $agent_code = $recrow['agent_code'];
	//获取这个订单号的总体信息，并打印
	$sql = "select sum(boxes) as boxes from orders where order_id='" . $id . "'";
	$sumres = WHDBmysql_query($sql);
	$sumrec = mysqli_fetch_array($sumres);
	echo "<table>";
	echo "<tr><td>订单号:</td><td>" . $id ."</td></tr>";
	echo "<tr><td>发货日期：</td><td>" . $recrow['date'] . "</td></tr>";
	echo "<tr><td>经销商名称：</td><td>" . getAgentName($agent_code) . "</td></tr>";
	echo "</table>";

	//打印第一条信息
	$order_total = 0;
	$order_box = 0;
	//echo "<table class='hovertable'>";
	echo "<table>";
	echo "<tr>";
	echo "<th>产品------</th>";
	echo "<th>等级------</th>";
	echo "<th>箱数------</th>";
	echo "<th>盆数------</th>";
	echo "<th>单价------</th>";
	echo "<th>金额------</th>";
	echo "<th>备注------</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>" .getProductName($recrow['product_code']) ."</td>";
	echo "<td>" .$recrow['class'] ."</td>";
	echo "<td>" .$recrow['boxes'] . "箱</td>";
	echo "<td>" .$recrow['plants'] . "盆</td>";
	echo "<td>" .$recrow['price'] . "元</td>";
	echo "<td>" .$recrow['subtotal'] . "元</td>";
	echo "<td>" .$recrow['comment'] . "</td>";
	echo "</tr>";
	$order_total = $recrow['subtotal'];
	$order_box = $recrow['boxes'];
	//打印该订单后续信息
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" .getProductName($recrow['product_code']) ."</td>";
		echo "<td>" .$recrow['class'] ."</td>";
		echo "<td>" .$recrow['boxes'] . "箱</td>";
		echo "<td>" .$recrow['plants'] . "盆</td>";
		echo "<td>" .$recrow['price'] . "元</td>";
		echo "<td>" .$recrow['subtotal'] . "元</td>";
		echo "<td>" .$recrow['comment'] . "</td>";
		echo "</tr>";
		$order_total = $order_total + $recrow['subtotal'];
		$order_box = $order_box + $recrow['boxes'];
		}
	echo "</table>";
	}
	echo "<br><span style='color: red; font-size:20px;'>订单总箱数：". $order_box ."</span>";
	echo "<br><span style='color: red; font-size:20px;'>订单总金额：". $order_total ."</span>";
    echo "<br><span style='color: red; font-size:20px;'>贡献度：". getAgentC($agent_code,$month_auth) ."</span>";
    
echo "</div>";
?>

<?php
require("menu.php");
require("../public/footer.php");
    
function getAgentC($agent_code,$month_auth){
        //计算所有权重总和
        $sum_auth=0;
        for ($i=0; $i < count($month_auth); $i++) {
            $sum_auth+=$month_auth[$i];
        }
        //计算当前月的第一天
        $this_month_first_date = monthFirstDate(time());
        //计算12个月前的那个月的第一天
        for ($i=0; $i<11; $i++){
            $this_month_first_date = $this_month_first_date - 2*24*60*60;
            $this_month_first_date = monthFirstDate($this_month_first_date);
        }
        //设定开始计算日期
        $start = $this_month_first_date;
        //每月百分数总和初始化为零
            $percentage = 0;
    //处理12个月，每个月的比例。算法：（当月该客户销售的盆数/当月的总盆数+当月该客户销售的次数/当月的总次数)/2*当月权重
        $start_date = $start;
  
    for($i=0;$i<12;$i++){
                $end_date = monthFinalDate($start_date);
                //取得一个月的销售盆数、次数
                $plant_s = getSalePlants($start_date,$end_date);
                $time_s = getSaleTimes($start_date,$end_date);
                $plant_a = getAgentSalePlants($agent_code,$start_date,$end_date);
                $time_a = getAgentSaleTimes($agent_code,$start_date,$end_date);
                //计算本月百分数
                if( $plant_s == 0 ) { $plant_s = 1;}
                if( $time_s == 0 ) { $time_s = 1;}
                $perc = $plant_a/$plant_s +$time_a/$time_s;
                $perc = $perc/2;
        
                //计算本月权重,并将本月百分百乘以权重
        
        $q = date('m',$start_date);
                $au = $month_auth[(int)$q-1];
                $perc = $perc * $au;
        //      echo $perc."/".$au."*";
    
         //计算百分数之和
                $percentage = $percentage + $perc;
                //计算下一个月的开始日期
                $start_date = getNextMonthFirstDate($start_date);
            }
        //计算最后的分货比例。算法：将过去12个月的比例相加，除以权重之和。如果小于1%，则按0处理。
        $percentage = $percentage/$sum_auth;
        if( $percentage < 0.0001 ){
                $percentage = 0;
        }
        return toPercentage($percentage);
}

?>
