<?php
// 判断用户是否有权限访问一个功能，如果有权限，则记录访问日志，返回TRUE
// 否则，返回FALSE；
function can_i($fcode){
	if(isset($_SESSION['WHOAMI']) == TRUE ){
		$flag = 0 ;
		//判断是否超级用户：功能为：SYS
		if( $flag == 0 ) {
			$sql = "select * from can where function_code='SYS' and user_name='" .$_SESSION['WHOAMI'] . "';";
			$result = my_sql_query($sql);
			$numrow = mysqli_num_rows($result);
			if($numrow == 1){
				$flag = 1;
			}
		}
		//如果不是超级用户，判断是否有组权限，功能为php文件前级目录名
		if( $flag == 0 ) {
			$array=explode('/', $fcode);
			$groupFunctionCode = $array[0];
			$sql = "select * from can where function_code='" . $groupFunctionCode . "' and user_name='" .$_SESSION['WHOAMI'] . "';";
			$result = my_sql_query($sql);
			$numrow = mysqli_num_rows($result);
			if($numrow == 1){
				$flag = 1;
			}
		}
		//如果没有组权限，判断是否有特定功能权限，功能未php文件名
		if($flag == 0 ){
			$sql = "select * from can where function_code='" . $fcode . "' and user_name='" .$_SESSION['WHOAMI'] . "';";
			$result = my_sql_query($sql);
			$numrow = mysqli_num_rows($result);
			if($numrow == 1){
				$flag = 1;
			}
		}
		//如果有访问权限写入功能使用日志
		if ( $flag == 1 ) {
			//写入access_log
			$sql = "INSERT INTO access_log (access_time,user_name,function_name) 
				VALUES('" .date('Y-m-d H:i:s',time()) . "',
				'" . $_SESSION['WHOAMI']. "','" . $fcode. "');";
			my_sql_query($sql);
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

//log记录
function write_log($ss){
	$fh = fopen("../../log/log.txt", "a");
	fwrite($fh,"\n");
	$t = date('Y-m-d h:i:sa',time(oid));
	$ss = $t . ":" . $ss;
	fwrite($fh, $ss);
	fclose($fh);
}
function get_average_temperature($location,$begin_time,$end_time){
	$sql = "SELECT AVG(temperature) as temperature from temperature 
			where location = '" . $location ."' 
			AND  rtime BETWEEN '" . $begin_time . "' AND '" .$end_time ."';";
	$result = my_sql_query($sql);
	$record = mysqli_fetch_array($result);
	$temp = round($record['temperature'],1);
	return $temp;
}
function get_average_humidity($location,$begin_time,$end_time){
	$sql = "SELECT AVG(humidity) as humidity from temperature 
			where location = '" . $location ."' 
			AND  rtime BETWEEN '" . $begin_time . "' AND '" .$end_time ."';";
	$result = my_sql_query($sql);
	$record = mysqli_fetch_array($result);
	$humidity = round($record['humidity'],1);
	return $humidity;
}
function get_variety($product_code){
	$sql = "SELECT * FROM variety 
		WHERE product_code = '" .$product_code ."' order by variety_code ASC;";
	$result = my_sql_query($sql);
	return $result;
}

function get_event_name($product_code,$event_code){
	$sql = "select * from event where product_code='" . $product_code ."' 
		and event_code = '".$event_code."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res);
	}
	else{
		return NULL;
	}
}


//////////////////////////////////
//生产资料出库
function material_out($material_code,$date,$total,$who,$comm){
	$sql = "INSERT INTO material_log
		(material_code,date,total,who,comm,flag,mname,mtime) 
		VALUES( '" . $material_code . "',
		'" .$date  ."', 
		'" .$total  ."',
		'" .$who  ."',
		'" .$comm  ."', 
		'OUT', 
		'" . $_SESSION['WHOAMI'] . "', '" . date('Y-m-d h:i:sa',time()) . "');";
        my_sql_query($sql);
}

//通过生产资料代码查询生产资料信息
function get_material($material_code){
	$sql = "select * from material where code='" . $material_code ."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res);
	}
	else{
		return NULL;
	}
}
//通过产品代码查询产品名称
function get_product($code){
	$sql = "select * from products where product_code='" . $code ."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res);
	}
	else{
		return NULL;
	}
}
//通过品种代码查询品种名称
function get_variety_name($product_code,$code){
	$sql = "select * from variety where variety_code='" . $code ."' 
		and product_code='".$product_code."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['variety_name'];
	}
	else{
		return "";
	}
}
//记录事件内容
function insert_event($event_date,$event_name,$product_code,$batch_code,$amount,$comm){
	$sql = "INSERT INTO event_log (
			event_date,event_name,product_code,batch_code,amount,comm,mname, mtime) 
			VALUES( '" . $event_date . "',
			'" .$event_mame . "',
			'" .$product_code  ."', 
			'" .$batch_code  ."',
			'" .$amount  ."',
			'" .$comm  ."', 
			'" . $_SESSION['WHOAMI'] . "', 
			'" . date('Y-m-d h:i:sa',time()) . "');";
	my_sql_query($sql);
}
//通过客户代码查询客户信息
function get_agent($code){
	$sql = "select * from agent where code='" . $code ."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res);
	}
	else{
		return NULL;
	}
}
//通过工作单元代码获得工作单元信息
function get_task($code){
	$sql = "select * from task where task_code='" . $code ."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res);
	}
	else{
		return NULL;
	}
}


//取得生产批次的日期
function get_batch($product_code,$batch_code){
	$sql = "select * from batch where product_code='" . $product_code ."' 
		AND batch_code='".$batch_code."';";
	$res = my_sql_query($sql);
	$numrow = mysqli_num_rows($res);
	if($numrow == 1){
		return mysqli_fetch_array($res)['batch_date'];
	}
	else{
		return NULL;
	}
}
?>