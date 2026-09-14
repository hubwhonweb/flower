<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/batch_data.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>玫瑰扦插批次管理</div>";
echo "<div id='right'>";

$queryDate = date('Y-m-d',(time()-30*24*60*60));
$sql = "SELECT * FROM batch WHERE batch_date > '".$queryDate."' ORDER BY batch_date DESC;";
if(isset($_POST['submit'])){
	 if($_POST['variety_code']!="99999"){
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
<form  action="<?php echo $BASE_DIR . "process_rose/batch_data.php"; ?>" method="post">
	
<select name="variety_code">
<?php
echo "<option value='99999'>全部品种</option>";
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
	echo "<th>磕盆数</th>";
    echo "<th>组盆损失</th>";
	echo "<th>组盆数</th>";
    echo "<th>在场数</th>";
	echo "<th>在场率</th>";
    echo "<th>入场数</th>";
    echo "<th>入场率</th>";

	echo "</tr>";
}
    
function print_table_body($result){
    $t_plant_pot = 0;
    $t_pot_out1 = 0;
    $t_pot_out2 = 0;
    $t_pot_in = 0;
    $t_have =0;
    $t_pot = 0;
    while($recrow = mysqli_fetch_assoc($result)){
        $v_code = $recrow['variety_code'];
        $b_date = $recrow['batch_date'];
        $plant_pot = $recrow['batch_pots'];
        $pot_out1 = getPotOut1($v_code,$b_date);
        $pot_out2 = getPotOut2($v_code,$b_date);
        $pot_in = getPotIn($v_code,$b_date);
        $have = $plant_pot - $pot_out1 - $pot_out2 + $pot_in;
        $pot = getHouseIn($v_code,$b_date);
        echo "<tr>";
        echo "<td>" . $v_code . "</td>";
        echo "<td>" . $b_date. "</td>";
        echo "<td>" . $plant_pot . "</td>";
        echo "<td>" . $pot_out1 . "</td>";
        echo "<td>" . $pot_out2 . "</td>";
        echo "<td>" . $pot_in . "</td>";
        echo "<td>" . $have . "</td>";
        echo "<td>" . toPercentage($have/$plant_pot) . "</td>";
        echo "<td>" . $pot . "</td>";
        echo "<td>" . toPercentage($pot/$plant_pot) . "</td>";
	    echo "</tr>";
        $t_plant_pot = $t_plant_pot + $plant_pot;
        $t_pot_out1 = $t_pot_out1 + $pot_out1;
        $t_pot_out2 = $t_pot_out2 + $pot_out2;
        $t_pot_in = $t_pot_in + $pot_in;
        $t_have = $t_have + $have;
        $t_pot = $t_pot + $pot;
	}
    echo "<tr>";
    echo "<td>合计</td>";
    echo "<td></td>";
    echo "<td>" . $t_plant_pot . "</td>";
    echo "<td>" . $t_pot_out1 . "</td>";
    echo "<td>" . $t_pot_out2 . "</td>";
    echo "<td>" . $t_pot_in . "</td>";
    echo "<td>" . $t_have . "</td>";
    echo "<td>" . toPercentage($t_have/$t_plant_pot) . "</td>";
    echo "<td>" . $t_pot . "</td>";
    echo "<td>" . toPercentage($t_pot/$t_plant_pot) . "</td>";
    echo "</tr>";
    echo "</table>";
}

function getPotOut1($v_code,$b_date){
    $ret = 1;
    $sql = "select sum(pot_out) as pot from lose
           where variety_code='".$v_code."'
           AND plant_date='".$b_date."'
           AND flag ='1';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['pot'];
    }
    return $ret;
}

function getPotOut2($v_code,$b_date){
    $ret = 1;
    $sql = "select sum(pot_out) as pot from lose
           where variety_code='".$v_code."'
           AND plant_date='".$b_date."'
           AND flag ='2';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['pot'];
    }
    return $ret;
}

function getPotIn($v_code,$b_date){
    $ret = 1;
    $sql = "select sum(pot_in) as pot from lose
           where variety_code='".$v_code."'
           AND plant_date='".$b_date."'
           AND flag ='2';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['pot'];
    }
    return $ret;
}

function getHouseIn($v_code,$b_date){
    $ret = 1;
    $sql = "select sum(pot) as pot from house_in
           where variety_code='".$v_code."'
           AND plant_date='".$b_date."';";
    $res = WHDBmysql_query($sql);
    if( $rec = mysqli_fetch_assoc($res) ) {
        $ret = $rec['pot'];
    }
    return $ret;
}

?>
