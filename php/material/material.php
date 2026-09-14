<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("material/material.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");

echo "<div id='title'>查询库存信息</div>";
echo "<div id='right'>";

print_table_head();
if(isset($_POST['submit'])){
	print_table_line($_POST['material_code']);
	}
else{
	//在生产资料表中查询有效的生产资料
	$sql ="SELECT * from material where active='YES' order by code;";
	$result = WHDBmysql_query($sql);
	$total = 0;
	while($rec = mysqli_fetch_array($result)){
		$total = $total + print_table_line($rec['code']);
	}
	mysqli_free_result($result);
}
print_table_foot($total);

echo "</table>";
echo "</div>";

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>--分类--</th>
	<th>--编号--</th>
	<th>--名称--</th>
	<th>--库存--</th>
	<th>--单位--</th>
	</tr>";
}
//打印一种生产资料的剩余数量
function print_table_line($material_code){
	echo "<tr>";
	//生产资料信息
	$sql = "SELECT * FROM material WHERE code='" .$material_code. "';";
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	$materialUnit = $rec['unit'];
	$materialPrice = $rec['price'];
	echo "<td>" . $rec['category'] . "</td>";
	echo "<td>" . $rec['code'] . "</td>";
	echo "<td>" . $rec['name'] . "</td>";
	//入库信息
	$sql = "SELECT SUM(total) AS total FROM material_log WHERE flag='IN' AND material_code ='" . $material_code . "'";
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	$materialIn = $rec['total'];
	//出库信息
	$sql = "SELECT SUM(total) AS total FROM material_log WHERE flag='OUT' AND material_code ='" . $material_code . "'";
	$result = WHDBmysql_query($sql);
	$rec = mysqli_fetch_array($result);
	$materialOut = $rec['total'];
	//打印、合计等
	$total = $materialIn - $materialOut;
	echo "<td>" . $total . "</td>";
	echo "<td>" . $materialUnit . "</td>";
	echo "</tr>";
	mysqli_free_result($result);
	return $total*$materialPrice;
}

function print_table_foot($total){
	echo "<tr>";
	echo "<td>价值估算</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td>" . $total . "</td>";
	echo "</tr>";
}
?>

<div id="search">
<form  action="<?php echo $BASE_DIR . "material/material.php"; ?>" method="post">

<select name="material_code">
<?php
   $catsql = "SELECT * FROM material where active='YES' order by code;";
   $catres = WHDBmysql_query($catsql);
   echo "<option value='99999' selected>'生产资料品种'</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
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
?>
