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
					<div class="breadcrumb-title pe-3">Order Detail </div>
					
					<div class="ms-auto">
						<div class="btn-group">
							
							<button type="button" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Total Entry : {{number_format($mastercount)}}</button>

							
							
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
						@csrf
					<div class="row">
						<div class="col-sm-3">
                         <p style='font-size:12px;color:black'>Dsf Name : {{$dsfname}}</p>
                        </div>
                        <div class="col-sm-3">
                         <p style='font-size:12px;color:black'>Branch Name : {{$branchname}}</p>
                        </div>
                        <div class="col-sm-3">
                         <p style='font-size:12px;color:black'>Order ID : {{$uqid}}</p>
                        </div>
                        <div class="col-sm-3">
                         <p style='font-size:12px;color:black'>CSV UploadID : {{$sysid}}</p>
                        </div>
                        <p style='font-size:15px;margin-top:5px;border-bottom:1px solid green;text-align:center'><span class='text-danger'>"</span>Please Review the CSV order & Press the Button Place Order ! <span class='text-danger'>"</span></p>
					</div>
				</h6>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">
							<table  class="table table-striped table-bordered" >
								<thead>
									<tr>
									<th style="text-align:center;">Date</th>
									<th style="text-align:center">Customer</th>
									<th style="text-align:center">Product</th>
									<th style="text-align:center">Rate</th>	
                                    <th style="text-align:center">Qty</th>	
                                    <th style="text-align:center">BookDatetime</th>	
                                    <th style="text-align:center">WeekDay</th>	
                                    <th style="text-align:center">Comment</th>

									</tr>
								</thead>
								<tbody>
                                    <?php $ordertotal=0;$unique_key=""; ?>
									@foreach($masterdata as $user)
									<?php 
         

            $compname="";
            $fg_br="SELECT customer_name FROM `sb_customers` where branch_code='$user->branch_code' AND customer_code='$user->cust_code'";
            $run_br=mysqli_query($con,$fg_br);
            if(mysqli_num_rows($run_br) !=0)
            {
            	$row_br=mysqli_fetch_array($run_br);
            	$compname=$row_br['customer_name'];
            }

            $prodname="";
            $fg_br2="SELECT product_name FROM `sb_products` where product_code='$user->prod_code'";
            $run_br2=mysqli_query($con,$fg_br2);
            if(mysqli_num_rows($run_br2) !=0)
            {
            	$row_br2=mysqli_fetch_array($run_br2);
            	$prodname=$row_br2['product_name'];
            }
            $ordertotal=$ordertotal + ($user->rate * $user->quantity);
			$unique_key=$user->unique_key;
									?>
									<tr>
                                <td style="text-align: center">{{date("d-M-Y", strtotime($user->order_date))}}</td>
								<td style="">{{$compname}}</td>
                                <td style="">{{$prodname}}</td>
								<td style="text-align: center">{{$user->rate}}</td>	
                                <td style="text-align: center">{{$user->quantity}}</td>	
								<td style="text-align: center">{{date("d-M-Y h:i:s", strtotime($user->booking_datetime))}}</td>
								<td style="text-align: center">{{$user->week_day}}</td>
                                <td style="">{{$user->comment}}</td>
								
								
										
										
									</tr>
									@endforeach
								</tbody>
							
							</table>

                            <center>
                                <form method="get" id="subform" action="<?php echo PROJECTURL ?>/csvplaceorder">
                                    <input type="hidden" value="{{$sysid}}" name="sysid">
                                    <input type="hidden" value="{{$mastercount}}" name="count">
                                    <input type="hidden" value="{{$ordertotal}}" name="ordertotal">
                                    <?php
									$fg_uqchk="SELECT * FROM `sb_order_master` where unique_key='$unique_key' AND day_over='Y'";
									$run_uqchk=mysqli_query($con,$fg_uqchk);
									$row_uqchk=mysqli_num_rows($run_uqchk);
									
									if($mastercount !=0 && $row_uqchk == 0){ ?>
                                    <input type="submit" value="Place Order" id="btnsub" class="btn btn-primary">
                                    <?php }  if($row_uqchk != 0){
										echo "<center><b class='text-danger'>This Order Day End Already Completed !</b></center>";
									} ?>
                                </form>

                            </center>
                           
							
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
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


</html>
<script>
	$('#btnsub').click(function(){
    $('#btnsub').prop('disabled',true);
	$('#btnsub').hide();
	$('#subform').submit();
	setTimeout(function() { 
		window.open('https://booster.b2bpremier.com/orderlist','_self');
    }, 3000);
	
	});
	
</script>
