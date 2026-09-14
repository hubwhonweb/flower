<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/house_out_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $out_date = $_POST['out_date'];
    $house_name = $_POST['house_name'];
    
    $variety1 = $_POST['variety_code1'];
    $out_class1 = $_POST['out_class1'];
	$pot1 = $_POST['pot1'];
   
    $variety2 = $_POST['variety_code2'];
    $out_class2 = $_POST['out_class2'];
    $pot2 = $_POST['pot2'];
    
    $variety3 = $_POST['variety_code3'];
    $out_class3 = $_POST['out_class3'];
    $pot3 = $_POST['pot3'];
    
    $variety4 = $_POST['variety_code4'];
    $out_class4 = $_POST['out_class4'];
    $pot4 = $_POST['pot4'];
    
    $variety5 = $_POST['variety_code5'];
    $out_class5 = $_POST['out_class5'];
    $pot5 = $_POST['pot5'];
    

    new_house_out_recorder($out_date,$house_name,$variety1,$out_class1,$pot1);
    new_house_out_recorder($out_date,$house_name,$variety2,$out_class2,$pot2);
    new_house_out_recorder($out_date,$house_name,$variety3,$out_class3,$pot3);
    new_house_out_recorder($out_date,$house_name,$variety4,$out_class4,$pot4);
    new_house_out_recorder($out_date,$house_name,$variety5,$out_class5,$pot5);

	header("Location: " . $BASE_DIR . "process_rose/house_out.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>温室出货记录</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/house_out_new.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>出货日期：</td>
<td>
<?php
echo "<input type='date' style='width:180px' name='out_date' value='" .date('Y-m-d',time()) . "' />";
?>
</td>
<td>出货温室：</td>
<td>
<select name="house_name" style="width:150px">
<?php
    foreach ($HOUSE as $lis) {
        echo "<option value='" . $lis . "'>" . $lis . "</option>";
    }
?>
</select>
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code1" style="width:150px">
<?php
$catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
}
mysqli_free_result($catres);
?>
</select>
</td>
<td>等级:</td>
<td>
<select name="out_class1">
<?php
    foreach( $CLASS as $li) {
        echo "<option value='".$li."'>".$li."</option>";
    }
?>
</select>
</td>
<td>出货数量:</td>
<td>
<input type='number' style='width:80px' name='pot1' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code2" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>等级:</td>
<td>
<select name="out_class2">
<?php
    foreach( $CLASS as $li) {
        echo "<option value='".$li."'>".$li."</option>";
    }
    ?>
</select>
</td>
<td>出货数量:</td>
<td>
<input type='number' style='width:80px' name='pot2' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code3" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>等级:</td>
<td>
<select name="out_class3">
<?php
    foreach( $CLASS as $li) {
        echo "<option value='".$li."'>".$li."</option>";
    }
    ?>
</select>
</td>
<td>出货数量:</td>
<td>
<input type='number' style='width:80px' name='pot3' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code4" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>等级:</td>
<td>
<select name="out_class4">
<?php
    foreach( $CLASS as $li) {
        echo "<option value='".$li."'>".$li."</option>";
    }
    ?>
</select>
</td>
<td>出货数量:</td>
<td>
<input type='number' style='width:80px' name='pot4' value='0' />
</td>
</tr>

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code5" style="width:150px">
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
</td>
<td>等级:</td>
<td>
<select name="out_class5">
<?php
    foreach( $CLASS as $li) {
        echo "<option value='".$li."'>".$li."</option>";
    }
    ?>
</select>
</td>
<td>出货数量:</td>
<td>
<input type='number' style='width:80px' name='pot5' value='0' />
</td>
</tr>


<tr>
<td></td>
<td><input type="submit" name="submit" id="sub" value="增加" ></td>
</tr>
</table>
</form>
</div>

<?php
require("menu.php");
require("../public/footer.php");
?>
<?php
function new_house_out_recorder($out_date,$house_name,$variety_code,$out_class,$pot){
    if($pot>0){
        $sql = "INSERT INTO house_out (
         out_date,house_name,variety_code,out_class,pot,mname, mtime)
            VALUES( '" . $out_date . "',
                   '" .$house_name  ."',
                   '" .$variety_code . "',
                   '" .$out_class  ."',
                   '" .$pot  ."',
                   '" . $_SESSION['WHOAMI'] . "',
                   '" . date('Y-m-d h:i:sa',time()) . "');";
            WHDBmysql_query($sql);
    }
}
?>
