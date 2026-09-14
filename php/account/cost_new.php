<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/cost_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	//在支出种类表中查找大类
	$sql = "SELECT * FROM cost_category WHERE item='" . $_POST['item'] . "';";
	$res = WHDBmysql_query($sql);
	$resrow = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	//写入新纪录
	$sql = "INSERT INTO cost (date, item, category, name, amount, comm, handler,confirm, mname, mtime) 
		VALUES( '" . $_POST['date'] . "',
		'" .$_POST['item']  ."', 
		'" .$resrow['category']  ."', 
		'" .$resrow['name']  ."', 
		'" .$_POST['amount']  ."',
		'" .$_POST['comm']  ."',
		'" .$_POST['handler']  ."',
		'未复核',
		'" . $_SESSION['WHOAMI'] . "', 
		'" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "account/cost_confirm.php");
}
else{
	require("../public/header.php");
}
?>
<div id='title'>增加支出记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "account/cost_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtbale">

<tr>
<td>发生日期</td>
<td>
<?php
echo "<input type='date' name='date' value='" . date('Y-m-d',time()) . "'/>"; 
?>
</td>
</tr>

<tr>
<td>项目</td>
<td>
<select name="item">
<?php
$catsql = "SELECT * FROM cost_category where active = 'YES' order by item ;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['item'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>金额</td>
<td>
<input type="number" step="0.01"  name="amount">
</td>
</tr>


<tr>
<td>备注</td>
<td><textarea  name="comm" rows="3" cols="50"></textarea></td>
</tr>

<tr>
<td>经手人</td>
<td>
<select name="handler">
<?php 
   $sql = "SELECT * FROM staff WHERE active='YES' ORDER BY name_pinyin DESC;";
   $res = WHDBmysql_query($sql);
   while($rec = mysqli_fetch_assoc($res)){
       echo "<option value='" . $rec['name'] . "'>" . $rec['name']. "</option>";
    }
	mysqli_free_result($resutl);
?>
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="增加"></td>
</tr>
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>