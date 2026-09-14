<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/batch.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>玫瑰扦插批次管理</div>";
echo "<div id='right'>";

$queryDate = date('Y-m-d',(time()-60*24*60*60));
$sql = "SELECT * FROM batch WHERE batch_date > '".$queryDate."' ORDER BY batch_date DESC;";
if(isset($_POST['submit'])){
	 if($_POST['variety_code']!="999"){
         $sql = "SELECT * FROM batch
             WHERE variety_code = '" .$_POST['variety_code']. "'
             ORDER BY batch_date DESC limit 50;";
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
	<a class="newform" href='batch_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/batch.php"; ?>" method="post">
	
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
	echo "<th>品种</th>";
	echo "<th>扦插日期</th>";
	echo "<th>扦插数量</th>";
  echo "<th>现有数量</th>";
  echo "<th>母本天数</th>";
  echo "<th>状态</th>";
  echo "<th>位置</th>";
  //echo "<th>间隔</th>";
	//echo "<th>二打日期</th>";
  //echo "<th>间隔</th>";
  echo "<th>母本情况</th>";
  echo "<th>评估</th>";
  echo "<th>备注</th>";
	echo "<th>操作</th>";
	echo "</tr>";
}
    
function print_table_body($result){
   while($recrow = mysqli_fetch_assoc($result)){
      $v_code = $recrow['variety_code'];
      $b_date = $recrow['batch_date'];
      $plants = $recrow['batch_pots'] - getPick($v_code,$b_date);
      echo "<tr>";
      echo "<td>" . $v_code . "</td>";
      echo "<td>" . $b_date. "</td>";
      echo "<td>" . $recrow['batch_pots'] . "</td>";
      echo "<td>" . $plants. "</td>";
      echoFirstCutDays($v_code,$b_date);
      echo "<td>" . $days . "</td>";
      echoPositionAndState($v_code,$b_date);
      echo "<td>" . $recrow['parent_batch'] . "</td>";
      echo "<td>" . $recrow['event_code'] . "</td>";
      echo "<td>" . $recrow['comm'] . "</td>";
      if( days(time(),strtotime($b_date)) <= 7 ){
         echo "<td>" . "<a href='batch_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除</td>";
      }
      else{
         echo "<td></td>";
      
      }
	  echo "</tr>";
	}
   echo "</table>";
}

function echoPositionAndState($v_code,$b_date){
    $ret = "";
    $state = "";
    $sql = "select * from plant_move where variety='".$v_code."' AND plant_date='".$b_date."' order by move_date DESC;";
    $res = WHDBmysql_query($sql);
    while( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $ret . "#".$rec['target_house'].$rec['target_bed'];
        $state = $rec['state'];
    }
    echo "<td>" . $state. "</td>";
    echo "<td>" . $ret. "</td>";
}
function echoFirstCutDays($v_code,$b_date){
  $sql = "select * from batch where variety_code='".$v_code."' AND first_date='".$b_date."' order by first_date DESC;";
  //echo "<td>" . $sql . "</td>";
  $res = WHDBmysql_query($sql);
  while( $rec = mysqli_fetch_assoc($res) ) {
    $days = days(strtotime($rec['first_date']),strtotime($rec['batch_date']));
  }
  echo "<td>" . $days . "</td>";
}

function getPick($v_code,$b_date){
    $ret = 0;
    $sql = "select sum(pick_pots) as picks from plant_merge where variety='".$v_code."' AND plant_date='".$b_date."';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['picks'];
    }
    return $ret;
}
?>
