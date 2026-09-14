<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_009.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>关键数据报表</div>";
echo "<div id='right'>";

//根据提交搜索条件生成搜索时间
if(isset($_POST['submit'])){
	$queryStartDate = $_POST['startdate'];
	$queryEndDate = $_POST['enddate'];
}
else{
	$queryStartDate = date('Y-m-d',monthFirstDate(time()));
	$queryEndDate = date('Y-m-d',time());
}
//打印名称
echo "<br/>";
print_search($queryStartDate,$queryEndDate);
//打印生产情况表
echo "<br/>";
print_body($queryStartDate,$queryEndDate);
echo "</div>";
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "report/report_009.php"; ?>" method="post">
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

function print_search($sy,$sm){
	echo "<span>".$sy."---".$sm."关键数据查询</span><br/>";
}

function print_body($queryStartDate,$queryEndDate){
	$stick = get_all_stick($queryStartDate,$queryEndDate);
    echo "扦插总量：".$stick;
    $stick_diruite = get_diruite_stick($queryStartDate,$queryEndDate);
    echo "<br>迪瑞特扦插量：".$stick_diruite;
    echo "<br>占比：".toPercentage($stick_diruite/$stick);
    $stick_kedesi = get_kedesi_stick($queryStartDate,$queryEndDate);
    echo "<br>科德斯扦插量：".$stick_kedesi;
    echo "<br>占比：".toPercentage($stick_kedesi/$stick);
    
    print_plant_lose($queryStartDate,$queryEndDate);
    print_house_in($queryStartDate,$queryEndDate);
    print_sales_rose10($queryStartDate,$queryEndDate);
    print_house_out($queryStartDate,$queryEndDate);
	
}
function print_house_out($queryStartDate,$queryEndDate){
        //all
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $out_total = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE out_class = 'A级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $out_a = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE out_class = 'B级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $out_b = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'A温室' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $a_out = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'A温室' AND out_class = 'A级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $a_out_a = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'A温室' AND out_class = 'B级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $a_out_b = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'B温室' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $b_out = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'B温室' AND out_class = 'A级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $b_out_a = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'B温室' AND out_class = 'B级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $b_out_b = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'C温室' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $c_out = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'C温室' AND out_class = 'A级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $c_out_a = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'C温室' AND out_class = 'B级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $c_out_b = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'E温室' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $e_out = $rec['pots'];
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'E温室' AND out_class = 'A级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $e_out_a = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_out
        WHERE house_name = 'E温室' AND out_class = 'B级' AND out_date <='".$queryEndDate."' AND out_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $e_out_b = $rec['pots'];
        
        echo "出货数量: ".$out_total."盆";
        echo "</br>-----A级: ".$out_a."盆;占比：".toPercentage($out_a/$out_total);
        echo "</br>-----B级: ".$out_b."盆;占比：".toPercentage($out_b/$out_total);
        echo "</br></br>--E温室: ".$e_out."盆";
        echo "</br>-----A级: ".$e_out_a."盆;---占比：".toPercentage($e_out_a/$e_out);
        echo "</br>-----B级: ".$e_out_b."盆;---占比：".toPercentage($e_out_b/$e_out);
        echo "</br></br>--A温室: ".$e_out."盆";
        echo "</br>-----A级: ".$a_out_a."盆;---占比：".toPercentage($a_out_a/$a_out);
        echo "</br>-----B级: ".$a_out_b."盆;---占比：".toPercentage($a_out_b/$a_out);
        echo "</br></br>--B温室: ".$e_out."盆";
        echo "</br>-----A级: --".$c_out_a."盆;---占比：".toPercentage($b_out_a/$b_out);
        echo "</br>-----B级: --".$c_out_b."盆;---占比：".toPercentage($b_out_b/$b_out);
        echo "</br></br>--C温室: --".$e_out."盆";
        echo "</br>-----A级: --".$c_out_a."盆;---占比：".toPercentage($c_out_a/$c_out);
        echo "</br>-----B级: --".$c_out_b."盆;---占比：".toPercentage($c_out_b/$c_out);
        
        echo "</br></br>";
    }
    function print_house_in($queryStartDate,$queryEndDate){
        //all house
        $sql = "SELECT SUM(pot) as pots FROM house_in
        WHERE in_date <='".$queryEndDate."' AND in_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $in_total = $rec['pots'];
        //house_A
        $sql = "SELECT SUM(pot) as pots FROM house_in
        WHERE house_name = 'A温室' AND in_date <='".$queryEndDate."' AND in_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $in_a = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_in
        WHERE house_name = 'B温室' AND in_date <='".$queryEndDate."' AND in_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $in_b = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_in
        WHERE house_name = 'C温室' AND in_date <='".$queryEndDate."' AND in_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $in_c = $rec['pots'];
        
        $sql = "SELECT SUM(pot) as pots FROM house_in
        WHERE house_name = 'E温室' AND in_date <='".$queryEndDate."' AND in_date >='".$queryStartDate . "';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $in_e = $rec['pots'];
        
        echo "共移苗数量: --".$in_total."盆;";
        echo "</br>-----E温室数量: --".$in_e."盆;";
        echo "</br>-----A温室数量: --".$in_a."盆;";
        echo "</br>-----B温室数量: --".$in_b."盆;";
        echo "</br>-----C温室数量: --".$in_c."盆;";
        echo "</br></br>";
    }

function get_all_stick($queryStartDate,$queryEndDate){
	$sql = "SELECT SUM(batch_pots) as pots FROM batch 
		WHERE batch_date <='".$queryEndDate."' AND batch_date >='".$queryStartDate . "';";
	$res = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($res);
    return $rec['pots'];
}
function get_diruite_stick($queryStartDate,$queryEndDate){
   $i = 0;
    $sql = "select * from variety where supplier = '迪瑞特';";
    $res = WHDBmysql_query($sql);
    while ( $rec = mysqli_fetch_array($res) ){
    
       $sql = "SELECT SUM(batch_pots) as pots FROM batch WHERE variety_code = '".$rec['variety_code'].
       "' AND batch_date <='".$queryEndDate.
       "' AND batch_date >='".$queryStartDate. "';";
       $ress = WHDBmysql_query($sql);
       $recc = mysqli_fetch_array($ress);
       $i = $i + $recc['pots'];
    }
    return $i;
}
function get_kedesi_stick($queryStartDate,$queryEndDate){
   $i = 0;
    $sql = "select * from variety where supplier = '科德纳';";
    $res = WHDBmysql_query($sql);
    while ( $rec = mysqli_fetch_array($res) ){
    
       $sql = "SELECT SUM(batch_pots) as pots FROM batch WHERE variety_code = '".$rec['variety_code'].
       "' AND batch_date <='".$queryEndDate.
       "' AND batch_date >='".$queryStartDate. "';";
       $ress = WHDBmysql_query($sql);
       $recc = mysqli_fetch_array($ress);
       $i = $i + $recc['pots'];
    }
    return $i;
}

function print_sales_rose10($queryStartDate,$queryEndDate){
	$sql = "SELECT SUM(plants) as pots FROM orders 
		WHERE date <='".$queryEndDate."' AND date >='".$queryStartDate . "' 
		AND product_code='0001';";
	$res = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($res);
	echo "销售数量：--".$rec['pots']."盆";
	echo "</br></br>";
}
function print_plant_lose($queryStartDate,$queryEndDate){
        $sql = "SELECT SUM(pot_out) as pots FROM lose
        WHERE lose_date <='".$queryEndDate."' AND lose_date >='".$queryStartDate . "'
        AND flag='1';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $lose1 =$rec['pots'];
        $sql = "SELECT SUM(pot_out) as pots FROM lose
        WHERE lose_date <='".$queryEndDate."' AND lose_date >='".$queryStartDate . "'
        AND flag='2';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $lose2 =$rec['pots'];
        $sql = "SELECT SUM(pot_in) as pots FROM lose
        WHERE lose_date <='".$queryEndDate."' AND lose_date >='".$queryStartDate . "'
        AND flag='2';";
        $res = WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        $in2 =$rec['pots'];
        echo "损失数量：--";
        echo $lose1+$lose2-$in2."盆</br>";
        echo "----生根磕盆数量：--".$lose1."盆，</br>";
        echo "----苗期磕盆数量：--".$lose2."盆，</br>";
        echo "----苗期组盆数量：--".$in2."盆，</br>";
        echo "</br></br>";
    }

?>
