<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/user_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlnumber() == FALSE){
	header("Location: " . $BASE_DIR . "admin/user.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$passwordMd5 = md5($_POST['password']);
	$sql = "UPDATE user SET  
		name = '" .$_POST['name']  ."',
		password = '" .$passwordMd5  ."',
		active = '" .$_POST['active']  ."',
		comm = '" .$_POST['comm']  ."',
		mname = '" . $_SESSION['WHOAMI'] . "', 
		mtime = '" . date('Y-m-d H:i:s a',time()) . "'
		WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "admin/user.php");
}
else{
	$sql = "SELECT * FROM user
		WHERE id = " . $id .";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "admin/user.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
    require("../public/header.php");
}

?>

<div id='title'>修改用户信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "admin/user_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputform">

<tr>
<td>用户名:</td>
<td>
<?php 
echo "<input type='text' name='name' value='" . $recrow['name'] . "'>";
?>
</td>
</tr>

<tr>
<td>密码：</td>
<td>
<?php 
echo "<input type='text' name='password' value='" . $recrow['password'] . "'>";
?>
</td>
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
<td>备注:</td>
<td>
<?php 
echo "<input type='text' name='comm' value='" . $recrow['comm'] . "'>";
?>
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