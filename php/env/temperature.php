<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("env/temperature.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询温室温度</div>";
echo "<div id='right'>";

//根据提交搜索条件生成搜索时间
if(isset($_POST['submit'])){
	$queryStart = $_POST['startdate'];
	$queryEnd = $_POST['enddate'];
	$queryLocation = $_POST['location'];
}
else{
	$queryStart = date('Y-m-d',monthFirstDate(time()));
	$queryEnd = date('Y-m-d',time());
	$queryLocation = "A";
}

print_search($queryLocation,$queryStart,$queryEnd);
print_head();
print_body($queryLocation,$queryStart,$queryEnd);
echo "</div>";

function print_search($location,$qs,$qe){
	echo "<span>".$location."温室".$qs."至".$qe."温度情况</span><br/>";
}

function print_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>日期</th>";
	echo "<th>平均温度ADT</th>";
	echo "<th>日均温度DT</th>";
	echo "<th>夜均温度NT</th>";
	echo "<th>昼夜温差DIF</th>";
	echo "</tr>";
}

function print_body($location,$queryStart,$queryEnd){
	$queryDays = days(strtotime($queryEnd),strtotime($queryStart));
	$queryStart = strtotime($queryStart);
	for ($i = 0; $i <= $queryDays; $i++) {
		echo "<tr>";
		echo "<td>".date('Y-m-d',$queryStart)."</td>";
		//显示日均温度
		$beginTime = date('Y-m-d',$queryStart)." 00:00:00";
		$endTime = date('Y-m-d',$queryStart)." 23:59:59";
		$sql = "SELECT AVG(temperature) as temperature from temperature 
			where location = '" . $location ."' 
			AND  rtime BETWEEN '" . $beginTime . "' AND '" .$endTime ."';";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
		$temp = round($record['temperature'],1);
		echo "<td>".$temp."</td>";
		//显示白天平均温度
		$beginTime = date('Y-m-d',$queryStart)." 07:00:00";
		$endTime = date('Y-m-d',$queryStart)." 18:59:59";
		$sql = "SELECT AVG(temperature) as temperature from temperature 
			where location = '" . $location ."' 
			AND  rtime BETWEEN '" . $beginTime . "' AND '" .$endTime ."';";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
		$dayTemp = round($record['temperature'],1);
		echo "<td>".$dayTemp."</td>";
		//显示夜晚平均温度
		$beginTime = date('Y-m-d',$queryStart)." 19:00:00";
		$endTime = date('Y-m-d',$queryStart+24*60*60)." 06:59:59";
		$sql = "SELECT AVG(temperature) as temperature from temperature 
			where location = '" . $location ."' 
			AND  rtime BETWEEN '" . $beginTime . "' AND '" .$endTime ."';";
		$result = WHDBmysql_query($sql);
		$record = mysqli_fetch_array($result);
		$nightTemp = round($record['temperature'],1);
		echo "<td>".$nightTemp."</td>";
		//显示昼夜温差
		echo "<td>".($dayTemp-$nightTemp)."</td>";		
		echo "</tr>";
		$queryStart = $queryStart + 24*60*60;
	}
	echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "env/temperature.php"; ?>" method="post">
选择日期：
<?php
//查询的时间条件，起始时间为月初，结束时间为当日
echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>"; 
echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
?>
选择温室：
<?php
echo "<select name='location'>";
echo "<option value='A' selected>A温室</option>";
echo "<option value='B' selected>B温室</option>";
echo "<option value='CH' selected>C温室高温区</option>";
echo "<option value='CL' selected>C温室低温区</option>";
echo "<option value='D' selected>D温室</option>";
echo "<option value='E' selected>E温室</option>";
echo "<option value='O' selected>室外</option>";
echo "</select>";
?>
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>