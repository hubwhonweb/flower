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

$queryDate = date('Y-m-d',(time()-30*24*60*60));
$sql = "SELECT * FROM batch WHERE batch_date > '".$queryDate."' ORDER BY batch_date DESC;";
if(isset($_POST['submit'])){
	 if($_POST['variety_code']!="999"){
         $sql = "SELECT * FROM batch
             WHERE variety_code = '" .$_POST['variety_code']. "'
             AND batch_date >= '" .$_POST['startdate']. "'
             AND batch_date <= '".$_POST['enddate']."'
             ORDER BY batch_date DESC;";
		}
     else{
         $sql = "SELECT * FROM batch
                 WHERE batch_date >= '" .$_POST['startdate']. "'
                 AND batch_date <= '".$_POST['enddate']."'
                 ORDER BY batch_date DESC;";
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

<?php
    //查询的时间条件，起始时间为月初，结束时间为当日
    echo "从<input type='date' name='startdate' value='" . date('Y-m-d',monthFirstDate(time())) . "'/>";
    echo "到<input type='date' name='enddate' value='" . date('Y-m-d',time()) . "'/>";
?>

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
	echo "<th>一打日期</th>";
    echo "<th>间隔</th>";
	echo "<th>二打日期</th>";
    echo "<th>间隔</th>";
    echo "<th>上市日期</th>";
    echo "<th>间隔</th>";
	echo "<th>操作</th>";
	echo "</tr>";
}
    
function print_table_body($result){
   $tt = 0;
   while($recrow = mysqli_fetch_assoc($result)){
      $v_code = $recrow['variety_code'];
      $b_date = $recrow['batch_date'];
      $tt = $tt + $recrow['batch_pots'];
      echo "<tr>";
      echo "<td>" . $v_code . "</td>";
      echo "<td>" . $b_date. "</td>";
      echo "<td>" . $recrow['batch_pots'] . "</td>";
      if( $recrow['first_date'] == "0000-00-00" ){
        echo "<td>" . "". "</td>";
        echo "<td>" . "". "</td>";
      }
      else{
        echo "<td>" . $recrow['first_date'] . "</td>";  
        $pot_days = days(strtotime($recrow['first_date']),strtotime($recrow['batch_date']));
        echo "<td>" . $pot_days . "</td>";
      }
      if( $recrow['second_date'] == "0000-00-00" ){
        echo "<td>" . "". "</td>";
        echo "<td>" . "". "</td>";
      }
      else{
        echo "<td>" . $recrow['second_date'] . "</td>";  
        $pot_days = days(strtotime($recrow['second_date']),strtotime($recrow['batch_date']));
        echo "<td>" . $pot_days . "</td>";
      }
      if( $recrow['finish_date'] == NULL ){
        echo "<td>" . "". "</td>";
        echo "<td>" . "". "</td>";
      }
      else{
        echo "<td>" . $recrow['finish_date'] . "</td>";  
        $pot_days = days(strtotime($recrow['finish_date']),strtotime($recrow['batch_date']));
        echo "<td>" . $pot_days . "</td>";
      }
      if( days(time(),strtotime($b_date)) <= 7 ){
         echo "<td>" . "<a href='batch_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除</td>";
      }
      else{
         echo "<td></td>";
      
      }
	  echo "</tr>";
	}
   echo "<tr>";
   echo "<td>合计</td>";
   echo "<td></td>";
   echo "<td>" . $tt . "</td>";
   echo "<td></td>";
   echo "<td></td>";
   echo "<td></td>";
   echo "<td></td>";
   echo "<td></td>";
   echo "</tr>";
   echo "</table>";
}

function getCutDate($v_code,$b_date){
    $ret = "None";
    $sql = "select * from finish where variety_code='".$v_code."' AND plant_date='".$b_date."';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['cut_date'];
    }
    return $ret;
}

function getFinishDate($v_code,$b_date){
    $ret = "None";
    $sql = "select * from finish where variety_code='".$v_code."' AND plant_date='".$b_date."';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['finish_date'];
    }
    return $ret;
}
?>
