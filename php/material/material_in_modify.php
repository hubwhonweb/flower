<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("material/material_in_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber()==FALSE){
	header("Location: " . $BASE_DIR . "material/material_in.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE material_log SET  material_code = '" .$_POST['material_code']  ."', 
	total = '" .$_POST['total']  ."',
	who = '" .$_POST['who']  ."',
	date = '" .$_POST['date']  ."',
	comm = '" .$_POST['comm']  ."',
	mname = '" . $_SESSION['WHOAMI'] . "', 
	mtime = '" . date('Y-m-d h:i:sa',time()) . "' 
	WHERE id = " . $id . ";";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "material/material_in.php");
}
else{
	$sql = "SELECT * FROM material_log 
		WHERE id = '" . $id ."';";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "material/material_in.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
	mysqli_free_result($result);
}

?>

<div id='righttop'>修改入库信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "material/material_in_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputform">

<tr>
<td>生产资料名称</td>
<td>
<select name="material_code">
<?php
   $catsql = "SELECT * FROM material WHERE active = 'YES' order by code;";
   $catres = WHDBmysql_query($catsql);
   
    while($catrow = mysqli_fetch_assoc($catres)){

        if($catrow['code'] == $recrow['material_code']){
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
<td>经手人</td>
<td>
<select name="who">
<?php
   echo "<option value='" . $recrow['who'] . "'>" . $recrow['who']. "</option>";
   $sql = "SELECT * FROM staff WHERE active='YES' ORDER BY name_pinyin;";
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
<td>日期</td>
<td>
<?php 
echo "<input type='date' name='date' value='" . date('Y-m-d',strtotime($recrow['date'])) . "'/>"; 
?>
</td>
</tr>

<tr>
<td>数量</td>
<td>
<?php echo "<input type='number' name='total' value='" . $recrow['total'] . "'/>"; ?>
</td>
</tr>

<tr>
<td>说明</td>
<td><textarea class="inputtext" name="comm" rows="5" cols="40"><?php echo $recrow['comm'];?></textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="button" value="修改"></td>
</tr>
</table>

</form>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>