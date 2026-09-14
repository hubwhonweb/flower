<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("task/staff_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "task/staff.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE staff SET  
		name = '" .$_POST['name']  ."', 
		tel = '" .$_POST['tel']  ."',
		id_no = '" .$_POST['id_no']  ."',
		department = '" .$_POST['department']  ."', 
		title = '" .$_POST['title']  ."', 
		mname = '" . $_SESSION['WHOAMI'] . "',
		mtime = '" . date('Y-m-d h:i:sa',time()) . "' WHERE id = " . $id . ";";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "task/staff.php");
}
else{
	$sql = "SELECT * FROM staff WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "task/staff.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
require("../public/header.php");
}
?>

<div id='title'>修改人员信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "task/staff_modify.php?id=" . $id; ?>"
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">

<tr>
<td>姓名：</td>
<td>
<?php
echo "<input type='text' name='name' value='" . $recrow['name'] . "'/>";
?>
</td>
</tr>
<tr>
<td>身份证号：</td>
<td>
<?php
echo "<input type='text' name='id_no' value='" . $recrow['id_no'] . "'/>";
?>
</td>
</tr>

<tr>
<td>联系电话：</td>
<td>
<?php
echo "<input type='text' name='tel' value='" . $recrow['tel'] . "'/>";
?>
</td>
</tr>

<tr>
<td>工作部门：</td>
<td>
<?php
echo "<input type='text' name='department' value='" . $recrow['department'] . "'/>";
?>
</td>
<td>职务：</td>
<td>
<?php
echo "<input type='text' name='title' value='" . $recrow['title'] . "'/>";
?>
</td>
</tr>

<tr>
<td><input type="submit" name="submit" id="sub" value="修改"></td>
</tr>
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
