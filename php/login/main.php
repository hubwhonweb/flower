<?php
session_start();
require("../public/config.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
require("../public/header.php");
?>
