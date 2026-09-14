<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("material/material_out.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询出库信息</div>";
echo "<div id='right'>";
	
if(isset($_POST['submit'])){
	$sql = make_sql($_POST['material_code'],$_POST['startdate'],$_POST['enddate']);
	print_title($_POST['startdate'],$_POST['enddate'],$_POST['material_code']);
}
 else{
	$material_code = "99999";
	$startd = date('Y-m-d',monthFirstDate(time())); 
	$endd = date('Y-m-d',time());
	$sql = make_sql($material_code,$startd,$endd);
	print_title($startd,$endd,$material_code);
}

$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	print_table_head();
	$total = print_table_body($result);
	print_table_foot($total);
}
echo "</div>";
mysqli_free_result($result);

function print_title($startd,$endd,$material_code){
	$sql = "SELECT * from material WHERE id = '" . $material_code . "'";
	$result = WHDBmysql_query($sql);
	$recrow = mysqli_fetch_assoc($result);
	echo "<span style='font-size:25px'>查询条件：";
	echo "生产资料：" . $recrow['name'];
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
	mysqli_free_result($result);
}

function make_sql($material_code,$startd,$endd){
	if( $material_code == "99999" ){
		$sqlmid = "";
	} 
	else{
		$sqlmid = " AND material_code = " . $material_code;
	}
	$sql = "SELECT material_log.*, material.unit, material.name 
		FROM material_log, material 
		 WHERE material_log.material_code=material.code AND flag='OUT' AND date >= '" . $startd . "' AND date <= '" . $endd . "'" .$sqlmid .
		" ORDER BY date DESC LIMIT " . $GLOBALS['DB_MAX']  . ";";
	return $sql;
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>名称</th>
	<th>数量</th>
	<th>单位</th>
	<th>领用人</th>
	<th>备注</th>
	<th>修改</th>
	</tr>";
}

function print_table_body($result){
	$total = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . date('Y-m-d',strtotime($recrow['date'])) . "</td>";
		echo "<td>" . $recrow['name'] . "</td>";
		echo "<td>" . (int)$recrow['total'] . "</td>";
		echo "<td>" . $recrow['unit'] . "</td>";
		echo "<td>" . $recrow['who'] . "</td>";
		echo "<td>" . $recrow['comm'] . "</td>";
		echo "<td>" . "<a href='material_out_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>";
		echo "</tr>";
		$total = $total + $recrow['total'];
	}
	return $total;
}
function print_table_foot($total){
	echo "<tr>";
	echo "<td></td>";
	echo "<td>合计</td>";
	echo "<td>" . $total . "</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>
<div id="new">
<a href='material_out_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "material/material_out.php"; ?>" method="post">

<select name="material_code">
<?php
   $catsql = "SELECT * FROM material where active='YES';";
   $catres = WHDBmysql_query($catsql);
   echo "<option value='99999' selected>'生产资料品种'</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</select>
<?php
//查询的时间条件，起始时间为年初，结束时间为当日
echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>"; 
echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
?>
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
