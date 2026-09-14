<?php
date_default_timezone_set('Asia/Shanghai');
 
//定义数据库连接。供本地测试用
//$GLOBALS['DB_HOST'] = "127.0.0.1";
//$GLOBALS['DB_USER'] = "root";
//$GLOBALS['DB_PASSWORD'] = "root123";
//$GLOBALS['DB_DATABASE'] = "flower";
//$GLOBALS['DB_MAX'] = 200;

//定义数据库链接。供数据库在阿里云上用
$GLOBALS['DB_HOST'] = "bdm250832506.my3w.com:3306";
$GLOBALS['DB_USER'] = "bdm250832506";
$GLOBALS['DB_PASSWORD'] = "19966666513098";
$GLOBALS['DB_DATABASE'] = "bdm250832506_db";
$GLOBALS['DB_MAX'] = 200;
//定义主机目录。用于生产系统部署在阿里云
$BASE_DIR = "http://www.flower2009.com/sys/php/";
//定义主机目录。用于生产系统部署在本地
//$BASE_DIR = "http://localhost/flower/sys/php/";

//温室名称
$HOUSE = array("A温室","B温室","C温室","D温室","E温室");
//账户操作种类。用于帐户管理程序，account
$ACCOUNT_SUBJECT = array("+-余额调整","+利息收入","+收贷款","-归还贷款","-利息支出","-借出借款","+收到还款","-股东分红","+其他收入","-其他支出");
$MATERIAL_CAT = array("01肥料","02农药","03基质盆器","04包装","05其他");
//年的下拉菜单用
$YEARS = array("2017","2018","2019","2020","2021","2022","2023","2024","2025","2026","2027");
$CLASS = array("A级","B级","C级","所有等级");
$PRODUCT_SUPPLIER=array("科德纳","迪瑞特","缤纷","其他");
$COST_CAT=array("生产成本","固定资产","其他成本");
//设置每个月的权重数，基础数为1
$month_auth = [1,1,1,1,1,1,2,3,1,2,2,1];
?>
