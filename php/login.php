<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
//if in session goto main page
if(isset($_SESSION['WHOAMI']) == TRUE ){
	header("Location: " . $BASE_DIR . "login/main.php");
}

//submit the form and check the user
$flag = 1;
if(isset($_POST['submit'])){
	//prepare sql
	$pwd = md5($_POST['password']);
	$sql = "SELECT * FROM user 
		WHERE active = 'YES' 
		AND name = '" .$_POST['name'] . "' 
		AND password = '" . $pwd . "';";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 1){ //if user is users then set session username
		    $row = mysqli_fetch_array($result);
			$_SESSION['WHOAMI'] = $row['name'];
            header("Location: " . $BASE_DIR . "login/main.php");
	}
	else{   
		$flag = 0;
	}
	mysqli_free_result($result);
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>炫美园艺</title>
    <meta charset="utf-8">
   
    <link rel="stylesheet" href="../../css/main.css" type="text/css" />
	
    <link rel="stylesheet" href="https://cdn.bootcss.com/foundation/5.5.3/css/foundation.min.css">
	<link rel="stylesheet" href="http://static.runoob.com/assets/foundation-icons/foundation-icons.css">
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/foundation/5.5.3/js/foundation.min.js"></script>
    <script src="https://cdn.bootcss.com/oundation/5.5.3/js/vendor/modernizr.js"></script>
	
    <script src="../../js/flower.js"></script>
</head>

<body>
<div id="login">
<?php
if( $flag == 0 ){
	echo "<p style='color:red'>用户名、密码不正确，<br />请重新登录。</p>";
}
?>
<form action="<?php echo $BASE_DIR . "login/login.php"; ?>" method="post">
<table>
<tr>
<td>用户名：</td>
<td><input type="text" name="name"></td>
</tr>

<tr>
<td>密码：</td>
<td><input type="password" name="password"></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="登录" ></td>
</tr>

</table>
</form>
</div>