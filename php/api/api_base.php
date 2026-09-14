<?php
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

//把一个数组处理成Json文件，发送给请求端
function send_result($result){
    echo json_encode($result,JSON_UNESCAPED_UNICODE);
}

//检查接受的Json文件是否合法。如果不合法向请求方发送Json文件，发送信息为json_count=>0
//接受的Json文件第一个参数为Key，是一个MD5加密的密码，密码为：19966666513098
//如果密码检查通过，返回TRUE，由其他程序继续完成后续操作
function check_token($data){
   $md5_token = $data['key'];
   if( $md5_token != md5("19966666513098") ) {
       $result = array ("json_count"=>0);
       echo json_encode($result);
       return FALSE;
   }
   else{
       return TRUE;
   }
}

function wlog($name,$body){
    $log_time = date("Y-m-d H:i:s",time());
    $sql = "INSERT INTO api_log(time,name,body) values ('".$log_time."','".$name."','".$body."');";
    WHDBmysql_query($sql);
}

?>
