<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/cost_category_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	$sql = "INSERT INTO cost_category (item, category, name, comm, active,mname, mtime) 
			VALUES( '" . $_POST['item'] . 
				"','" . $_POST['category'] . 
				"','" . $_POST['name'] . 
				"','" .$_POST['comm']  .
				"', 'YES" .
				"', '" . $_SESSION['WHOAMI'] . 
				"', '" . date('Y-m-d h:i:sa',time()) . "');";
	
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/cost_category.php");
}
else{
	require("../public/header.php");
}
?>
<div id='title'>增加支出类型</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/cost_category_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return costCategoryDataCheck();">
<table class="inputtable">

<tr>
<td>科目:</td>
<td>
<input type="number" name="item" id="item" onblur="costCategoryItemAjax(this.value)">
<span id="itemmsg" style="color:red">*</span>
</td>
</tr>

<tr>
<td>类别:</td>
<td>
<?php
echo "<select name='category'>";
foreach ($COST_CAT as $cat) {
	echo "<option value='" . $cat . "'>" . $cat . "</option>";
}
echo "</select>";
?>
</td>
</tr>

<tr>
<td>名称:</td>
<td>
<input type="text" name="name" id="name">
</td>
</tr>

<tr>
<td>描述:</td>
<td><textarea  name="comm" rows="4" cols="40"></textarea></td>
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