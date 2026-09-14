<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/account_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	//写入新纪录
	$sql = "INSERT INTO account (date, subject, amount, comm, mname, mtime) 
		VALUES( '" . $_POST['date'] . "',
		'" .$_POST['subject']  ."', 
		'" .$_POST['amount']  ."',
		'" .$_POST['comm']  ."',
		'" . $_SESSION['WHOAMI'] . "', 
		'" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "account/account.php");
}
else{
	require("../public/header.php");
}
?>

<div id='title'>增加账户记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "account/account_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

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
<?php
echo "<select name='subject'>";
foreach ($ACCOUNT_SUBJECT as $li) {
	echo "<option value='" . $li . "'>" . $li . "</option>";
}
echo "</select>";
?>
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
<td><textarea  name="comm" rows="10" cols="50"></textarea></td>
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