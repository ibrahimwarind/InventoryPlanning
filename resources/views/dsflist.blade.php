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
					<div class="breadcrumb-title pe-3">DSF List</div>
					
					<div class="ms-auto">
						<div class="btn-group">
							
							<button type="button" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Total DSF : {{number_format($totaldsf)}}</button>

							
							
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
					<form method="get" action="<?php echo PROJECTURL ?>/dsflist2">
						@csrf
					<div class="row">
						
						<div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_branch" id="pro_branch" required="">
								<option value="{{$b_code}}">{{$b_name}}</option>
								@foreach($branchmaster as $branch)
								<option value="{{$branch->branch_code}}">{{$branch->branch_code}} - {{$branch->branch_name}}</option>

								@endforeach
							</select>
						</div>
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_group" id="pro_group">
								<option value="" hidden="">Select Group</option>
								
							</select>
						</div>
						
						

						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/dsflist" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
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
									<th style="text-align:center;width: 300px">Branch</th>
									<th style="text-align:center">DSF Name</th>
									<th style="text-align:center">CNIC</th>
									<th style="text-align:center">Group Name</th>
									<th style="text-align:center">Login Name </th>
									
									<th style="text-align:center">Last Login</th>
									<th style="text-align:center">Action</th>
										
 
									</tr>
								</thead>
								<tbody>
									<?php $oldbranch="";?>
									@foreach($dsfdata as $user)
									<?php 
 
        

            $compname="";
            $fg_br="SELECT branch_name FROM `sb_branch_master` where branch_code='$user->branch_code'";
            $run_br=mysqli_query($con,$fg_br);
            if(mysqli_num_rows($run_br) !=0)
            {
            	$row_br=mysqli_fetch_array($run_br);
            	$compname=$row_br['branch_name'];
            }
            if($oldbranch !=$user->branch_code)
{
	echo "
<tr style='background-color:#679149'>
<th colspan='7'>Branch : ".$user->branch_code." - ".$compname."</th>
</tr>
	";
} 

//getting group name
$groupstring="";
$fg_groupdata="SELECT supply_groupid,supply_groupname FROM `sb_dsf_businessline` where branch_code='$user->branch_code' AND dsf_code='$user->dsf_code'";
$run_groupdata=mysqli_query($con,$fg_groupdata);
if(mysqli_num_rows($run_groupdata)){
while($row_groupdata=mysqli_fetch_array($run_groupdata))
{
	$supply_groupid=$row_groupdata['supply_groupid'];
	$supply_groupname=$row_groupdata['supply_groupname'];
    if($groupstring !=""){$groupstring.=" , ";}
	$groupstring.=$supply_groupid . " - ".$supply_groupname;
}
}

									?>
				<tr>
				<td style="font-size: 12px;">{{$user->branch_code}} - {{$compname}}</td>
				<td style="font-size: 12px">{{$user->dsf_code}} - {{$user->dsf_name}}</td>
				<td style="font-size: 12px;text-align: center;">{{$user->dsf_nic}}</td>
				
				<td style="font-size: 12px;text-align: center;">{{$groupstring}}</td>
				<td style="font-size: 12px;text-align: center;">{{$user->loginname}}</td>
				<td style="font-size: 12px;text-align: center;">{{date("d-M-Y h:i:s", strtotime($user->last_logindate))}}</td>
			<td><select class="form-control statuschange" name="" id="pro_status" data-id='{{$user->dsf_id}}' style='display: inline-block;font-size:14px;width:60%'>
				{{
				$status=$user->dsfstatus;}}
				@if($status==1)
				<option value="1">Live</option>
				<option value="0">Block</option>
				@elseif($status==0)
				<option value="0">Block</option>
				<option value="1">Live</option>
				@endif
             	</select>
				
				<a href="<?php echo PROJECTURL ?>/dsfchangepassword/{{$user->dsf_id}}/{{$user->dsf_name}}"  target="_blank" style='display: inline-block;width:28%'>
				<i class="fa fa-pen" aria-hidden="true"></i>
				</a>
				</td>	
								
<?php $oldbranch=$user->branch_code?>			
										
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;width: 300px">Branch</th>
									<th style="text-align:center">DSF Name</th>
									<th style="text-align:center">CNIC</th>
									<th style="text-align:center">Group Name</th>
									<th style="text-align:center">Login Name </th>
									
									<th style="text-align:center">Last Login</th>
									<th style="text-align:center">Action</th>
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

          //change status
		$('.statuschange').on('change',function(){
		var id=$(this).data('id');
		var status=$(this).val();
		

		$.ajax({
            type:"POST",
            url: "{{ url('dsfstatuschange') }}",
            data: { id: id,status:status },
            dataType: 'json',
            success: function(res){
               openForm();
            	closeForm();
            
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
              	$('#pro_group').append("<option value='"+res[i].supply_groupid+"'>"+res[i].supply_groupname+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });


		});


 function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
	setTimeout(function() { 
        document.getElementById("myForm").style.display = "none";
    }, 1500);
 
}
	</script>
	<script>
		

 
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


</html>
