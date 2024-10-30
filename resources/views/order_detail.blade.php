@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME); ?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFkvJ5vPzUed6bCEBWo6UC11RnthpwVdo&callback=initMap&v=weekly"
      defer>
    </script>
	<div id="map"></div>
	<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Order Detail</div>
                    <div class="ms-auto">
						<div class="btn-group">

							<a href="<?php echo PROJECTURL ?>/exportordercsv_formbms/{{$oid}}/{{$uid}}" class="btn btn-success" id="exportexcel" style="padding:2px !important;font-size:15px;height:30px">Export Excel</a>&nbsp;&nbsp;&nbsp;
							
							<a href="<?php echo PROJECTURL ?>/view_dsf_route/{{$oid}}/{{$uid}}" class="btn btn-info" id="" style="padding:2px !important;font-size:15px;height:30px;color:white" target="_blank">&nbsp;<i class="fa fa-map-marker" aria-hidden="true"></i>  View Route</a>&nbsp;&nbsp;&nbsp;
							
							
						</div>
						<span id="shownotice">
								<br>
							<!-- 	<p class="text-danger" style="margin-top: 5px !important">Please Wait for a while, the file was generated...</p> -->
						</span>
					</div>
					
				</div>
				
				<!--end breadcrumb-->
				<div class="row">
					<div class="col">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<?php $orderid=0; ?>
									@foreach($masterdata as $master)
									<?php $orderid=$master->order_id;
									  $is_excel_allow=$master->is_excel_allow; ?>
									 
								<div class="col-sm-7 col-12">
									<hr>
									<table>
									
										<tr>
											<th>DSF Name</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{$master->dsf_code}} - {{$master->dsf_name}}</td>
										</tr>
<tr>
											<th>Branch Name</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{$master->branch_name}}</td>
										</tr>
										<tr>
											<th>Order Date</th>
											<td>: </td>
                                            <?php $pl_date=date("d-M-Y", strtotime($master->order_date)); ?>
											<td style='color:black'>&nbsp;&nbsp;{{$pl_date}}</td>
										</tr>
										<tr>
											<th>Order Total</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{number_format($master->order_total)}}</td>
										</tr>
										
									
										
										

									</table>

								</div>
								<div class="col-sm-5 col-12">
									<hr>
									<table>
										<tr>
											<th>Unique Key</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{$master->unique_key}}</td>
										</tr> 
										<tr>
											<th>Day Over</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{$master->day_over}}</td>
											<input type="hidden" value="{{$master->is_excel_allow}}" id="dover_status">
										</tr>
										<tr>
											<th>Count</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{$master->count}}</td>
										</tr>
											<tr>
											<th>Status</th>
											<td>: </td>
											<td style='color:black'>&nbsp;&nbsp;{{$master->status}}</td>
										</tr>
									
										
										
									</table>

								</div>
								@endforeach
							</div>
							</div>
						</div>
                        <h6 class="mb-0 text-uppercase">Order Detail</h6>
						<hr/>
                        <div class="card">
							<div class="card-body table-responsive">
								<table class="table mb-0">
									<thead>
										
									</thead>
									<tbody>
                                        <?php $index=1;$custcode="";$custtotal=0;$custcode2=""?>
										@foreach($orderdetail as $odetail)
                                        <?php
										if($index ==1){$custcode=$odetail->customer_code;}
                                        if($custcode != $odetail->customer_code)
                                        {
                                            echo "
                                            <tr style='background-color:#D3D3D3;height:10px !important'>
                                            <th colspan='4'><center>Total </center></th>
                                            <th><center style='font-size:15px;'>".number_format($custtotal,2)."</center></th>
                                            <th colspan='5'><center> </center></th>
                                            </tr>
                                            
                                            ";
                                            $custtotal=0;
                                        }
										
										if($custcode2 != $odetail->customer_code)
										{ echo "
                                            <tr style='background-color:gray;height:10px !important'>
                                            <th colspan='10'>$odetail->customer_code -$odetail->customer_name</th>
                                            
                                            </tr>
                                            
                                            ";
											echo "<tr>
											<th scope='col' style='text-align:center;width:40px'>#</th>
											
											<th scope='col' style=''>Product</th>
                                            <th scope='col' style='text-align:center'>Qty</th>
                                            <th scope='col' style='text-align:center'>Rate</th>
                                            <th scope='col' style='text-align:center'>Amount</th>
                                            <th scope='col' style='text-align:center'>Booking Datetime</th>
                                            <th scope='col' style='text-align:center'>Route</th>
											<th scope='col' style='text-align:center'>Trip</th>
                                            <th scope='col' style='text-align:center'>Comment</th>
                                            <th scope='col' style='text-align:center'>Location</th>
                                            
										</tr>";

										}
                                        
                                        $custtotal=$custtotal + ($odetail->rate * $odetail->quantity);

										//get product code/name
										$prodname="";
										$fg_prod="SELECT product_name FROM `sb_products` where product_code='$odetail->product_code'";
										$run_prod=mysqli_query($con,$fg_prod);
										if(mysqli_num_rows($run_prod) !=0)
										{
											$row_prod=mysqli_fetch_array($run_prod);
											$prodname=$row_prod['product_name'];
										}

                                         ?>
										<tr>
											<th scope="row"><center>{{$index}}</center></th>
										
                                            <td>{{$odetail->product_code}} - {{$prodname}}</td>
											<td><center>{{$odetail->quantity}}</center></td>
                                            <td><center>{{$odetail->rate}}</center></td>
                                            <td><center>{{number_format($odetail->rate * $odetail->quantity,2)}}</center></td>
                                            <td><center>{{date("d-M-Y h:i:s", strtotime($odetail->booking_datetime));}}</center></td>
											<td><center>{{$odetail->route}}</center></td>
											<td><center>{{$odetail->trip_no}}</center></td>
                                            <td><center>{{$odetail->comment}}</center></td>
                                            <td><center><a href="https://www.google.com/maps/?q= {{$odetail->latitude}},{{$odetail->longitude}}" target="_blank">View</a></center></td>
												
										</tr>
                                       
                                       
                                        <?php $custcode=$odetail->customer_code;$custcode2=$odetail->customer_code; $index=$index + 1;?>
										@endforeach
                                        <?php 
                                        echo "
                                        <tr style='background-color:#D3D3D3;height:10px !important'>
                                        <th colspan='4'><center>Total </center></th>
                                        <th><center style='font-size:15px;'>".number_format($custtotal,2)."</center></th>
                                        <th colspan='5'><center> </center></th>
                                        </tr>
                                        
                                        ";
                                        ?>

										
										
									</tbody>
								</table>
							</div>
						</div>
						




						<h6 class="mb-0 text-uppercase">Product Wise Summary</h6>
						<hr/>
						<div class="card">
							<div class="card-body table-responsive">
								<table class="table mb-0">
									<thead>
										<tr>
											<th scope="col">Company Name</th>
											<th scope="col">Product Name</th>
											<th scope="col">Total Qty</th>
											
										</tr>
									</thead>
									<tbody>
										@foreach($summarydata as $sdetail)
										
										<tr>
											<td>{{$sdetail->company_title}}</td>
                                            <td>{{$sdetail->product_name}}</td>
											<td><span>{{$sdetail->totalqty}}</span></td>
												
										</tr>
										@endforeach

										<tr>
										<th scope="col">Company Name</th>
											<th scope="col">Product Name</th>
											<th scope="col">Total Qty</th>
										</tr>
										
									</tbody>
								</table>
							</div>
						</div>


                        <h6 class="mb-0 text-uppercase">Order Remark Log</h6>
						<hr/>
						<div class="card">
							<div class="card-body table-responsive">
								<table class="table mb-0">
									<thead>
										<tr>
											<th scope="col">Date</th>
											<th scope="col">Remark/Log Detail</th>
											<th scope="col">Post User</th>
											
										</tr>
									</thead>
									<tbody>
										@foreach($remarkdetail as $hdetail)
										
										<tr>
											<th scope="row"><span>{{date("d-M-Y", strtotime($hdetail->remark_date));}}</span></th>
											<td>{{$hdetail->remark_text}}</td>
											<td><span>{{$hdetail->post_user}}</span></td>
												
										</tr>
										@endforeach

										<tr>
										<th scope="col">Date Time</th>
											<th scope="col">Status Detail</th>
											<th scope="col"><center>Status</center></th>
										</tr>
										
									</tbody>
								</table>
							</div>
						</div>


					
			
						
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>

@include('footer')
<script>
 
 var dover_status=$('#dover_status').val();
 if(dover_status == 0)
 {
   $('#exportexcel').hide();
 }
	
 function initMap() {
    const directionsService = new google.maps.DirectionsService();
const directionsRenderer = new google.maps.DirectionsRenderer();
  
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 10,
    center: { lat: 24.8607, lng: 67.0011 },
  });
  
  
  var o_lat=document.getElementById("o_lat").value;
 var o_lng=document.getElementById("o_lng").value;
 var s_lat=document.getElementById("s_lat").value;
 var s_lng=document.getElementById("s_lng").value;

  directionsRenderer.setMap(map);
  calculateAndDisplayRoute(directionsService, directionsRenderer);
function calculateAndDisplayRoute(directionsService, directionsRenderer) {

  directionsService
    .route({
      origin: new google.maps.LatLng(s_lat,s_lng),
      destination:new google.maps.LatLng(o_lat, o_lng),
      travelMode: google.maps.TravelMode.DRIVING,
    })
    .then((response) => {
      directionsRenderer.setDirections(response);
      console.log(response);
      const route = response.routes[0];
      const request = response.request['waypoints'];
      
// For each route, display summary information.
var totaldistance=0;
var totaltime=0;var totalprice=0;
      for (let i = 0; i < route.legs.length; i++) {
        const routeSegment = i + 1;

  console.log(route.legs[i]);
  var strlats=route.legs[i].start_location.lat();
  var strlngs=route.legs[i].start_location.lng();

  var endlats=route.legs[i].end_location.lat();
  var endlngs=route.legs[i].end_location.lng(); 
  console.log(route.legs[i].distance.text);
  document.getElementById("distance_txt").innerHTML =route.legs[i].distance.text;

   totaldistance=totaldistance + route.legs[i].distance.value;
   totaltime=totaltime + route.legs[i].duration.value;


         
      }
      
         
      })

     
    }
    


window.initMap = initMap;
  }

</script>