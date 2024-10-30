@include('header')
@include('PHPExcel/Classes/PHPExcel/IOFactory')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
date_default_timezone_set("Asia/Karachi");
$curr_date = date('y-m-d h:i:s');
$adminid=session('adminid');
?>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Import Order CSV</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-file me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 text-primary">Upload Order CSV File</h5>
								</div>
								<hr>

								<form method="post" enctype="multipart/form-data" class="row g-3">
									@csrf

									<div class="col-md-4">
										<label for="inputFirstName" class="form-label">Upload File</label>
										<input type="file" class="form-control" id="inputFirstName"  name="import_file" required="">
									</div>
									
							
									
								

									
									<div class="col-12">
										<p class="text-dark">{{$data}}</p>
										<button type="submit" name="usubmit" class="btn btn-primary px-5">Upload</button>
									</div>
								</form>

							</div>
						</div>
					</div>

					

				</div>
				<!--end row-->
				
				<!--end row-->
			</div>
		</div>
		<!--end page wrapper -->
		@extends('footer')
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>

    <?php 
    if(isset($_REQUEST['usubmit']))
    {
$filename=$_FILES["import_file"]["name"];		
$extension1 = explode(".", $filename);
$extension=end($extension1);

$allowed_extension = array("csv");
if(in_array($extension, $allowed_extension))
{
	$file = $_FILES["import_file"]["tmp_name"];
	$objPHPExcel = PHPExcel_IOFactory::load($file);
    $emptycheck=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	$highestcolumn=$objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
    
	if($emptycheck != 1){

	if($highestcolumn == "R"){
		
	$systemgenerateid=date('Ymd')."".rand(999,9999)."".time();	
   foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
  {
   $highestRow = $worksheet->getHighestRow();
   $bookdates=mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(0, 2)->getValue()); 
   $dsfcode=mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(1, 2)->getValue()); 
   $branchcode=mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(2, 2)->getValue()); 
   $bookdates=date("Ymd", strtotime($bookdates));
   $unique_key=$branchcode."".$dsfcode."".$bookdates;

  //  $fg_uniqcheck="SELECT * FROM `sb_order_master` where unique_key='$unique_key'";
  //  $run_uniquekey=mysqli_query($con,$fg_uniqcheck);
  //  if(mysqli_num_rows($run_uniquekey) == 0)
  //  {

   for($row=2; $row<=$highestRow; $row++)
   {
//   $orderdate =PHPExcel_Style_NumberFormat::toFormattedString(39984,PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
  $orderdate =mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
  $dsfcode = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
  $branchcode = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
  $uniquekey = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
  $customercode = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
  $productcode = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(5, $row)->getValue());
  $rate = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(6, $row)->getValue());
  $quantity = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(7, $row)->getValue());
  $bookdate = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(8, $row)->getValue());
  $devicename = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(9, $row)->getValue());
  $latitude = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(10, $row)->getValue());
  $longitude = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(11, $row)->getValue());
  $weekday = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(12, $row)->getValue());
  $tripno = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(13, $row)->getValue());
  $dayid = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(14, $row)->getValue());
  $cashcredit = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(15, $row)->getValue());
  $route = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(16, $row)->getValue());
  $comment = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(17, $row)->getValue());
  
  $ins_data="INSERT INTO sb_order_csv_log(system_generate_id,upload_datetime,post_by,order_date,dsf_code,branch_code,unique_key,cust_code,prod_code,rate,quantity,booking_datetime,device_name,latitude,longitude,week_day,trip_no,day_id,cash_credit,route,comment) VALUES 
  ('$systemgenerateid','$curr_date','$adminid','$orderdate','$dsfcode','$branchcode','$unique_key','$customercode','$productcode','$rate','$quantity','$bookdate','$devicename','$latitude','$longitude','$weekday','$tripno','$dayid','$cashcredit','$route','$comment')";
  $run_data=mysqli_query($con,$ins_data);

   }

   echo "<script>window.open('https://booster.b2bpremier.com/csv_order_detail/$systemgenerateid/$unique_key/$dsfcode/$branchcode','_self')</script>";

//  }
//  else{
// 	echo "<script>alert('This order is Already Uploaded in System')</script>";
//  }



   
   

 }
}
  else{
	echo "<script>alert('Missing Column, your file data was not Match the format please check again')</script>";
   }

  }
  else{
	echo "<script>alert('File was Empty please check the file')</script>";
   }

}
else{
  echo "<script>alert('Unvalid Extension..please upload .csv file only')</script>";
}

    }
    
    ?>
