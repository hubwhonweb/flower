<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/plant_move_modify.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if( checkUrlNumber() == FALSE){
	header("Location: " . $BASE_DIR . "process_rose/menu.php");
}
else{
	$id = $_GET['id'];
}


//提交了确认按钮，进行数据库修改
if(isset($_POST['submit'])){
        $sql = "UPDATE plant_move
		SET move_date = '" . $_POST['in_date'] ."' ,
		target_house = '" . $_POST['house_name'] ."' ,
        target_bed = '" . $_POST['bed'] ."' ,
        plant_date = '" . $_POST['plant_date'] ."' ,
		variety = '" . $_POST['variety_code'] ."' ,
        state = '" . $_POST['state'] ."' ,
        plants = '" . $_POST['pot'] . "' WHERE id = " . $id . ";";
        WHDBmysql_query($sql);
        header("Location: " . $BASE_DIR . "process_rose/plant_move.php");
}
//没有确认提交按钮，从数据库中找到指定记录
else{
	$sql = "SELECT * FROM plant_move WHERE id = " . $id . ";";
	$result = WHDBmysql_query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow == 0){
		header("Location: " . $BASE_DIR . "process_rose/plant_mvoe.php");
	}
	else {
		$recrow = mysqli_fetch_assoc($result);
	}
	require("../public/header.php");
	mysqli_free_result($result);
}
?>

<div id='title'>修改移苗记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/plant_move_modify.php?id=" . $id; ?>"
	onkeydown ="if(event.keyCode==13) return false;" 
	method="post">
<table class="inputtable">

<tr>
<td>移苗日期</td>
<td>
<?php
echo "<input type='date' name='in_date' value='" . $recrow['move_date'] . "'/>";
?>
</td>
</tr>
<tr>
<td>进苗温室</td>
<td>
<select name="house_name">
<?php
    echo "<option value='" . $recrow['target_house'] . "'>" . $recrow['target_house']."</option>";
    echo "<option value='D'>D</option>";
    echo "<option value='C'>C</option>";
    echo "<option value='B'>B</option>";
    echo "<option value='A'>A</option>";
    echo "<option value='E'>E</option>";
?>
</select>
</td>
<td>进苗床号：</td>
<td>
<select name="bed" style="width:150px">
<?php
echo "<option value='" . $recrow['target_bed'] . "'>" . $recrow['target_bed']."</option>";
for( $i=1; $i<=59;$i++){
    echo "<option value='" . $i . "'>" .$i. "</option>";
}
?>
</select>
</td>
</tr>

<tr>
<td>扦插日期</td>
<td>
<?php
    echo "<input type='date' name='plant_date' value='" . $recrow['plant_date'] . "'/>";
    ?>
</td>
<td>品种编号</td>
<td>
<select name="variety_code">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    echo "<option value='" . $recrow['variety'] . "'>" . $recrow['variety']."</option>";
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
?>
</select>
</td>
</tr>

<tr>
<td>进苗数量</td>
<td>
<?php
    echo "<input type='number' name='pot' value='" . $recrow['plants'] . "'/>";
    ?>
</td>
<td>状态：</td>
<td>
<select name="state" style="width:100px">
<?php
   echo "<option value='" . $recrow['state'] . "'>" . $recrow['state']."</option>";
?>
   <option value='密放'>密放</option>
   <option value='半拉开'>半拉开</option>
   <option value='全拉开'>全拉开</option> 
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="修改"></td>
</tr>	
	
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
