<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
error_reporting(e_all);
include('ulo.php');
  $sid = $_GET['id'];
if(isset($_POST['editscked'])){
//print_r($_POST);
  $dfr1 = $_POST['date_fr'];
  $dto1 = $_POST['date_to'];
  $date_fr1 = strtotime($dfr1);
  $date_to1 = strtotime($dto1);
  $xid = $_POST['xid'];
  $name1 = trim($_POST['name']);
  $region = $_POST['region'];
  $place = $_POST['place'];
  $booth = trim($_POST['booth']);
  $reparray = array("$",",");
  $cost1 = $_POST['cost'];
  $cost = str_replace($reparray,"",$cost1);
  $dpayment_1 = trim($_POST['dpayment']);
  $dpayment = str_replace($reparray,"",$dpayment_1);
  $dpayment1 = trim($_POST['dpayment1']);
  $dpayment1 = str_replace($reparray,"",$dpayment1);
  $dpayment2 = trim($_POST['dpayment2']);
  $dpayment2 = str_replace($reparray,"",$dpayment2);
  $balance1 = trim($_POST['balance']);
  $balance1 = str_replace($reparray,"",$balance1);
  $logistics = isset($_POST['logistics']) ? 1 : 0 ;
  $hotel = isset($_POST['hotel']) ? 1 : 0 ;
  $status = $_POST['status'];
  $comment = trim($_POST['comment']);
  $publish = isset($_POST['publish']) ? 1 : 0 ;
  //$updatedb = mysql_query("UPDATE schedule SET name='$name1', dpayment='$dpayment', dpayment1='$dpayment1', dpayment2='$dpayment2', date_fr='$dfr1', date_to='$dto1', logistics='$logistics', hotel='$hotel', booth='$booth', cost='$cost', balance='$balance', status='$status', comment='$comment', publish='$publish', place='$place', date_fr1='$date_fr1', date_to1='$date_to1', region='$region' WHERE id='$xid'"); 	
  $update_query = "UPDATE schedule SET name='$name1', date_fr='$dfr1', date_to='$dto1', dpayment='$dpayment', dpayment1='$dpayment1', dpayment2='$dpayment2', logistics='$logistics', hotel='$hotel', booth='$booth', cost='$cost', balance='$balance', status='$status', comment='$comment', publish='$publish', place='$place', date_fr1='$date_fr1', date_to1='$date_to1', region='$region' WHERE id='$xid'";
//echo "$update_query";

  if (mysqli_query($link, $update_query)) {
	echo "<script>alert('Record Updated')</script><script>window.location='index.php'</script>";	
	//`header('Location: https://www.pukacreations.com/tradex/shows.php');
  }
  else {
	//echo "<script>alert($mysqli->error)</script><script>window.location='shows.php'</script>";	
	echo "<script>alert($mysqli->error)</script>";
	//header('Location: https://www.pukacreations.com/tradex/shows.php');
  }
/*
  $updatedb = mysqli_query($link,"UPDATE schedule SET name='$name1', date_fr='$dfr1', date_to='$dto1', dpayment='$dpayment', dpayment1='$dpayment1', dpayment2='$dpayment2', logistics='$logistics', hotel='$hotel', booth='$booth', cost='$cost', balance='$balance', status='$status', comment='$comment', publish='$publish', place='$place', date_fr1='$date_fr1', date_to1='$date_to1', region='$region' WHERE id='$xid'");
  //echo "<script>alert('Successfully edited schedule number $xid')</script><script>window.location='index.php'</script>";	
echo "error is : $mysqli->error";
*/
}else{
  $sid = $_GET['id'];
  $result = mysqli_query($link, "select * from schedule where id='$sid'");
  $row=$result->fetch_object();
  $id = $row->id;
  $name = trim($row->name);
  $dfr = $row->date_fr;
  $dto = $row->date_to;
  $booth = $row->booth;
  $cost = $row->cost;
  $dpayment = $row->dpayment;
  $dpayment1 = $row->dpayment1;
  $dpayment2 = $row->dpayment2;  
  $balance = $row->balance;
  $status = $row->status;
  $logistics = $row->logistics;
  $hotel = $row->hotel;
  $comment = $row->comment;
  $posted = $row->posted;
  $place = $row->place;
  $publish = $row->publish;
  $region = $row->region;
}  
?>
<tr>
<td align="center">
  <tr>  
  <td>
  <table align="center">
  <tr>
  <td style="width:100%;background:#f8f9f0;font:bold 14px Arial;">EDIT SCHEDULE</td>
  </tr>
  <tr>
  <td>
  <form action="editsched.php?id="<?=$sid?> method="post">
  <table>
  <tr><td style="font:normal 11px Arial;">Region</td><td>:</td><td style="font:normal 11px Arial;">
  <input type="radio" name="region" value="0"<? echo $region==0 ? ' checked' : ''?>>&nbsp;US&nbsp;&nbsp;&nbsp;<input type="radio" name="region" value="1"<? echo $region==1 ? ' checked' : ''?>>&nbsp;Asia
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">ID</td><td>:</td><td style="font:normal 11px Arial;"><?=$id?></td></tr>
  <tr><td style="font:normal 11px Arial;">Name</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="name" value="<?=$name?>"></td></tr>
  <tr><td style="font:normal 11px Arial;">Place</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="place" value="<?=$place?>"></td></tr>
  <tr><td style="font:normal 11px Arial;">From</td><td>:</td><td style="font:normal 11px Arial;"><script>DateInput('date_fr', true, 'MM/DD/YYYY', '<?=$dfr?>')</script></td></tr>
  <tr><td style="font:normal 11px Arial;">To</td><td>:</td><td style="font:normal 11px Arial;"><script>DateInput('date_to', true, 'MM/DD/YYYY', '<?=$dto?>')</script></td></tr>
  <tr><td style="font:normal 11px Arial;">Booth</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="booth" value="<?=$booth?>"></td></tr>  
    <tr><td style="font:normal 11px Arial;">Down Payment I</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="dpayment" value="<?=$dpayment?>"></td></tr>  
  <tr><td style="font:normal 11px Arial;">Down Payment II</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="dpayment1" value="<?=$dpayment1?>"></td></tr>  
  <tr><td style="font:normal 11px Arial;">Down Payment III</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="dpayment2" value="<?=$dpayment2?>"></td></tr>  
  <tr><td style="font:normal 11px Arial;">Balance</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="balance" value="<?=$balance?>"></td></tr>    
  <tr><td style="font:normal 11px Arial;">Total Cost</td><td>:</td><td style="font:normal 11px Arial;"><input type="text" name="cost" value="<?=$cost?>"></td></tr>  
  <tr><td style="font:normal 11px Arial;">Logistics</td><td>:</td><td style="font:normal 11px Arial;">
  <input type="checkbox" name="logistics" value="1"<? echo $logistics==1 ? ' checked' : ''?>>
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">Hotel</td><td>:</td><td style="font:normal 11px Arial;">
  <input type="checkbox" name="hotel" value="1"<? echo $hotel==1 ? ' checked' : ''?>>
  </td></tr>  
  <tr><td style="font:normal 11px Arial;">Status</td><td>:</td><td>
  <select name="status">
  <option value=""></option>
  <option value="Cancelled" <? echo ($status=="Cancelled") ? ' selected':''?>>Cancelled</option>
  </select>
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">Comment</td><td>:</td><td>
  <textarea cols="40" rows="10" name="comment"><?=$comment?></textarea>
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">Publish</td><td>:</td><td><input type="checkbox" name="publish" <? echo ($publish==1) ? 'checked' : '' ?>></td></tr>      
  <tr><td align="right" style="font:normal 11px Arial;"></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  	<input type="submit" name="editscked" value="Save Edit">&nbsp;&nbsp;&nbsp;
  	<input type="reset" name="reset" value="Reset">
  	<input type="hidden" name="xid" value="<?=$sid?>"></td></tr>      
  </table>
  </td>
  </tr>
  </table>
  </td>
  </tr>
</td>
</tr>
<?
include('paa.php');
?>
