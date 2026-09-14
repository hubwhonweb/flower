<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/batch_check.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>最近30天进苗批次数据</div>";
echo "<div id='right'>";

//查询近30天进苗的数据
$startDate = date('Y-m-d',(time()-30*24*60*60));
$sql = "SELECT distinct(batch_code) as b_code FROM house_in
    WHERE in_date > '".$startDate."'
    ORDER BY b_code DESC;";
$res = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($res);
if($numrow == 0) {
    echo $sql;
}
else{
print_table_head();
print_table_body($res);
}
echo "</div>";

require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
    echo "<th>批次号</th>";
	echo "<th>进苗日期</th>";
    echo "<th>苗期天</th>";
    echo "<th>扦插数</th>";
    echo "<th>磕盆数</th>";
    echo "<th>损失数</th>";
    echo "<th>组盆数</th>";
	echo "<th>在场数</th>";
	echo "<th>进苗数</th>";
	echo "<th>备注</th>";
	echo "</tr>";
}
    
function print_table_body($res){
  while($rec = mysqli_fetch_assoc($res)){
    $b_code = $rec['b_code'];
    $in_date = get_in_date($b_code);
    $in_days = get_in_days($b_code);
    $plant_pot = get_plant_pot($b_code);
    $ke_pot = get_ke_pot($b_code);
    $lose_pot = get_lose_pot($b_code);
    $create_pot = get_create_pot($b_code);
    $real_pot = $plant_pot - $ke_pot - $lose_pot + $create_pot;
    $house_in = get_house_in($b_code);
    $flag = "";
    if( ($plant_pot - $real_pot)/$plant_pot > 0.7 ){
      $flag = "*损失";
    }
    if( abs($real_pot - $house_in) > 30 ){
      $flag = $flag."*进苗";
    }
 	echo "<tr>";
	echo "<td>" . $b_code . "</td>";
    echo "<td>" . $in_date . "</td>";
	echo "<td>" . $in_days . "</td>";
    echo "<td>" . $plant_pot . "</td>";
    echo "<td>" . $ke_pot . "</td>";
    echo "<td>" . $lose_pot . "</td>";
    echo "<td>" . $create_pot . "</td>";
    echo "<td>" . $real_pot . "</td>";
    echo "<td>" . $house_in . "</td>";
    echo "<td>" . $flag . "</td>";
	echo "</tr>";
	}
  echo "</table>";
}

function get_in_date($b_code){
    $sql = "select * from house_in where batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    return $c['in_date'];
}
function get_in_days($b_code){
    $sql = "select * from house_in where batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    $btime = strtotime($c['in_date']);
    $stime = strtotime($c['plant_date']);
    return days($btime,$stime);
}
function get_plant_pot($b_code){
    $sql = "select * from batch where batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    return $c['batch_pots'];
}
function get_ke_pot($b_code){
    $sql = "select sum(pot_out) as pot_out from lose where flag = '1' and batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    return $c['pot_out'];
}
function get_lose_pot($b_code){
    $sql = "select sum(pot_out) as pot_out from lose where flag = '2' and batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    return $c['pot_out'];
}
function get_create_pot($b_code){
    $sql = "select sum(pot_in) as pot_in from lose where flag = '2' and batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    return $c['pot_in'];
}
function get_house_in($b_code){
    $sql = "select sum(pot) as pot from house_in where batch_code='".$b_code."';";
    $s = WHDBmysql_query($sql);
    $c = mysqli_fetch_assoc($s);
    return $c['pot'];
}
?>
