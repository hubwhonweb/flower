<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/comment.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}

if(isset($_POST['submit'])){
	
    
    $variety1 = $_POST['variety_code1'];
    $batch_date1 = $_POST['batch_date1'];
    $comment1 = $_POST['comment1'];

    $variety2 = $_POST['variety_code2'];
    $batch_date2 = $_POST['batch_date2'];
    $comment2 = $_POST['comment2'];

    $variety3 = $_POST['variety_code3'];
    $batch_date3 = $_POST['batch_date3'];
    $comment3 = $_POST['comment3'];

    $variety4 = $_POST['variety_code4'];
    $batch_date4 = $_POST['batch_date4'];
    $comment4 = $_POST['comment4'];

    $variety5 = $_POST['variety_code5'];
    $batch_date5 = $_POST['batch_date5'];
    $comment5 = $_POST['comment5'];

    new_comment($comment1,$variety1,$batch_date1);
    new_comment($comment2,$variety2,$batch_date2);
    new_comment($comment3,$variety3,$batch_date3);
    new_comment($comment4,$variety4,$batch_date4);
    new_comment($comment5,$variety5,$batch_date5);
    
	header("Location: " . $BASE_DIR . "process_rose/batch.php");
}
else{
require("../public/header.php");
}
?>

<div id='title'>备注</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/comment.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">

<tr>
<td>品种编号:</td>
<td>
<select name="variety_code1">
<option value="999">无</option>
<?php
$catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
$catres = WHDBmysql_query($catsql);
while($catrow = mysqli_fetch_assoc($catres)){
       echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
}
mysqli_free_result($catres);
?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='batch_date1' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>
<td>备注:</td>
<td>
<input type='text' name='comment1' value='' />
</td>
</tr>
<tr>
<td>品种编号:</td>
<td>
<select name="variety_code2">
<option value="999">无</option>
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='batch_date2' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>
<td>备注:</td>
<td>
<input type='text' name='comment2' value='' />
</td>
</tr>
<tr>
<td>品种编号:</td>
<td>
<select name="variety_code3">
<option value="999">无</option>
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='batch_date3' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>
<td>备注:</td>
<td>
<input type='text' name='comment3' value='' />
</td>
</tr>
<tr>
<td>品种编号:</td>
<td>
<select name="variety_code4">
<option value="999">无</option>
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='batch_date4' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>
<td>备注:</td>
<td>
<input type='text' name='comment4' value='' />
</td>
</tr>
<tr>
<td>品种编号:</td>
<td>
<select name="variety_code5">
<option value="999">无</option>
<?php
    $catsql = "SELECT * FROM variety WHERE active='在产' order by variety_code;";
    $catres = WHDBmysql_query($catsql);
    while($catrow = mysqli_fetch_assoc($catres)){
        echo "<option value='" . $catrow['variety_code'] . "'>" . $catrow['variety_code'].$catrow['variety_name']. "</option>";
    }
    mysqli_free_result($catres);
    ?>
</select>
<td>扦插日期：</td>
<td>
<?php
    echo "<input type='date' name='batch_date5' value='" .date('Y-m-d',(time()-35*24*60*60)) . "' />";
    ?>
</td>
<td>备注:</td>
<td>
<input type='text' name='comment5' value='' />
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

function new_comment($comment,$variety,$batch_date){

    if( $variety != "999") {
        $sql = "SELECT * FROM batch WHERE batch_date='".$batch_date."' AND variety_code='".$variety."';";
        $res = WHDBmysql_query($sql);
        $row = mysqli_fetch_assoc($res);
        $comm = $row['comm']."#".$comment;

        $sql = "UPDATE batch SET comm='".$comm."'
                WHERE variety_code='".$variety."'
                AND batch_date='".$batch_date."'";
        WHDBmysql_query($sql);
    }
}
?>
