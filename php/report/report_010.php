<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_010.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>色系生产比例</div>";
echo "<div id='right'>";

if(isset($_POST['submit'])){
	 //查询的起止日期
	 $w_startdate = getYearWeeksMonday($_POST['year'],$_POST['week']);
	 $w_startdate = $w_startdate-24*60*60;
	 $w_enddate = $w_startdate+6*24*60*60;
     $w_startdate = date('Y-m-d',$w_startdate);
     $w_enddate = date('Y-m-d',$w_enddate);
}
else{
	 //设置查询的起止日期
    $yearWeek = getYearAndWeeks(time());
	$w_startdate = $w_startdate-24*60*60;
    $w_startdate = getYearWeeksMonday($yearWeek['year'],($yearWeek['week']-1));
    $w_enddate = $w_startdate+6*24*60*60;
    $w_startdate = date('Y-m-d',$w_startdate);
    $w_enddate = date('Y-m-d',$w_enddate);
}
echo "开始日期：".$w_startdate.",结束日期：".$w_enddate;
print_table_head();
print_table_body($w_startdate,$w_enddate);
echo "</div>";


function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>色系</th>";
    echo "<th>品种</th>";
    echo "<th>批次</th>";
	echo "<th>扦插日期</th>";
    echo "<th>盆数</th>";
    echo "<th>比例</th>";
	echo "</tr>";
}

function print_table_body($start,$end){
    //取得查询时间段生产总量
    $sql = "select sum(batch_pots) as pots from batch where batch_date >='".$start."'
    and batch_date <='".$end."';";
    $res = WHDBmysql_query($sql);
    $rec = mysqli_fetch_array($res);
    $total = $rec['pots'];
    //取得色系数据
    $sql = "select distinct(color) from variety where active='在产'";
    $res = WHDBmysql_query($sql);
    while($rec = mysqli_fetch_array($res) ){
        //取得色系品种
        $color = $rec['color'];
        //取得该色系生产总量
        $colorTotal = 0;
        $sql = "select * from variety where active='在产' and color ='".$color."'";
        $ress = WHDBmysql_query($sql);
        while($recc = mysqli_fetch_array($ress)){
            $vari = $recc['variety_code'];
            $sql = "select * from batch where variety_code='".$vari."'
            and batch_date >='".$start."'and batch_date <='".$end."';";
			//echo $sql;
            $resss = WHDBmysql_query($sql);
            $numrow = mysqli_num_rows($resss);
            $varietyTotal =0;
            if($numrow != 0) {
                while( $reccc = mysqli_fetch_array($resss)){
                    echo "<tr>";
                    echo "<td>".$color."</td>";
                    echo "<td>".$reccc['variety_code']."</td>";
                    echo "<td>".$reccc['batch_code']."</td>";
                    echo "<td>".$reccc['batch_date']."</td>";
                    echo "<td>".$reccc['batch_pots']."</td>";
                    echo "<td>".number_format(($reccc['batch_pots']/$total)*100,2)."%</td>";
                    echo "</tr>";
                    $variteyCode = $reccc['variety_code'];
                    $varietyTotal = $varietyTotal + $reccc['batch_pots'];
                    $colorTotal = $colorTotal + $reccc['batch_pots'];
                }
            }
        }
        //打印数据：色系、生产量、比例
        if($colorTotal != 0){
            echo "<tr>";
            echo "<td><span style='font-size:20px;color:red;'>".$color."总计</td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td><span style='font-size:20px;color:red;'>".$colorTotal."</td>";
            echo "<td><span style='font-size:20px;color:red;'>".number_format(($colorTotal/$total)*100,2)."%</td>";
            echo "</tr>";
        }
    }
    echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>".$total."</td>";
    echo "<td></td>";
    echo "</tr>";
    echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "report/report_010.php"; ?>" method="post">

选择年份：
<?php
    echo "<select name='year'>";
    foreach ($YEARS as $li) {
        echo "<option value='" . $li . "'>" . $li . "</option>";
    }
    echo "</select>";
?>
选择第几周：
<?php
    echo "<select name='week'>";
    for ($i = 1; $i<=52; $i++) {
        echo "<option value='" . $i . "'>" . $i . "</option>";
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
