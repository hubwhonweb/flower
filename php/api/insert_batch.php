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
   $key = "";
   if( db_insert_batch($data['data']) ){
     $key = "ok";
   }
   
   $result = get_return_array();
   
   if( $key != "ok" ){
       $key = "error code";
   }
   $result['key']=$key;
   

   //wlog("bbb","ex=".$i);
   return $result;
}


function db_insert_batch($data){
  return FALSE;
}

function get_return_array(){
   $qdate = date('Y-m-d',time()-3*24*60*60);
   $sql = "select * from batch where batch_date >= '".$qdate."' order by batch_date DESC;";
   $res =  WHDBmysql_query($sql);
   $i = 0;
   while( $rec =  mysqli_fetch_assoc($res)){
     $result['data'][$i]['batch_code'] = $rec['batch_code'];
     $result['data'][$i]['batch_pots'] = $rec['batch_pots'];
     $i = $i + 1;
   }
   return $result;
}
?>
