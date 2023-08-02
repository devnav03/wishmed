<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// Report all PHP errors (see changelog)
error_reporting(E_ALL);
include('ulo.php');
include('paa.php');
?>
