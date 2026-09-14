<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_in_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
        $sql = "INSERT INTO one_in (one_date, one_name,one_number,one_price,one_amount)
		VALUES( '" . $_POST['one_date'] . "',
		'" .$_POST['one_name']  ."',
		'" .$_POST['one_number']  ."',
		'" .$_POST['one_price']  ."',
        '" .$_POST['one_price']*$_POST['one_number']  ."');";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "one/one_in.php");
    }
    else{
         require("../public/header.php");
    }
?>
<div id='title'>采购登记</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "one/one_in_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>日期</td>
<td>
<?php
echo "<input type='date' name='one_date' value='" . date('Y-m-d',time()) . "'/>";
?>
</td>
</tr>

<tr>
<td>名称</td>
<td>
<select name="one_name">
<?php
   $catsql = "SELECT * FROM one order by one_name;";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['one_name'] . "'>" . $catrow['one_name'] . $catrow['unit']."</option>";
    }
	mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>数量</td>
<td>
<input type="number" name="one_number" value="" />
</td>
</tr>

<tr>
<td>价格</td>
<td>
<input type="number" step="0.01" name="one_price" value="" />
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="入库"></td>
</tr>
</table>
</form>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>
