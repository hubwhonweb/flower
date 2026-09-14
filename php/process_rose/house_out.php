<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/house_out.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>温室出货数据</div>";
echo "<div id='right'>";

    //如果没有查询，给出近30天的所有品种的数据
$startDate = date('Y-m-d',(time()-30*24*60*60));
$sql = "SELECT * FROM house_out
    WHERE out_date > '".$startDate."'
    ORDER BY out_date DESC;";
    //根据查询条件进行查询
if(isset($_POST['submit'])){
    $startDate = $_POST['startdate'];
    $endDate = $_POST['enddate'];
    
    if($_POST['house_name']=="所有温室" and $_POST['out_class']=="所有等级"){
         $sql = "SELECT * FROM house_out
         WHERE out_date >'".$queryDate."'
         AND out_date <='".$endDate."'
         ORDER BY out_date DESC;";
    }
    elseif( $_POST['house_name']=="所有温室" ){
        $sql = "SELECT * FROM house_out
        WHERE out_class = '" .$_POST['out_class']. "'
        AND out_date >='".$startDate."'
        AND out_date <='".$endDate."'
        ORDER BY out_date DESC;";
    }
    elseif($_POST['out_class']=="所有等级"){
        $sql = "SELECT * FROM house_out
        WHERE house_name = '" .$_POST['house_name']. "'
        AND out_date >='".$startDate."'
        AND out_date <='".$endDate."'
        ORDER BY out_date DESC;";
    }
    else{
        $sql = "SELECT * FROM house_out
        WHERE house_name = '" .$_POST['house_name']. "'
        AND out_class = '" .$_POST['out_class']. "'
        AND out_date >='".$startDate."'
        AND out_date <='".$endDate."'
        ORDER BY out_date DESC;";
    }
}
//echo $sql;
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
	<a out_class="newform" href='house_out_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/house_out.php"; ?>" method="post">
	
<select name="house_name">
<?php
  foreach ($HOUSE as $lis) {
    echo "<option value='" . $lis . "'>" . $lis . "</option>";
  }
?>
</select>
<select name="out_class">
<?php
    foreach ($CLASS as $lis) {
        echo "<option value='" . $lis . "'>" . $lis . "</option>";
    }
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
    echo "<th>出货日期</th>";
	echo "<th>温室</th>";
    echo "<th>品种</th>";
    echo "<th>等级</th>";
	echo "<th>数量</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
    $total1 = 0;
while($recrow = mysqli_fetch_assoc($result)){
    $total1 = $total1 + $recrow['pot'];
	echo "<tr>";
	echo "<td>" . $recrow['out_date'] . "</td>";
    echo "<td>" . $recrow['house_name'] . "</td>";
	echo "<td>" . $recrow['variety_code'] . "</td>";
    echo "<td>" . $recrow['out_class'] . "</td>";
    echo "<td>" . $recrow['pot'] . "</td>";
	echo "<td>" . "<a href='house_out_modify.php?id=" . $recrow['id'] . "'>修改</td>";
	echo "<td>" . "<a href='house_out_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
	echo "</tr>";
	}
    echo "<tr>";
    echo "<td>合计</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>".$total1."</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "</tr>";
echo "</table>";
}
?>
