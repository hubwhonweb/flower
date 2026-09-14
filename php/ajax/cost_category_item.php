<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");

$q=$_GET["q"];
$sql = "select * from cost_category where item='".$q."';";
$result = WHDBmysql_query($sql);
$numrow = mysqli_num_rows($result);

if ($numrow == 0 )
{
  $response="NO";
}
else
{
  $response="YES";
}

echo $response;
?>