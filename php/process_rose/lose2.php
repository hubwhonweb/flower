<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/lose2.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>幼苗组盆数据</div>";
echo "<div id='right'>";

    //如果没有查询，给出近30天的所有品种的数据
$startDate = date('Y-m-d',(time()-30*24*60*60));
$sql = "SELECT * FROM lose
    WHERE lose_date > '".$startDate."'
    AND flag = '2'
    ORDER BY lose_date DESC;";
    //根据查询条件进行查询
if(isset($_POST['submit'])){
    $startDate = $_POST['startdate'];
    $endDate = $_POST['enddate'];
	 if($_POST['variety_code']!="99999"){
		 $sql = "SELECT * FROM lose
         WHERE variety_code = '" .$_POST['variety_code']. "'
         AND lose_date >='".$startDate."'
         AND lose_date <='".$endDate."'
         AND flag = '2'
         ORDER BY lose_date DESC;";
		}
     else{
         $sql = "SELECT * FROM lose
         WHERE lose_date >'".$queryDate."'
         AND lose_date <='".$endDate."'
         AND flag = '2'
         ORDER BY lose_date DESC;";
     }
}
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
	<a class="newform" href='lose2_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/lose2.php"; ?>" method="post">
	
<select name="variety_code">
<?php
echo "<option value='99999'>全部品种</option>";
$catsql = "SELECT * FROM variety WHERE product_code = '" .$product_code ."' and 
active='在产' order by variety_code ASC;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>
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
    echo "<th>操作日期</th>";
	echo "<th>品种</th>";
    echo "<th>扦插日期</th>";
    echo "<th>组盆数量</th>";
    echo "<th>损失数量</th>";
    echo "<th>扦插数量</th>";
    echo "<th>组盆比例</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
    $total1 = 0;
    $total2 = 0;
    $total = 0;
while($recrow = mysqli_fetch_assoc($result)){
    echo "<tr>";
    echo "<td>" . $recrow['lose_date'] . "</td>";
    echo "<td>" . $recrow['variety_code'] . "</td>";
    echo "<td>" . $recrow['plant_date'] . "</td>";
    echo "<td>" . $recrow['pot_in'] . "</td>";
    echo "<td>" . $recrow['pot_out'] . "</td>";
    $plant_pot = getPlantPots($recrow['variety_code'],$recrow['plant_date']);
    echo "<td>" . $plant_pot . "</td>";
    $percent = ($recrow['pot_out']-$recrow['pot_in'])/$plant_pot;
    echo "<td>" . toPercentage($percent) . "</td>";
	echo "<td>" . "<a href='lose2_modify.php?id=" . $recrow['id'] . "'>修改</td>";
	echo "<td>" . "<a href='lose2_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
	echo "</tr>";
    $total1 = $total1 + $recrow['pot_in'];
    $total2 = $total2 + $recrow['pot_out'];
    $total = $total + $plant_pot;
	}
    echo "<tr>";
    echo "<td>合计</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>".$total1."</td>";
    echo "<td>".$total2."</td>";
    echo "<td>".$total."</td>";
    echo "<td>".toPercentage(($total2-$total1)/$total)."</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "</tr>";
echo "</table>";
}
?>
