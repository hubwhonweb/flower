<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

if(isset($_SESSION['WHOAMI']) == FALSE ){
	header("Location: " . $BASE_DIR . "login/login.php");
}
if( canI("process_rose/gallonPot_new.php") == FALSE){
	echo "权限不够,请与管理员联系。";
	exit();
}
if(isset($_POST['submit'])){
	
    $in_date = $_POST['in_date'];
    $pot1 = $_POST['pot1'];
    $merge_pot1 = $_POST['merge_pot1'];
   
    pick_new_recorder($in_date,"99","2022-09-22","加仑组盆",$pot1,$merge_pot1);
    
	header("Location: " . $BASE_DIR . "process_rose/gallonPot.php");
}
else{
require("../public/header.php");
}

?>
<div id='title'>加仑组盆</div>
<div id='right'>
<form action="<?php echo $BASE_DIR . "process_rose/gallonPot_new.php"; ?>"
	onkeydown ="if(space.keyCode==13) return false;" 
	method="post" >
<table class="inputtable">


<tr>
<td>组盆日期：</td>
<td>
<?php
echo "<input type='date' style='width:180px' name='in_date' value='" .date('Y-m-d',time()) . "' />";
?>
</td>
</tr>

<td>磕盆数量:</td>
<td>
<input type='number' style='width:80px' name='pot1' value='0' />
</td>
</tr>

<td>组加仑盆数量:</td>
<td>
<input type='number' style='width:80px' name='merge_pot1' value='0' />
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
function pick_new_recorder($in_date,$variety_code,$plant_date,$stage,$pickPots,$mergePots){
    if($pickPots>0){
        $sql = "INSERT INTO plant_merge (
         merge_date,variety,plant_date,stage,pick_pots,merge_pots)
            VALUES( '" . $in_date . "',
                   '" .$variety_code  ."',
                   '" .$plant_date  ."',
                   '" .$stage . "',
                   '" .$pickPots ."',
                   '" .$mergePots ."');";
        //echo $sql;
        WHDBmysql_query($sql);
    }
}
?>
