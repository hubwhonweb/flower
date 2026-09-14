<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("material/material_out_batch.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='righttop'>批量整理出库信息</div>";
echo "<div id='right'>";
//处理每个日期
$sql = "select date from material_log where who = 'SYS' order by date;";
$result = WHDBmysql_query($sql);
while ( $recrow = mysqli_fetch_array($result) ) {
	//处理一个日期中的每个生产资料
	$sql = "SELECT DISTINCT(material_code) FROM `material_log` WHERE who = 'SYS' AND date='".$recrow['date']."';";
	$result1 = WHDBmysql_query($sql);
	while ( $recrow1 = mysqli_fetch_array($result1)) {
		//处理一个日期中一个生产资料
        //求和
		$sql = "SELECT SUM(total) as total FROM `material_log` WHERE who = 'SYS' AND date='".$recrow['date']."' AND material_code ='".$recrow1['material_code']."';";
		$result2 = WHDBmysql_query($sql);
		$recrow2 = mysqli_fetch_array($result2);
		//插入
		$sql = "INSERT INTO material_log (material_code, date, total, who, comm, flag, mname, mtime) 
		VALUES( '" . $recrow1['material_code'] . "',
		'" .$recrow['date']  ."', 
		'" .$recrow2['total']  ."',
		'SYSTEM',
		'系统自动整理', 
		'OUT', 
		'" . $_SESSION['WHOAMI'] . "', '" . date('Y-m-d h:i:sa',time()) . "');";
        WHDBmysql_query($sql);
        //删除
        $sql = "delete from material_log where who = 'SYS' AND date='".$recrow['date']."' AND material_code ='".$recrow1['material_code']."';";
        WHDBmysql_query($sql);
        //显示结果
        echo "date='".$recrow['date']."' material_code ='".$recrow1['material_code']."'处理完成<br>";
	}

}
echo "</div>";
require("menu.php");
require("../public/footer.php");
?>