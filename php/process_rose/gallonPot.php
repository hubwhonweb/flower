<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/gallonPot.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>组加仑盆</div>";
echo "<div id='right'>";


if(isset($_POST['submit'])){
	 //设置付款标志，查询的起止日期
	 $w_startdate = $_POST['startdate'];
	 $w_enddate = $_POST['enddate'];
	 //生成查询的条件语句并查询
	$sql = "SELECT * FROM plant_merge WHERE variety = '99' AND merge_date >='" . $w_startdate . "' AND merge_date <='" . $w_enddate . "' ORDER BY merge_date DESC;";
}
else{
	 //生成查询的条件语句并查询
	 $sql = "SELECT * FROM plant_merge WHERE variety= '99' ORDER BY merge_date DESC LIMIT 40;";
}
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
	<a class="newform" href='gallonPot_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/gallonPot.php"; ?>" method="post">
	

<?php
//查询的时间条件，起始时间为月初，结束时间为当日
echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>"; 
echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
?>

<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>组盆日期</th>";
    echo "<th>磕盆数</th>";
	echo "<th>组盆数</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
	$totalPick = 0;
	$totalMerge = 0;
  while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>" . $recrow['merge_date'] . "</td>";
    echo "<td>" . $recrow['pick_pots'] . "</td>";
	echo "<td>" . $recrow['merge_pots'] . "</td>";
	if( (time()-strtotime($recrow['merge_date'])) > 7*24*60*60 ){
       echo "<td></td>";
    }
    else{
		echo "<td>" . "<a href='gallonPot_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    }
	echo "</tr>";
	$totalPick = $totalPick + $recrow['pick_pots'];
	$totalMerge = $totalMerge + $recrow['merge_pots'];
	}
	echo "<tr>";
	echo "<td>合计</td>";
	echo "<td>" . $totalPick . "</td>";
	echo "<td>" . $totalMerge . "</td>";
	echo "<td></td>";
	echo "</tr>";
  echo "</table>";
}
?>
