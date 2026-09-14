<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");


if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/house_in.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
require("../public/header.php");

echo "<div id='title'>温室进苗数据</div>";
echo "<div id='right'>";


$sql = "SELECT * FROM house_in
    ORDER BY plant_date DESC LIMIT 100;";
    //根据查询条件进行查询
if(isset($_POST['submit'])){
    $variety_code = $_POST['variety_code'];
	if( $_POST['house_name'] == "所有温室" ){
        if( $_POST['variety_code'] == "所有品种"){
            $sql = "SELECT * FROM house_in
            ORDER BY plant_date DESC Limit 100;";
        }
        else{
            $sql = "SELECT * FROM house_in
            WHERE variety_code ='".$variety_code."'
            ORDER BY plant_date DESC;";
        }
	}
    else{
        if( $_POST['variety_code'] == "所有品种"){
            $sql = "SELECT * FROM house_in
            WHERE house_name = '" .$_POST['house_name']. "'
            ORDER BY plant_date DESC;";
        }
        else{
            $sql = "SELECT * FROM house_in
            WHERE house_name = '" .$_POST['house_name']. "'
            AND variety_code ='".$variety_code."'
            ORDER BY plant_date DESC;";
        }
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
	<a class="newform" href='house_in_new.php'><img class="imgnew" src="../../img/new.png" /></a>
</div>
<div id="search">
<form  action="<?php echo $BASE_DIR . "process_rose/house_in.php"; ?>" method="post">
	
<select name="house_name">
<?php
echo "<option value='所有温室'>所有温室</option>";
  foreach ($HOUSE as $lis) {
    echo "<option value='" . $lis . "'>" . $lis . "</option>";
  }
?>
</select>
<select name="variety_code">
<?php
echo "<option value='所有品种'>所有品种</option>";
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
    echo "<th>温室</th>";
    echo "<th>进苗日期</th>";
	echo "<th>进苗数量</th>";
	echo "<th>修改</th>";
	echo "<th>删除</th>";
	echo "</tr>";
}
    
function print_table_body($result){
    $total1 = 0;
while($recrow = mysqli_fetch_assoc($result)){
    $total1 = $total1 + $recrow['pot'];
	echo "<tr>";
    echo "<td>" . $recrow['variety_code'] . "</td>";
    echo "<td>" . $recrow['plant_date'] . "</td>";
    echo "<td>" . $recrow['house_name'] . "</td>";
	echo "<td>" . $recrow['in_date'] . "</td>";
    echo "<td>" . $recrow['pot'] . "</td>";
    if( days(time(),strtotime($recrow['in_date'])) <= 7 ){
	  echo "<td>" . "<a href='house_in_modify.php?id=" . $recrow['id'] . "'>修改</td>";
	  echo "<td>" . "<a href='house_in_delete.php?id=" . $recrow['id'] . "' onclick='return defaultConfirm()'>删除" ."</td>";
    }
    else{
      echo "<td></td>";
      echo "<td></td>";
    }
    echo "</tr>";
	}
    echo "<tr>";
    echo "<td>合计</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>".$total1."</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "</tr>";
echo "</table>";
}
?>
