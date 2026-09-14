<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if( canI("admin/can_delete.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "admin/can.php");
}
else{
	$id = $_GET['id'];
}

$sql = "DELETE FROM can WHERE id = " . $id . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "admin/can.php");

require("../public/footer.php");
?>