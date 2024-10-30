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
					<div class="breadcrumb-title pe-3">Dsf Fancing Order Report </div>
					

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
					<form method="get" action="<?php echo PROJECTURL ?>/dsffancing_order_report2">
						@csrf
					<div class="row">
						
						<div class="col-sm-3 col-8">
							<input type="date" placeholder="" class="form-control" value="{{$curdate}}" name="str_date" id="str_date">
						</div>
						
						
					
						<div class="col-sm-2 col-4">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/dsffancing_order_report" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
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
										<th style="text-align:center;font-size:12px">Dsf Name</th>
                                        <th style="text-align:center;font-size:12px">Total Booking Customer</th>
										<th style="text-align:center;font-size:12px">Total Tag Customer</th>
										<th style="text-align:center;font-size:12px">Total "No Order Booking"</th>
										<th style="text-align:center;font-size:12px">Fancing Customer "No Order Booking"</th>
										

									</tr>
								</thead>
								<tbody><?php $index=1;$oldbranchnane="";?>
									@foreach($dsfdata as $order)
							        <?php if($oldbranchnane != $order->branch_name)
                                    {
                                        echo "
                                        <tr style='background-color:lightgray;color:black'>
                                          <th colspan='5'>$order->branch_name</th>
                                        </tr>
                                        ";

                                    }
                                    $fg_totalcust="select * from sb_order_detail where dsf_code='$order->dsf_code' AND branch_code='$order->branch_code' AND date_format(booking_datetime,'%Y-%m-%d')='$curdate' group by customer_code";
                                    $run_totalcust=mysqli_query($con,$fg_totalcust);
                                    $row_totalcust=mysqli_num_rows($run_totalcust);

                                    $fg_totanoorder="select * from sb_order_detail where dsf_code='$order->dsf_code' AND branch_code='$order->branch_code' AND date_format(booking_datetime,'%Y-%m-%d')='$curdate' AND comment !='Productive' group by customer_code";
                                    $run_totalnoorder=mysqli_query($con,$fg_totanoorder);
                                    $row_totalnoorder=mysqli_num_rows($run_totalnoorder);

                                    $fg_totalfancing="select * from sb_order_detail det,sb_customers cust where det.customer_code=cust.customer_code AND det.branch_code=cust.branch_code AND det.dsf_code='$order->dsf_code' AND det.branch_code='$order->branch_code' AND date_format(det.booking_datetime,'%Y-%m-%d')='$curdate' AND det.comment !='Productive' AND cust.is_fancing=1 group by det.customer_code";
                                    $run_totalfancing=mysqli_query($con,$fg_totalfancing);
                                    $row_totalfancing=mysqli_num_rows($run_totalfancing);

									$fg_totalfancing2="select * from sb_order_detail det,sb_customers cust where det.customer_code=cust.customer_code AND det.branch_code=cust.branch_code AND det.dsf_code='$order->dsf_code' AND det.branch_code='$order->branch_code' AND date_format(det.booking_datetime,'%Y-%m-%d')='$curdate' AND cust.is_fancing=1 group by det.customer_code";
                                    $run_totalfancing2=mysqli_query($con,$fg_totalfancing2);
                                    $row_totalfancing2=mysqli_num_rows($run_totalfancing2);
                                    
                                    ?>
                                    <tr>
									
									<td style="font-size:12px">{{$order->dsf_code}} - {{$order->dsf_name}}</td>
                                    <td style="text-align: center;font-size:12px;">{{number_format($row_totalcust)}}</td>
									<td style="text-align: center;font-size:12px;">{{number_format($row_totalfancing2)}}</td>
                                    <td style="text-align: center;font-size:12px;">{{number_format($row_totalnoorder)}}</td>
                                    <td style="text-align: center;font-size:12px;">{{number_format($row_totalfancing)}}</td>
										
										
									</tr>
									<?php $index=$index + 1;$oldbranchnane=$order->branch_name; ?>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
                                    <th style="text-align:center;font-size:12px">Dsf Name</th>
                                        <th style="text-align:center;font-size:12px">Total Booking Customer</th>
										<th style="text-align:center;font-size:12px">Total Tag Customer</th>
										<th style="text-align:center;font-size:12px">Total No Order Booking</th>
										<th style="text-align:center;font-size:12px">Fancing Customer No Order Booking</th>
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