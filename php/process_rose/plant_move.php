<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/plant_move.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>移苗</div>";
echo "<div id='right'>";


$sql = "SELECT * FROM plant_move
    ORDER BY move_date DESC LIMIT 300;";
if(isset($_POST['submit'])){
    if($_POST['variety_code']!="999"){
		 $sql = "SELECT * FROM plant_move
         WHERE variety = '" .$_POST['variety_code']. "'
         ORDER BY move_date DESC LIMIT 100;";
    }
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
	<a class="newform" href='plant_move_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/plant_move.php"; ?>" method="post">
	
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
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>


<?php
require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th>移苗日期</th>";
	echo "<th>品种</th>";
    echo "<th>扦插日期</th>";
	echo "<th>扦插天数</th>";
    echo "<th>温室</th>";
    echo "<th>床号</th>";
	echo "<th>移苗数量</th>";
	//echo "<th>状态</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
    $days = 0;
  while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>" . $recrow['move_date'] . "</td>";
	echo "<td>" . $recrow['variety'] . "</td>";
    echo "<td>" . $recrow['plant_date'] . "</td>";
	$days = days(strtotime($recrow['move_date']),strtotime($recrow['plant_date']));
	echo "<td>" . $days . "</td>";
    echo "<td>" . $recrow['target_house'] . "</td>";
    echo "<td>" . $recrow['target_bed'] . "</td>";
    echo "<td>" . $recrow['plants'] . "</td>";
    //echo "<td>" . $recrow['state'] . "</td>";
	echo "<td>" . "<a href='plant_move_modify.php?id=" . $recrow['id'] . "'>修改</td>";
	echo "<td>" . "<a href='plant_move_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    
    echo "</tr>";
	}
  echo "</table>";
}
?>
