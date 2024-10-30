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
					<div class="breadcrumb-title pe-3">DSF Route Tracking </div>
					
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
					<form method="get" action="<?php echo PROJECTURL ?>/dsfroutetracking2">
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

            <div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_dsf" id="pro_dsf">
                              <option value='' hidden=''>Select DSF</option>
							</select>
						</div>

                        <div class="col-sm-2 col-6"  style="">
							 <input type="date" class="form-control" style='height:30px' name="pro_date" id="pro_date" value="<?php echo $curdate ?>" required>
						</div>
						
					
						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/dsfroutetracking" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
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
									<th style="text-align:center;">Customer Name</th>
                                    <th style="text-align:center;">Booking Amount</th>
                                    <th style="text-align:center;">Total SKU</th>
									<th style="text-align:center;width:200px">Booking Start Time</th>
									<th style="text-align:center;width:200px">Booking End Time</th>
									<th style="text-align:center;width:200px">Total Booking Duration</th>
										

									</tr>
								</thead>
								<tbody>
			<?php $oldgroup="";?>
			@foreach($dsfdata as $user)
			<?php 
if($oldgroup !=$user->supply_groupid)
{
	echo "
<tr style='background-color:#679149'>
<th colspan='6'>Group : ".$user->supply_groupid." - ".$user->supply_groupname." ( Branch : ".$user->branch_code." - ".$user->branch_name." )</th>
</tr>
	";
} 
$fg_checkdata="SELECT * from sb_dsf_latlng where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND date(tag_datetime) = '$curdate'";
$run_checkdata=mysqli_query($con,$fg_checkdata);
$row_checkdata=mysqli_num_rows($run_checkdata);
?>						
<tr style="background-color:lightgray;color:black;border-bottom:1px solid black">
	<th colspan='6'>{{$user->dsf_code}} - {{$user->dsf_name}} <span style='float:right !important'>
  
</span>
</th>
					
</tr>

<?php 
$oldcustomercode="";$booking_endtime="";$booking_starttime="";$index=1;$dsfdaystartbooking="";$dsfdayendbooking="";
$notusedcustcode="";$notusedstarttime="";$notusedendtime="";$notusedbookingamount=0;
$arraydata=array();$sumofbookamount=0;
$fg_getcustdata="select * from sb_order_detail where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND date(booking_datetime)='$curdate' order by booking_datetime asc";
$run_getcustdata=mysqli_query($con,$fg_getcustdata);
while($row_getcustdata=mysqli_fetch_array($run_getcustdata))
{
    $customer_code=$row_getcustdata['customer_code'];
    $booking_datetime=$row_getcustdata['booking_datetime'];
    $rate=$row_getcustdata['rate'];
    $quantity=$row_getcustdata['quantity'];

   $sumofbookamount=$sumofbookamount + ($rate * $quantity);

   if($index == 1)
   {
    $dsfdaystartbooking=$booking_datetime;
   }


   if($oldcustomercode != $customer_code)
   {
    
    if (in_array($oldcustomercode, $arraydata) == false){
    array_push($arraydata,$oldcustomercode);
    if($index !=1){
    $fg_customers="SELECT customer_name FROM sb_customers where branch_code='$user->branch_code' AND customer_code='$oldcustomercode'";
    $run_customers=mysqli_query($con,$fg_customers);
    $row_customers=mysqli_fetch_array($run_customers);
    $customer_name=$row_customers['customer_name'];


    $fg_skucount="SELECT * FROM `sb_order_detail` WHERE branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND date(booking_datetime) = '$curdate' AND customer_code='$oldcustomercode' AND comment='Productive'";
    $run_skucount=mysqli_query($con,$fg_skucount);
    $row_skucount=mysqli_num_rows($run_skucount);


    $time1 = strtotime($booking_starttime);
    $time2 = strtotime($booking_endtime);
    $diff_seconds = abs($time2 - $time1);
    $minutes = floor($diff_seconds / 60);
    $seconds = $diff_seconds % 60;
    echo "
      <tr>
      <td style='font-size:14px;color:black'>$oldcustomercode - $customer_name</td>
      <td style='font-size:14px;color:black;text-align:center'>Rs ".number_format($notusedbookingamount)."</td>
      <td style='font-size:14px;color:black;text-align:center'>$row_skucount Sku</td>
      <td style='font-size:14px;text-align:center;color:black'>".date("h:i:s A", strtotime($booking_starttime))."</td>
      <td style='font-size:14px;color:black;text-align:center'>".date("h:i:s A", strtotime($booking_endtime))."</td>
      <td style='font-size:14px;color:black;text-align:center'>$minutes Minutes $seconds seconds </td>
      </tr>
    ";
    }
    $notusedbookingamount=0;

}

    $booking_starttime=$booking_datetime;
   }
   $oldcustomercode=$customer_code;
   $booking_endtime=$booking_datetime;
   $notusedbookingamount=$notusedbookingamount + ($rate * $quantity);
   $index=$index + 1;
   $dsfdayendbooking=$booking_datetime;
}
$customer_name="";
$fg_customers="SELECT customer_name FROM sb_customers where branch_code='$user->branch_code' AND customer_code='$oldcustomercode'";
$run_customers=mysqli_query($con,$fg_customers);
if(mysqli_num_rows($run_customers) !=0){
$row_customers=mysqli_fetch_array($run_customers);
$customer_name=$row_customers['customer_name'];
}

    $time1 = strtotime($booking_starttime);
    $time2 = strtotime($booking_endtime);
    $diff_seconds = abs($time2 - $time1);
    $minutes = floor($diff_seconds / 60);
    $seconds = $diff_seconds % 60;
echo "
      <tr>
      <td style='font-size:14px;color:black'>$oldcustomercode - $customer_name</td>
      <td style='font-size:14px;color:black;text-align:center'>Rs ".number_format($notusedbookingamount)."</td>
      <td></td>
      <td style='font-size:14px;text-align:center;color:black'>".date("h:i:s A", strtotime($booking_starttime))."</td>
      <td style='font-size:14px;color:black;text-align:center'>".date("h:i:s A", strtotime($booking_endtime))."</td>
      <td style='font-size:14px;color:black;text-align:center'>$minutes Minutes $seconds seconds</td>
      </tr>
    ";
?>



<?php $oldgroup=$user->supply_groupid;

$time1 = strtotime($dsfdaystartbooking);
    $time2 = strtotime($dsfdayendbooking);
    $diff_seconds = abs($time2 - $time1);
    $total_minutes = floor($diff_seconds / 60);
$hours = floor($total_minutes / 60);
$minutes = $total_minutes % 60;
$dsfname=str_replace("/","",$user->dsf_name); 
echo "
<tr style='background-color: lightgray;color:black'>
  <th><center style='color:black;font-weight:bold'>"; ?>
  <a href='https://booster.b2bpremier.com/dsfsaf_inmap/{{$user->dsf_code}}/{{$user->branch_code}}/{{$curdate}}/{{$user->branch_name}}/{{$dsfname}}/{{$hours}}/{{$minutes}}/{{$dsfdaystartbooking}}/{{$dsfdayendbooking}}' target='_blank' class='btn btn-primary' style='padding:4px !important;margin-top:-7px !important;color:white'>View SAF In Map &nbsp; <i class='fas fa-route'></i></a>
  <?php if($row_checkdata !=0){
    ?>
    <a href='https://booster.b2bpremier.com/dsfday_route/{{$user->dsf_code}}/{{$user->branch_code}}/{{$curdate}}/{{$user->branch_name}}/{{$dsfname}}/{{$hours}}/{{$minutes}}/{{$dsfdaystartbooking}}/{{$dsfdayendbooking}}' target='_blank' class='btn btn-info' style='padding:4px !important;margin-top:-7px !important;color:white'>View Route &nbsp; <i class='fas fa-route'></i></a>
   <?php } else {?>
     <a style='padding:4px !important;margin-top:-7px !important;color:white' class="btn btn-danger">No Route Available</a>
    <?php } ?>
 <?php  echo " &nbsp;&nbsp; Dsf Total : </center></th>
  <th><center>Rs : ".number_format($sumofbookamount)."</center></th>
  <th></th>
  <th colspan='2'><center style='font-size:15px'>Booking Start : ".date("h:i:s A", strtotime($dsfdaystartbooking))." &  End : ".date("h:i:s A", strtotime($dsfdayendbooking))."</center></th>
  <th style='font-size:14px;color:black;text-align:center'>$hours hours $minutes minutes</th>
</tr>
<tr>
<th colspan='6'><br></th>
</tr>

";

$sumofbookamount=0;

?>

@endforeach
								
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;">Customer Name</th>
                  <th style="text-align:center;">Booking Amount</th>
                  <th style="text-align:center;">Total SKU</th>
									<th style="text-align:center">Booking Start Time</th>
									<th style="text-align:center">Booking End Time</th>
									<th style="text-align:center">Total Booking Duration</th>
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

<div class="modal fade" data-bs-backdrop="static" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"><span id="brname"></span> Data Sync !
	</h6>
        
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/sync.gif') }}" style="height: 150px;width:150px">
           	<h5 style="margin-top: -18px">DSF SAF Detail Synchronizing Please Wait !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"> Data Sync Success !
	</h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/checked.png') }}" >
           	<h5 ><br>Synchronize Successfully Done !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"> Data Sync Failed !
	</h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/warning.png') }}" >
           	<h5  class="text-danger"><br>Synchronize Failed !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
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
    var pro_group=$('#pro_group').val();
		$('#pro_dsf').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchdsf') }}",
            data: { branchcode: pro_branch,pro_group:pro_group},
            dataType: 'json',
            success: function(res){
            	$('#pro_dsf').append("<option value='' hidden>Select Dsf</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_code +" - "+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });
   $('#pro_group').on('change',function(){
		var pro_branch=$('#pro_branch').val();
    var pro_group=$('#pro_group').val();
		$('#pro_dsf').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchdsf') }}",
            data: { branchcode: pro_branch,pro_group:pro_group},
            dataType: 'json',
            success: function(res){
            	$('#pro_dsf').append("<option value='' hidden>Select Dsf</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_code +" - "+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
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
