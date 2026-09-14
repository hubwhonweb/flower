<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("one/one_cost_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
        $sql = "INSERT INTO one_cost (one_date, one_cat, one_amount, one_comm)
		VALUES( '" . $_POST['one_date'] . "',
		'" .$_POST['one_cat']  ."',
		'" .$_POST['one_amount']  ."',
		'" .$_POST['one_comm']  ."');";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "one/one_cost.php");
}
else{
	require("../public/header.php");
}
?>
<div id='title'>成本记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "one/one_cost_new.php"; ?>"
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>日期</td>
<td>
<?php
echo "<input type='date' name='one_date' value='" . date('Y-m-d',time()) . "'/>";
?>
</td>
</tr>

<tr>
<td>分类</td>
<td>
<select name="one_cat">
<option value='人力资源'>人力资源</option>
<option value='包装材料'>包装材料</option>
<option value='快递费'>快递费</option>
<option value='办公用品'>办公用品</option>
</td>
</select>
</tr>


<tr>
<td>金额</td>
<td>
<input type="number" step="0.01" name="one_amount" value="" />
</td>
</tr>

<tr>
<td>说明</td>
<td><textarea class="inputtext" name="one_comm" rows="5" cols="40"></textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="确定"></td>
</tr>
</table>
</form>
</div>
<?php
require("menu.php");
require("../public/footer.php");
?>
