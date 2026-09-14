<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/order_new_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}


if(isset($_POST['submit'])){
	//在经销商信息表中找到该经销商所在城市
	$sql = "SELECT * FROM agent WHERE code='" . $_POST['agent_code'] . "';";
	$res = WHDBmysql_query($sql);
	$resrow = mysqli_fetch_assoc($res);
	$city = $resrow['city'];
	mysqli_free_result($res);
	//生产订单号
	$sql = "SELECT * FROM user WHERE name='" . $_SESSION['WHOAMI'] . "';";
	$res = WHDBmysql_query($sql);
	$resrow = mysqli_fetch_assoc($res);
	$uid = $resrow['id'];//操作用户的id
	$whtime = getdate();
	$orderid = $uid . "-" . date('Ymd',time()) . "-" . $whtime['hours'] . $whtime['minutes'] . $whtime['seconds'];
	//标志位设置
	$flag = "新订单";
	//$flag = "已发货";
	//写入新纪录
	if($_POST['plants']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code'],$_POST['class'],$_POST['boxes'],$_POST['plantsinbox'],$_POST['plants'],$_POST['price'],$_POST['subtotal'],$_POST['comment'],$flag);
	}
	if($_POST['plants1']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code1'],$_POST['class1'],$_POST['boxes1'],$_POST['plantsinbox1'],$_POST['plants1'],$_POST['price1'],$_POST['subtotal1'],$_POST['comment1'],$flag);
		
	}
	if($_POST['plants2']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code2'],$_POST['class2'],$_POST['boxes2'],$_POST['plantsinbox2'],$_POST['plants2'],$_POST['price2'],$_POST['subtotal2'],$_POST['comment2'],$flag);
	}
	if($_POST['plants3']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code3'],$_POST['class3'],$_POST['boxes3'],$_POST['plantsinbox3'],$_POST['plants3'],$_POST['price3'],$_POST['subtotal3'],$_POST['comment3'],$flag);
	}
	if($_POST['plants4']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code4'],$_POST['class4'],$_POST['boxes4'],$_POST['plantsinbox4'],$_POST['plants4'],$_POST['price4'],$_POST['subtotal4'],$_POST['comment4'],$flag);
	}
	if($_POST['plants5']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code5'],$_POST['class5'],$_POST['boxes5'],$_POST['plantsinbox5'],$_POST['plants5'],$_POST['price5'],$_POST['subtotal5'],$_POST['comment5'],$flag);
	}
	if($_POST['plants6']!=0){
		insert_order_data($orderid,$_POST['date'],$_POST['agent_code'],$city,$_POST['product_code6'],$_POST['class6'],$_POST['boxes6'],$_POST['plantsinbox6'],$_POST['plants6'],$_POST['price6'],$_POST['subtotal6'],$_POST['comment6'],$flag);
	}
	
	header("Location: " . $BASE_DIR . "sale/order_new.php");
}
else{
	require("../public/header.php");
}

function insert_order_data($worderid,$wdate,$wagent_code,$wcity,$wproduct_code,$wclass,$wboxes,$wplantsinbox,$wplants,$wprice,$wsubtotal,$wcomment,$wflag)
{
	$sql = "INSERT INTO orders (order_id, date, agent_code,  city, product_code, class, boxes, plants_in_box, plants, price, subtotal, flag, comment, mname, mtime) 
	VALUES( '" . $worderid . "',
	'" .$wdate."', 
	'" .$wagent_code."', 
	'" .$wcity."',
	'" .$wproduct_code."',
	'" .$wclass."',
	'" .$wboxes."',
	'" .$wplantsinbox."',
	'" .$wplants."',
	'" .$wprice."',
	'" .$wsubtotal."',
	'" .$wflag."',
	'" .$wcomment."',
	'" .$_SESSION['WHOAMI'] . "', 
	'" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	//修改经销商信息销售时间和次数
	$sql ="update agent SET order_times = order_times +1, 
	order_date='" .date('Y-m-d',time()) ."' 
	where code ='" . $wagent_code."';";
	WHDBmysql_query($sql);
}
?>

<div id='title'>新订单</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "sale/order_new_new.php"; ?>" 
	  method="post" 
	  onkeydown ="if(event.keyCode==13) return false;"
>

<span id="whtext">发生日期：</span>
<?php
echo "<input type='date' name='date' value='" . date('Y-m-d',time()) . "'/>"; 
?>
<span id="whtext">经销商:</span>
<select name="agent_code">
<?php
$catsql = "SELECT * FROM agent WHERE active='YES' order by order_date DESC, name_pinyin;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['code'] . "'>" . $catrow['name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>

<table class="hovertable">
<tr>
<th>产品名</th>
<th>等级</th>
<th>箱数</th>
<th>每箱数量</th>
<th>盆数</th>
<th>单价</th>
<th>金额</th>
<th>备注</th>
</tr>

<tr>
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
</td>
<td>
<select name="class">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes" id="boxes" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox" id="plantsinbox" value="28">
</td>
<td>
<input type="number" name="plants" id="plants" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number" style="width:80px" step="0.01"  name="price" id="price" value="0">
</td>
<td>
<input type="number" step="0.01"  name="subtotal" style="color:red;width:80px" id="subtotal" value="0">
</td>
<td>
<input type="text" name="comment" value="">
</td>
</tr>
<tr>
<td>
<select name="product_code1">
<?php
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class1">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes1" id="boxes1" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox1" id="plantsinbox1" value="28">
</td>
<td>
<input type="number" name="plants1" id="plants1" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number" style="width:80px" step="0.01"  name="price1" id="price1" value="0">
</td>
<td>
<input type="number" step="0.01"  name="subtotal1" style="color:red;width:80px" id="subtotal1" value="0">
</td>
<td>
<input type="text" name="comment1" value="">
</td>
</tr>
<tr>
<td>
<select name="product_code2">
<?php
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class2">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes2" id="boxes2" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox2" id="plantsinbox2" value="28">
</td>
<td>
<input type="number"  name="plants2" id="plants2" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number"  style="width:80px" step="0.01"  name="price2" id="price2" value="0">
</td>
<td>
<input type="number"  step="0.01"  name="subtotal2" style="color:red;width:80px" id="subtotal2" value="0">
</td>

<td>
<input type="text" name="comment2" value="">
</td>
</tr>
<tr>
<td>
<select name="product_code3">
<?php
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class3">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes3" id="boxes3" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox3" id="plantsinbox3" value="28">
</td>
<td>
<input type="number" name="plants3" id="plants3" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number" style="width:80px" step="0.01"  name="price3" id="price3" value="0">
</td>
<td>
<input type="number" step="0.01"  name="subtotal3" style="color:red;width:80px" id="subtotal3" value="0">
</td>

<td>
<input type="text" name="comment3" value="">
</td>
</tr>
<tr>
<td>
<select name="product_code4">
<?php
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class4">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes4" id="boxes4" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox4" id="plantsinbox4" value="28">
</td>
<td>
<input type="number" name="plants4" id="plants4" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number" style="width:80px" step="0.01"  name="price4" id="price4" value="0">
</td>
<td>
<input type="number" step="0.01"  name="subtotal4" style="color:red;width:80px" id="subtotal4" value="0">
</td>

<td>
<input type="text" name="comment4" value="">
</td>
</tr>
<tr>
<td>
<select name="product_code5">
<?php
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class5">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes5" id="boxes5" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox5" id="plantsinbox5" value="28">
</td>
<td>
<input type="number" name="plants5" id="plants5" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number" style="width:80px" step="0.01"  name="price5" id="price5" value="0">
</td>
<td>
<input type="number" step="0.01"  name="subtotal5" style="color:red;width:80px" id="subtotal5" value="0">
</td>

<td>
<input type="text" name="comment5" value="">
</td>
</tr>
<tr>
<td>
<select name="product_code6">
<?php
 $catsql = "SELECT * FROM products WHERE active = '在产';";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['product_code'] . "'>" . $catrow['product_name'] . "</option>";
    }
	mysqli_free_result($catres);
?>
</td>
<td>
<select name="class6">
<?php
foreach ($CLASS as $lis) {
	echo "<option value='" . $lis . "'>" . $lis . "</option>";
}
?>
</select>
</td>
<td>
<input type="number" style="width:80px" name="boxes6" id="boxes6" value="0">
</td>
<td>
<input type="number" style="width:80px" name="plantsinbox6" id="plantsinbox6" value="28">
</td>
<td>
<input type="number" name="plants6" id="plants6" style="color:red;width:80px" value="0">
</td>
<td>
<input type="number" style="width:80px" step="0.01"  name="price6" id="price6" value="0">
</td>
<td>
<input type="number" step="0.01"  name="subtotal6" style="color:red;width:80px" id="subtotal6" value="0">
</td>

<td>
<input type="text" name="comment6" value="">
</td>
</tr>
<tr>
<td>
合计
</td>
<td>
</td>
<td>
<input type="number" name="boxes10" id="boxes10" style="color:red;width:80px" value="0">
</td>
<td>
</td>
<td>
</td>
<td>
</td>
<td>
<input type="number" step="0.01"  name="subtotal10" style="color:red;width:80px" id="subtotal10" value="0">
</td>
</tr>
</table>


<table class="inputform">
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