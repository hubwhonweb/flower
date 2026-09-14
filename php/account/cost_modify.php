<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/cost_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "account/menu.php");
}
else{
	$id = $_GET['id'];
}


//提交了确认按钮，进行数据库修改
if(isset($_POST['submit'])){
	//根据输入的项目名称，找到对应的大类
	$sql = "SELECT * FROM cost_category WHERE name='" . $_POST['name'] . "';";
	$res = WHDBmysql_query($sql);
	$resrow = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	//修改记录
    $sql = "UPDATE cost 
		SET amount = '" . $_POST['amount'] ."' , 
		date = '" . $_POST['date'] ."' , 
		name = '" . $_POST['name'] ."' , 
		item = '" . $resrow['item'] ."' , 
		category = '" . $resrow['category'] ."' ,
		comm = '" . $_POST['comm'] ."' , 
		handler = '" . $_POST['handler'] ."' ,
		mname = '" . $_SESSION['WHOAMI'] . "', 
		mtime = '" . date('Y-m-d h:i:sa',time()) . "' WHERE id = " . $id . ";";
    WHDBmysql_query($sql);
    header("Location: " . $BASE_DIR . "account/cost_confirm.php");
}
//没有确认提交按钮，从数据库中找到指定记录
else{
	$sql = "SELECT * FROM cost WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "account/cost_confirm.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
    require("../public/header.php");
	mysqli_free_result($result);
}
?>

<div id='title'>支出记录修改</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "account/cost_modify.php?id=" . $id; ?>" 
	method="post" 
	onkeydown ="if(event.keyCode==13) return false;" 
>
<table class="inputtable">

<tr>
<td>发生日期</td>
<td>
<?php
echo "<input type='date' name='date' value='" . date('Y-m-d',strtotime($recrow['date'])) . "'/>"; 
?>
</td>
</tr>

<tr>
<td>项目</td>
<td>
<select name="name">
<?php
$catsql = "SELECT * FROM cost_category order by item;";
$catres = WHDBmysql_query($catsql);
echo "<option value='" . $recrow['name'] . "' selected>" . $recrow['name'] . "</option>";
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['name'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($result);
?>
</select>
</td>
</tr>

<tr>
<td>金额</td>
<td>
<?php
echo "<input type='number' step='0.01' name='amount' value='" . $recrow['amount'] . "'/>"; 
?>
</td>
</tr>


<tr>
<td>备注</td>
<td>
<textarea class="inputtext" name="comm" rows="10" cols="50"><?php echo $recrow['comm'];?></textarea>
</td>
</tr>

<tr>
<td>经手人</td>
<td>
<select name="handler">
<?php
   echo "<option value='" . $recrow['handler'] . "'>" . $recrow['handler']. "</option>";
   $sql = "SELECT * FROM staff WHERE active='YES' ORDER BY name_pinyin DESC;";
   $res = WHDBmysql_query($sql);
   while($rec = mysqli_fetch_assoc($res)){
       echo "<option value='" . $rec['name'] . "'>" . $rec['name']. "</option>";
    }
	mysqli_free_result($res);
?>
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="修改"></td>
</tr>

</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>