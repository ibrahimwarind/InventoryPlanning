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
					<div class="breadcrumb-title pe-3">Supervisor Product List</div>
					
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
					<form method="get" action="<?php echo PROJECTURL ?>/supervisorproducts2">
						@csrf
					<div class="row">
						<div class="col-sm-2 col-6"  style="">
							<input type="text" placeholder="Product Code" id="pro_code" name="pro_code" value="{{$prodcode}}" style="height: 30px" class="form-control">
						</div>
						<div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_company" id="pro_company" >
								<option value="{{$c_code}}">{{$c_name}}</option>
								@foreach($companymaster as $branch)
								<option value="{{$branch->company_code}}">{{$branch->company_code}} - {{$branch->company_title}}</option>

								@endforeach
							</select>
						</div>
                        <div class="col-sm-2 col-3"  style="">
							<select class="form-control" style="" name="pro_active" id="pro_active" style='height:30px'>
								<option value="{{$activecode}}">{{$activestatus}}</option>
								<option value="1">Active</option>
                                <option value="0">In Active</option>
							</select>
						</div>
						
						

						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/supervisorproducts" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
						</div>
					</div>
				   </form>
				</h6>
				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">
							<table id="example" class="table table-striped table-bordered" >
								<thead>
									<tr>

									<th style="text-align:center">Product Name</th>
                                    <th style="text-align:center">Price(T.P)</th>
                                    <th style="text-align:center">Productive</th>
									<th style="text-align:center">Action</th>	

									</tr>
								</thead>
								<tbody>
									@foreach($productdata as $user)
                                    <?php 
                                    $prodstatus="Not Productive";
                                    if($user->is_productive == "1")
                                    {
                                        $prodstatus="Productive";
                                    }
                                    ?>
									
								<tr>

								<td style="">{{$user->product_code}} - {{$user->product_name}}<br>
                                 <b>Company Name : </b> {{$user->company_code}} - {{$user->company_title}}</td>
                                 <td style="text-align:center">Rs : {{$user->price}}</td>	
                                 <td style="text-align:center">{{$prodstatus}}</td>	
								<td style="width:100px">
                                <select class="form-control statuschange" name="pro_status" id="pro_status" data-id='{{$user->attribute_id}}'
											style='display:inline-block;font-size:13px'>
												{{$status=$user->active_status;}}
												@if($status==1)
												<option value="1">Active</option>
												<option value="0">In-Active</option>
												@elseif($status==0)
												<option value="0">In-Active</option>
												<option value="1">Active</option>
												
												@endif

											</select>
				                </td>
								
								
										
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center">Product Name</th>
                                    <th style="text-align:center">Price(T.P)</th>
                                    <th style="text-align:center">Productive</th>
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

  $(document).ready(function(){
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

          //change status
	   //change status
       $('.statuschange').on('change',function(){
		var id=$(this).data('id');
		var status=$(this).val();
		

		$.ajax({
            type:"POST",
            url: "{{ url('productstatuschange2') }}",
            data: { id: id,status:status },
            dataType: 'json',
            success: function(res){
             
             openForm();
             closeForm();
            	
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
