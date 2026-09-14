<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/first_delete.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "process_rose/menu.php");
}
else{
	$id = $_GET['id'];
}


//提交了确认按钮，进行数据库修改
$sql = "UPDATE batch
       SET first_date = '0000-00-00' ,second_date = '0000-00-00' ,
       mname = '" . $_SESSION['WHOAMI'] . "',
       mtime = '" . date('Y-m-d h:i:sa',time()) . "' WHERE id = " . $id . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "process_rose/first.php");

require("menu.php");
require("../public/footer.php");
?>
