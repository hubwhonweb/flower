<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/second.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>二打时间（一打后15-40天）</div>";
echo "<div id='right'>";

$startDate = date('Y-m-d',(time()-50*24*60*60));
$endDate = date('Y-m-d',(time()-15*24*60*60));
$sql = "SELECT * FROM batch WHERE first_date >= '".$startDate."' AND first_date <= '" .$endDate."' 
       ORDER BY second_date DESC;";
//根据查询条件进行查询
if(isset($_POST['submit'])){
    if($_POST['variety_code']!="999"){
		 $sql = "SELECT * FROM batch
         WHERE variety_code = '" .$_POST['variety_code']. "'
         ORDER BY batch_date DESC LIMIT 50;";
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
	<a class="newform" href='second_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/second.php"; ?>" method="post">
	
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
  echo "<th>一打日期</th>";
  echo "<th>距一打天数</th>";
  echo "<th>二打日期</th>";
  echo "<th>二打天数</th>";
  echo "<th>位置</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
while($recrow = mysqli_fetch_assoc($result)){
	echo "<tr>";
  echo "<td>" . $recrow['variety_code'] . "</td>";
  echo "<td>" . $recrow['batch_date'] . "</td>";
  if( $recrow['first_date'] == "0000-00-00" ){
      echo "<td>" . "". "</td>";
      echo "<td>" . "". "</td>";
  }
    else{
      echo "<td>" . $recrow['first_date'] . "</td>";  
  }
  $days = days(time(),strtotime($recrow['first_date']));
  echo "<td>" . $days . "</td>";
  if( $recrow['second_date'] == "0000-00-00" ){
      echo "<td>" . "". "</td>";
      echo "<td>" . "". "</td>";
    }
    else{
      echo "<td>" . $recrow['second_date'] . "</td>";  
      $pot_days = days(strtotime($recrow['second_date']),strtotime($recrow['first_date']));
      echo "<td>" . $pot_days . "</td>";
    }  
    
    echoPosition($recrow['variety_code'],$recrow['batch_date']);
    
    if( (time()-strtotime($recrow['second_date'])) > 7*24*60*60 ){
       echo "<td></td>";
       echo "<td></td>";
    }
    else{
       echo "<td>" . "<a href='second_modify.php?id=" . $recrow['id'] . "'>修改</td>";
       echo "<td>" . "<a href='second_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    }
	echo "</tr>";
	}
echo "</table>";
}


function echoPosition($v_code,$b_date){
    $ret = "";
    $sql = "select * from plant_move where variety='".$v_code."' AND plant_date='".$b_date."';";
    $res = WHDBmysql_query($sql);
    while( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $ret . "#".$rec['target_house'].$rec['target_bed'];
    }
    echo "<td>" . $ret. "</td>";
}
?>
