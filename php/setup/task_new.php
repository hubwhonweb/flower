<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/task_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
        $sql = "INSERT INTO task 
        (task_code,task_name, task_group,task_unit,task_number task_price,task_describe, mname, mtime)
         VALUES( '" . $_POST['task_code'] . "',
         '" . $_POST['task_name'] . "',
         '" . $_POST['task_group'] . "',
         '" . $_POST['task_unit'] . "',
         '" . $_POST['task_number'] . "',
         '" .$_POST['task_price']  ."', 
         '" .$_POST['task_describe']  ."', 
         '" . $_SESSION['WHOAMI'] . "', 
         '" . date('Y-m-d h:i:sa',time()) . "');";
        WHDBmysql_query($sql);
		header("Location: " . $BASE_DIR . "setup/task.php");
    }
    else{
         require("../public/header.php");
    }
?>
<div id='title'>增加工作任务</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/task_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">
<tr>
<td>编码：</td>
<td>
<input type="text" name="task_code" value="" />
</td>
</tr>
<tr>
<td>名称：</td>
<td>
<input type="text" name="task_name" value="" />
</td>
</tr>
<tr>
<td>组名：</td>
<td>
<input type="text" name="task_group" value="" />
</td>
</tr>
<tr>
<td>最小单位</td>
<td>
<input type="text" name="task_unit" value="" />
</td>
</tr>
<tr>
<td>指标</td>
<td>
<input type="text" name="task_number" value="" />
</td>
</tr>

<tr>
<td>单价</td>
<td>
<input type="text" name="task_price" value="" />
</td>
</tr>

<tr>
<td>备注</td>
<td><textarea  name="task_describe" rows="4" cols="50"></textarea></td>
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
