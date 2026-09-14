<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/account_modify.php") == FALSE){
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
        $sql = "UPDATE account 
		SET amount = '" . $_POST['amount'] ."' , 
		date = '" . $_POST['date'] ."' , 
		subject = '" . $_POST['subject'] ."' , 
		comm = '" . $_POST['comm'] ."' , 
		mname = '" . $_SESSION['WHOAMI'] . "', 
		mtime = '" . date('Y-m-d h:i:sa',time()) . "' WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "account/account.php");
}
//没有确认提交按钮，从数据库中找到指定记录
else{
	$sql = "SELECT * FROM account WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "account/account.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
	mysqli_free_result($result);
}
?>

<div id='title'>修改账户记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "account/account_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
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
<?php
echo "<select name='subject'>";
echo "<option value='" . $recrow['subject'] . "' >" . $recrow['subject'] . "</option>";
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
<?php echo "<input type='number' step='0.01' name='amount' value='" . $recrow['amount'] . "'/>"; ?>
</td>
</tr>

<tr>
<td>备注</td>
<td>
<textarea  name="comm" rows="10" cols="50"><?php echo $recrow['comm']; ?></textarea>
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