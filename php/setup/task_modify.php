<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/task_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "setup/task.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
        $sql = "UPDATE task SET  
        task_code = '" .$_POST['task_code']  ."', 
        task_name = '" .$_POST['task_name']  ."',
        task_group = '" .$_POST['task_group']  ."',
        task_unit = '" .$_POST['task_unit']  ."', 
    task_number = '" .$_POST['task_number']  ."',
    task_price = '" .$_POST['task_price']  ."',
        task_describe = '" .$_POST['task_describe']  ."',
        mname = '" . $_SESSION['WHOAMI'] . "', 
        mtime = '" . date('Y-m-d h:i:sa',time()) . "' 
        WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
		header("Location: " . $BASE_DIR . "setup/task.php");
    }
    else{
        $sql = "SELECT * FROM task WHERE id = " . $id . ";";
        $result = WHDBmysql_query($sql);
        $numrow = mysqli_num_rows($result);
        if($numrow == 0){
			header("Location: " . $BASE_DIR . "setup/task.php");
		}
		else {
		$recrow = mysqli_fetch_assoc($result);
		}
	require("../public/header.php");
}
?>

<div id='title'>修改员工任务信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/task_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputform">
<tr>
<td>编号</td>
<td>
<input name="task_code" value="<?php echo $recrow['task_code'];?>">
</td>
</tr>
<tr>
<td>名称</td>
<td>
<input name="task_name" value="<?php echo $recrow['task_name'];?>">
</td>
</tr>
<tr>
<td>组名</td>
<td>
<input name="task_group" value="<?php echo $recrow['task_group'];?>">
</td>
</tr>

<tr>
<td>最小单位</td>
<td>
<input name="task_unit" value="<?php echo $recrow['task_unit'];?>">
</td>
</tr>
<tr>
<tr>
<td>指标</td>
<td>
<input name="task_number" value="<?php echo $recrow['task_number'];?>">
</td>
</tr>
<tr>
<td>单价</td>
<td>
<input name="task_price" value="<?php echo $recrow['task_price'];?>">
</td>
</tr>
<tr>
<td>任务描述</td>
<td><textarea  name="task_describe" rows="10" cols="50"><?php echo $recrow['task_describe'];?></textarea></td>
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
