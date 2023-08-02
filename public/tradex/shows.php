<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include('ulo.php');
?>
<tr>
<td>
<table align="center" width="880" style="text-align:center;">
<?
if(!isset($_POST['submit'])){
?>
  <tr>
    <td style="font:bold 12px Arial;border:1px solid black;">#</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Name</td>
    <td style="font:bold 12px Arial;border:1px solid black;">From</td>
    <td style="font:bold 12px Arial;border:1px solid black;">To</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Booth</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Cost</td>
    <td style="font:bold 12px Arial;border:1px solid black;">DP1</td>
    <td style="font:bold 12px Arial;border:1px solid black;">DP2</td>
    <td style="font:bold 12px Arial;border:1px solid black;">DP3</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Reg</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Log</td>
    <td style="font:bold 12px Arial;border:1px solid black;">HTL</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Comment</td>
    <td style="font:bold 12px Arial;border:1px solid black;">By</td>
  </tr>
<?
$result = mysqli_query($link, "select * from schedule where publish='1' order by date_fr1 desc");
while($row=$result->fetch_object()){
  $region = ($row->region == 1) ? "AS" : "US";
  $sid = $row->id;
  $name = $row->name;
  $dfr = $row->date_fr;
  $dto = $row->date_to;
  $booth = $row->booth;
  $cost = $row->cost;
  $dpayment = $row->dpayment;
  $dpayment1 = $row->dpayment1;
  $dpayment2 = $row->dpayment2;
  $balance = $row->balance;
  $hotel1 = $row->hotel;
  $logistics1 = $row->logistics;
  $logistics = ($logistics1==1) ? 'Y' : 'N';
  $hotel = ($hotel1==1) ? 'Y' : 'N';
  $status = $row->status;
  $comment1 = $row->comment;
  $comment=substr($comment1,0,20);
  $posted = $row->posted;
  $evenodd = $i % 2;
  $onclick = "onClick=javascript:window.location='editsched.php?id=$sid';";
  if ($evenodd == 0){
    $bg = "background:#e5e5e5;cursor:pointer;cursor:hand;";
  }else{
    $bg = "background:#F4F4F4;cursor:pointer;cursor:hand;";
  }
  $i++;
  echo "<tr>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$i</td>\n";
  echo "<td $onclick style=\"text-align:left;font:normal 11px Arial;$bg\">$name</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$dfr</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$dto</td>\n";
  echo "<td $onclick style=\"text-align:left;font:normal 11px Arial;$bg\">$booth</td>\n";
  echo "<td align='left' $onclick style=\"font:normal 11px Arial;$bg\">".number_format($cost)."</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">".number_format($dpayment)."</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">".number_format($dpayment1)."</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">".number_format($dpayment2)."</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$region</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$logistics</td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$hotel</td>\n";
  echo "<td $onclick style=\"text-align:left;font:normal 11px Arial;$bg\">$comment</a></td>\n";
  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$posted</td>\n";
  echo "</tr>\n";
  $totalcost = $cost + $totalcost;
}
}
?> 
  <tr>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;">$<?=number_format($totalcost)?></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
  </tr>  
</table>
</td>
</tr>
<tr><td><hr></td></tr>
<tr>
<td>
<form action="addsched.php" method="post">
<input type="submit" name="addsched" value="Add Schedule">
</form>
</td>
</tr>
<?
include('paa.php');
?>
