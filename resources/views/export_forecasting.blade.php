@include('vendor/autoload')
<?php
error_reporting(0);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$con=mysqli_connect('127.0.0.1','root','','db_ipl');

$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('W')->setWidth(20);

$activeWorksheet = $spreadsheet->getActiveSheet();

$fg_detail="SELECT sale_month1,sale_month2,sale_month3,current_stockmonth FROM `ipl_forecasting_detail` where forecasting_id=$fid";
$run_detail=mysqli_query($con,$fg_detail);
$row_detail=mysqli_fetch_array($run_detail);
$sale_month1=$row_detail['sale_month1'];
$sale_month2=$row_detail['sale_month2'];
$sale_month3=$row_detail['sale_month3'];
$current_stockmonth=$row_detail['current_stockmonth'];

$activeWorksheet->setCellValue('A1', 'Formula');
$activeWorksheet->setCellValue('B1', 'Branch');
$activeWorksheet->setCellValue('C1', 'Company');
$activeWorksheet->setCellValue('D1', 'Key');
$activeWorksheet->setCellValue('E1', 'Version');
$activeWorksheet->setCellValue('F1', 'Product');
$activeWorksheet->setCellValue('G1', date("M", strtotime($sale_month1)).' Sales');
$activeWorksheet->setCellValue('H1', date("M", strtotime($sale_month2)).' Sales');
$activeWorksheet->setCellValue('I1', date("M", strtotime($sale_month3)).' Sales');
$activeWorksheet->setCellValue('J1', 'Avg Sales');
$activeWorksheet->setCellValue('K1', 'Max Sales');
$activeWorksheet->setCellValue('L1', date("M", strtotime($current_stockmonth)).' Stock');
$activeWorksheet->setCellValue('M1', 'Br Int');
$activeWorksheet->setCellValue('N1', 'Comp Int');
$activeWorksheet->setCellValue('O1', 'Net Stock');
$activeWorksheet->setCellValue('P1', 'Suggested Plan');
$activeWorksheet->setCellValue('Q1', 'Demand');
$activeWorksheet->setCellValue('R1', 'Diff');
$activeWorksheet->setCellValue('S1', 'Final Plan Qty');
$activeWorksheet->setCellValue('T1', 'Final Plan Value');
$activeWorksheet->setCellValue('U1', 'Final Plan (Carton)');
$activeWorksheet->setCellValue('V1', 'Multiply');
$activeWorksheet->setCellValue('W1', 'Remarks');

foreach($forecastingdetail as $detail)
{
   $formula_name=$detail->formula_name;
   $branch_name=$detail->branch_name;
   $company_title=$detail->company_title;
   $shelf_life=$detail->shelf_life;
   $version_no=$detail->version_no; 
}

if($brcode == 0)
{
$fg_groupdata="SELECT det.*,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' order by det.product_code asc";
}
else{
$fg_groupdata="SELECT det.*,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' AND det.branch_row_id='$brcode' order by det.product_code asc";	
}


 $run_groupdata=mysqli_query($con,$fg_groupdata);
$rowcount=2;$index=1;
foreach($run_groupdata as $odetail)
{

$detail_id = $odetail['detail_id'];
$product_code = $odetail['product_code'];
$product_name = $odetail['product_name'];
$sale_qty1 = $odetail['sale_qty1'];
$sale_qty2 = $odetail['sale_qty2'];
$sale_qty3 = $odetail['sale_qty3'];
$avg_sales = $odetail['avg_sales'];
$max_sales = $odetail['max_sales'];
$current_stock = $odetail['current_stock'];
$current_stockmonth = $odetail['current_stockmonth'];
$in_transit_stock = $odetail['in_transit_stock'];
$manual_stock = $odetail['manual_stock'];
$net_stock = $odetail['net_stock'];
$pr_diff = $odetail['pr_diff'];
$demand = $odetail['demand'];
$pspl_diff = $odetail['pspl_diff'];
$multiply_by = $odetail['multiply_by'];
$branch_row_id = $odetail['branch_row_id'];
$remarks = $odetail['remarks'];
$final_planoutput = $odetail['final_planoutput'];
$final_output_value = $odetail['final_output_value'];
$final_output_packingsize = $odetail['final_output_packingsize'];

$brnames="";   
$fg_branch="SELECT branch_name FROM `ipl_branch_master` where branch_code='$branch_row_id'";   
$run_branch=mysqli_query($con,$fg_branch);
if(mysqli_num_rows($run_branch) !=0)
{
	$row_branch=mysqli_fetch_array($run_branch);
	$brnames=$row_branch['branch_name'];
}
    

$activeWorksheet->setCellValue('A'. $rowcount, $formula_name);
$activeWorksheet->setCellValue('B'. $rowcount,  $brnames);
$activeWorksheet->setCellValue('C'. $rowcount,  $company_title);
$activeWorksheet->setCellValue('D'. $rowcount, $key);
$activeWorksheet->setCellValue('E'. $rowcount, $version_no);
$activeWorksheet->setCellValue('F'. $rowcount, $product_code."-".$product_name);
$activeWorksheet->setCellValue('G'. $rowcount, $sale_qty1);
$activeWorksheet->setCellValue('H'. $rowcount, $sale_qty2);
$activeWorksheet->setCellValue('I'. $rowcount, $sale_qty3);
$activeWorksheet->setCellValue('J'. $rowcount, $avg_sales);
$activeWorksheet->setCellValue('K'. $rowcount, $max_sales);
$activeWorksheet->setCellValue('L'. $rowcount, $current_stock);
$activeWorksheet->setCellValue('M'. $rowcount, $in_transit_stock);
$activeWorksheet->setCellValue('N'. $rowcount, $manual_stock);
$activeWorksheet->setCellValue('O'. $rowcount, $net_stock);
$activeWorksheet->setCellValue('P'. $rowcount, $pr_diff);
$activeWorksheet->setCellValue('Q'. $rowcount, $demand);
$activeWorksheet->setCellValue('R'. $rowcount, $pspl_diff);
$activeWorksheet->setCellValue('S'. $rowcount, $final_planoutput);
$activeWorksheet->setCellValue('T'. $rowcount, $final_output_value);
$activeWorksheet->setCellValue('U'. $rowcount, $final_output_packingsize);
$activeWorksheet->setCellValue('V'. $rowcount, $multiply_by);
$activeWorksheet->setCellValue('W'. $rowcount, $remarks);





$rowcount++;
$index=$index +1;
}

$writer = new Xlsx($spreadsheet);
//$writer->save('hello world.xlsx');
header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=ForeCasting Sheet - $key.xlsx");
$writer->save('php://output');
?>