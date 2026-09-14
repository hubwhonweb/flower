<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("task/task_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(checkUrlNumber()==FALSE){
	header("Location: " . $BASE_DIR . "task/task_staff.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
    $sql = "SELECT * from task where task_code='".$_POST['task_code']."';";
    $res =  WHDBmysql_query($sql);
    $rec = mysqli_fetch_array($res);
     
	$sql = "UPDATE task_log SET  
	task_date = '" .$_POST['task_date']  ."', 
	staff_name = '" .$_POST['staff_name']  ."',
	task_code = '" .$_POST['task_code']  ."',
	task_name = '" .$rec['task_name']  ."',
	task_unit = '" .$rec['task_unit']  ."',
	task_price = '" .$rec['task_price']  ."',
	task_count = '" .$_POST['task_count']  ."',
	comm = '" .$_POST['comm']  ."',
	mname = '" . $_SESSION['WHOAMI'] . "', 
	mtime = '" . date('Y-m-d h:i:sa',time()) . "' 
	WHERE id = " . $id . ";";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "task/task_staff.php");
}
else{
	$sql = "SELECT * FROM task_log
		WHERE id = '" . $id ."';";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "task/task_staff.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
}
mysqli_free_result($result);
?>

<div id='title'>修改工作记录信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "task/task_modify.php?id=" . $id; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">


<tr>
<td>日期</td>
<td>
<?php 
echo "<input type='date' name='task_date' value='" . $recrow['task_date'] . "'/>"; 
?>
</td>
</tr>

<tr>
<td>姓名</td>
<td>
<select name="staff_name">
<?php
   $catsql = "SELECT * FROM staff where active='YES' order by name;";
   $catres = WHDBmysql_query($catsql);
   echo "<option value='" . $recrow['staff_name'] . "' selected>" . $recrow['staff_name'] . "</option>";
	while($catrow = mysqli_fetch_assoc($catres)){
		echo "<option value='" . $catrow['name'] . "'>" . $catrow['name'] .  $catrow['unit']."</option>";
    }
	mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>工作单元:</td>
<td>
<select name="task_code">
<?php
   $catsql = "SELECT * FROM task order by task_code;";
   $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        if($catrow['task_code'] == $recrow['task_code']){
            echo "<option value='" . $catrow['task_code'] . "' selected>" . $catrow['task_name'] . "</option>";
        }
		else{code
	       echo "<option value='" . $catrow['task_code'] . "'>" . $catrow['task_name'] .  $catrow['unit']."</option>";
        }
    }
	mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>数量</td>
<td>
<?php echo "<input type='number' step='0.01' name='task_count' value='" . $recrow['task_count'] . "'/>"; ?>
</td>
</tr>

<tr>
<td>说明</td>
<td><textarea class="inputtext" name="comm" rows="5" cols="40"><?php echo $recrow['comm'];?></textarea></td>
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
