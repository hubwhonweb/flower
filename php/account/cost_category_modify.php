<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/cost_category_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(checkUrlNumber()==FALSE){
	header("Location: " . $BASE_DIR . "setup/cost_category.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE cost_category 
			SET comm = '" .$_POST['comm']  ."',
				mname = '" . $_SESSION['WHOAMI'] . "', 
				mtime = '" . date('Y-m-d h:i:sa',time()) . "',  
				name ='" . $_POST['name']. "', 
				active ='" . $_POST['active']. "', 
				category ='" . $_POST['category']. "' WHERE id = " . $id . ";";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/cost_category.php");
}
else{
	$sql = "SELECT * FROM cost_category WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
    if($numrow == 0){
		header("Location: " . $BASE_DIR . "setup/cost_category.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
require("../public/header.php");
}
?>

<div id='title'>修改支出项目分类</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/cost_category_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return costCategoryDataCheckModify();">
<table class="inputtable">

<tr>
<td>科目:</td>
<td>
<?php 
echo $recrow['item'];
?>
</td>
</tr>

<tr>
<td>类别：</td>
<td>
<select name="category">
<?php
	echo "<option value='" . $recrow['category'] . "' selected >" . $recrow['category'] . "</option>";
	foreach ($COST_CAT as $cat) {
		echo "<option value='" . $cat . "'>" . $cat . "</option>";
	}
?>
</select>
</td>
</tr>

<tr>
<td>名称:</td>
<td>
<?php 
echo "<input type='text' name='name' id='name' value='" . $recrow['name'] . "'>";
?>
</td>
</tr>
<tr>
<td>描述:</td>
<td><textarea class="inputtext" name="comm" rows="4" cols="50"><?php echo $recrow['comm'];?></textarea></td>
</tr>
<tr>
<td>状态：</td>
<td>
<select name="active">
<?php
if ($recrow['active']=="YES") echo "<option value='YES' selected>YES</option>";
else echo "<option value='NO' selected>NO</option>";
echo "<option value='YES'>YES</option>";
echo "<option value='NO'>NO</option>";
?>
</select>
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