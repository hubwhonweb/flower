<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/finish.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>上市时间</div>";
echo "<div id='right'>";

$startDate = date('Y-m-d',time());
$sql = "SELECT * FROM batch
    ORDER BY batch_date DESC LIMIT 500;";
//根据查询条件进行查询
if(isset($_POST['submit'])){
    if($_POST['variety_code']!="999"){
		 $sql = "SELECT * FROM batch
         WHERE variety_code = '" .$_POST['variety_code']. "'
         ORDER BY batch_date DESC LIMIT 200;";
    }
}
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);

if($numrow == 0) {
	echo "没有记录";	 
}
else{
print_table_head();
print_table_body($result);
}
echo "</div>";
?>

<div id="new">
	<a class="newform" href='finish_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/finish.php"; ?>" method="post">
	
<select name="variety_code">
<?php
echo "<option value='999'>全部品种</option>";
$catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code ASC;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name'] . "</option>";
}
mysqli_free_result($catres);
?>
</select>
<input type="submit" name="submit" id="ssub" value="查询">
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");

function print_table_head(){
	echo "<table class='hovertable'>";
	echo "<tr>";
	echo "<th width=70px>品种</th>";
    echo "<th>扦插日期</th>";
    echo "<th>上市日期</th>";
    echo "<th>生产天数</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>" . $recrow['variety_code'] . "</td>";
    echo "<td>" . $recrow['batch_date'] . "</td>";
    echo "<td>" . $recrow['finish_date'] . "</td>";
    $pot_days = days(strtotime($recrow['finish_date']),strtotime($recrow['batch_date']));
    echo "<td>" . $pot_days . "</td>";
    if( (time()-strtotime($recrow['finish_date'])) > 10*24*60*60 ){
       echo "<td></td>";
       echo "<td></td>";
    }
    else{
       echo "<td>" . "<a href='finish_modify.php?id=" . $recrow['id'] . "'>修改</td>";
       echo "<td>" . "<a href='finish_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    }
	echo "</tr>";
	}
echo "</table>";
}
?>
