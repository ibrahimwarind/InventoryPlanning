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
					<div class="breadcrumb-title pe-3">Group Mapping List</div>
					
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
					<form method="get" action="<?php echo PROJECTURL ?>/groupmappinglist2">
						@csrf
					<div class="row">
						
						<div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_company" id="pro_company" required="">
								<option value="" hidden="">Select Company</option>
								@foreach($companymaster as $comp)
								<option value="{{$comp->company_code}}">{{$comp->company_code}} - {{$comp->company_title}}</option>

								@endforeach
							</select>
						</div>
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="permanent_temporary" id="permanent_temporary" >
								<option value="" hidden="">Select Type</option>
								<option value="1">Permanent</option>
								<option value="2">Temporary</option>

								 
							</select>
						</div>
						
						

						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/groupmappinglist" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
						</div>
						
                       

					</div>
				   </form>
				</h6>
				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">
							<table class="table table-striped table-bordered" >
								<thead>
									<tr>
									<th style="text-align:center;">Group Name</th>
									<th style="text-align:center">Formula Type</th>
									<th style="text-align:center">Formula Detail</th>
									<th style="text-align:center">Company Name</th>
									<th style="text-align:center">Type</th>
									<th style="text-align:center">Total Product</th>
									<th style="text-align:center">Action</th>
										

									</tr>
								</thead>
								<tbody>
									
							@foreach($groupdata as $user)
							<?php 
							$fmname="";
							$fg_grpdata="SELECT formula_name FROM `ipl_formula_master` where formula_id='$user->formula_id'";
							$run_grpdata=mysqli_query($con,$fg_grpdata);
							if(mysqli_num_rows($run_grpdata) !=0)
							{
                             $row_grpdata=mysqli_fetch_array($run_grpdata);
                             $fmname=$row_grpdata['formula_name'];
							}

							$fmdetail="";
							$fg_grpdetail="SELECT formula_description FROM `ipl_formula_detail` where detail_id='$user->formula_detail_id'";
							$run_grpdetail=mysqli_query($con,$fg_grpdetail);
							if(mysqli_num_rows($run_grpdetail) != 0)
							{
                             $row_grpdetail=mysqli_fetch_array($run_grpdetail);
                             $fmdetail=$row_grpdetail['formula_description'];
							}

							$compname="";
							$fg_compdata="SELECT company_title FROM `ipl_company_master` where company_code='$user->company_id'";
							$run_compdata=mysqli_query($con,$fg_compdata);
							if(mysqli_num_rows($run_compdata) !=0)
							{
								$row_compdata=mysqli_fetch_array($run_compdata);
								$compname=$row_compdata['company_title'];
							}

							$fg_gettotal="SELECT ifnull(count(product_code),0) as totalproduct FROM `ipl_group_mapping` where group_id='$user->group_id'";
							$run_gettotal=mysqli_query($con,$fg_gettotal);
							$row_gettotal=mysqli_fetch_array($run_gettotal);
							$totalproduct=$row_gettotal['totalproduct'];

							$grouptype="";
							if($user->permanent_temporary == 1)
							{
								$grouptype="Permanent";
							}
							else if($user->permanent_temporary == 2){
								$grouptype="Temporary";
							}

							?>
									
							<tr>
								<td>{{$user->group_name}}</td>
								<td>{{$fmname}}</td>
								<td>{{$fmdetail}}</td>
								<td>{{$compname}}</td>
								<td style="text-align: center">{{$grouptype}}</td>
								<td style="text-align: center">{{number_format($totalproduct)}}</td>	
								<td><center><a href="/viewgroupmapping/{{$user->group_id}}/{{$user->permanent_temporary}}" target="_blank" class="text-success">
								View</a></center></td>
										
										
										
									</tr>
							@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;">Group Name</th>
									<th style="text-align:center">Formula Type</th>
									<th style="text-align:center">Formula Detail</th>
									<th style="text-align:center">Company Name</th>
									<th style="text-align:center">Group Type Name</th>
									<th style="text-align:center">Total Product</th>
									<th style="text-align:center">Action</th>
									</tr>
								</tfoot>
							</table>
                           
							
						</div>
					</div>

 
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
			<p class="mb-0">Copyright © 2022. All right reserved.</p>


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
$('#pro_company').chosen();
$('#permanent_temporary').chosen();

  
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


	</script>
	<script>
		

 
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


</html>