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
					<div class="breadcrumb-title pe-3">DSF SAF Report Summarize</div>
					
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
					<form method="get" action="<?php echo PROJECTURL ?>/dsfsafereportsummarize2">
						@csrf
					<div class="row">
						
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_branch" id="pro_branch">
								<?php 
								if($pro_branch == "")
								{
                            echo "<option value='' hidden=''>Select Branch</option>";
								}
								else
								{
                            echo "<option value='$pro_branch'>$pro_branch - $pro_branch_name</option>";
								}?>
								@foreach($branchmaster as $branch)
								<option value="{{$branch->branch_code}}">{{$branch->branch_code}} - {{$branch->branch_name}}</option>

								@endforeach
							</select>
						</div>
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_dsf" id="pro_dsf">
								<?php 
								if($pro_dsf == "")
								{
                            echo "<option value='' hidden=''>Select DSF</option>";
								}
								else
								{
                            echo "<option value='$pro_dsf'>$pro_dsf - $pro_dsf_name</option>";
								}?>
								
							</select>
						</div>
						
						
						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/dsfsafereportsummarize" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
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
									<th style="text-align:center;width: 300px">Branch Name</th>
									<th style="text-align:center">Group Name</th>
									<th style="text-align:center">DSF Name</th>
									<th style="text-align:center">Monday</th>
									<th style="text-align:center">Tuesday</th>
									<th style="text-align:center">Wednesday</th>
									<th style="text-align:center">Thursday</th>
									<th style="text-align:center">Friday</th>
									<th style="text-align:center">Saturday</th>
										

									</tr>
								</thead>
								<tbody>
							<?php $oldbranch=""?>
			@foreach($dsfsafdata as $user)
			<?php 



            $brname="";
            $fg_br="SELECT branch_name FROM `sb_branch_master` where branch_code='$user->branch_code'";
            $run_br=mysqli_query($con,$fg_br);
            if(mysqli_num_rows($run_br) !=0)
            {
            	$row_br=mysqli_fetch_array($run_br);
            	$brname=$row_br['branch_name'];
            }

            $gpname="";
            $fg_gp="SELECT supply_groupname FROM `sb_dsf_businessline` where dsf_code='$user->dsf_code' AND branch_code='$user->branch_code'";
            $run_gp=mysqli_query($con,$fg_gp);
            if(mysqli_num_rows($run_gp) !=0)
            {
            	$row_gp=mysqli_fetch_array($run_gp);
            	$gpname=$row_gp['supply_groupname'];
            }

           

if($oldbranch !=$user->branch_code)
{
	echo "
<tr style='background-color:#679149'>
<th colspan='9'>Branch : ".$user->branch_code." - ".$brname."</th>
</tr>
	";
} 

//calcu monday
$fg_monday="SELECT ifnull(count(DISTINCT customer_code),0) as mondaycustomer FROM `sb_dsf_saf_detail` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND day_name='MONDAY'";
$run_monday=mysqli_query($con,$fg_monday);
$row_monday=mysqli_fetch_array($run_monday);
$mondaycustomer=$row_monday['mondaycustomer'];

//calcu tuesday
$fg_tuesday="SELECT ifnull(count(DISTINCT customer_code),0) as tuesdaycustomer FROM `sb_dsf_saf_detail` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND day_name='TUESDAY'";
$run_tuesday=mysqli_query($con,$fg_tuesday);
$row_tuesday=mysqli_fetch_array($run_tuesday);
$tuesdaycustomer=$row_tuesday['tuesdaycustomer'];

//calcu wednesday
$fg_wednesday="SELECT ifnull(count(DISTINCT customer_code),0) as wednesdaycustomer FROM `sb_dsf_saf_detail` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND day_name='WEDNESDAY'";
$run_wednesday=mysqli_query($con,$fg_wednesday);
$row_wednesday=mysqli_fetch_array($run_wednesday);
$wednesdaycustomer=$row_wednesday['wednesdaycustomer'];

//calcu thursday
$fg_thursday="SELECT ifnull(count(DISTINCT customer_code),0) as thursdaycustomer FROM `sb_dsf_saf_detail` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND day_name='THURSDAY'";
$run_thursday=mysqli_query($con,$fg_thursday);
$row_thursday=mysqli_fetch_array($run_thursday);
$thursdaycustomer=$row_thursday['thursdaycustomer'];

//calcu friday
$fg_friday="SELECT ifnull(count(DISTINCT customer_code),0) as fridaycustomer FROM `sb_dsf_saf_detail` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND day_name='FRIDAY'";
$run_friday=mysqli_query($con,$fg_friday);
$row_friday=mysqli_fetch_array($run_friday);
$fridaycustomer=$row_friday['fridaycustomer'];

//calcu saturday
$fg_saturday="SELECT ifnull(count(DISTINCT customer_code),0) as saturdaycustomer FROM `sb_dsf_saf_detail` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code' AND day_name='SATURDAY'";
$run_saturday=mysqli_query($con,$fg_saturday);
$row_saturday=mysqli_fetch_array($run_saturday);
$saturdaycustomer=$row_saturday['saturdaycustomer'];

?>						
<tr>
								<td style="font-size:12px">{{$user->branch_code}} - {{$brname}} </td>
								<td style="font-size:12px">{{$gpname}}</td>
								<td style="font-size:12px">{{$user->dsf_code}} - {{$user->dsf_name}}</td>	
								<td style="font-size:12px;text-align:center">{{$mondaycustomer}}</td>	
								<td style="font-size:12px;text-align:center">{{$tuesdaycustomer}}</td>	
								<td style="font-size:12px;text-align:center">{{$wednesdaycustomer}}</td>	
								<td style="font-size:12px;text-align:center">{{$thursdaycustomer}}</td>	
								<td style="font-size:12px;text-align:center">{{$fridaycustomer}}</td>
								<td style="font-size:12px;text-align:center">{{$saturdaycustomer}}</td>	
								
								
					
									</tr>
<?php $oldbranch=$user->branch_code;?>

									@endforeach
							
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;width: 300px">Branch Name</th>
									<th style="text-align:center">Group Name</th>
									<th style="text-align:center">DSF Name</th>
									<th style="text-align:center">Monday</th>
									<th style="text-align:center">Tuesday</th>
									<th style="text-align:center">Wednesday</th>
									<th style="text-align:center">Thursday</th>
									<th style="text-align:center">Friday</th>
									<th style="text-align:center">Saturday</th>
									</tr>
								</tfoot>
							</table>
                            <div style="margin-top: 10px">
							{{$dsfsafdata->links()}}
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
			<p class="mb-0">Copyright © 2023. All right reserved.</p>


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

          //change status
	


	$('#pro_branch').on('change',function(){
		var pro_branch=$('#pro_branch').val();
		var pro_group="";
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
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_code+" - "+res[i].dsf_name+"</option>").trigger('chosen:updated');

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
