<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_mention.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>催款通知</div><div id='right'>";
echo "<table>";
$sql = "SELECT distinct(agent_code) as agent_code FROM orders where flag='已发货';";
$cres = WHDBmysql_query($sql);
while($crow = mysqli_fetch_assoc($cres)){
	$agentName = getAgentName($crow['agent_code']);
	echo "<tr><td><a href='order_mention_do.php?id=". $crow['agent_code'] .  "'>" . $agentName . "</a></td></tr>"; 
}
echo "</table>";
echo "</div>"; 
mysqli_free_result($cres);
require("menu.php");
require("../public/footer.php");
?>