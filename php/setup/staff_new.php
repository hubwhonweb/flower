<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("setup/staff_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	$sql = "INSERT INTO staff (name,code,name_pinyin, tel, birthday, address, id_no, department, title, begin_date, end_date, active, mname, mtime) 
			VALUES( 
				'" . $_POST['name'] . "',
				'" . $_POST['code'] . "',
				'" . $_POST['name_pinyin'] . "',
				'" . $_POST['tel'] . "',
				'" . $_POST['birthday'] . "',
				'" . $_POST['address'] . "',
				'" . $_POST['id_no'] . "',
				'" . $_POST['department'] . "',
				'" . $_POST['title'] . "',
				'" . $_POST['begin_date'] . "',
				'2099-12-31',
				'YES', 
				'" . $_SESSION['WHOAMI'] . "', 
				'" . date('Y-m-d h:i:sa',time()) . "');";
	WHDBmysql_query($sql);
	header("Location: " . $BASE_DIR . "setup/staff.php");
}
else{
	require("../public/header.php");
}
?>

<div id='title'>增加人员信息</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "setup/staff_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return staffDataCheck();">
<table class="inputtable">

<tr>
<td>姓名：</td>
<td>
<input type="text" name="name" value="" />
</td>
</tr>
<tr>
<td>编号：</td>
<td>
<input type="text" name="code" id="staffcode" onblur="staffCodeAjax(this.value)">
<span id="staffcodemsg" style="color:red">*</span>
</td>
</tr>
<tr>
<td>姓名拼音：</td>
<td>
<input type="text" name="name_pinyin" value="" />
</td>
</tr>
<tr>
<td>身份证号：</td>
<td>
<input type="text" name="id_no" value="" />
</td>
<td>出生日期：</td>
<td>
<input type="date" name="birthday" value="1970-01-01" />
</td>
</tr>

<tr>
<td>家庭住址：</td>
<td>
<input type="text" name="address" value="" />
</td>
<td>联系电话：</td>
<td>
<input type="text" name="tel" value="" />
</td>
</tr>

<tr>
<td>工作部门：</td>
<td>
<input type="text" name="department" value="" />
</td>
<td>职务：</td>
<td>
<input type="text" name="title" value="" />
</td>
</tr>

<tr>
<td>入职时间：</td>
<td>
<input type="date" name="begin_date" value="<?php echo date('Y-m-d',time()); ?>">

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