<?php
require("api_base.php");

//接受请求，并检查合法性
$data = json_decode(file_get_contents('php://input'), true);
if( check_token($data) == FALSE ){
  echo "token error";
  exit();
}
//处理数据，并返回结果
$result = make_result($data);
//发送结果
send_result($result);

function make_result($data){
   $sql = "select * from staff;";
   $res =  WHDBmysql_query($sql);
   $i = 0;
   while( $rec =  mysqli_fetch_assoc($res)){
     $result[$i]['name'] = $rec['name'];
     $i = $i + 1;
   }
   //wlog("bbb","ex=".$i);
   return $result;
}
?>
