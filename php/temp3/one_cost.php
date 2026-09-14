<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_cost.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>查询销售信息</div>";
echo "<div id='right'>";
	
if(isset($_POST['submit'])){
	$sql = make_sql($_POST['one_cat'],$_POST['startdate'],$_POST['enddate']);
	print_title($_POST['startdate'],$_POST['enddate'],$_POST['one_cat']);
}
 else{
	$one_cat = "99999";
	$startd = date('Y-m-d',monthFirstDate(time())); 
	$endd = date('Y-m-d',time());
	$sql = make_sql($one_cat,$startd,$endd);
	print_title($startd,$endd,$one_cat);
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

function print_title($startd,$endd,$one_to){
    echo "<span style='font-size:25px'>查询条件：";
	echo "分类：" . $one_cat;
	echo "     从" . $startd;
	echo "到". $endd;
	echo "</span><br />";
}

function make_sql($one_cat,$startd,$endd){
	if( $one_cat == "99999" ){
		$sqlmid = "";
	} 
	else{
		$sqlmid = " AND one_cat = '" . $one_cat."'";
	}
	$sql = "SELECT * FROM one_cost where one_date>= '" . $startd . "'
		 AND one_date <= '" . $endd . "'" .$sqlmid .
		" ORDER BY one_date DESC;";
	return $sql;
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>
	<th>日期</th>
    <th>分类</th>
    <th>金额</th>
	<th>备注</th>
	<th>修改</th>
	</tr>";
}

function print_table_body($result){
	$total = 0;
	while($recrow = mysqli_fetch_assoc($result)){
		echo "<tr>";
		echo "<td>" . date('Y-m-d',strtotime($recrow['one_date'])) . "</td>";
		echo "<td>" . $recrow['one_cat'] . "</td>";
		echo "<td>" . $recrow['one_amount'] . "</td>";
		echo "<td>" . $recrow['one_comm'] . "</td>";
		echo "<td>" . "<a href='one_out_delete.php?id=" . $recrow['id'] . "'>删除" ."</td>";
		echo "</tr>";
        $total = $total+$recrow['one_amount'];
	}
	return $total;
}
    
function print_table_foot($total){
	echo "<tr>";
	echo "<td>合计</td>";
    echo "<td></td>";
    echo "<td>" . $total . "</td>";
    echo "<td></td>";
    echo "</tr>";
	echo "</table>";
}
?>

<div id="new">
	<a href='one_cost_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "one/one_cost.php"; ?>" method="post">

<select name="one_cat">
<option value='99999' selected>'分类'</option>
<option value='人力资源' >'人力资源'</option>
<option value='包装材料' >'包装材料'</option>
<option value='快递费' >'快递费'</option>
<option value='办公用品' >'办公用品'</option>
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
