@include('header')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
$connection_string = 'DRIVER={ODBC Driver 18 for SQL Server};SERVER=tcp:202.142.180.146,9438;DATABASE=DeliveryManagementDB;TrustServerCertificate=yes;';
$conn = odbc_connect( $connection_string,  'sa', 'Database123$' ) or die (odbc_errormsg());
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
					<div class="breadcrumb-title pe-3">Salesman Route Tracking </div>
					
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
					<form method="get" action="<?php echo PROJECTURL ?>/salesman_routetracking2">
						@csrf
					<div class="row">
						
						<div class="col-sm-3 col-6"  style="">
							<select class="form-control" style="" name="pro_branch" id="pro_branch">
                            <option value='' hidden=''>Select Branch</option>
								
								@foreach($branchdata as $branch)
								<option value="{{$branch->branch_code}}">{{$branch->branch_code}} - {{$branch->branch_name}}</option>

								@endforeach
							</select>
						</div>
						
					
						

                        <div class="col-sm-2 col-6"  style="">
							 <input type="date" class="form-control" style='height:30px' name="pro_date" id="pro_date" value="<?php echo $curdate ?>" required>
						</div>
						
					
						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/salesman_routetracking" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
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
									<th style="text-align:center;">Salesman Name </th>
                                    <th style="text-align:center;">Trip Start Time</th>
                                    <th style="text-align:center;">Trip End Time</th>
									<th style="text-align:center;">Total Trip Customers</th>
									<th style="text-align:center;">Total Journey Time</th>
                  <th style="text-align:center;">Action</th>
										

									</tr>
								</thead>
								<tbody><?php $oldgroup="";?>
                            <?php 
                            $indexcount=0;$overalltime=0;$overallstarttime=0;
                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                              CURLOPT_URL => 'http://202.143.120.43:5000/getsalesmanmaster?branchcode='.$branchid.'&suppdate='.$curdate2.'',
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 0,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'GET',
                              CURLOPT_HTTPHEADER => array(
                                'Authorization: Basic WHlqS25VazpQeXRob25AMTIzQA=='
                              ),
                            ));
                             
                            $response = curl_exec($curl);
                            
                            curl_close($curl);
                            $response_data = json_decode($response);
                            foreach ($response_data as $user) {
  
                              $ExecutionDate=$user->ExecutionDate;
                              $branchcode=$user->branchcode;
                              $cust=$user->totaltripcustomers;
                              $groupname=$user->groupname;
                              $salesmancode=$user->salesmancode;
                              $salesmanname=$user->salesmanname;
                              $tripend=$user->tripend;
                              $tripstart=$user->tripstart;

                              if($oldgroup !=$groupname)
                              {
                                echo "
                              <tr style='background-color:#679149'>
                              <th colspan='6'>Group : ".$groupname." </th>
                              </tr>
                                ";
                              }          
                           
                     if(date("d-m-Y", strtotime($tripend)) == "01-01-1900")
                     {
                      $tripend=$tripstart;
                     }

                    $time1 = strtotime($tripstart);
                    $time2 = strtotime($tripend);
                    $diff_seconds = abs($time2 - $time1);
                    $total_minutes = floor($diff_seconds / 60);
                $hours = floor($total_minutes / 60);
                $minutes = $total_minutes % 60;

                $overalltime=$overalltime + $total_minutes;
               
                $timeformatchange=date("Y-m-d G:i:s A", strtotime($tripstart));
                $hourst=date("h", strtotime($tripstart));
                $minutest=date("i", strtotime($tripstart));
                $overallmint=($hourst * 60) + $minutest;
                $overallstarttime=$overallstarttime + $overallmint;
                $salesmanname=str_replace("/","",$salesmanname);


                $tripendlat="0";$tripendlng="0";
                // $queryendtime="SELECT [ordercustid] ,[starttime],[endtime],[phy_long],[phy_lat],[userid],[Timing_Type],[entrytime] FROM [DeliveryManagementDB].[dbo].[mobile_timing] WHERE  CAST(starttime AS DATE) = '$curdate' AND LEFT(CAST(userid AS VARCHAR), 3) = '$branchid' AND userid='$salesmancode' AND Timing_Type='Tripend' order by userid,Timing_Type desc";
                // $queryendResult = odbc_exec($conn,$queryendtime);
                // $fetchendresult=odbc_fetch_row($queryendResult);
                // if(odbc_result($queryendResult,"phy_long") != "")
                // {
                //   $tripendlat =odbc_result($queryendResult,"phy_lat");
                //   $tripendlng =odbc_result($queryendResult,"phy_long");
                // }

                $phy_lat="0";$phy_long="0";
                // $queryendtime="SELECT [ordercustid] ,[starttime],[endtime],[phy_long],[phy_lat],[userid],[Timing_Type],[entrytime] FROM [DeliveryManagementDB].[dbo].[mobile_timing] WHERE  CAST(starttime AS DATE) = '$curdate' AND LEFT(CAST(userid AS VARCHAR), 3) = '$branchid' AND userid='$salesmancode' AND Timing_Type='TripStart' order by userid,Timing_Type desc";
                // $queryendResult = odbc_exec($conn,$queryendtime);
                // $fetchendresult=odbc_fetch_row($queryendResult);
                // if(odbc_result($queryendResult,"phy_long") != "")
                // {
                //   $phy_lat =odbc_result($queryendResult,"phy_lat");
                //   $phy_long =odbc_result($queryendResult,"phy_long");
                // }



									  echo "
	   <tr>
      <td style='font-size:14px;color:black'>$salesmancode - $salesmanname </td>
	  <td style='font-size:14px;text-align:center;color:black'>".date("h:i:s A", strtotime($tripstart))."</td>
	  <td style='font-size:14px;text-align:center;color:black'>".date("h:i:s A", strtotime($tripend))."</td>
	  <td style='font-size:14px;color:black;text-align:center'> ".number_format($cust)."</td>
	  <td style='font-size:14px;color:black;text-align:center'>$hours Hours $minutes Minutes  </td>
    <td><center>  
    <a href='https://booster.b2bpremier.com/salesmanday_route/$salesmancode/$salesmanname/$tripstart/$tripend/$cust/$hours/$minutes/$branchid/$curdate/$phy_lat/$phy_long/$tripendlat/$tripendlng' target='_blank' class='btn btn-info' style='padding:4px !important;margin-top:-7px !important;color:white'>View Route &nbsp; <i class='fas fa-route'></i></a>
    || <a href='https://booster.b2bpremier.com/salesman_route_detail/$salesmancode/$salesmanname/$tripstart/$tripend/$cust/$hours/$minutes/$branchid/$curdate/$phy_lat/$phy_long/$tripendlat/$tripendlng' target='_blank' class='btn btn-primary' style='padding:4px !important;margin-top:-7px !important;color:white'>View Journey &nbsp;</a>
    || <a href='https://booster.b2bpremier.com/salesman_shop_detail/$salesmancode/$salesmanname/$tripstart/$tripend/$cust/$hours/$minutes/$branchid/$curdate/$phy_lat/$phy_long/$tripendlat/$tripendlng' target='_blank' class='btn btn-secondary' style='padding:4px !important;margin-top:-7px !important;color:white'>View Customer &nbsp;</a>
    
    </center></td>

	  </tr>
									  
									  ";
   $indexcount=$indexcount + 1;
   $oldgroup=$groupname;
								  }
                            
                            
                            
                            ?>
								
								</tbody>
								<tfoot>
                  <?php 
      if($indexcount == 0 ){$indexcount=1;}            
$avgminuteday=$overalltime / $indexcount;
$avgdayhour=intdiv($avgminuteday,60);
$remainderday = $avgminuteday % 60;


$avgminuteday2=$overallstarttime / $indexcount;
$avgdayhour2=intdiv($avgminuteday2,60);
$remainderday2 = $avgminuteday2 % 60;
                  ?>
									<tr>
                                    <th style="text-align:center;">Avg Branch Wise Data </th>
                                    <th style="text-align:center;">{{date("h:i:s A", strtotime($avgdayhour2.":".$remainderday2.":00"))}}</th>
                                    <th style="text-align:center;"></th>
									<th style="text-align:center;"></th>
									<th style="text-align:center;">{{$avgdayhour}} hours {{$remainderday}} minutes</th>
                  <th style="text-align:center;"></th>
									</tr>
								</tfoot>
							</table>
         
							
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
			<p class="mb-0">Copyright © 2024. All right reserved.</p>


		</footer>
	</div>

<!-- <button class="open-button" onclick="openForm()">Open Form</button> -->

<div class="form-popup" id="myForm">
  <div  class="form-container" style="padding: 20px">
     
     <i class="fa fa-check text-success" style="font-weight: bold;font-size: 29px;" aria-hidden="true" ></i> &nbsp;&nbsp;<span style="font-weight: bold;font-size: 20px;">Successfully Updated</span>
    
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"><span id="brname"></span> Data Sync !
	</h6>
        
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/sync.gif') }}" style="height: 150px;width:150px">
           	<h5 style="margin-top: -18px">DSF SAF Detail Synchronizing Please Wait !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"> Data Sync Success !
	</h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/checked.png') }}" >
           	<h5 ><br>Synchronize Successfully Done !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"> Data Sync Failed !
	</h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/warning.png') }}" >
           	<h5  class="text-danger"><br>Synchronize Failed !</h5>
           </center>

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
$('#pro_branch').chosen();
$('#pro_dsf').chosen();
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

    $('#pro_branch').on('change',function(){
		var pro_branch=$('#pro_branch').val();
    var pro_group=$('#pro_group').val();
		$('#pro_dsf').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchdsf') }}",
            data: { branchcode: pro_branch,pro_group:pro_group},
            dataType: 'json',
            success: function(res){
            	$('#pro_dsf').append("<option value='' hidden>Select Dsf</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_code +" - "+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });
   $('#pro_group').on('change',function(){
		var pro_branch=$('#pro_branch').val();
    var pro_group=$('#pro_group').val();
		$('#pro_dsf').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchdsf') }}",
            data: { branchcode: pro_branch,pro_group:pro_group},
            dataType: 'json',
            success: function(res){
            	$('#pro_dsf').append("<option value='' hidden>Select Dsf</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_code +" - "+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
            	
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
              	$('#pro_group').append("<option value='"+res[i].supply_groupid+"'>"+res[i].supply_groupid +" - "+res[i].supply_groupname+"</option>").trigger('chosen:updated');

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