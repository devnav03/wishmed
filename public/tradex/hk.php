<?php
$link = mysqli_connect('mysql.pukacreations.store', 'pukastore', 'p1u2k3a4', 'robertch_tradex');
if(!$link){
    die('Not connected : ' . mysql_error());
}	
$ngayon = strtotime('-6 days');
  $susunod = strtotime("+5 year");
 // echo "<tr>";
  //echo "<td style=\"font:bold 54px Arial\">Trade Shows ".date('Y')."</td>";
 //echo "</tr>";
  echo "<tr>";
  echo "<td align=\"center\" valign=\"middle\">";
  echo "<table border=\"0\" style=\"margin-left:10px;padding:10px;font:bold 14px 'Trebuchet MS'\">";
  echo "<tr><td align=\"center\">Show</td><td align=\"center\">Booth</td><td align=\"center\">From</td><td align=\"center\">To</td></tr>";
$result = mysqli_query($link, "SELECT * FROM schedule WHERE region = '0' AND publish='1' AND date_fr1 > '$ngayon' AND date_fr1 < '$susunod' ORDER BY date_fr1 ASC");


while($row=$result->fetch_object()){
  $evenodd = $i % 2;
  if ($evenodd == 0){
    $bg = "background:#e5e5e5;cursor:pointer;cursor:hand;";
  }else{
    $bg = "background:#F4F4F4;cursor:pointer;cursor:hand;";
  }
  $i++;
  $sid = $row->id;
  $name = $row->name;
  $dfr = $row->date_fr;
  $dto = $row->date_to;
  $booth = $row->booth;
  echo "<tr><td style=\"font:normal 17px 'Trebuchet MS';$bg\">".' '."$name</td><td style=\"font:normal 17px 'Trebuchet MS';$bg\">$booth</td><td style=\"font:normal 17px 'Trebuchet MS';$bg\">$dfr</td><td style=\"font:normal 17px 'Trebuchet MS';$bg\">$dto</td></tr>";

}
  echo "</table>";
  echo "</tr>";
?>           
