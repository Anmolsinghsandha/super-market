<?php
  include 'admin/php_files/database.php';
  echo gethostname(); 
  $myvar = gethostname();
  $str1 = "https://";
  $str2 = "/super-market";
  $hostname = $str1 . ''. $myvar . ''. $str2;
  echo $hostname;
?>