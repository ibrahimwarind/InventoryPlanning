<?php
//url = https://booster.b2bpremier.com/powerbi/test.php

$link = mysqli_connect("localhost","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");

header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0");

set_time_limit(300);




  $DataDetails = array();
  $StatusDetails = array();
  $jsonData      = array();
  $jsonTempData  = array();
  $jsonStatData = array();


$fg_get="SELECT * FROM `Orders_PowerBi_View` where order_date between '2024-01-01' and '2024-01-01' order by order_id DESC ";
$run_get=mysqli_query($link,$fg_get);       


   $i = 0;      
  while($fetch = mysqli_fetch_assoc( $run_get )){

   $i++;
  $DataDetails['s_no'] = $i;         
  $DataDetails['order_id'] = $fetch['order_id'];
  $DataDetails['dsf_code'] =$fetch['dsf_code'];
 
    //$jsonTempData[] = $DataDetails;
      // Process the record
    echo json_encode($DataDetails) . PHP_EOL;

    // Unset the record to free up memory
       unset($fetch);
       unset($DataDetails);
    
 
          }



?>