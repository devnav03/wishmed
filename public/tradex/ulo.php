<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$link = mysqli_connect('mysql.pukacreations.store', 'pukastore', 'p1u2k3a4', 'robertch_tradex');
//$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Not connected : ' . mysql_error());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Show Schedules</title>
<style type="text/css">
body{
background:#f8f9f0;
}

#underlinemenu{
margin: 0;
padding: 0;
}

#underlinemenu ul{
margin: 0;
margin-bottom: 1em;
padding-left: 0;
float: left;
font-weight: bold;
width: 100%;
border: 1px solid black;
border-width: 1px 0;
}

* html #underlinemenu ul{ /*IE only rule. Delete extra margin-bottom*/
margin-bottom: 0;
}

#underlinemenu ul li{
display: inline;
}


#underlinemenu ul li a{
float: left;
color: black;
font-weight: bold;
padding: 2px 6px 4px 6px;
text-decoration: none;
background: white url(images/menudivide.gif) top right repeat-y;
}

#underlinemenu ul li a:hover{
color: black;
background-color: #F3F3F3;
border-bottom: 4px solid black;
padding-bottom: 0;
}
</style>
<script type="text/javascript" src="calendar.js"></script>
</head>
<body>
<table style="border:1px solid black" align="center">
<tr>
<td><img src="../images/header.jpg"></td>
</tr>
<tr>
<td>
<div id="underlinemenu">
<ul>
<li><a href="shows.php" title="Home">Home</a></li>
<li><a href="shows.php" title="Show All">Show All Schedule</a></li>
<li><a href="addsched.php" title="Add Show">Add Show</a></li>
<li>&nbsp;
	<select name="jump" onchange="window.location = this.options[this.selectedIndex].value">
  <option value="">Search Year</option>
  <option value="search.php?q=2020&loc=2">Show All 2020</option>
  <option value="search.php?q=2012&loc=2">Show All 2012</option>
  <option value="search.php?q=2011&loc=2">Show All 2011</option>
  <option value="search.php?q=2010&loc=2">Show All 2010</option>
  <option value="search.php?q=2008&loc=2">Show All 2008</option>
  <option value="search.php?q=2007&loc=2">Show All 2007</option>
  <option value="search.php?q=2009&loc=2">Show All 2009</option>
  <option value="search.php?q=2020&loc=0">Show All US 2020</option>
  <option value="search.php?q=2012&loc=0">Show All US 2012</option>
  <option value="search.php?q=2011&loc=0">Show All US 2011</option>
  <option value="search.php?q=2012&loc=1">Show All Asia 2012</option>
  <option value="search.php?q=2011&loc=1">Show All Asia 2011</option>
  <option value="search.php?q=2010&loc=0">Show All US 2010</option>
  <option value="search.php?q=2010&loc=1">Show All Asia 2010</option>
  <option value="search.php?q=2009&loc=0">Show All US 2009</option>
  <option value="search.php?q=2009&loc=1">Show All Asia 2009</option>
  <option value="search.php?q=2008&loc=0">Show All US 2008</option>
  <option value="search.php?q=2008&loc=1">Show All Asia 2008</option>
  <option value="search.php?q=2007&loc=0">Show All US 2007</option>
  <option value="search.php?q=2007&loc=1">Show All Asia 2007</option>
  </select>	
</li>	
</ul>
</div>

</td>
</tr>
