<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/agent_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "sale/agent.php");
}
else{
	$id = $_GET['id'];
}

if(isset($_POST['submit'])){
	$sql = "UPDATE agent SET  
		city = '" .$_POST['city']  ."',
		name = '" .$_POST['name']  ."',
		name_pinyin = '" .$_POST['name_pinyin']  ."',
		tel = '" .$_POST['tel']  ."',
		active = '" .$_POST['active']  ."',
		detail = '" .$_POST['detail']  ."',
		mname = '" . $_SESSION['WHOAMI'] . "', 
		mtime = '" . date('Y-m-d H:i:s',time()) . "'
		WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "sale/agent.php");
    }
else{
  		$sql = "SELECT * FROM agent WHERE id = " . $id .";";
 		$result = WHDBmysql_query($sql);
	 	$numrow = mysqli_num_rows($result);
 	 	if($numrow == 0){
			header("Location: " . $BASE_DIR . "sale/agent.php");
		}
		else {
			$recrow = mysqli_fetch_assoc($result);
		}
        require("../public/header.php");
    }
?>

<div id='title'>修改经销商信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "sale/agent_modify.php?id=" . $id; ?>"
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return agentDataCheckModify();">
<table class="inputtable">

<tr>
<td>经销商编码:</td>
<td>
<?php 
echo  $recrow['code'];
?>
</td>
</tr>

<tr>
<td>经销商名称:</td>
<td>
<?php 
echo "<input type='text' name='name' id='name' value='". $recrow['name'] ."'>";
?>
</td>
</tr>

<tr>
<td>经销商名称拼音:</td>
<td>
<?php 
echo "<input type='text' name='name_pinyin' value='". $recrow['name_pinyin'] ."'>";
?>
</td>
</tr>

<tr>
<td>市场:</td>
<td>
<?php 
echo "<input type='text' name='city' value='". $recrow['city'] ."'>";
?>
</td>
</tr>

<tr>
<td>电话:</td>
<td>
<?php 
echo "<input type='text' name='tel' value='". $recrow['tel'] ."'>";
?>
</td>
</tr>

<tr>
<td>状态:</td>
<td>
<select name="active">
<?php
echo "<option value='" . $recrow['active'] . "' selected>" . $recrow['active'] . "</option>";
?>
<option value='YES'>YES</option>
<option value='NO'>NO</option>
</select>
</td>
</tr>
<tr>
<td>描述:</td>
<td><textarea class="inputtext" name="detail" rows="3" cols="50"><?php echo $recrow['detail'];?></textarea></td>
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
