
@include('header')
<?php  $con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd"); ?>
	<link href="https://codervent.com/synadmin/demo/vertical/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<style type="text/css">
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
					<div class="breadcrumb-title pe-3">Pending Saf Open Request</div>
					<!-- <div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="/index"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Product List</li>
							</ol>
						</nav>
					</div> -->
					<div class="ms-auto">
						<div class="btn-group">
					
						</div>
						<span id="shownotice">
								<br>
<!-- 								<p class="text-danger" style="margin-top: 5px !important">Please Wait for a while, the file was generated...</p> -->
							</span>
					</div>
				</div>
				<hr/>

				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
                            <form method="GET" onsubmit="return confirm('Are you sure you want to perform this action?');">
							<input type="checkbox" id="checkAll"> &nbsp;Check All &nbsp;&nbsp;&nbsp;
                            <input type="submit" class="btn btn-primary"  value="Click to Approve Multiple" name="btnmulti" id="btnmulti" >
							<input type="submit" class="btn btn-danger"  value="Click to Reject Multiple" name="btnmulti2" id="btnmulti2" >
							<table border="1" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
									  
                                    <th style="">Dsf Name</th>
										<th style="text-align:center">Group Name</th>
										<th style="text-align:center">&nbsp;&nbsp;Request Date&nbsp;&nbsp;</th>
                                        <th style="text-align:center">&nbsp;&nbsp;Request Day&nbsp;&nbsp;</th>
										
										<th style="text-align:center">Approved/Reject</th>

									</tr>
								</thead>
								<tbody>
									@foreach($approvaldata as $product)
                            <?php 
                            $dsfname="";
                            $fg_grbranch="SELECT dsf_name FROM `sb_dsf_master` where branch_code='$product->branch_code' AND dsf_code='$product->dsf_code'";
                            $run_grbranch=mysqli_query($con,$fg_grbranch);
                            if(mysqli_num_rows($run_grbranch) !=0)
                            {
                                $row_grbranch=mysqli_fetch_array($run_grbranch);
                                $dsfname=$row_grbranch['dsf_name'];
                            }

							$groupname="";
							$fg_grp="SELECT supply_groupname FROM sb_business_line where branch_code='$branchid' AND supply_groupid='$usercode'";
							$run_grp=mysqli_query($con,$fg_grp);
							if(mysqli_num_rows($run_grp) !=0)
							{
								$row_grp=mysqli_fetch_array($run_grp);
								$groupname=$row_grp['supply_groupname'];
							}
                            ?>        
									<tr id="{{$product->request_id}}">
									<td style="font-size:16px">
                                    <input type='checkbox' class='heckbox' data-id='{{$product->request_id}}'  id='entry{{$product->request_id}}' name='entry[]' value='{{$product->request_id}}' style='display:inline-block !important'>
                                     {{$dsfname}}</td>
                                    <td style="font-size:16px"> {{$groupname}}</td>
                                    <td style="font-size:16px;text-align:center">{{date("d-M-Y", strtotime($product->req_date))}}</td>
                                    <td style="font-size:16px;text-align:center"> {{$product->req_day}}</td>
									<td><center>

											&nbsp;&nbsp;
											<select class="form-control statuschange" name="pro_status" id="pro_status" data-id='{{$product->request_id}}'
											style='display:inline-block;width: 40%;font-size:13px'>
												{{$status=$product->request_id;}}
                                                <option>Select Status</option>
												
												<option value="1">Approved</option>
												<option value="2">Reject</option>
												

											</select>
											
                        </center></td>
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="">Dsf Name</th>
										<th style="text-align:center">Group Name</th>
										<th style="text-align:center">&nbsp;&nbsp;Request Date&nbsp;&nbsp;</th>
                                        <th style="text-align:center">&nbsp;&nbsp;Request Day&nbsp;&nbsp;</th>
										
										<th style="text-align:center">Approved/Reject</th>
									</tr>
								</tfoot>
							</table>
                        </form>

							

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
		 $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
	 $('#btnmulti').show();
	 $('#btnmulti2').show();
 });

   $('#btnmulti').hide();
   $('#btnmulti2').hide();
   $(".heckbox").change(function() {
    if(this.checked) {
 $('#btnmulti').show();
 $('#btnmulti2').show();
    }

  });
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
            url: "{{ url('safopenstatuschange') }}",
            data: { id: id,status:status },
            dataType: 'json',
            success: function(res){
             openForm();
            	closeForm();
                $('#'+id).hide();
            	// if(res[0].success == true)
            	// {

            	// }
            	// else
            	// {

            	// }
            	// alert(res[0].success);
              // window.location.reload();
           }
        });
    });

   //type status
		$('.statuschange2').on('change',function(){
		var id=$(this).data('id');
		var status=$(this).val();
		

		$.ajax({
            type:"POST",
            url: "{{ url('producttypechange') }}",
            data: { id: id,status:status },
            dataType: 'json',
            success: function(res){
             openForm();
            	closeForm();
            	// if(res[0].success == true)
            	// {

            	// }
            	// else
            	// {

            	// }
            	// alert(res[0].success);
              // window.location.reload();
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


<!-- Mirrored from codervent.com/synadmin/demo/vertical/table-datatable.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Sep 2022 05:32:45 GMT -->
</html>
<?php 
if(isset($_GET['btnmulti']))
{
   // echo "<script>alert(123)</script>";
   
  
  $index=1;$outwardid="";$firstid=0;
  foreach ($_GET['entry'] as $entry) {
     
    if($index !=1)
    {
      $outwardid.=",";
    }else if($index == 1){$firstid=$entry;}     
    $outwardid.=$entry;

   $index=$index + 1;
  }

  if($index == 2)
  {
  echo "<script>alert('Please Select Multiple Approvals If you want to Approved multiple salesman')</script>";
  }
  else{
    
    $myuserid=session("adminname");
    date_default_timezone_set("Asia/Karachi");
    $currentdate=date('Y-m-d h:i:s a');

    $upd_status="update `sb_safopen_request` set approved_status=1,approved_userid='$myuserid',approved_datetime='$currentdate' where request_id in ($outwardid)";
    $run_status=mysqli_query($con,$upd_status);

    if($upd_status)
    {
echo "<script>window.open('https://booster.b2bpremier.com/safopenrequest','_self')</script>";
    }
    else{
        echo "<script>alert('Something went Wrong..please try again')</script>";
echo "<script>window.open('https://booster.b2bpremier.com/safopenrequest','_self')</script>";
    }

  }
}



if(isset($_GET['btnmulti2']))
{
   // echo "<script>alert(123)</script>";
   
  
  $index=1;$outwardid="";$firstid=0;
  foreach ($_GET['entry'] as $entry) {
     
    if($index !=1)
    {
      $outwardid.=",";
    }else if($index == 1){$firstid=$entry;}     
    $outwardid.=$entry;

   $index=$index + 1;
  }

  if($index == 2)
  {
  echo "<script>alert('Please Select Multiple Approvals If you want to Reject multiple salesman')</script>";
  }
  else{
    
    $myuserid=session("adminname");
    date_default_timezone_set("Asia/Karachi");
    $currentdate=date('Y-m-d h:i:s a');

    $upd_status="update `sb_safopen_request` set approved_status=2,approved_userid='$myuserid',approved_datetime='$currentdate' where request_id in ($outwardid)";
    $run_status=mysqli_query($con,$upd_status);

    if($upd_status)
    {
echo "<script>window.open('https://booster.b2bpremier.com/safopenrequest','_self')</script>";
    }
    else{
        echo "<script>alert('Something went Wrong..please try again')</script>";
echo "<script>window.open('https://booster.b2bpremier.com/safopenrequest','_self')</script>";
    }

  }
}
?>