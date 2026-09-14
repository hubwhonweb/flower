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
             ORDER BY batch_date DESC limit 110;";
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
  echo "<th>位置</th>";
  echo "<th>一打日期</th>";
  echo "<th>一打天数</th>";
  echo "<th>二打日期</th>";
  echo "<th>二打天数</th>";
  echo "<th>上市日期</th>";
  echo "<th>上市天数</th>";
  echo "<th>总天数</th>";
	echo "<th>操作</th>";
	echo "</tr>";
}
    
function print_table_body($result){
   while($recrow = mysqli_fetch_assoc($result)){
      echo "<tr>";
      echo "<td>" . $recrow['variety_code'] . "</td>";
      echo "<td>" . $recrow['batch_date'] . "</td>";
      echo "<td>" . $recrow['batch_pots'] . "</td>";
      //echo "<td>" . $recrow['position'] . "</td>";
      echoPosition($recrow['variety_code'],$recrow['batch_date']);
      echo "<td>" . $recrow['first_date'] . "</td>";
      echoFirstDays($recrow['first_date'] ,$recrow['batch_date']);
      echo "<td>" . $recrow['second_date'] . "</td>";
      echoSecondDays($recrow['second_date'] ,$recrow['first_date']);
    
      echo "<td>" . $recrow['finish_date'] . "</td>";
      
      if( $recrow['second_date'] == "0000-00-00") {
          echoFinishDays($recrow['finish_date'] ,$recrow['first_date']);
      }
      else{
          echoFinishDays($recrow['finish_date'] ,$recrow['second_date']);
      }
  
      echoTotalDays($recrow['finish_date'] ,$recrow['batch_date']);
      
      if( days(time(),strtotime($recrow['batch_date'])) <= 7 ){
         echo "<td>" . "<a href='batch_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除</td>";
      }
      else{
         echo "<td></td>";
      
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

function echoFirstDays($big_date,$small_date){
  if( $big_date == "0000-00-00" ){
    $big_date = date('Y-m-d',time());
  }
  $days = days(strtotime($big_date),strtotime($small_date));
  echo "<td>" . $days . "</td>";
}

function echoSecondDays($big_date,$small_date){
  if( $big_date == "0000-00-00" ){
    $big_date = date('Y-m-d',time());
  }
  if( $small_date == "0000-00-00" ){
    $small_date = date('Y-m-d',time());
  }
  $days = days(strtotime($big_date),strtotime($small_date));
  echo "<td>" . $days . "</td>";
}

function echoFinishDays($big_date,$small_date){
  
  if( $big_date == NULL ){
    $big_date = date('Y-m-d',time());
  }
  if( $small_date == "0000-00-00" ){
    $small_date = date('Y-m-d',time());
  }
  $days = days(strtotime($big_date),strtotime($small_date));
  echo "<td>" . $days . "</td>";
  
}


function echoTotalDays($big_date,$small_date){
  if( $big_date == NULL ){
    $big_date = date('Y-m-d',time());
  }
  $days = days(strtotime($big_date),strtotime($small_date));
  echo "<td>" . $days . "</td>";
}
?>
