<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("admin/can_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
       $sql = "INSERT INTO can (user_name, function_code) 
			VALUES( '" . $_POST['user_name'] . "',
				'" .$_POST['function_code']  ."');";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "admin/can.php");
    }
    else{
         require("../public/header.php");
    }
?>
<div id='title'>增加权限设置</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "admin/can_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>用户名：</td>
<td>
<input type="text" name="user_name" value="" />
</td>
</tr>

<tr>
<td>功能代码:</td>
<td>
<input type="text" name="function_code" value="" />
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