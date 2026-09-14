<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
require("../public/header.php");
echo "<div id='title'>可用网站</div>";
echo "<div id='right'>";
echo "<li><a href='http://www.aliuyun.com.cn/logon/'>阿里邮箱</a></li>"; 
echo "<li><a href='http://www.0531yun.cn'>温度监控,用户密码：171024xmyy</a></li>";
echo "</div>";

require("menu.php");
require("../public/footer.php");
?>