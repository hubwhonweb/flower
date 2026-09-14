<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("env/get_temperature.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>从温度监控平台中提取数据到系统数据库中</div>";
echo "<div id='right'>";

//$locationDev = array("342377","350569","406945","350601","350633","350665","358281");
//$locationName = array("A","B","CH","CL","D","E","O");

insert_data("342377","A");
insert_data("350569","B");
insert_data("406945","CH");
insert_data("350601","CL");
insert_data("350633","D");
insert_data("350665","E");
insert_data("358281","O");

echo "</div>";
require("menu.php");
require("../public/footer.php");

function insert_data($locationDev,$locationName){
	$sql = "SELECT MAX(rtime) as max_time from temperature where location='" . $locationName."';";
	$dbLink = connectDB();
	$result = mysqli_query($dbLink,$sql);
	$maxDate = mysqli_fetch_array($result);
	closeDB($dbLink);
	
	if( strtotime($maxDate['max_time']) < (time()-24*60*60) ){
		//如果数据库中最大的日期小于15天以前，则从15天前开始获取数据
		if(strtotime($maxDate['max_time']) < (time()-24*60*60*15)){
			$beginTime = date('YmdHis',(time()-24*60*60*15));
		}
		else {
			$beginTime = date('YmdHis',strtotime($maxDate['max_time']));
		}
		
		$endTime = date('Ymd',time()-24*60*60);
		$endTime = $endTime."235959";
		echo "位置:" .$locationName . "<br>";
		echo "开始时间:" .$beginTime. "<br>";
		echo "结束时间:" .$endTime . "<br>";
		$url ="http://www.0531yun.cn/wsjc/Device/getDevHisData?devKey=".$locationDev.
		"&beginTime=" . $beginTime. 
		"&endTime=". $endTime.
		"&userID=171024xmyy&userPassword=171024xmyy";
		echo $url."<br>";
		$ss = file_get_contents($url);
		$data = json_decode($ss, true);
		$recordCount =  count($data['HisData']);
		
		$dbLink = connectDB();
		for ($j = 0; $j < $recordCount; $j++) {
			$sql = "INSERT INTO temperature (location,rtime,temperature,humidity) 
			values('" .$locationName."',
			'".$data['HisData'][$j]['TimeValue']."',
			'".$data['HisData'][$j]['TempValue']."',
			'".$data['HisData'][$j]['HumiValue']."');";
			mysqli_query($dbLink,$sql);
		}
		closeDB($dbLink);
		echo "记录数：" . $j . "<br>";
	}
}
?>
