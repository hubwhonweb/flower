<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("account/invoice_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	//写入新纪录
	$sql = "INSERT INTO invoice (pay_date, amount, subject, flag) 
		VALUES( '" . $_POST['pay_date'] . "',
		'" .$_POST['amount']  ."', 
		'" .$_POST['subject']  ."',
		'未取得发票');";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "account/invoice.php");
}
else{
	require("../public/header.php");
}
?>

<div id='title'>新增付款</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "account/invoice_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>付款日期</td>
<td>
<?php
echo "<input type='date' name='pay_date' value='" . date('Y-m-d',time()) . "'/>"; 
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
<td>项目</td>
<td><textarea  name="subject" rows="3" cols="30"></textarea></td>
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