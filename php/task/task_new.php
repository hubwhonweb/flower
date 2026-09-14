<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("task/task_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
        $sql = "SELECT * from task where task_code='".$_POST['task_code']."';";
        $res =  WHDBmysql_query($sql);
        $rec = mysqli_fetch_array($res);
        
        $sql = "INSERT INTO task_log 
        (task_date, staff_name, task_code, task_name, task_unit, task_price,task_count, comm, flag, mname, mtime) 
		VALUES( '" . $_POST['task_date'] . "',
		'" .$_POST['staff_name']  ."', 
		'" .$_POST['task_code'] ."',
		'" .$rec['task_name'] ."',
		'" .$rec['task_unit'] ."',
		'" .$rec['task_price'] ."',
		'" .$_POST['task_count']  ."',
		'" .$_POST['comm']  ."', 
		'待复核', 
		'" . $_SESSION['WHOAMI'] . "', '" . date('Y-m-d h:i:sa',time()) . "');";

        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "task/task_staff.php");
}
else{
	require("../public/header.php");
}
?>
<div id='title'>增加工作记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "task/task_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>日期</td>
<td>
<?php
echo "<input type='date' name='task_date' value='" . date('Y-m-d',(time()-24*60*60)) . "'/>"; 
?>
</td>
</tr>

<tr>
<td>姓名</td>
<td>
<select name="staff_name">
<?php
   $catsql = "SELECT * FROM staff WHERE active = 'YES' order by name;";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['name'] . "'>" . $catrow['name'] . "</option>";
    }
	mysqli_free_result($result);
?>
</select>
</td>
</tr>
<tr>
<td>工作单元</td>
<td>
<select name="task_code">
<?php
   $catsql = "SELECT * FROM task order by task_code;";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['task_code'] . "'>" . $catrow['task_name']. "</option>";
    }
	mysqli_free_result($result);
?>
</select>
</td>
</tr>

<tr>
<td>数量</td>
<td>
<input type="number" step="0.01" name="task_count" value="" />
</td>
</tr>

<tr>
<td>备注</td>
<td><textarea  name="comm" rows="5" cols="40"></textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="提交"></td>
</tr>
</table>
</form>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>
