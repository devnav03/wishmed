<?php 
$q = (isset($_GET['q'])) ? $_GET['q'] : '';
include('ulo.php');
echo "<tr>";
echo "<td>";
echo "<table align=\"center\" width=\"100%\">";
?>
  <tr>
    <td colspan="15" style="font:bold 15px Arial;"><?=$q?></td>
  </tr>
  <tr>
    <td style="font:bold 12px Arial;border:1px solid black;">#</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Name</td>
    <td style="font:bold 12px Arial;border:1px solid black;">From</td>
    <td style="font:bold 12px Arial;border:1px solid black;">To</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Booth</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Cost</td>
    <td style="font:bold 12px Arial;border:1px solid black;">DP</td>
    <td style="font:bold 12px Arial;border:1px solid black;">DP1</td>
    <td style="font:bold 12px Arial;border:1px solid black;">DP2</td>
    <td style="font:bold 12px Arial;border:1px solid black;">TP</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Bal</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Reg</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Log</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Htl</td>
    <td style="font:bold 12px Arial;border:1px solid black;">Comment</td>
  </tr>
<?
if(is_numeric($q)){
	if(strlen($q)==4){
	  $dates = strtotime("01/01/$q");
	  $datee = strtotime("12/31/$q");
	  $results = mysql_query("select * from schedule where publish='1' order by date_fr1 asc");
	  $loc = $_GET['loc'];
	  if($loc==2){
	  	$results = mysql_query("select * from schedule where publish='1' order by date_fr1 asc");
	  }elseif($loc==0){
	  	$results = mysql_query("select * from schedule where publish='1' AND region='0' order by date_fr1 asc");
	  }elseif($loc==1){
	  	$results = mysql_query("select * from schedule where publish='1' AND region='1' order by date_fr1 asc");
	  }
}
}
			
	$tparray = array();
	$balarray = array();
	while($row=mysql_fetch_object($results)){
		if($row->date_fr1 >= $dates && $row->date_fr1 <= $datee){
			$region = ($row->region == 1) ? "AS" : "US";
			$sid = $row->id;
		  $name = $row->name;
		  $dfr = substr($row->date_fr,0,5);
		  $dto = substr($row->date_to,0,5);
		  $booth = $row->booth;
		  $cost = floatval($row->cost);
		  $dpayment = $row->dpayment;
		  $dpayment1 = $row->dpayment1;
		  $dpayment2 = $row->dpayment2;
		  $totalpaid = floatval($dpayment) + floatval($dpayment1) + floatval($dpayment2);
		  $balance1 = floatval($cost) - floatval($totalpaid);
		  $thisbalance = (floatval($cost)==floatval($totalpaid)) ? '<span style="color:red">FP</span>' : number_format($balance1,2,'.','');
		  $hotel = ($row->hotel==1) ? 'Y' : 'N';
		  $logistics = ($row->logistics==1) ? 'Y' : 'N';
		  $status = $row->status;
		  $comment1 = $row->comment;
		  $comment=substr($comment1,0,20);
		  $posted = $row->posted;
		  $evenodd = $i % 2;
		  array_push($tparray, $totalpaid);
		  array_push($balarray, $thisbalance);
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
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg text-align:right\">".number_format($cost,2,'.','')."</td>\n";
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg text-align:right\">".$dpayment."</td>\n";
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg text-align:right\">".$dpayment1."</td>\n";
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg text-align:right\">".$dpayment2."</td>\n";
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg text-align:right\">".number_format($totalpaid,2,'.','')."</td>\n";
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg text-align:right\">".$thisbalance."</td>\n";
		  echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$region</td>\n";
      echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$logistics</td>\n";
      echo "<td $onclick style=\"font:normal 11px Arial;$bg\">$hotel</td>\n";
		  echo "<td $onclick style=\"text-align:left;font:normal 11px Arial;$bg\">$comment</a></td>\n";
		  echo "</tr>\n";
			  $totalcost = $cost + $totalcost;
	  
		}	
  }
$grandtotalpaid = array_sum($tparray);
$grandbalance = array_sum($balarray);
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
    <td style="font:bold 12px Arial;border:1px solid black;">$<?=number_format($grandtotalpaid)?></td>
    <td style="font:bold 12px Arial;border:1px solid black;">$<?=number_format($grandbalance)?></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
    <td style="font:bold 12px Arial;border:1px solid black;"></td>
  </tr>  
<?
echo "</table>";
echo "</td>";
echo "</tr>";
?>

<?
include('paa.php');
?>
