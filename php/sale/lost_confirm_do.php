<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/lost_confirm_do.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "sale/menu.php");
}
else{
	$id = $_GET['id'];
}

$sql = "UPDATE lost SET  flag = '已复核',
mname = '" . $_SESSION['WHOAMI'] . "', 
mtime = '" . date('Y-m-d h:i:sa',time()) . "' 
WHERE id = " . $id . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "sale/lost_confirm.php");

require("menu.php");
require("../public/footer.php");
?>