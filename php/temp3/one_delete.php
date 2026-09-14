<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_delete.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "one/one.php");
}
else{
	$id = $_GET['id'];
}

$sql = "DELETE FROM one WHERE id = " . $id . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "one/one.php");

require("menu.php");
require("../public/footer.php");
?>
