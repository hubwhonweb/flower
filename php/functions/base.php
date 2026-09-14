<?php
//替代php5的mysql_query()函数
function WHDBmysql_query($sql){
	$link = mysqli_connect($GLOBALS['DB_HOST'],$GLOBALS['DB_USER'],$GLOBALS['DB_PASSWORD'],$GLOBALS['DB_DATABASE']);
	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	mysqli_query($link,"set names UTF8");
	mysqli_query($link,"set character set 'utf8'");
	$result = mysqli_query($link,$sql);
	mysqli_close($link);
	return $result;
}
//优化数据库函数
function connectDB(){
	$link = mysqli_connect($GLOBALS['DB_HOST'],$GLOBALS['DB_USER'],$GLOBALS['DB_PASSWORD'],$GLOBALS['DB_DATABASE']);
	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	mysqli_query($link,"set names UTF8");
	mysqli_query($link,"set character set 'utf8'");
	return $link;
}

function closeDB($link){
	mysqli_close($link);
}

//检查URL所带get的参数是否为数字
function checkUrlNumber(){
	if(isset($_GET['id']) == TRUE){
		$id = $_GET['id'];
		if(is_numeric($id) == TRUE){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	else{
		return FALSE;
	}
}
//检查字符串是否全部由字母组成
function checkChar($str){
	$str = strtoupper($str);
	return preg_match('/[A-Z]/', $str);
}
//检查URL所带get的参数是否为数字+减号+点
function checkUrlOrderID(){
	if(isset($_GET['id']) == TRUE){
		$id = $_GET['id'];
		if(preg_match("[[0-9]*\-[0-9]*\-[0-9]*]", $id)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	else{
		return FALSE;
	}
}

//输入为一个时间变量，返回这个时间变量所在月份的天数
function monthDays($timest){
	switch (date('m',$timest)){
		case 1:
			return 31;
		case 2: 
			if(date("L",$timest) == 1) return 29;
			else return 28;
		case 3: 
			return 31;
		case 4: 
			return 30;
		case 5: 
			return 31;
		case 6: 
			return 30;
		case 7: 
			return 31;
		case 8: 
			return 31;
		case 9: 
			return 30;
		case 10: 
			return 31;
		case 11:
			return 30;
		case 12:
			return 31;
		default:
			return 99;
		}
}
//输入一个时间变量，返回这个时间所在年的第一天时间变量
function yearFirstDate($timest){
	$yearf = date('Y',$timest);
	$yearf = $yearf . "-01-01";
	return strtotime($yearf);	
}
//输入一个时间变量，返回当月最后一天的时间变量
function monthFinalDate($timest){
	$mon = date('m',$timest);
	while($mon == date('m',$timest)){
		$timest = $timest + 1*24*60*60;
	}
	return $timest - 1*24*60*60;
}
//返回一个时间变量的当月第一天的时间变量
function monthFirstDate($timest){
	$mon = date('m',$timest);
	while($mon == date('m',$timest)){
		$timest = $timest - 1*24*60*60;
	}
	return $timest + 1*24*60*60;
}
//两个时间变量之间的天数
function days($btime,$stime){
	$rt = floor($btime-$stime)/(24*60*60);
	return $rt;
}
//给一个时间变量，返回下个月第一天日期
function getNextMonthFirstDate($time){
	$next_month_first=monthFinalDate($time) + 1*24*60*60;
	return $next_month_first;
}
//返回给定的时间变量前几天的时间
function getBeforDate($timest,$days){
	$timest = $timest + $days*24*60*60;
	return $timest;
}
//返回给定的时间变量后几天的时间
function getAfterDate($timest,$days){
	$timest = $timest - $days*24*60*60;
	return $timest;
}
//返回给定时间的周一
function getMonday( $stime ){
	$weekday = date('w',$stime);
	if( $weekday == 0 ){
		$monday = $stime - 6*24*60*60;
	}
	else{
		$monday = $stime - ($weekday-1)*24*60*60;
	}
	return $monday;
}
//返回给定时间的下周一
function getNextMonday( $stime ){
	$stime = $stime + 7*24*60*60;;
	$weekday = date('w',$stime);
	if( $weekday == 0 ){
		$monday = $stime - 6*24*60*60;
	}
	else{
		$monday = $stime - ($weekday-1)*24*60*60;
	}
	return $monday;
}
//返回给定时间所在的年、第几周（字典：['year'],['week']）
function getYearAndWeeks($stime){
	$week = date('W',$stime);
	$year = date('Y',$stime);
	if( $week > 50 && date('m',$stime)=="01"){
		$year = $year - 1;
	}
	$arr['week'] = $week;
	$arr['year'] = $year;
	return $arr;
}
//返回给定年分的第几周的周一的日期
function getYearWeeksMonday($year,$no){
	$stime = strtotime($year."-01-01");
	$week = date('W',$stime);
	if( $week > 50 ){
		$stime = $stime + 7*24*60*60;
	}
	$monday = getMonday($stime);
	return $monday +($no-1)*7*24*60*60;
}
//把数字转换成货币格式
function numberToMoney($number){
	setlocale(LC_MONETARY, 'en_US');
	return money_format('%!.2n', $number);
}
//把小数转成百分数
function toPercentage($n){
 return sprintf("%01.2f", $n*100).'%'; 
}
?>