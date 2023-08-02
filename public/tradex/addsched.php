<?php 
include('ulo.php');
if(isset($_POST['addscked'])){
  $name = trim($_POST['name']);
  /*$nodate = isset($_POST['nodate']) ? 1 : 0;
  if($nodate==1){
    $dfr = "01/01/2001";
    $dto = "01/01/2001";
  }else{
    $dfr = $_POST['date_fr'];
    $dto = $_POST['date_to'];
  }*/		
  $dfr = $_POST['date_fr'];
  $dto = $_POST['date_to'];
  $date_fr1 = strtotime($dfr);
  $date_to1 = strtotime($dto);
  $region1 = $_POST['region'];
  $booth = trim($_POST['booth']);
  $place = trim($_POST['place']);
  $reparray = array("$",".00",",");
  $cost1 = trim($_POST['cost']);
  $cost = str_replace($reparray,"",$cost1);
  $dpayment_1 = trim($_POST['dpayment']);
  $dpayment = str_replace($reparray,"",$dpayment_1);
  $dpayment1 = trim($_POST['dpayment1']);
  $dpayment1 = str_replace($reparray,"",$dpayment1);
  $dpayment2 = trim($_POST['dpayment2']);
  $dpayment2 = str_replace($reparray,"",$dpayment2);
  $dpayment3 = trim($_POST['dpayment3']);
  $dpayment3 = str_replace($reparray,"",$dpayment3);
  $balance1 = trim($_POST['balance']);
  $balance1 = str_replace($reparray,"",$balance1);
  $logistics = isset($_POST['logistics']) ? 1 : 0;
  $hotel = isset($_POST['hotel']) ? 1 : 0;
  $status = $_POST['status'];
  $comment = trim($_POST['comment']);
  $posted = $_POST['posted'];
  $publish = 1;
  if(!empty($name)){
    $updatedb = mysqli_query($link, "insert into schedule (id, logistics, hotel, name, date_fr, date_to, booth, cost, dpayment, dpayment1, dpayment2, balance, status, comment, posted, publish, place, date_fr1, date_to1, region) values ('', '$logistics', '$hotel', '$name', '$dfr', '$dto', '$booth', '$cost', '$dpayment', '$dpayment1', '$dpayment2', '$balance', '$status', '$comment', '$posted', '$publish', '$place', '$date_fr1', '$date_to1', '$region1')"); 	
    echo "<script>alert('Successfully updated the database.')</script><script>window.location='shows.php'</script>";	
  }else{
    echo "<script>alert('Fields are required.')</script><script>history.go(-1)</script>";	
  }
}
?>
<tr>
<td align="center">
  <tr>  
  <td>
  <table align="center">
  <tr>
  <td style="width:100%;background:#f8f9f0;font:bold 14px Arial;">ADD SCHEDULE</td>
  </tr>
  <tr>
  <td>
  <form action='addsched.php' method="post">
  <table>
  <tr><td style="font:normal 11px Arial;">Region</td><td>:</td><td style="font:normal 11px Arial;">
  <input type="radio" name="region" value="1">&nbsp;Asia&nbsp;&nbsp;&nbsp;<input type="radio" name="region" value="0" checked>&nbsp;US
  </td></tr>      

  <tr><td style="font:normal 11px Arial;">Show Name</td><td>:</td><td><input type="text" name="name" value="<?=$name?>"></td></tr>
  <tr><td style="font:normal 11px Arial;">Place</td><td>:</td><td><input type="text" name="place" value="<?=$place?>"></td></tr>
  <tr><td style="font:normal 11px Arial;">From</td><td>:</td><td><script>DateInput('date_fr', true)</script></td></tr>
  <tr><td style="font:normal 11px Arial;">To</td><td>:</td><td><script>DateInput('date_to', true)</script></td></tr>
  <!--<tr><td style="font:normal 11px Arial;">Check if no specific Date</td><td>:</td><td><input type="checkbox" name="nodate"></td></tr>-->
  <tr><td style="font:normal 11px Arial;">Booth</td><td>:</td><td><input type="text" name="booth" value="<?=$booth?>"><? //echo date("M")."/".date("d")."/".date("Y"); ?></td></tr>  
  <tr><td style="font:normal 11px Arial;">Down Payment I</td><td>:</td><td><input type="text" name="dpayment" value="<?=$dpayment?>"></td></tr>  
  <tr><td style="font:normal 11px Arial;">Down Payment II</td><td>:</td><td><input type="text" name="dpayment1" value="<?=$dpayment1?>"></td></tr>    
  <tr><td style="font:normal 11px Arial;">Down Payment III</td><td>:</td><td><input type="text" name="dpayment2" value="<?=$dpayment1?>"></td></tr>    
  <tr><td style="font:normal 11px Arial;">Balance</td><td>:</td><td><input type="text" name="balance" value="<?=$balance?>"></td></tr>    
  <tr><td style="font:normal 11px Arial;">Total Cost</td><td>:</td><td><input type="text" name="cost" value="<?=$cost?>"></td></tr>  
  <tr><td style="font:normal 11px Arial;">Hotel Booking</td><td>:</td><td style="font:normal 11px Arial;">
  <input type="checkbox" name="hotel" value="1">
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">Logistics</td><td>:</td><td style="font:normal 11px Arial;">
  <input type="checkbox" name="logistics" value="1">
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">Comment</td><td>:</td><td>
  <textarea name="comment" cols="40" rows="10"><?=$comment?></textarea>
  </td></tr>      
  <tr><td style="font:normal 11px Arial;">Posted By</td><td>:</td><td style="font:normal 11px Arial;">
  <select style="font:normal 11px Arial;" name="posted">
  <option value="Sezer">Sezer</option>
  <option value="Robert">Robert</option>
  </select>&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="publish" checked>
  </td></tr>        
  <tr><td align="right" style="font:normal 11px Arial;"></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="addscked" value="Publish">&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="Reset"></td></tr>      
  </table>
  </td>
  </tr>
  </table>
  </td>
  </tr>
</td>
<?
include('paa.php');
?>
