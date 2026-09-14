<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_pay_modidy.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(checkUrlOrderID()==FALSE){
	header("Location: " . $BASE_DIR . "sale/menu.php");
}
else{
	$id = $_GET['id'];
}

$sql = "UPDATE orders set
			flag='已发货',
			mname='" . $_SESSION['WHOAMI'] . "', 
			mtime='" . date('Y-m-d h:i:sa',time()) . "' 
			WHERE order_id='" . $id . "';";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "sale/order_done.php");

require("menu.php");
require("../public/footer.php");
?>
