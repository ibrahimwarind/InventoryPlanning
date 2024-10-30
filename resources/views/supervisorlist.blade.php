
@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME); ?>
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
					<div class="breadcrumb-title pe-3">Supervisor List</div>
					
					<div class="ms-auto">
						<div class="btn-group">
							
							<button type="button" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Total Supervisor : {{number_format($totaluser)}}</button>

							
							
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
					
				</h6>
				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">
							<table id="example" class="table table-striped table-bordered" >
								<thead>
									<tr>
										<th style="text-align:center">Name</th>
										<th style="text-align:center">Login Name</th>
										<th style="text-align:center">Password</th>
										<th style="text-align:center;">Branch</th>
										<th style="text-align:center">Group's</th>
										<th style="text-align:center">Action</th>

									</tr>
								</thead>
								<tbody>
									@foreach($userdata as $user)
									<?php 
									$compid=$user->branch_id;
									$fg_pro="select * from b2b_branch where id='$compid'";
									$run_pro=mysqli_query($con,$fg_pro);
									$row_pro=mysqli_num_rows($run_pro);
									if($row_pro !=0)
									{
										$cn_pro=mysqli_fetch_array($run_pro);
										$branch_name=$cn_pro['branch_name'];

									}else{
										$branch_name="";
									}
                                    $groupcode="";
                                    $fg_groupdata="SELECT sp.auto_id,sb.supply_groupname FROM `sb_supervisor_businessgroup` sp,sb_business_line sb where sp.group_id=sb.supply_groupid AND sp.supervisor_code='$user->user_code' AND sp.branch_id='$compid'  AND sb.branch_code='$compid' group by sb.supply_groupid";
                                    $run_groupdata=mysqli_query($con,$fg_groupdata);
                                    if(mysqli_num_rows($run_groupdata) !=0)
                                    {
                                        while($row_groupdata=mysqli_fetch_array($run_groupdata))
										{

										$supply_groupname=$row_groupdata['supply_groupname'];
                                        $groupcode.=$supply_groupname." ,";
									    
									    }
                                    
                                    }
                                    

									?>
									<tr>
										<td style='text-align:center'>{{$user->user_code}} - {{$user->user_name}}</td>
										<td style='text-align:center'>{{$user->admin_name}}</td>
										<td style='text-align:center'>{{$user->admin_password}}</td>
                                        <td style='text-align:center'>{{$branch_name}}</td>
										<td style='text-align:center'>{{$groupcode}}</td>

										
										<td style="width:60px !important">
											<center>
											
											<a href="<?php echo PROJECTURL ?>/editsupervisor/{{$user->admin_id}}"  target="_blank" style='display: inline-block;'>
												<i class="fa fa-pen" aria-hidden="true"></i>
											</a>
											
										</center>
											
										</td>
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center">Name</th>
										<th style="text-align:center">Login Name</th>
										<th style="text-align:center">Password</th>
										<th style="text-align:center;">Branch</th>
										<th style="text-align:center">Group's</th>
										<th style="text-align:center">Action</th>
									</tr>
								</tfoot>
							</table>

							<div style="margin-top: 10px">
							{{$userdata->links()}}
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
		$('#pro_brand').chosen();
        $('#pro_company').chosen();
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
	</script>
	<script>
		$('#shownotice').hide();
		// $(document).ready(function() {
		// 	var table = $('#example2').DataTable( {
		// 		lengthChange: false,
		// 		"pageLength": 100,
		// 		buttons: [ 'copy', 'excel', 'pdf', 'print']
		// 	} );
		 
		// 	table.buttons().container()
		// 		.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		// } );
		$('#exportexcel').click(function(){
		$('#shownotice').show();

              
		});

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
            url: "{{ url('companystatuschange') }}",
            data: { id: id,status:status },
            dataType: 'json',
            success: function(res){
            	openForm();
            	closeForm();
           // alert(res);
            	
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
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


</html>
