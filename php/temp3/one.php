<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");

echo "<div id='title'>一件代发产品</div>";
echo "<div id='right'>";

$sql = "SELECT * FROM one;";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
	echo "没有记录";	
}
else{
	printTableHead();
	printTableBody($result);
	printTableFoot();
}
echo "</div>";
?>

<div id="new">
	<a href='one_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<?php
require("menu.php");
require("../public/footer.php");

function printTableHead(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>产品名称</th>";
	echo "<th>动作</th>";
	echo "</tr>";
}

function printTableBody($result){
	while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>" . $recrow['one_name'] . "</td>";
	echo "<td>" . "<a href='one_delete.php?id=" . $recrow['id'] . "' onclick='return deleteConfirm()'>删除" ."</td>";
	echo "</tr>";
	}
}

function printTableFoot(){
	echo "</table>";
}
?>
