<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/cost.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

require("../public/header.php");
echo "<div id='title'>查询支出记录</div><div id='right'>";
// 提交查询表单后处理
if(isset($_POST['submit'])){
	 //为查询各个变量赋值
	 $wCategory = $_POST['category'];
	 $wName = $_POST['name'];
	 $wStartDate = $_POST['startdate'];
	 $wEndDate = $_POST['enddate'];
	 //print query title
	 print_title($wCategory,$wName,$wStartDate,$wEndDate);
	 $sql = make_sql($wCategory,$wName,$wStartDate,$wEndDate);
	 $result = WHDBmysql_query($sql);
	 $numrow = mysqli_num_rows($result);
	 if($numrow == 0) {
 		echo "没有记录";	 
 	}
	else{
		print_table_head();
		print_table_body($result);
	}
 }
 //没有提交查询表单的缺省处理
else{
	//为查询各个变量赋值
	 $wCategory = "99999";
	 $wName = "99999";
	 $wStartDate = date('Y-m-d',monthFirstDate(time()));
	 $wEndDate = date('Y-m-d',time());
	 print_title($wCategory,$wName,$wStartDate,$wEndDate);
	 $sql = make_sql($wCategory,$wName,$wStartDate,$wEndDate);
	 //生成查询的条件语句
	 $result = WHDBmysql_query($sql);
	 $numrow = mysqli_num_rows($result);
	 if($numrow == 0) {
 		echo "没有记录";	 
 	}
	else{
		print_table_head();
		print_table_body($result);
	}
 }
mysqli_free_result($result);
echo "</div>";

function make_sql($wCategory,$wName,$wStartDate,$wEndDate){
	 $w_sql = " WHERE confirm='已复核' AND date >='" . $wStartDate . "' AND date <='" . $wEndDate . "'";
	 if($wCategory != "99999"){
		 $w_sql = $w_sql . " AND category='" . $wCategory . "'";
	 }
	 if($wName != "99999"){
		 $w_sql = $w_sql . " AND name='" . $wName . "'"; 
	 }
	 //生成查询语句
	 $sql = "SELECT * FROM cost " . $w_sql . " ORDER BY date DESC;";
	return $sql; 
}

function print_title($cat1,$cat2,$startd,$endd){
	echo "<span style='font-size:25px'>查询条件：";
	if($cat1=="99999"){
		echo "大类=全部";
	}
	else{
		echo "大类=" . $cat1;
	}
	if($cat2=="99999"){
		echo "，小类=全部";
	}
	else{
		echo "，小类=" . $cat2;
	}
	echo "，从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
}
function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>科目</th>
	<th>分类</th>
	<th>子项</th>
	<th>金额</th>
	<th>说明</th>
	<th>经手人</th>
	<th>状态</th>
	<th>修改人</th>
	<th>修改</th>
	</tr>";
}
function print_table_body($result){
	$tt = 0;
	while($recrow = mysqli_fetch_assoc($result)){
	echo"<tr>
	<td>" . $recrow['date'] . "</td>
	<td>" . $recrow['item'] . "</td>
	<td>" . $recrow['category'] . "</td>
	<td>" . $recrow['name'] . "</td>
	<td>" . $recrow['amount'] . "</td>
	<td>" . $recrow['comm'] . "</td>
	<td>" . $recrow['handler'] . "</td>
	<td>" . $recrow['confirm'] . "</td>
	<td>" . $recrow['mname'] . "</td>
	<td>" . "<a href='cost_modify.php?id=" . $recrow['id'] . "'>修改" ."</td>
	</tr>";
	$tt = $tt + $recrow['amount'];
	}
	echo"<tr><td></td><td></td><td></td><td></td>";
	echo "<td><span style='color:red' >" . $tt . "</span></td>";
	echo "<td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	echo "</table>";
}
?>

<div id="new">
	<a href='cost_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "account/cost.php"; ?>" method="post">
分类<select name="category">
<?php
/*
echo "<option value='99999' selected>'全部'</option>";
foreach ($COST_CAT as $key ) {
		echo "<option value='" . $key . "'>" . $key . "</option>";
}
*/
$catsql = "SELECT distinct(category) as cat FROM cost_category;";

$catres = WHDBmysql_query($catsql);
echo "<option value='99999' selected>'全部'</option>";
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['cat'] . "'>" . $catrow['cat'] . "</option>";
}
mysqli_free_result($result);
?>
</select>
子项<select name="name">
<?php
$catsql = "SELECT * FROM cost_category where active='YES' order by item;";
$catres = WHDBmysql_query($catsql);
echo "<option value='99999' selected>'全部'</option>";
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['name'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($result);
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