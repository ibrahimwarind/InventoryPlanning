<?php 
$con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");

$curdate=date('d-m-Y');
$curdate2=date('Y-m-d');

$fg_markatten="SELECT * FROM `sb_attendance_mark` where mark_date='$curdate2'";
$run_markatten=mysqli_query($con,$fg_markatten);
$row_markatten=mysqli_num_rows($run_markatten);

// echo $output;
$array = array($row_markatten);
echo json_encode($array);
?>