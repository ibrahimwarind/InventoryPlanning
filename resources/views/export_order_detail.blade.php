@include('vendor/autoload')
<?php
error_reporting(0);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$con=mysqli_connect('127.0.0.1','ytwpdncbtd','9DKdWEz2sr','ytwpdncbtd');

$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);

$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->setCellValue('A1', 'Unique Key');
$activeWorksheet->setCellValue('B1', 'DSF Code');
$activeWorksheet->setCellValue('C1', 'DSF Name');
$activeWorksheet->setCellValue('D1', 'Branch Code');
$activeWorksheet->setCellValue('E1', 'Branch Name');
$activeWorksheet->setCellValue('F1', 'Customer Code');
$activeWorksheet->setCellValue('G1', 'Customer Name');
$activeWorksheet->setCellValue('H1', 'Product Code');
$activeWorksheet->setCellValue('I1', 'Product Name');
$activeWorksheet->setCellValue('J1', 'Qty');
$activeWorksheet->setCellValue('K1', 'Rate');
$activeWorksheet->setCellValue('L1', 'Amount');
$activeWorksheet->setCellValue('M1', 'Order Date');
$activeWorksheet->setCellValue('N1', 'Booking Datetime');
$activeWorksheet->setCellValue('O1', 'Route');
$activeWorksheet->setCellValue('P1', 'Comment');



$rowcount=2;$index=1;
foreach($orderdetail as $odetail)
{
    $unique_key=$odetail->unique_key;
    $dsf_code=$odetail->dsf_code;
    $dsf_name=$odetail->dsf_name;
    $branch_code=$odetail->branch_code;
    $branch_name=$odetail->branch_name;
    $customer_code=$odetail->customer_code;
    $customer_name=$odetail->customer_name;
    $product_code=$odetail->product_code;
    $product_name=$odetail->product_name;
    $quantity=$odetail->quantity;
    $booking_datetime=$odetail->booking_datetime;
    $rate=$odetail->rate;
    $amount=$rate * $quantity;
    $route=$odetail->route;
    $comment=$odetail->comment;
   
    $booking_datetime=date("d-m-Y h:i:s", strtotime($booking_datetime));



   
    $fg_code="SELECT order_date FROM `sb_order_master` where unique_key='$unique_key'";
    $run_code=mysqli_query($con,$fg_code);
    $row_code=mysqli_fetch_array($run_code);
    $order_date=$row_code['order_date'];
    $order_date=date("d-m-Y", strtotime($order_date));
   

$activeWorksheet->setCellValue('A'. $rowcount, $unique_key);
$activeWorksheet->setCellValue('B'. $rowcount,  $dsf_code);
$activeWorksheet->setCellValue('C'. $rowcount,  $dsf_name);
$activeWorksheet->setCellValue('D'. $rowcount, $branch_code);
$activeWorksheet->setCellValue('E'. $rowcount, $branch_name);
$activeWorksheet->setCellValue('F'. $rowcount, $customer_code);
$activeWorksheet->setCellValue('G'. $rowcount, $customer_name);
$activeWorksheet->setCellValue('H'. $rowcount, $product_code);
$activeWorksheet->setCellValue('I'. $rowcount, $product_name);
$activeWorksheet->setCellValue('J'. $rowcount, $quantity);
$activeWorksheet->setCellValue('K'. $rowcount, $rate);
$activeWorksheet->setCellValue('L'. $rowcount, $amount);
$activeWorksheet->setCellValue('M'. $rowcount, $order_date);
$activeWorksheet->setCellValue('N'. $rowcount, $booking_datetime);
$activeWorksheet->setCellValue('O'. $rowcount, $route);
$activeWorksheet->setCellValue('P'. $rowcount, $comment);

$rowcount++;
$index=$index +1;
}

$writer = new Xlsx($spreadsheet);
//$writer->save('hello world.xlsx');
header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=Order Detail - $uid.xlsx");
$writer->save('php://output');
?>