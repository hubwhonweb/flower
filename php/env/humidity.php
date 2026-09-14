<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("env/humidity.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询温室温湿     度每小时平均值</div>";
echo "<div id='right'>";

//根据提交搜索条件生成搜索时间
if(isset($_POST['submit'])){
	$queryYear = $_POST['year'];
	$queryMonth = $_POST['month'];
	$queryLocation = $_POST['location'];
}
else{
	$queryYear = date('Y',monthFirstDate(time()));
	$queryMonth = date('m',monthFirstDate(time()));
	$queryLocation = "A";
}
$beginTime = strtotime($queryYear."-".$queryMonth."-01");


print_search($queryLocation,$queryYear,$queryMonth);
print_head();
print_body($queryLocation,$beginTime);
echo "</div>";

function print_search($location,$sy,$sm){
	echo "<span>".$location."温室".$sy."年".$sm."温湿度情况</span><br/>";
}

function print_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>日期</th>";
	for ($i = 0; $i < 24; $i++) {
		echo "<th>".$i."点</th>";
	}
	echo "</tr>";
}

function print_body($location,$beginDate){
	for ($i = 0; $i < 31; $i++) {
		echo "<tr>";
		echo "<td>".date('m-d',$beginDate)."</td>";
		$dbLink = connectDB();
		for ($j = 0; $j < 24; $j++) {
			if( $j < 10 ){
				$hour = " 0".$j;
			}
			else{
				$hour = " ".$j;
			}
			$beginTime = date('Y-m-d',$beginDate).$hour.":00:00";
			$endTime = date('Y-m-d',$beginDate).$hour.":59:59";
			$sql = "SELECT AVG(temperature) as temperature, AVG(humidity) as humidity from temperature where location = '" . $location ."' AND  rtime BETWEEN '" . $beginTime . "' AND '" .$endTime ."';";
			$result = mysqli_query($dbLink,$sql);
			$record = mysqli_fetch_array($result);
			//$temp = round($record['temperature'],1);
			$humi = round($record['humidity']);
			echo "<td>".$humi."</td>";
		}
		echo "</tr>";
		closeDB($dbLink);
		$beginDate = $beginDate + 24*60*60;
	}
	echo "</table>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "task/humidity.php"; ?>" method="post">
查询：
选择年份：
<?php
echo "<select name='year'>";
foreach ($YEARS as $li) {
	if(date('Y',monthFirstDate(time()))==$li){
		echo "<option value='" . $li . "' selected>" . $li . "</option>";
	}
	else{
		echo "<option value='" . $li . "'>" . $li . "</option>";
	}
	
}
echo "</select>";
?>
选择月份：
<?php
echo "<select name='month'>";
$month = [1,2,3,4,5,6,7,8,9,10,11,12];
foreach ($month as $li) {
	if(date('m',monthFirstDate(time()))==$li){
		echo "<option value='" . $li . "' selected>" . $li . "</option>";
	}
	else{
		echo "<option value='" . $li . "'>" . $li . "</option>";
	}
	
}
echo "</select>";
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