<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("material/material_in_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
        $sql = "INSERT INTO material_log (material_code, date, total, who, comm, flag, mname, mtime) 
		VALUES( '" . $_POST['material_code'] . "',
		'" .$_POST['date']  ."', 
		'" .$_POST['total']  ."',
		'" .$_POST['who']  ."',
		'" .$_POST['comm']  ."', 
		'IN', 
		'" . $_SESSION['WHOAMI'] . "', '" . date('Y-m-d h:i:sa',time()) . "');";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "material/material_in.php");
    }
    else{
         require("../public/header.php");
    }
?>
<div id='title'>增加库存</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "material/material_in_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputform">

<tr>
<td>日期</td>
<td>
<?php
echo "<input type='date' name='date' value='" . date('Y-m-d',time()) . "'/>"; 
?>
</td>
</tr>

<tr>
<td>名称</td>
<td>
<select name="material_code">
<?php
   $catsql = "SELECT * FROM material WHERE active = 'YES' order by code;";
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
<td>数量</td>
<td>
<input type="number" name="total" value="" />
</td>
</tr>

<tr>
<td>经手人</td>
<td>
<select name="who">
<?php 
   $sql = "SELECT * FROM staff WHERE active='YES' ORDER BY name_pinyin;";
   $res = WHDBmysql_query($sql);
   while($rec = mysqli_fetch_assoc($res)){
       echo "<option value='" . $rec['name'] . "'>" . $rec['name']. "</option>";
    }
	mysqli_free_result($res);
?>
</td>
</select>
</tr>

<tr>
<td>备注</td>
<td><textarea  name="comm" rows="5" cols="40"></textarea></td>
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
