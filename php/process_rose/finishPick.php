<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/finishPick.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>成品组盆</div>";
echo "<div id='right'>";


$sql = "SELECT * FROM plant_merge where stage='成品组盆' 
    ORDER BY merge_date DESC LIMIT 300;";
    //根据查询条件进行查询
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);

if($numrow == 0) {
	echo "没有记录";	 
}
else{
print_table_head();
print_table_body($result);
}
echo "</div>";
?>

<div id="new">
	<a class="newform" href='finishPick_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<?php
require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>磕盆日期</th>";
    echo "<th>扦插日期</th>";
    echo "<th>品种</th>";
	echo "<th>扦插天数</th>";
    echo "<th>生长阶段</th>";
	echo "<th>磕盆数</th>";
	echo "<th>组盆数</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
  $days = 0;
  while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>" . $recrow['merge_date'] . "</td>";
    echo "<td>" . $recrow['plant_date'] . "</td>";
    echo "<td>" . $recrow['variety'] . "</td>";
    $days = days(strtotime($recrow['merge_date']),strtotime($recrow['plant_date']));
	echo "<td>" . $days . "</td>";
    echo "<td>" . $recrow['stage'] . "</td>";
	echo "<td>" . $recrow['pick_pots'] . "</td>";
    echo "<td>" . $recrow['merge_pots'] . "</td>";
	echo "<td>" . "<a href='finishPick_modify.php?id=" . $recrow['id'] . "'>修改</td>";
	echo "<td>" . "<a href='finishPick_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    
    echo "</tr>";
	}
  echo "</table>";
}
?>
