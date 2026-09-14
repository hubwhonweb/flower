<?php
// 判断用户是否有权限访问一个功能，如果有权限，则记录访问日志，返回TRUE
// 否则，返回FALSE；2022-02-12修改
function canI($fcode){
	if(isset($_SESSION['WHOAMI']) == TRUE ){
		$flag = 1 ;
		//判断是否有权限使用改组功能，功能为php文件前级目录名；如果有记录则不能使用
		if( $flag == 1 ) {
			$array=explode('/', $fcode);
			$groupFunctionCode = $array[0];
			$sql = "select * from can where function_code='" . $groupFunctionCode . "' and user_name='" .$_SESSION['WHOAMI'] . "';";
			$result = WHDBmysql_query($sql);
			$numrow = mysqli_num_rows($result);
			if($numrow == 1){
				$flag = 0;
			}
		}
		//如果没有组权限限制，判断是否有特定功能权限限制，功能未php文件名,如果有记录则不能使用
		if($flag == 1 ){
			$sql = "select * from can where function_code='" . $fcode . "' and user_name='" .$_SESSION['WHOAMI'] . "';";
			$result = WHDBmysql_query($sql);
			$numrow = mysqli_num_rows($result);
			if($numrow == 1){
				$flag = 0;
			}
		}
		//如果有访问权限写入功能使用日志
		if ( $flag == 1 ) {
			//写入access_log
			$sql = "INSERT INTO access_log (access_time,user_name,function_name) 
				VALUES('" .date('Y-m-d H:i:s',time()) . "',
				'" . $_SESSION['WHOAMI']. "','" . $fcode. "');";
			WHDBmysql_query($sql);
		}

		//返回函数值
		if( $flag == 1 ){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	else{
		return FALSE;
	}
}

//获取批次记录中，扦插数量。输入：扦插日期、品种
function getPlantPots($v_code,$b_date){
    $ret = 1; //如果查询不到，返回1.避免计算成活率时溢出
    $sql = "select * from batch where variety_code='".$v_code."' AND batch_date='".$b_date."';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['batch_pots'];
    }
    return $ret;
}
//获取批次记录中，扦插数量。输入： 批次号
function getPlantPotsByCode($batch_code){
    $ret = 1; //如果查询不到，返回1.避免计算成活率时溢出
    $sql = "select * from batch where batch_code='".$batch_code."';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['batch_pots'];
    }
    return $ret;
}

//log记录
function writeLog($ss){
$fh = fopen("../../log/log.txt", "a");
fwrite($fh,"\n");
$t = date('Y-m-d h:i:sa',time(oid));
$ss = $t . ":" . $ss;
fwrite($fh, $ss);
fclose($fh);
}
//删除表中记录
function deleteFromTable($table,$id){
    $sql = "delete from ".$table." where id = '".$id."'";
    WHDBmysql_query($sql);
}
//通过产品代码查询产品名称
function getProductName($code){
	$sql = "select * from products where product_code='" . $code ."';";
	$res = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['product_name'];
	}
	else{
		return "";
	}
}
//通过品种代码查询品种名称
function getVarietyName($code){
	$sql = "select * from variety where variety_code='" . $code ."';";
	$res = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['variety_name'];
	}
	else{
		return "";
	}
}
//通过客户代码查询客户名称
function getAgentName($code){
	$sql = "select * from agent where code='" . $code ."';";
	$res = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['name'];
	}
	else{
		return "";
	}
}
//通过客户代码查询客户所属城市名
function getAgentCity($code){
	$sql = "select * from agent where code='" . $code ."';";
	$res = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['city'];
	}
	else{
		return "";
	}
}
//通过工作单元代码获得工作单元名称
function getTaskName($code){
	$sql = "select * from task where task_code='" . $code ."';";
	$res = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['task_name'];
	}
	else{
		return "";
	}
}

//记录事件内容
function insertEvent($eventDate,$eventName,$productCode,$batchCode,$number1,$text1,$comm){
$sql = "INSERT INTO event_log (
			event_date,event_name,product_code,batch_code,number1,text1,comm,flag,mname, mtime) 
			VALUES( '" . $eventDate . "',
			'" .$eventName . "',
			'" .$productCode  ."', 
			'" .$batchCode  ."',
			'" .$number1  ."',
			'" .$text1  ."', 
			'" .$comm  ."', 
			'未复核', 
			'" . $_SESSION['WHOAMI'] . "', 
			'" . date('Y-m-d h:i:sa',time()) . "');";
WHDBmysql_query($sql);
}
//在损失表中插入一条记录
function newLoseRecorder($lose_date,$variety_code,$plant_date,$pot_in,$pot_out,$flag){
    $batch_code = date('ymd',strtotime($plant_date));
    $batch_code = $batch_code.$variety_code;
    
    if($pot_in>0 or $pot_out>0){
        $sql = "INSERT INTO lose (
        lose_date,variety_code,plant_date,batch_code,pot_in,pot_out,flag,mname, mtime)
        VALUES( '" . $lose_date . "',
               '" .$variety_code . "',
               '" .$plant_date  ."',
               '" .$batch_code  ."',
               '" .$pot_in  ."',
               '" .$pot_out  ."',
               '" .$flag  ."',
               '" . $_SESSION['WHOAMI'] . "',
               '" . date('Y-m-d h:i:sa',time()) . "');";
        WHDBmysql_query($sql);
    }
}
//用品种和日期生产批次号
function createBatchCode($c_date,$variety){
    $six_date = date('ymd',strtotime($c_date));
    return $six_date.$variety;
}
//在批次表中增加一条记录
function newBatchRecorder($batch_date,$batch_variety,$batch_pots){
	$batch_code = createBatchCode($batch_date,$batch_variety);
	if( isNewBatch($batch_code) ){
       if($batch_pots > 10 ){
		$sql = "INSERT INTO batch (batch_code,variety_code,batch_date,batch_pots,mname, mtime)
			VALUES('" .$batch_code . "',
			'" .$batch_variety  ."', 
			'" .$batch_date  ."', 
			'" .$batch_pots  ."', 
			'" . $_SESSION['WHOAMI'] . "',
			'" . date('Y-m-d h:i:sa',time()) . "');";
	   WHDBmysql_query($sql);
	   }
	}
}
//在订单中取得一段时间之内的销售数量
function getSalePlants($start,$end){
    $start = date('Y-m-d',$start);
    $end = date('Y-m-d',$end);
        
    $sql = "select sum(plants) as plants from orders
        where date >='".$start."'and date <='".$end."';";
    $res = WHDBmysql_query($sql);
    $nn = mysqli_fetch_array($res)['plants'];
    if( $nn <= 0 ){
        return 0;
    }
    else{
        return $nn;
    }
}
//在订单中取得一段时间之内的销售次数
function getSaleTimes($start,$end){
    $start = date('Y-m-d',$start);
    $end = date('Y-m-d',$end);
        
    $sql = "SELECT distinct(order_id) FROM orders where date >='".$start."'and date <='".$end."';";
    $res = WHDBmysql_query($sql);
    $nn = mysqli_num_rows($res);
    if( $nn <= 0 ){
        return 0;
    }
    else{
        return $nn;
    }
}
//在订单中取得一段时间之内某经销商的销售数量
function getAgentSalePlants($agent_code,$start,$end){
    $start = date('Y-m-d',$start);
    $end = date('Y-m-d',$end);
        
    $sql = "select sum(plants) as plants from orders where agent_code = '".$agent_code."' and date >='".$start."'and date <='".$end."';";
    $res = WHDBmysql_query($sql);
    $nn = mysqli_fetch_array($res)['plants'];
    if( $nn <= 0 ){
        return 0;
    }
    else{
        return $nn;
    }
}
//在订单中取得一段时间之内某经销商的销售次数
function getAgentSaleTimes($agent_code,$start,$end){
    $start = date('Y-m-d',$start);
    $end = date('Y-m-d',$end);
        
    $sql = "SELECT distinct(order_id) FROM orders where agent_code = '".$agent_code."' and date >='".$start."'and date <='".$end."';";
    $res = WHDBmysql_query($sql);
    $nn = mysqli_num_rows($res);
    if( $nn <= 0 ){
        return 0;
    }
    else{
        return $nn;
    }
}
//判断是否为新批次
function isNewBatch($batch_code){
   $sql = "select * from batch where batch_code='".$batch_code."';";
   $res = WHDBmysql_query($sql);
   $nn = mysqli_num_rows($res);
   if( $nn > 0 ){
        return 0;
    }
    else{
        return 1;
    }
}
?>
