<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/lost_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "sale/menu.php");
}
else{
	$id = $_GET['id'];
}


if(isset($_POST['submit'])){
	$sql = "UPDATE lost SET  date = '" .$_POST['date']  ."', 
	amount = '" .$_POST['amount']  ."',
	product_code = '" .$_POST['product_code'] ."',
	agent_code = '" .$_POST['agent_code']  ."',
	comm = '" .$_POST['comm']  ."',
	mname = '" . $_SESSION['WHOAMI'] . "', 
	mtime = '" . date('Y-m-d h:i:sa',time()) . "' 
	WHERE id = " . $id . ";";
	whDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "sale/lost_confirm.php");
}
else{
	$sql = "SELECT * FROM lost WHERE id = '" . $id ."';";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "sale/lost_confirm.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
}
mysqli_free_result($result);
?>

<div id='title'>修改报损信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "sale/lost_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>日期</td>
<td>
<?php 
echo "<input type='date' name='date' value='" . $recrow['date'] . "'/>"; 
?>
</td>
</tr>

<tr>
<td>产品</td>
<td>
<select name="product_code">
<?php
echo "<option value='" . $recrow['product_code'] . "' selected>" . getProductName($recrow['product_code']) . "</option>";
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
   $catsql = "SELECT * FROM agent where active='YES' order by name_pinyin;";
   $catres = WHDBmysql_query($catsql);
   
    while($catrow = mysqli_fetch_assoc($catres)){
        if($catrow['code'] == $recrow['agent_code']){
            echo "<option value='" . $catrow['code'] . "' selected>" . $catrow['name'] . "</option>";
        }else{
	       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
        }
    }
	mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>金额</td>
<td>
<?php echo "<input type='number' name='amount' value='" . $recrow['amount'] . "'/>"; ?>
</td>
</tr>

<tr>
<td>说明</td>
<td><textarea class="inputtext" name="comm" rows="5" cols="40"><?php echo $recrow['comm'];?></textarea></td>
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