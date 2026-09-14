<?php
//功能：删除存取列表中30天前的记录。
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/access_delete.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
echo "<div id='title'>删除用户使用记录</div>";
echo "<div id='right'>";
$deleteTime = date('Y-m-d H:i:s',time()-30*24*60*60);
echo "delete before ".$deleteTime."...</br>";
delete_access_log($deleteTime);
echo "</div>";
require("menu.php");
require("../public/footer.php");

function delete_access_log($deleteTime){
	$sql = "DELETE FROM access_log WHERE access_time <'".$deleteTime."';";
    $result = WHDBmysql_query($sql);
	echo $sql;
}
?>