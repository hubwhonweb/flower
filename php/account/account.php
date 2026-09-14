<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/account.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");
echo "<div id='title'>账户信息查询</div><div id='right'>";
//如果提交搜索做如下处理
if(isset($_POST['submit'])){
	$startd = $_POST['startdate'];
	$endd = $_POST['enddate'];
	$subject = $_POST['subject'];
	if( $subject == "99999"){
		$msql = "'";
	}
	else{
		$msql = "' AND subject='" . $subject . "'";
	}
	$sql = "SELECT  * FROM account  WHERE date >= '" . $startd . "' AND date <= '" . $endd . $msql." ORDER BY date DESC LIMIT " . $GLOBALS['DB_MAX'] .";";
	print_title($subject,$startd,$endd);
 	$result = WHDBmysql_query($sql);
 	$num = mysqli_num_rows($result);
	// 打印表格
	if( $num == 0 ) {
		echo "账户信息中没有记录";
	}
	else{
		print_table_head();
		print_table_body($result);
	}
}
else{
	//在支出账户表中查询信息
	$startd = date('Y-m-d',yearFirstDate(time()));
	$endd = date('Y-m-d',time());
	$sql = "SELECT  * FROM account  WHERE date >= '" . $startd . "' AND date <= '" . $endd . "' ORDER BY date DESC LIMIT " . $GLOBALS['DB_MAX'] .";";
	print_title("99999",$startd,$endd);
	$result = WHDBmysql_query($sql);
	$num = mysqli_num_rows($result);
	// 打印表格
	if( $num == 0 ) {
		echo "账户信息中没有记录";
	}
	else{
		print_table_head();
		print_table_body($result);
	}
}
echo "</div>";
mysqli_free_result($result);

function print_title($subject,$startd,$endd){
	echo "<span style='font-size:25px'>查询条件：";
	if( $subject == "99999" ){
		echo "所有分类。";
	}
	else{
		echo $subject."类。";
	}
	echo "从:" . $startd;
	echo "到:". $endd;
	echo "</span><br />";
}

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo " <th>记账日期</th>";
	echo " <th>项目</th>";
	echo " <th>金额</th>";
	echo " <th>说明</th>";
	echo " <th>修改人</th>";
	echo " <th>修改时间</th>";
	echo " <th>操作</th>";
	echo "</tr>";
}
function print_table_body($result){
	$tt = 0;
	while( $rec = mysqli_fetch_assoc($result)  ) {
	echo "<tr>";
	echo "<td>" . $rec['date'] . "</td>";
	echo "<td>" . $rec['subject'] . "</td>";
	echo "<td>" . $rec['amount'] . "</td>";
	echo "<td>" . $rec['comm'] . "</td>";
	echo "<td>" . $rec['mname'] . "</td>";
	echo "<td>" . $rec['mtime'] . "</td>";
	echo "<td>" ."<a href='account_modify.php?id=" . $rec['id'] . "'>修改</td>";
	echo "</tr>";
	$tt = $tt + $rec['amount'];
	} 
	echo "<tr>";
	echo "<td</td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><span style='color:red' >" . number_format($tt,2) . "</span></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";
	echo "</table>";
}
?>


<div id="new">
	<a href='account_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>

<div id="search">
<form  action="<?php echo $BASE_DIR . "account/account.php"; ?>" method="post">
<select name="subject">
<option value="99999">全部分类</option>
<?php
foreach ($ACCOUNT_SUBJECT as $li) {
	echo "<option value='" . $li . "'>" . $li . "</option>";
}
?>
</select>
<?php
//查询的时间条件，起始时间为年初，结束时间为当日
echo "从<input type='date' name='startdate' value='" . date('Y-m-d',yearFirstDate(time())) . "'/>"; 
echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>"; 
?>
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>