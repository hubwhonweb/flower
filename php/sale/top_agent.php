<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/top_agent.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>TOP AGENT</div>";
echo "<div id='right'>";
print_table_head();
print_table_body($month_auth);
print_table_foot();
echo "</div>";
?>

<?php
require("menu.php");
require("../public/footer.php");
?>

<?php
function print_table_head(){
    //计算当前月的第一天
    $this_month_first_date = monthFirstDate(time());
    //计算12个月前的那个月的第一天
    for ($i=0; $i<11; $i++){
        $this_month_first_date = $this_month_first_date - 2*24*60*60;
        $this_month_first_date = monthFirstDate($this_month_first_date);
    }
    $start_date = $this_month_first_date;
    
    echo "<table class='hovertable'>";
    echo "<tr>";
    echo "<th>姓名</th>";
    for($i=0;$i<12;$i++){
        echo "<th>".date('m',$start_date)."</th>";
        $start_date = getNextMonthFirstDate($start_date);
    }
    echo "<th>比例</th>";
    echo "</tr>";
    }

function print_table_foot(){
        echo "</table>";
    }
    
function print_table_body($month_auth){
    
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
    
    //查询过去一年销售过产品的经销商和该经销商销售的盆数，并按盆数多少排序
    $start = date('Y-m-d',$this_month_first_date);
    $end = date('Y-m-d',time());
    $sql = "select agent_code,sum(plants) as plants from orders
      where date >='".$start."' and date <='".$end."'
      group by agent_code order by plants DESC limit 30;";
    $res = WHDBmysql_query($sql);
    //逐个经销商处理
    while($rec = mysqli_fetch_array($res)){
        echo "<tr>";
        //echo "<td>" ."<a href='top_agent_view.php?id=" . $rec['agent_code'] . "'>".getAgentName($rec['agent_code'])."</td>";
        echo "<td>".getAgentName($rec['agent_code'])."</td>";
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
            $plant_a = getAgentSalePlants($rec['agent_code'],$start_date,$end_date);
            $time_a = getAgentSaleTimes($rec['agent_code'],$start_date,$end_date);
            //计算本月百分数
            if( $plant_s == 0 ){ $plant_s = 1;}
            if( $time_s == 0 ){ $time_s = 1;}
            $perc = $plant_a/$plant_s + $time_a/$time_s;
            $perc = $perc/2;
            //计算本月权重,并将本月百分百乘以权重
            $q = date('m',$start_date);
            $au = $month_auth[(int)$q-1];
            $perc = $perc * $au;
            //12个月的百分数之和
            $percentage = $percentage + $perc;
            //显示每月百分数
            //echo "<td>".toPercentage($perc)."</d>";
            //显示每月销售盆数和次数
            echo "<td>".$plant_a."/".$time_a."</d>";
            //计算下一个月的开始日期
            $start_date = getNextMonthFirstDate($start_date);
        }
        //计算最后的分货比例。算法：将过去12个月的比例相加，除以权重之和。如果小于1%，则按0处理。
        $percentage = $percentage/$sum_auth;
        if( $percentage < 0.01 ){
            $percentage = 0;
        }
        echo "<td>".toPercentage($percentage)."</td>";
        echo "</tr>";
    }
}
?>
