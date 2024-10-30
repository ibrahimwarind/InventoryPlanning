@include('header')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
?>
	<link href="https://codervent.com/synadmin/demo/vertical/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<style type="text/css">

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}


	</style>
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3" style="margin-top: -20px">
					<div class="breadcrumb-title pe-3">DSF Booking Analysis </div>
					
					<div class="ms-auto">
						<div class="btn-group">
							
							
							
						</div>
						<span id="shownotice">
								<br>
							<!-- 	<p class="text-danger" style="margin-top: 5px !important">Please Wait for a while, the file was generated...</p> -->
							</span>
					</div>
				</div>
				<hr/>

				<!--end breadcrumb-->
				<h6 class="mb-0 text-uppercase">
					<form method="get" action="<?php echo PROJECTURL ?>/dsfbooking_analysis2">
						@csrf
					<div class="row">
						
						<div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_branch" id="pro_branch">
                            <option value='' hidden=''>Select Branch</option>
								
								@foreach($branchdata as $branch)
								<option value="{{$branch->branch_code}}">{{$branch->branch_code}} - {{$branch->branch_name}}</option>

								@endforeach
							</select>
						</div>
						
					
						<div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_group" id="pro_group">
                              <option value='' hidden=''>Select Group</option>
							</select>
						</div>

            

                        <div class="col-sm-2 col-6"  style="">
							 <input type="date" class="form-control" style='height:30px' name="str_date" id="str_date" value="<?php echo $startdate ?>" required>
						</div>
                        <div class="col-sm-2 col-6"  style="">
							 <input type="date" class="form-control" style='height:30px' name="end_date" id="end_date" value="<?php echo $enddate ?>" required>
						</div>
						
					
						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/dsfbooking_analysis" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
						</div>
					</div>
				   </form>
				</h6>
				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">

                        <table id="" class="table table-striped table-bordered" >
								<thead>
									<tr>
									<th style="text-align:center;width:200px">DSF</th>
                                    <th style="text-align:center;">Total SKU</th>
                                    <th style="text-align:center;">Booked SKU</th>
									<th style="text-align:center">Avg Sku Per Call</th>
									<th style="text-align:center">Avg Time Elapsed Per Day</th>
									<th style="text-align:center">Total SAF Shop</th>
                                    <th style="text-align:center">Productive Shop</th>
                                    <th style="text-align:center">Productive %</th>
                                    <th style="text-align:center">UnProductive Shop</th>
                                    <th style="text-align:center">Avg Booking per Customer</th>
                                    <th style="text-align:center">Avg Start Booking Time</th>
										

									</tr>
								</thead>
								<tbody>
                                <?php $oldgroup="";$indexing=1;$grouptotalelapsetime=0;$groupindexcount=0;$grouptotalelapsetime2=0;$groupdaycount=0;?>
			@foreach($dsfdata as $user)
			<?php 
if($oldgroup !=$user->supply_groupid)
{
   if($indexing != 1)
   {
    if($oldgroup !=$user->supply_groupid)
{
$avgminutetime=$grouptotalelapsetime / $groupindexcount;
$avghour=intdiv($avgminutetime,60);
$remainder = $avgminutetime % 60;


$avgminuteday=$grouptotalelapsetime2 / $groupdaycount;
$avgdayhour=intdiv($avgminuteday,60);
$remainderday = $avgminuteday % 60;

 echo "
 <tr style='background-color:lightgray;color:black'>
   <th colspan='3'><b style='color:black;font-weight:bold'>Group Total : </b></th>
   <th></th>
   <td style='font-size:13px;color:black;text-align:center;font-weight:bold'>$avgdayhour hours $remainderday minutes</td>
   <th colspan='5'></th>
   <th style='font-size:13px;color:black;text-align:center;font-weight:bold'>".date("h:i:s A", strtotime($avghour.":".$remainder.":00"))."</th>
 </tr>
 
 ";
$grouptotalelapsetime=0;$groupindexcount=0;$grouptotalelapsetime2=0;$groupdaycount=0;
}
   }


	echo "
<tr style='background-color:lightgray;color:black'>
<th colspan='11' style='font-size:13px'>Group : ".$user->supply_groupid." - ".$user->supply_groupname." ( Branch : ".$user->branch_code." - ".$user->branch_name." )</th>
</tr>
	";
} 
$fg_gettotalsku="SELECT ifnull(count(DISTINCT product_code),0) as totalsku FROM `sb_businessline_product` where branch_code='$user->branch_code' AND supply_groupid='$user->supply_groupid' AND is_productive=1 AND status=1";
$run_gettotalsku=mysqli_query($con,$fg_gettotalsku);
$row_totalsku=mysqli_fetch_array($run_gettotalsku);
$totalsku=$row_totalsku['totalsku'];


$fg_getotalsaf="select ifnull(count(DISTINCT customer_code),0) as totalsafcustomer from sb_dsf_saf_detail_new where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code'";
$run_getotalsaf=mysqli_query($con,$fg_getotalsaf);
$row_getotalsaf=mysqli_fetch_array($run_getotalsaf);
$totalsafcustomer=$row_getotalsaf['totalsafcustomer'];


$fg_getdonedata="SELECT ifnull(COUNT(DISTINCT det.product_code),0) AS unique_product_code,ifnull(COUNT(DISTINCT det.customer_code),0) AS unique_customer_code FROM `sb_order_master` mst inner join sb_order_detail det on mst.order_id=det.order_id where mst.branch_code='$user->branch_code' AND mst.dsf_code='$user->dsf_code' AND mst.order_date between '$startdate' AND '$enddate' AND det.comment='Productive'";
$run_getdonedata=mysqli_query($con,$fg_getdonedata);
$row_getdonedata=mysqli_fetch_array($run_getdonedata);
$unique_product_code=$row_getdonedata['unique_product_code'];
$unique_customer_code=$row_getdonedata['unique_customer_code'];

$fg_avgtime="SELECT date_format(booking_datetime,'%H') as hours,date_format(booking_datetime,'%i') as minutes FROM `sb_order_master` mst inner join sb_order_detail det on mst.order_id=det.order_id where mst.branch_code='$user->branch_code' AND mst.dsf_code='$user->dsf_code' ANd mst.order_date between '$startdate' AND '$enddate' group by date(booking_datetime) order by booking_datetime asc";
$run_avgtime=mysqli_query($con,$fg_avgtime);
$indexcount=0;$totalminute=0;
while($row_avgtime=mysqli_fetch_array($run_avgtime))
{
    $hours=$row_avgtime['hours'];
    $minutes=$row_avgtime['minutes'];

    $totalminute+=($hours * 60) + $minutes;
    $indexcount=$indexcount + 1;
}
$groupindexcount=$groupindexcount + $indexcount;
$grouptotalelapsetime=$grouptotalelapsetime + $totalminute;
$avgminutetime=$totalminute / $indexcount;
$avghour=intdiv($avgminutetime,60);
$remainder = $avgminutetime % 60;

if($totalsafcustomer == 0){$totalsafcustomer=1;}
$unproductiveshop=$totalsafcustomer - $unique_customer_code;
$exceper=($unique_customer_code * 100) / $totalsafcustomer;

$fg_elapse="SELECT min(det.booking_datetime) as starttime,max(det.booking_datetime) as endtime,mst.order_date FROM `sb_order_master` mst inner join sb_order_detail det on mst.order_id=det.order_id where mst.branch_code='$user->branch_code' AND mst.dsf_code='$user->dsf_code' AND mst.order_date between '$startdate' AND '$enddate' group by mst.order_date";
$run_elapse=mysqli_query($con,$fg_elapse);
$daycount=0;$totalelapsemint=0;
while($row_elapse=mysqli_fetch_array($run_elapse))
{
    $starttime=$row_elapse['starttime'];
    $endtime=$row_elapse['endtime'];
    $time1 = strtotime($starttime);
    $time2 = strtotime($endtime);
    $diff_seconds = abs($time2 - $time1);
    $minutes = floor($diff_seconds / 60);

    $daycount=$daycount + 1;
    $totalelapsemint= $totalelapsemint + $minutes;
    
}
$grouptotalelapsetime2=$grouptotalelapsetime2 + $totalelapsemint;
$groupdaycount=$groupdaycount + $daycount;
$avgminuteday=$totalelapsemint / $daycount;
$avgdayhour=intdiv($avgminuteday,60);
$remainderday = $avgminuteday % 60;

//avg booking amount per customer
$avgcount=0;$totalcountproduct=0;$totalcustomeramount=0;
$fg_avgcust="SELECT sum(det.quantity * det.rate) as sumpercust,count(det.product_code) as totalproduct FROM `sb_order_master` mst inner join sb_order_detail det on mst.order_id=det.order_id where mst.branch_code='$user->branch_code' AND mst.dsf_code='$user->dsf_code' AND mst.order_date between '$startdate' AND '$enddate' AND det.comment='Productive' group by mst.order_date,det.customer_code";
$run_avgcust=mysqli_query($con,$fg_avgcust);
while($row_avgcust=mysqli_fetch_array($run_avgcust))
{
    $sumpercust=$row_avgcust['sumpercust'];
    $totalproduct=$row_avgcust['totalproduct'];

    $totalcountproduct=$totalcountproduct + $totalproduct;
    $totalcustomeramount=$totalcustomeramount + $sumpercust;

    $avgcount=$avgcount + 1;
}
if($avgcount == 0){$avgcount=1;}
$avg_skupercall=$totalcountproduct / $avgcount;
$avg_bookingamount=$totalcustomeramount / $avgcount;
echo "
<tr>
<td style='font-size:13px;color:black'>$user->dsf_code - $user->dsf_name</td>
<td style='font-size:13px;color:black;text-align:center'>$totalsku</td>
<td style='font-size:13px;color:black;text-align:center'>$unique_product_code</td>
<td style='font-size:13px;color:black;text-align:center'>".number_format($avg_skupercall)."</td>
<td style='font-size:13px;color:black;text-align:center'>$avgdayhour hours $remainderday minutes</td>
<td style='font-size:13px;color:black;text-align:center'>$totalsafcustomer</td>
<td style='font-size:13px;color:black;text-align:center'>$unique_customer_code</td>
<td style='font-size:13px;color:black;text-align:center'>".number_format($exceper,2)." %</td>
<td style='font-size:13px;color:black;text-align:center'>$unproductiveshop</td>
<td style='font-size:13px;color:black;text-align:center'>Rs ".number_format($avg_bookingamount)."</td>
<td style='font-size:13px;color:black;text-align:center'>".date("h:i:s A", strtotime($avghour.":".$remainder.":00"))."</td>
</tr>
";
?>						

<?php
$oldgroup=$user->supply_groupid;
$indexing=$indexing + 1;


?>

@endforeach
<?php 
$avgminutetime=$grouptotalelapsetime / $groupindexcount;
$avghour=intdiv($avgminutetime,60);
$remainder = $avgminutetime % 60;


$avgminuteday=$grouptotalelapsetime2 / $groupdaycount;
$avgdayhour=intdiv($avgminuteday,60);
$remainderday = $avgminuteday % 60;

 echo "
 <tr style='background-color:lightgray;color:black'>
   <th colspan='3'><b style='color:black;font-weight:bold'>Group Total : </b></th>
   <th></th>
   <td style='font-size:13px;color:black;text-align:center;font-weight:bold'>$avgdayhour hours $remainderday minutes</td>
   <th colspan='5'></th>
   <th style='font-size:13px;color:black;text-align:center;font-weight:bold'>".date("h:i:s A", strtotime($avghour.":".$remainder.":00"))."</th>
 </tr>
 
 ";
$grouptotalelapsetime=0;$groupindexcount=0;$grouptotalelapsetime2=0;$groupdaycount=0;
?>

			
								
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;width:200px">DSF</th>
                                    <th style="text-align:center;">Total SKU</th>
                                    <th style="text-align:center;">Booked SKU</th>
									<th style="text-align:center;">Avg Sku Per Call</th>
									<th style="text-align:center;">Avg Time Elapsed Per Day</th>
									<th style="text-align:center;">Total SAF Shop</th>
                                    <th style="text-align:center;">Productive Shop</th>
                                    <th style="text-align:center;">Productive %</th>
                                    <th style="text-align:center;">UnProductive Shop</th>
                                    <th style="text-align:center;">Avg Booking per Customer</th>
                                    <th style="text-align:center;">Avg Start Booking Time</th>
									</tr>
								</tfoot>
							</table>
                            <div style="margin-top: 10px">
							{{$dsfdata->links()}}
							</div>
							
						</div>
					</div>
<style>
	.w-5{
		display: none;
	}
</style>
 
				</div>
				
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2024. All right reserved.</p>


		</footer>
	</div>

<!-- <button class="open-button" onclick="openForm()">Open Form</button> -->

<div class="form-popup" id="myForm">
  <div  class="form-container" style="padding: 20px">
     
     <i class="fa fa-check text-success" style="font-weight: bold;font-size: 29px;" aria-hidden="true" ></i> &nbsp;&nbsp;<span style="font-weight: bold;font-size: 20px;">Successfully Updated</span>
    
  </div>
</div>


	<!--end wrapper-->
	<!--start switcher-->

	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
	<script>
$('#pro_branch').chosen();
$('#pro_dsf').chosen();
$('#pro_group').chosen();



  
		$(document).ready(function() {

        	var table = $('#example').DataTable( {
				lengthChange: false,
				"paging": false ,
               "bInfo" : false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example_wrapper .col-md-6:eq(0)' );



// $('#example').DataTable({
//     lengthChange: false,
//     "paging": false ,
//     "bInfo" : false,
//     buttons: [ 'copy', 'excel', 'pdf', 'print']

// });
		  } );

  $(document).ready(function(){
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });



  $('#pro_branch').on('change',function(){
		var pro_branch=$('#pro_branch').val();
		$('#pro_group').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchgroup') }}",
            data: { branchcode: pro_branch},
            dataType: 'json',
            success: function(res){
            	$('#pro_group').append("<option value='' hidden>Select Group</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_group').append("<option value='"+res[i].supply_groupid+"'>"+res[i].supply_groupid +" - "+res[i].supply_groupname+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });

	});


	</script>
	<script>
		

 
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


</html>
