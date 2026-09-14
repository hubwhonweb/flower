<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("report/report_008.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>生产量周报</div>";
echo "<div id='right'>";

//打印7周
print_table_head();
print_stick_variety();
print_table_end();

echo "</div>";

function print_stick_variety(){
	$sql = "select * from variety where active='在产' order by variety_code;";
	$result = WHDBmysql_query($sql);
	//计算并打印每一个品种每周的扦插数量
    $tot = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	while ($record = mysqli_fetch_array($result)) {
		$thisMonday = getMonday( time() );
		$startSunday = $thisMonday-17*7*24*60*60;//20周前的星期一
		
		echo "<tr>";
		echo "<td>" .$record['variety_code'] . "</td>";
		for( $i = 0; $i < 18; $i++ ){
			$DateStart = date('Y-m-d',$startSunday);
			$DateEnd = date('Y-m-d',$startSunday+7*24*60*60);
			$sql = "select sum(batch_pots) as pots from batch 
			where variety_code='" . $record['variety_code'] ."' 
			AND batch_date>='" .$DateStart . "' 
			AND batch_date<'" .$DateEnd . "';";
			$res = WHDBmysql_query($sql);
			$rec =  mysqli_fetch_array($res);  
			echo "<td>" .$rec['pots'] . "</td>";
            $tot[$i] = $tot[$i] + $rec['pots'];
			$startSunday = $startSunday+7*24*60*60;
			//echo $sql;
		}
       echo "</tr>";
	}
    echo "</tr>";
    echo "<tr>";
    echo "<td>合计</td>";
    for( $i = 0; $i < 18; $i++ ){
          echo "<td>" .$tot[$i] . "</td>";
    }
}

function print_table_end(){
	echo "</table>";
}

function print_table_head(){
	$week = date('W',time());
	echo "<table class='hovertable'>";
	echo "<tr><th>品种</th>";
	for($i=-17;$i<=0;$i++){
		echo"<th>".($i+$week)."</th>";
	}
	echo "</tr>";
}
?>


<?php
require("menu.php");
require("../public/footer.php");
?>
