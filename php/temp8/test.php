<?php
	require("../public/config.php");
	require("../functions/flower.php");
    //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'flower';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
   if( $str == $signature && $echostr ){
        echo  $echostr;
        exit;
    }
    else{
        //接受信息并回复        
        $postArr = file_get_contents("php://input");
        if( !empty($postArr) ){
            $postObj = simplexml_load_string($postArr);
            //处理事件
            if( strtolower($postObj->MsgType) == 'event'){
                //writeLog("deal event");
                /*<xml>
                <ToUserName>< ![CDATA[toUser] ]></ToUserName>
                <FromUserName>< ![CDATA[FromUser] ]></FromUserName>
                <CreateTime>123456789</CreateTime>
                <MsgType>< ![CDATA[event] ]></MsgType>
                <Event>< ![CDATA[subscribe] ]></Event>
                </xml>*/
                if( strtolower($postObj->Event) == 'subscribe'){
                    //writeLog("deal subscribe");
                    $content = "欢迎关注北京炫美园艺！回复数字：999，系统将在24小时内为您开通账单查询功能。";
                }
            }
            //处理文本信息事件
            if( strtolower($postObj->MsgType) == 'text'){
                /* <xml>  <ToUserName>< ![CDATA[toUser] ]></ToUserName>  <FromUserName>< ![CDATA[fromUser] ]></FromUserName>  <CreateTime>1348831860</CreateTime>  <MsgType>< ![CDATA[text] ]></MsgType>  <Content>< ![CDATA[this is a test] ]></Content>  <MsgId>1234567890123456</MsgId>  </xml>*/
                //writeLog("deal text");
                $content = $postObj->Content;
                if( $content == "1" ){
                    $content = get_all_oders($postObj->FromUserName);
                }
                elseif( $content == "2" ){
                    $content = get_pay_oders($postObj->FromUserName);
                }
                elseif( $content == "999" ){
                    writeLog("新用户申请：".$postObj->FromUserName);
                    $content = "我们将在24小时内为您开通查询信息，如有问题可打电话：18010181090";
                }
                else{
                     $content = "输入数字1，可以查询最近20笔订单情况。\n输入数字2，查询未付款订单。";
                }
               
            }
           //writeLog("get content");
           send_text_msg($postObj,$content);
        }
        else{
            writeLog("HPPT form weixin is empty");
        }
    }

function send_text_msg($recObj,$content){
    /*<xml>
     <ToUserName>< ![CDATA[toUser] ]></ToUserName>
     <FromUserName>< ![CDATA[fromUser] ]></FromUserName> 
     <CreateTime>12345678</CreateTime> 
     <MsgType>< ![CDATA[text] ]></MsgType> 
     <Content>< ![CDATA[你好] ]></Content> 
     </xml>*/
    $toUser = $recObj->FromUserName;
    $fromUser = $recObj->ToUserName;
    $time = time();
    $msgType = "text";
    $template = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[%s]]></MsgType>
    <Content><![CDATA[%s]]></Content>
    </xml>";
     $xmlFile = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
     //writeLog($xmlFile);
     echo $xmlFile;
}
function get_all_oders($openID){
    //查找openID对应的经销商号
    $sql = "select * from agent where weixin='" . $openID. "';";
    $result = WHDBmysql_query($sql);
    $numrow = mysqli_num_rows($result);
    if($numrow == 0) {
        $content = "您尚未开通账单查询功能。";     
    }
    else{
        //查找该经销商的前20笔订单
        $recrow = mysqli_fetch_array($result);
        $sql = "select * from orders where agent_code='". $recrow['code']. "' order by send_date DESC limit 20;";
        $result = WHDBmysql_query($sql);
        $numrow = mysqli_num_rows($result);
        if($numrow == 0) {
            $content = "没有订单。";     
        }
        else
        {
            $content = "您最近的订单(20笔)：\n";
            while($recrow = mysqli_fetch_array($result)){
                $content = $content . 
                date('m/d',strtotime($recrow['send_date'])) .
                "日".$recrow['product_name'] .
                "-".$recrow['plants'] . "盆-单价:" .$recrow['price'] .
                "元-总价:" .$recrow['subtotal']."元-".$recrow['flag']."\n";
            }

        }
    }
    return $content;
}
function get_pay_oders($openID){
    //查找openID对应的经销商号
    $sql = "select * from agent where weixin='" . $openID. "';";
    $result = WHDBmysql_query($sql);
    $numrow = mysqli_num_rows($result);
    if($numrow == 0) {
        $content = "您尚未开通账单查询功能。";     
    }
    else{
        //查找该经销商的未付款
        $recrow = mysqli_fetch_array($result);
        $sql = "select * from orders where agent_code='". $recrow['code']. "' and flag='已发货' order by send_date DESC;";
        $result = WHDBmysql_query($sql);
        $numrow = mysqli_num_rows($result);
        if($numrow == 0) {
            $content = "您没有未付款订单。";     
        }
        else
        {
            $content = "您的未付款信息：\n";
            $total = 0;
            while($recrow = mysqli_fetch_array($result)){
                $content = $content . 
                date('m/d',strtotime($recrow['send_date'])) .
                "日".$recrow['product_name'] .
                "-".$recrow['plants'] . "盆-单价:" .$recrow['price'] .
                "元-总价:" .$recrow['subtotal']."元-".$recrow['flag']."\n";
                $total = $total + $recrow['subtotal'];
            }
            $content = $content . "未付款总计：" .$total ."元";
        }
    }
    return $content;
}
?>