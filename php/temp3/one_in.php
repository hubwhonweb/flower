<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_in.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>采购信息</div>";
echo "<div id='right'>";
	
if(isset($_POST['submit'])){
	$sql = make_sql($_POST['one_name'],$_POST['startdate'],$_POST['enddate']);
	print_title($_POST['startdate'],$_POST['enddate'],$_POST['one_name']);
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

function print_title($startd,$endd,$one_name){
	$sql = "SELECT * from one WHERE one_name = '" . $one_name . "'";
	$result = WHDBmysql_query($sql);
	$recrow = mysqli_fetch_assoc($result);
	echo "<span style='font-size:25px'>查询条件：";
	echo "产品：" . $recrow['one_name'];
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
	mysqli_free_result($result);
}

function make_sql($one_name,$startd,$endd){
	if( $one_name == "99999" ){
		$sqlmid = "";
	} 
	else{
		$sqlmid = " AND one_name = '" . $one_name."'";
	}
	$sql = "SELECT * FROM one_in
		 WHERE one_date >= '" . $startd . "' AND one_date <= '" . $endd . "'" .$sqlmid .
		" ORDER BY one_date DESC;";
	return $sql;
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
	<th>名称</th>
	<th>数量</th>
	<th>单价</th>
	<th>合计</th>
	</tr>";
}

function print_table_body($result){
	$total = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . $recrow['one_date'] . "</td>";
		echo "<td>" . $recrow['one_name'] . "</td>";
		echo "<td>" . $recrow['one_number'] . "</td>";
		echo "<td>" . $recrow['one_price'] . "</td>";
        echo "<td>" . $recrow['one_amount']. "</td>";
		echo "</tr>";
		$total = $total + $recrow['one_amount'];
	}
	return $total;
}
function print_table_foot($total){
	echo "<tr>";
	echo "<td></td>";
	echo "<td>合计</td>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td>" . $total . "</td>";
	echo "</tr>";
	echo "</table>";
}
?>

<div id="new">
		<a href='one_in_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "one/one_in.php"; ?>" method="post">

<select name="one_name">
<?php
   $catsql = "SELECT * FROM one;";
   $catres = WHDBmysql_query($catsql);
   echo "<option value='99999' selected>'产品'</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['one_name'] . "'>" . $catrow['one_name']. "</option>";
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
