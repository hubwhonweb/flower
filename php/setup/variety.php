<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/variety.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>查询品种信息</div>";

$sql = "SELECT * FROM variety ORDER BY active,variety_code ASC;";
if(isset($_POST['submit'])){
	 if($_POST['product_code']!="99999"){
		 $sql = "SELECT * FROM variety WHERE product_code='" .$_POST['product_code']. "' 
		 ORDER BY product_code,variety_code ASC;";
		}
}

$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);
echo "<div id='right'>";
if($numrow == 0) {
	echo "没有记录";	 
}
else{
	printTableHead();
	printTableBody($result);
}
echo "</div>";
?>

<div id="new">
	<a href='variety_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "setup/variety.php"; ?>" method="post">
	
<select name="product_code">
<?php
echo "<option value='99999'>全部产品</option>";
$catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
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

function printTableHead(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>产品名</th>
	<th>品种编号</th>
	<th>品种名称</th>
	<th>颜色</th>
	<th>厂商</th>
	<th>备注</th>
	<th>状态</th>
	<th>动作</th>
	</tr>";
}
function printTableBody($result){
	$i = 1;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . getProductName($recrow['product_code']) . "</td>";
		echo "<td>" . $recrow['variety_code'] . "</td>";
		echo "<td>" . $recrow['variety_name'] . "</td>";
		echo "<td>" . $recrow['color'] . "</td>";
		echo "<td>" . $recrow['supplier'] . "</td>";
		echo "<td>" . $recrow['comm'] . "</td>";
		echo "<td>" . $recrow['active'] . "</td>";
		echo "<td>" . "<a href='variety_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>";
		echo "</tr>";
		$i = $i+1;
	}
	echo "</table>";
}
?>