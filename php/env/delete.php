<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/batch_delete.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

$ddate = date('Y-m-d',time()-365*24*60*60);
$sql = "DELETE FROM temperature WHERE rtime <= " . $ddate . ";";
WHDBmysql_query($sql);
header("Location: " . $BASE_DIR . "env/temperature.php");

require("menu.php");
require("../public/footer.php");
?>
