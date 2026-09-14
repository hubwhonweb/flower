<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/halfSpace.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>扦插后半拉开（扦插后20天到40天）</div>";
echo "<div id='right'>";

$startDate = date('Y-m-d',(time()-40*24*60*60));
$endDate = date('Y-m-d',(time()-20*24*60*60));
$sql = "SELECT * FROM batch WHERE batch_date > '".$startDate."' AND batch_date < '" .$endDate."' 
       ORDER BY batch_date DESC;";
if(isset($_POST['submit'])){
	 if($_POST['variety_code']!="999"){
         $sql = "SELECT * FROM batch
             WHERE variety_code = '" .$_POST['variety_code']. "'
             ORDER BY batch_date DESC limit 30;";
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

<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/halfSpace.php"; ?>" method="post">
	
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
  echo "<th>扦插日期</th>";
	echo "<th>品种</th>";
  echo "<th>扦插数量</th>";
  echo "<th>距今日</th>";
  echo "<th>状态</th>";
  echo "<th>位置</th>";
	echo "</tr>";
}
    
function print_table_body($result){
while($recrow = mysqli_fetch_assoc($result)){
  echo "<tr>";
  echo "<td>" . $recrow['batch_date'] . "</td>";
  echo "<td>" . $recrow['variety_code'] . "</td>";
  echo "<td>" . $recrow['batch_pots'] . "</td>";
  $days = days(time(),strtotime($recrow['batch_date']));
  echo "<td>" . $days . "</td>";
  echoPositionAndState($recrow['variety_code'],$recrow['batch_date']);
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
?>
