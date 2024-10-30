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
					<div class="breadcrumb-title pe-3">Add Must Selling Product</div>
					<?php 
					$branchname="";
					$fg_branch="select * from sb_branch_master where branch_code='$brid'";
					$run_branch=mysqli_query($con,$fg_branch);
					if(mysqli_num_rows($run_branch) !=0)
					{
						$row_branch=mysqli_fetch_array($run_branch);
						$branchname=$row_branch['branch_name'];
						echo "&nbsp;&nbsp;&nbsp;<span style='color:black'>".$branchname." Branch </span>";
					}
					?>
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
					<form method="get" action="<?php echo PROJECTURL ?>/addmustsellingproduct3">
						@csrf
					<div class="row">
						<input type="hidden" value="<?php echo $brid ?>" name="brid">
						<div class="col-sm-2 col-6"  style="">
							<input type="text" placeholder="Product Code / Name" id="pro_code" name="pro_code" value="{{$pro_code}}" style="height: 30px" class="form-control">
						</div>
						<div class="col-sm-4 col-6"  style="">
							<select class="form-control" style="" name="pro_company" id="pro_company" >
								<option value="{{$c_code}}">{{$c_name}}</option>
								@foreach($companymaster as $branch)
								<option value="{{$branch->company_code}}">{{$branch->company_code}} - {{$branch->company_title}}</option>

								@endforeach
							</select>
						</div>
						
						

						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/addmustsellingproduct" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
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
									<th style="text-align:center;">Branch Name</th>
									<th style="text-align:center;">Company Name</th>
									<th style="text-align:center">Product Name</th>
									<th style="text-align:center;width:100px">Action</th>	

									</tr>
								</thead>
								<tbody>
								
									@foreach($productdata as $user)
									<?php 
         

            $compname="";
            $fg_br="SELECT company_title FROM `sb_company_master` where company_code='$user->company_code'";
            $run_br=mysqli_query($con,$fg_br);
            if(mysqli_num_rows($run_br) !=0)
            {
            	$row_br=mysqli_fetch_array($run_br);
            	$compname=$row_br['company_title'];
            }
									?>
									<tr>
									<td style="text-align: center;font-size:13px">{{$brid}} - {{$branchname}}</td>
									<td style="font-size:13px">{{$user->company_code}} - {{$compname}}</td>
								<td style="font-size:13px">{{$user->product_code}} - {{$user->product_name}}</td>	
				<td style="font-size:13px">
				<center>
				<?php 
				$fg_brj="SELECT * FROM `sb_must_selling_product` where branch_code='$brid' AND product_code='$user->product_code' AND is_active=1";
				$run_brj=mysqli_query($con,$fg_brj);
				if(mysqli_num_rows($run_brj) ==0){ 
				?>	
				<a  style='display: inline-block;font-size:11px' class='showpopup2 text-success' data-brcode='<?php echo $brid ?>'  data-prodcode='<?php echo $user->product_code ?>'  data-prodname='<?php echo $user->product_name ?>' >
				Click to Add in MSL
				</a>
				<?php } else{ ?>
				<a  style='display: inline-block;font-size:11px' class='text-danger' >
				Already in MSL
				</a>
				<?php } ?>
			</center>
				</td>
								
								
										
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;">Branch Name</th>
									<th style="text-align:center;">Company Name</th>
									
									<th style="text-align:center">Product Name</th>
									<th style="text-align:center">Action</th>
									</tr>
								</tfoot>
							</table>
							<div style="margin-top: 10px">
							{{$productdata->links()}}
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


<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel">Product : 
	<span id="prodname2"></span></h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
         <input type='hidden' id='prodcode2'>
		 <input type='hidden' id='brcode2'>


		 <div class="col-sm-12">
          <br>
           <label>Must Sell Qty</label>
		   <input class="form-control" name="mst_qty" id="mst_qty" style="border:1px solid black;" 
		    />
		   
         
         </div>
        
       



         <div class="col-sm-12">
<br>
		   <button id="btnaddschedule" class="btn btn-success" style="padding-top: 10px;padding-bottom: 10px;padding-right: 15px;padding-left: 15px;">Submit</button>
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

$('.showpopup2').click(function(){

$('#exampleModal2').modal('show');
var brcode=$(this).data('brcode');
var prodcode=$(this).data('prodcode');
var prodname=$(this).data('prodname');


$('#brcode2').val(brcode);
$('#prodcode2').val(prodcode);
$('#prodname2').text(prodname);
});

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
	$('#btnaddschedule').on('click',function(){
    
    var brcode2=$('#brcode2').val();
    var prodcode2=$('#prodcode2').val();
	var mst_qty=$('#mst_qty').val();
    

    $.ajax({
            type:"POST",
            url: "{{ url('addproductmst') }}",
            data: { brcode: brcode2,prodcode: prodcode2,mst_qty:mst_qty},
            dataType: 'json',
            success: function(res){

				$('#closebtn').click();
                location.reload(true);
              
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
