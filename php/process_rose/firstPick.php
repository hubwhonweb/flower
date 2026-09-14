<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/firstPick.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>组盆磕盆</div>";
echo "<div id='right'>";


if(isset($_POST['submit'])){
	 //设置付款标志，查询的起止日期
	 $w_startdate = $_POST['startdate'];
	 $w_enddate = $_POST['enddate'];
	 //生成查询的条件语句并查询
	if($_POST['variety_code']=="999"){
		 $sql = "SELECT * FROM plant_merge  ORDER BY merge_date DESC LIMIT 200;";
	}
	else{
		 $sql = "SELECT * FROM plant_merge WHERE variety='" .$_POST['variety_code']. "'  ORDER BY merge_date DESC;";
	}
}
else{
	 //设置查询的起止日期
	 //$w_startdate = date('Y-m-d',monthFirstDate(time()));
	 //$w_enddate = date('Y-m-d',time());
	 //生成查询的条件语句并查询
	 $sql = "SELECT * FROM plant_merge  ORDER BY merge_date DESC LIMIT 200;";
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
	<a class="newform" href='firstPick_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/firstPick.php"; ?>" method="post">
	
	
<select name="variety_code">
<?php
echo "<option value='999'>全部品种</option>";
$catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code ASC;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>

<?php
//查询的时间条件，起始时间为月初，结束时间为当日
//echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>"; 
//echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
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
	echo "<th>磕盆日期</th>";
    echo "<th>扦插日期</th>";
    echo "<th>品种</th>";
	echo "<th>扦插天数</th>";
    //echo "<th>生长阶段</th>";
	echo "<th>磕盆数</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
	$days = 0;
	$total = 0;
  while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>" . $recrow['merge_date'] . "</td>";
    echo "<td>" . $recrow['plant_date'] . "</td>";
    echo "<td>" . $recrow['variety'] . "</td>";
    $days = days(strtotime($recrow['merge_date']),strtotime($recrow['plant_date']));
	echo "<td>" . $days . "</td>";
    //echo "<td>" . $recrow['stage'] . "</td>";
	echo "<td>" . $recrow['pick_pots'] . "</td>";
	echo "<td>" . "<a href='firstPick_modify.php?id=" . $recrow['id'] . "'>修改</td>";
	echo "<td>" . "<a href='firstPick_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    echo "</tr>";
	$total = $total + $recrow['pick_pots'];
	}
	echo "<tr>";
	echo "<td>合计</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>" . $total . "</td>";
	echo "</tr>";
  echo "</table>";
}
?>
