@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME); ?>
	<link href="https://codervent.com/synadmin/demo/vertical/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<style type="text/css">
	
</style>

   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3" style="margin-top: -20px">
					<div class="breadcrumb-title pe-3">Order List </div>
					

					<div class="ms-auto">
						<div class="btn-group">
                        <?php 
                        if($str_date == ""){$str_date2=0;}else{$str_date2=$str_date;}
						if($ord_id == ""){$ord_id2=0;}else{$ord_id2=$ord_id;}
						if($end_date == ""){$end_date2=0;}else{$end_date2=$end_date;}
						if($brid == ""){$brid2=0;}else{$brid2=$brid;}
						if($dfid == ""){$dfid2=0;}else{$dfid2=$dfid;}
						if($dover == ""){$dover2=0;}else{$dover2=$dover;}
						if($status == ""){$status2=0;}else{$status2=$status;}
						
						?>
							<a href="<?php echo PROJECTURL ?>/exportorderlist/{{$str_date2}}/{{$end_date2}}/{{$ord_id2}}/{{$brid2}}/{{$dfid2}}/{{$dover2}}/{{$status2}}" class="btn btn-success" id="exportexcel" style="padding:2px !important;font-size:15px;height:30px">Export Excel</a>&nbsp;&nbsp;&nbsp;
							
							<button type="button" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Order : {{number_format($totalorder)}} , Amount: {{number_format($totalorderamount)}}</button>
							
							
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
					<form method="get" action="<?php echo PROJECTURL ?>/searchorder">
						@csrf
					<div class="row">
						<div class="col-sm-2 col-2">
							<input type="text" placeholder="Unique Key" class="form-control" value="{{$ord_id}}" name="ord_id" id="ord_id">
						</div>
                        <div class="col-sm-2 col-6">
							<select  class='form-control' name="branch_name" id="branch_name">
                            
								<option value="{{$brid}}">Branch</option>
								
								<?php
								
                                $fg_mem="select * from b2b_branch where status=1 AND id in ($brid)";
                                $run_mem=mysqli_query($con,$fg_mem);
                                while($row_mem=mysqli_fetch_array($run_mem))
                                {
                                $branch_name=$row_mem['branch_name'];
                                $id=$row_mem['id'];
                                echo "<option value='$id'>$branch_name</option>";
								}
								
								   ?>
                                
							</select>
			            </div>
						<div class="col-sm-2 col-6">
							<select  class='form-control' name="dsf_code" id="dsf_code">
							  <?php if($dfid == ""){ ?>	
                              <option value="" hidden="">Select DSF</option>
                              <?php } else{ 
                               $fg_df="SELECT dsf_code,dsf_name,branch_code FROM `sb_dsf_master` where loginname='$dfid'";
                               $run_df=mysqli_query($con,$fg_df);$dcode="";$dname="";$dfname="";
                               if(mysqli_num_rows($run_df) !=0){
                                $row_df=mysqli_fetch_array($run_df);
                                $dcode=$row_df['dsf_code'];
                                $dname=$row_df['dsf_name'];
								$bcode=$row_df['branch_code'];
                                $dfname=$bcode."".$dcode;
                               }  
                               echo "<option value='$dfname'>$dcode - $dname</option>";
                                ?>

                              <?php } 
                            //    $fg_dsf="select dsf_code,dsf_name,dsf_id,branch_code FROM `sb_dsf_master`";
                            //    $run_dsf=mysqli_query($con,$fg_dsf);
                            //    while($row_dsf=mysqli_fetch_array($run_dsf))
                            //    {
                            //     $dsf_code=$row_dsf['dsf_code'];
                            //     $dsf_name=$row_dsf['dsf_name'];
                            //     $dsf_id=$row_dsf['dsf_id'];
							// 	$bcode=$row_dsf['branch_code'];
                            //     $dfname=$bcode."".$dsf_code;
                            //     echo "<option value='$dfname'>$dsf_code - $dsf_name</option>";
                            //    }
                              ?>
								
								<?php 
                                // $fg_mem="select * from sb_dsf_master where branch_code in ($brid)";
                                // $run_mem=mysqli_query($con,$fg_mem);
                                // $co_mem=mysqli_num_rows($run_mem);
                                // if($co_mem != 0)
                                // {
                                // $row_mem=mysqli_fetch_array($run_mem);
                                // $dsf_id=$row_mem['dsf_id'];
                                // $dsf_code=$row_mem['dsf_code'];
                                // $dsf_name=$row_mem['dsf_name'];
                                // echo "<option value='$dsf_id'>$dsf_code - $dsf_name</option>";
								// }
                                ?>
                                
							</select>
						</div>
						<div class="col-sm-2 col-6">
							<input type="date" placeholder="" class="form-control" value="{{$str_date}}" name="str_date" id="str_date" value="{{$str_date}}">
						</div>
						<div class="col-sm-2 col-6">
							<input type="date" placeholder="" class="form-control" value="{{$end_date}}" name="end_date" id="end_date" value="{{$end_date}}">
						</div>
                        <div class="col-sm-1 col-6">
							<select  class='form-control' name="day_over" id="day_over">
					        	<?php if($dover == ""){ ?>
                                    <option value='' hidden>Day Over</option>
                                <?php } else{ ?>
                                    <option>{{$dover}}</option>
                                <?php } ?>
                                <option>Y</option>
                                <option>N</option>
								
								
								
                                
							</select>
			            </div>
                        <div class="col-sm-1 col-6">
							<select  class='form-control' name="status" id="status">
					        	<?php if($status == ""){ ?>
                                    <option value='' hidden>Status</option>
                                <?php } else{ ?>
                                    <option>{{$status}}</option>
                                <?php } ?>
                                <option>1</option>
                                <option>0</option>
								
								
								
                                
							</select>
			            </div>
                        
						
						
					
						<div class="col-sm-2 col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/orderlist" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
						</div>
					</div>
				   </form>
				</h6>
				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div id="resp">
							<table id="" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<th style="text-align:center;font-size:12px">Unique<br>ID</th>
										<th style="text-align:center;font-size:12px">DSF Name</th>
										<th style="text-align:center;font-size:12px">Date</th>
										<th style="text-align:center;font-size:12px">Total</th>
										
										<th style="text-align:center;font-size:12px">Branch</th>
										
										<th style="text-align:center;font-size:12px">Row Count</th>
										<th style="text-align:center;font-size:12px">Status</th>
                                        <th style="text-align:center;font-size:12px">Action</th>

									</tr>
								</thead>
								<tbody><?php $index=1;?>
									@foreach($orderdata as $order)
									<?php $mecount=0;
                                    $fg_shopcnt="SELECT count(DISTINCT customer_code) as totalshop FROM `sb_order_detail` where unique_key='$order->unique_key'";
                                    $run_shopcnt=mysqli_query($con,$fg_shopcnt);
                                    if(mysqli_num_rows($run_shopcnt) !=0)
                                    {
                                      $row_shopcnt=mysqli_fetch_array($run_shopcnt);
                                      $mecount=$row_shopcnt['totalshop'];

                                    }
									$ordertotal=0;
									$fg_gettingtot="SELECT ifnull(sum(rate * quantity),0) as totalamount FROM sb_order_detail where unique_key='$order->unique_key'";
									$run_gettingtot=mysqli_query($con,$fg_gettingtot);
									if(mysqli_num_rows($run_gettingtot) !=0)
									{
										$row_gettingtot=mysqli_fetch_array($run_gettingtot);
										$ordertotal=$row_gettingtot['totalamount'];
									}

									//check already excel
									$fg_alreadys="SELECT * FROM `export_bmsexcel_log` where unique_key='$order->unique_key'";
									$run_already=mysqli_query($con,$fg_alreadys);
									$row_already=mysqli_num_rows($run_already);

									
                                    ?>
									<tr id="row{{$index}}">
									
										<td style="text-align:center;width:40px !important;font-size:12px">
										 {{$order->unique_key}}
											<br><b>Day Over : {{$order->day_over}}</b>
										</td>
										<td style="width:350px !important;font-size:12px">
										<?php if($row_already !=0)
										{ ?>
<span style="font-weight:bold" class='text-info' id="rty{{$index}}"> 
{{$order->dsf_code}} - {{$order->dsf_name}}</span><br>
										<?php }
										else{ ?>
<span id="rty{{$index}}">{{$order->dsf_code}} - {{$order->dsf_name}}</span<<br>
										<?php }?>
										

                                    <b>Customer Count : </b>{{$mecount}} </span>
										<td style="text-align: center;font-size:12px;width:150px;">{{date("d-M-Y", strtotime($order->order_date))}}
                                    <br><b>Day Name : <?php echo date('l', strtotime($order->order_date)); ?></b></td>
										
										<td style="text-align: center;font-size:12px;width:85px">{{number_format($ordertotal,2)}}</td>
								        <td style="width:258px !important;font-size:12px">{{$order->branch_code}} - {{$order->branch_name}}</td>
                                        <td style="text-align: center;font-size:12px;width:65px;">{{$order->count}}</td>
                                        <td style="text-align: center;font-size:12px;width:55px;">{{$order->status}}</td>
										<td style='width:65px;'> 
                                            <a href="<?php echo PROJECTURL ?>/vieworder/{{$order->order_id}}/{{$order->unique_key}}" target="_blank" style='display: inline-block;'><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;
											<?php if(session('admintype') == "superadmin"){ ?>
											<?php  echo "<span class='showpopup2' data-orderid='$order->order_id'>
											<i class='fa fa-pen text-success' aria-hidden='true'></i></span>";
											?>&nbsp;&nbsp; <?php } ?>
											<?php if($order->is_excel_allow == 1){ ?>
											<a href="<?php echo PROJECTURL ?>/exportordercsv_formbms/{{$order->order_id}}/{{$order->unique_key}}" style='display: inline-block;' ><i class="fa fa-file-excel-o text-success" aria-hidden="true" data-id='{{$index}}' id="excelrow"></i></a>&nbsp;
							                 <?php } ?>
                                        </td>
										
									</tr>
									<?php $index=$index + 1; ?>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
                                    <th style="text-align:center;font-size:12px">Unique<br>ID</th>
										<th style="text-align:center;font-size:12px">DSF Name</th>
										<th style="text-align:center;font-size:12px">Date</th>
										<th style="text-align:center;font-size:12px">Total</th>
										
										<th style="text-align:center;font-size:12px">Branch</th>
										
										<th style="text-align:center;font-size:12px">Row Count</th>
										<th style="text-align:center;font-size:12px">Status</th>
									</tr>
								</tfoot>
							</table>

							<div style="margin-top: 10px">
							{{$orderdata->links()}}
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
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel">Order ID : 
	<span id="oid2"></span></h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
         <input type='hidden' id='oid'>

         <div class="col-sm-12">
          <br>
           <label>Reason</label>
		   <select class='form-control' name='status' id='status' style="border:1px solid black;">
            <option value='' hidden>Select Reason</option>
<option>Stock not available </option>
<option>Customer not available</option>
<option>Shop was closed</option>
<option>Order returned by Customer</option>
<option>Cash unavailability</option>
<option>Customer not interested in order</option>
<option>Expire product delivered </option>
<option>Damage product delivered </option>
<option>Wrong product delivered </option>
<option>Location is out of delivery</option>
<option>Duplicate order</option>
<option>Order cancelled by customer</option>
<option>Test Order</option>
          </select>

		   
         
         </div>

		 <div class="col-sm-12">
          <br>
           <label>Remarks / Log</label>
		   <textarea class="form-control" name="remarks" id="remarks" style="border:1px solid black;" rows="5"
		   ></textarea>
		   
         
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

	<script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
	<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
	<script>

	$('#dsf_code').chosen();
    $('#branch_name').chosen();
    $('#day_over').chosen();
    $('#status').chosen();
    
		$(document).ready(function() {

        	var table = $('#example').DataTable({
				lengthChange: false,
				"paging": false ,
               "bInfo" : false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			});
		 
			table.buttons().container()
				.appendTo( '#example_wrapper .col-md-6:eq(0)' );



// $('#example').DataTable({
//     lengthChange: false,
//     "paging": false ,
//     "bInfo" : false,
//     buttons: [ 'copy', 'excel', 'pdf', 'print']

// });
		  } );
		

		//   $('#excelrow').click(function(){

		// 	var detid=$(this).data('id');
		// 	$('#rty5').css("color", "skyblue");
		//   });

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
		// $('#exportexcel').click(function(){
		// $('#shownotice').show();

              
		// });

  // $(document).ready(function(){
  //       $.ajaxSetup({
  //       headers: {
  //       'X-CSRF-TOKEN': $('#csrf_token').val()
  //       }
  //   });

  //         //change status
		// $('.statuschange').on('change',function(){
		// var id=$(this).data('id');
		// var status=$(this).val();
		

		// $.ajax({
  //           type:"POST",
  //           url: "{{ url('productstatuschange') }}",
  //           data: { id: id,status:status },
  //           dataType: 'json',
  //           success: function(res){

  //           	// if(res[0].success == true)
  //           	// {

  //           	// }
  //           	// else
  //           	// {

  //           	// }
  //           	// alert(res[0].success);
  //             // window.location.reload();
  //          }
  //       });
  //   });
		// });

$('.showpopup2').click(function(){

$('#exampleModal2').modal('show');
var orderid=$(this).data('orderid');


$('#oid').val(orderid);
$('#oid2').text(orderid);
});


if(window.matchMedia("(max-width: 767px)").matches){
        // The viewport is less than 768 pixels wide
       $('#resp').addClass('table-responsive');
    } 
    else
    {
    	  $('#resp').removeClass('table-responsive');
    }
	$(document).ready(function(){
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

	$('#branch_name').on('change',function(){
    
    var branch_name=$('#branch_name').val();
    $('#dsf_code').html("<option value='' hidden>Select DSF</option>");

    $.ajax({
            type:"POST",
            url: "{{ url('getbranchdsfdata') }}",
            data: { branch_name: branch_name},
            dataType: 'json',
            success: function(res){
              
			for(var i=0;i <= res.length;i++)
              { 
				var dfname=res[i].branch_code+""+res[i].dsf_code;
              	$('#dsf_code').append("<option value='"+dfname+"'>"+res[i].dsf_code+" - "+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
              
           }
        });
    
    });
});

	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


<!-- Mirrored from codervent.com/synadmin/demo/vertical/table-datatable.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Sep 2022 05:32:45 GMT -->
</html>