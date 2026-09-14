<?php
session_start();
require("../public/config.php");
require("../functions/flower.php");
require("../functions/base.php");
require("../public/header.php");
echo "<div id='title'>function list</div>";
echo "<div id='right'>";
?>
<table class='hovertable'>
   <tr><th>base.php</th><th>base.php</th></tr>
   <tr>
   <td>WHDBmysql_query($sql)::::::::::执行一条sql语句</td>
   <td>checkUrlNumber()::::::::检查URL id变量是否是数字</td>
   </tr>
   <tr>
   <td>checkChar($str)::::::::检查是否是字符串</td>
   <td>checkUrlOrderID():::::::检查URL输入是否只是数字，+-.</td>
   </tr>
   <tr>
   <td>monthDays($time)::::::::返回本月的天数</td>
   <td>yearFirstDate($time):::返回本年第一天</td>
   </tr>
   <tr>
   <td>monthFinalDate($time):::::返回本月的最后一天</td>
   <td>days($big,$small):::::::返回两个时间之间的天数</td>
   </tr>
   <tr>
   <td>getNextMonthFirstDate($time):</td>
   <td>getBeforeDate($time,$days):</td>
   </tr>
   <tr>
   <td>getAfterDate($time,$days):</td>
   <td>getMonthDay($time):</td>
   </tr>
   <tr>
   <td>getNextMonday($time):</td>
   <td>getYearAndWeeks($time):第几周array['week'] 年份array['year']</td>
   </tr>
   <tr>
   <td>getYearWeeksMonday($year,$no)::算第几周的周一</td>
   <td>numberToMoney($number)</td>
   </tr>
   <tr>
   <td>toPercentage($n)</td>
   </tr>
</table>
<table class='hovertable'>
   <tr><th>flower.php</th><th>flower.php</th></tr>
   <tr>
   <td>canI($f_code):权限判断</td>
   <td>getPlantPots($v_code,$b_date)：通过品种、扦插日期取扦插数量</td>
   </tr>
   <tr>
   <td>getPlantPotsByCode($b_code)：通过批次号取扦插数量</td>
   <td>writeLog($ss):写log文件</td>
   </tr>
   <tr>
   <td>deleteFromTable($table,$id)</td>
   <td>getProductName($code)</td>
   </tr>
   <tr>
   <td>getVarietyName($code)</td>
   <td>getAgentName($code)</td>
   </tr>
   <tr>
   <td>getTaskName($code)</td>
   <td>newLoseRecorder($lose_date,$variety_code,$plant_date,$pot_in,$pot_out,$flag)</td>
   </tr>
   <tr>
   <td>createBatchCode($c_date,$variety)</td>
   <td>newBatchRecorder($batch_date,$batch_variety,$batch_pots)</td>
   </tr>
   <tr>
   <td>getSalePlants($start,$end)</td>
   <td>getSaleTimes($start,$end)</td>
   </tr>
   <tr>
   <td>getAgentSalePlants($agent_code,$start,$end)</td>
   <td>getAgentSaleTimes($agent_code,$start,$end)</td>
   </tr>
   <tr>
   <td>isNewBatch($batch_code)</td>
   <td></td>
   </tr>
   <tr>
   <td></td>
   <td></td>
   </tr>
   <tr>
   <td></td>
   <td></td>
   </tr>
   <tr>
   <td></td>
   <td></td>
   </tr>


   <tr>
   <td></td>
   <td></td>
   </tr>

 </table>

<?php
echo "</div>";

require("menu.php");
require("../public/footer.php");
?>
