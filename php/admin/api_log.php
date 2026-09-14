<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/api_log.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");

$sql = "SELECT * FROM api_log;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);

echo "<div id='right'>";
if($numrow == 0) {
 	echo "没有记录";	 
 }
else{
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>time</th>
	<th>api</th>
	<th>log</th>
	</tr>";
while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
		<td>" . $recrow['time'] . "</td>
		<td>" . $recrow['name'] . "</td>
		<td>" . $recrow['body'] . "</td>
		</tr>";
}
echo "</table>";
}
echo "</div>";

require("menu.php");
require("../public/footer.php");
?>
