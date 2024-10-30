@include('vendor/autoload')
<?php
error_reporting(0);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$con=mysqli_connect('127.0.0.1','ytwpdncbtd','9DKdWEz2sr','ytwpdncbtd');
//insert log
date_default_timezone_set("Asia/Karachi");
$currentdate=date('Y-m-d');
$myuserid=session("adminname");
$ins_logs="INSERT INTO `export_bmsexcel_log`(export_user_id,unique_key,export_datetime) VALUES ('$myuserid','$uqid','$currentdate')";
$run_inslog=mysqli_query($con,$ins_logs);



$spreadsheet = new Spreadsheet();
// $spreadsheet->setEnclosureRequired(false);
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
// $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
// $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
// $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
// $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);

$activeWorksheet = $spreadsheet->getActiveSheet();





$rowcount=1;$index=1;
foreach($orderdetail as $odetail)
{

    $customer_code=$odetail->customer_code;
    $product_code=$odetail->product_code;
    $rate=$odetail->rate;
    $quantity=$odetail->quantity;
    $booking_datetime=$odetail->booking_datetime;
    $device_name=$odetail->device_name;
    $server_datetime=$odetail->server_datetime;
    $latitude=$odetail->latitude;
    $longitude=$odetail->longitude;
    $weekdays=$odetail->weekdays;
    $weekdays=substr($weekdays,0,3);
    $order_id=$odetail->order_id;
    $dsf_code=$odetail->dsf_code;
    $branch_code=$odetail->branch_code;
    $trip_no=$odetail->trip_no;
    $day_id=$odetail->day_id;
    $cash_credit=$odetail->cash_credit;
    $unique_key=$odetail->unique_key;

    $booking_datetime=date("m/d/Y h:i:s a", strtotime($booking_datetime));
    

   

$activeWorksheet->setCellValue('A'. $rowcount, $customer_code);
$activeWorksheet->setCellValue('B'. $rowcount,  $product_code);
$activeWorksheet->setCellValue('C'. $rowcount,  $rate);
$activeWorksheet->setCellValue('D'. $rowcount, $quantity);
$activeWorksheet->setCellValue('E'. $rowcount, $booking_datetime);
$activeWorksheet->setCellValue('F'. $rowcount, $device_name);
$activeWorksheet->setCellValue('G'. $rowcount, $booking_datetime);
$activeWorksheet->setCellValue('H'. $rowcount, $latitude);
$activeWorksheet->setCellValue('I'. $rowcount, $longitude);
$activeWorksheet->setCellValue('J'. $rowcount, $weekdays);
$activeWorksheet->setCellValue('K'. $rowcount, $unique_key);
$activeWorksheet->setCellValue('L'. $rowcount, $dsf_code);
$activeWorksheet->setCellValue('M'. $rowcount, $branch_code);
$activeWorksheet->setCellValue('N'. $rowcount, $trip_no);
$activeWorksheet->setCellValue('O'. $rowcount, $day_id);
$activeWorksheet->setCellValue('P'. $rowcount, $cash_credit);
$activeWorksheet->setCellValue('Q'. $rowcount, $unique_key);

$rowcount++;
$index=$index +1;
}

$writer = new Csv($spreadsheet);
$writer->setEnclosureRequired(false);
//$writer->save('hello world.xlsx');
header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=$unique_key.csv");
$writer->save('php://output');

?>