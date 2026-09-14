<?php
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

$data = json_decode(file_get_contents('php://input'), true);
$md5_token = $data[0]['key'];

if( $md5_token != md5("19966666513098") ) {
 $result = array ("k1"=>404,"k2"=>3,"k3"=>"34","k4"=>"99",);
 echo json_encode($result);
 exit();
}
$sql = "select * from staff;";
$res =  WHDBmysql_query($sql);
$rec = mysqli_fetch_assoc($res);
$result = array ("k1"=>$rec['name'],"k2"=>$data[2]['name']);
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
