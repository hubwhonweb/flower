<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("sale/agent_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
       $sql = "INSERT INTO agent (code,name,name_pinyin,city,tel,detail,active,order_times, mname, mtime)
			VALUES( '" . $_POST['code'] . "',
				'" .$_POST['name']  ."', 
				'" .$_POST['name_pinyin']  ."', 
				'" .$_POST['city']  ."', 
				'" .$_POST['tel']  ."',
				'" .$_POST['detail']  ."', 
				'YES',
				'0',
				'" . $_SESSION['WHOAMI'] . "', 
				'" . date('Y-m-d H:i:s',time()) . "');";
        //echo $sql;
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "sale/agent.php");
}
else{
	require("../public/header.php");
}

?>
<div id='title'>增加经销商</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "sale/agent_new.php"; ?>" 
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post" onsubmit="return agentDataCheck();">
<table class="inputtable">

<tr>
<td>经销商编码：</td>
<td>
<input type="text" name="code" id="agentcode" onblur="agentCodeAjax(this.value)">
<span id="agentcodemsg" style="color:red">*</span>
</td>
</tr>

<tr>
<td>经销商名称:</td>
<td>
<input type="text" name="name" id="name" value="">
</td>
</tr>

<tr>
<td>经销商名称拼音:</td>
<td>
<input type="text" name="name_pinyin" value="">
</td>
</tr>

<tr>
<td>市场:</td>
<td>
<input type="text" name="city" value="">
</td>
</tr>
<tr>
<td>电话:</td>
<td>
<input type="text" name="tel" value="">
</td>
</tr>

<tr>
<td>备注:</td>
<td><textarea  name="detail" rows="3" cols="40"></textarea></td>
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
