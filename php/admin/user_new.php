<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/user_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	$passwordMd5 = md5($_POST['password']);
    $sql = "INSERT INTO user (name, password, active, comm,mname, mtime) 
			VALUES( '" . $_POST['name'] . "',
				'" .$passwordMd5  ."', 
				'" .$_POST['active']  ."',
				'" .$_POST['comm']  ."',
				'" . $_SESSION['WHOAMI'] . "', 
				'" . date('Y-m-d H:i:s',time()) . "');";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "admin/user.php");
    }
    else{
         require("../public/header.php");
    }
?>

<div id='title'>增加用户</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "admin/user_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>用户名：</td>
<td>
<input type="text" name="name" value="" />
</td>
</tr>

<tr>
<td>密码：</td>
<td>
<input type="text" name="password" value="" />
</td>
</tr>

<tr>
<td>活动状态：</td>
<td>
<select name="active">
<?php
echo "<option value='YES'>YES</option>";
echo "<option value='NO'>NO</option>";
?>
</select>
</td>
</tr>
<tr>
<td>备注：</td>
<td>
<input type="text" name="comm" value=""/>
</td>
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
