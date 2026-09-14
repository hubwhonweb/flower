<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/lost_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	//insert into losts table
	$sql = "INSERT INTO lost (date, product_code,agent_code, amount, comm, flag, mname, mtime) 
	VALUES( '" . $_POST['date'] . "',
	'" .$_POST['product_code']  ."', 
	'" .$_POST['agent_code']  ."', 
	'" .$_POST['amount']  ."',
	'" .$_POST['comm']  ."', 
	'未复核', 
	'" . $_SESSION['WHOAMI'] . "', '" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	//relocation to lost.php
	header("Location: " . $BASE_DIR . "sale/lost_confirm.php");
    }
else{
    require("../public/header.php");
}
?>

<div id='title'>增加报损少付</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "sale/lost_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>日期</td>
<td>
<?php
echo "<input type='date' name='date' value='" . date('Y-m-d',time()) . "'/>"; 
?>
</td>
</tr>

<tr>
<td>产品</td>
<td>
<select name="product_code">
<?php
   $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>经销商</td>
<td>
<select name="agent_code">
<?php
   $catsql = "SELECT * FROM agent WHERE active = 'YES' order by order_date DESC;";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>金额</td>
<td>
<input type="number" name="amount" value="" />
</td>
</tr>


<tr>
<td>备注</td>
<td><textarea  name="comm" rows="5" cols="40"></textarea></td>
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