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
					<div class="breadcrumb-title pe-3">{{$smancode}} - {{$smanname}} Route Detail - Date ({{$tripdate}}) </div>
					
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
				
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">
							<table id="example" class="table table-striped table-bordered" >
								<thead>
									<tr>
									<th style="text-align:center;">Customer Name</th>
                                    <th style="text-align:center;">Start Time</th>
                                    <th style="text-align:center;">Total Sku</th>
									<th style="text-align:center;">Total Amount</th>
                                    </tr>


                                    <tr>
                                    <td style='font-size:14px;color:black'>Trip Start</td>
                                    <td style='font-size:14px;text-align:center;color:black'>{{date("h:i:s A", strtotime($strtime))}}</td>
                                    <td style='font-size:14px;color:black;text-align:center'>0</td>
                                    <td style='font-size:14px;color:black;text-align:center'>Rs 0</td>
                                    </tr>

                                    <?php 
                                    $userid=$brcode."".$smancode;
                                    $tripdate2=date('d-M-Y', strtotime($tripdate));

                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL => 'http://202.143.120.43:5000/salesmancustomerjourney?branchcode='.$brcode.'&suppdate='.$tripdate2.'&smancode='.$smancode.'',
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
                                         $TRIPstartdatetime=$user->TRIPstartdatetime;
                                         $branchcode=$user->branchcode;
                                         $custcode=$user->custcode;
                                         $custname=$user->custname;
                                         $salesmancode=$user->salesmancode;
                                         $salesmanname=$user->salesmanname;
                                         $salesvalue=$user->salesvalue;
                                         $totalsku=$user->totalsku;
                                         $TRIPstartdatetime=date("h:i:s A", strtotime($TRIPstartdatetime));
								   
    

                                      

                                      echo "
                                      <tr>
                                     <td style='font-size:14px;color:black'>$custcode - $custname</td>
                                     <td style='font-size:14px;text-align:center;color:black'>$TRIPstartdatetime</td>
                                     <td style='font-size:14px;color:black;text-align:center'>$totalsku</td>
                                    <td style='font-size:14px;color:black;text-align:center'>Rs ".number_format($salesvalue)."</td>
                                     </tr>";

                                  }

                              
                                    
         
                                    
                                    ?>





                                    <tr>
                                    <td style='font-size:14px;color:black'>Trip End</td>
                                    <td style='font-size:14px;text-align:center;color:black'>{{date("h:i:s A", strtotime($endtime))}}</td>
                                    <td style='font-size:14px;color:black;text-align:center'>0</td>
                                    <td style='font-size:14px;color:black;text-align:center'>Rs 0</td>
                                    </tr>
								</thead>
								<tbody>

                          
								
								</tbody>
								<tfoot>
                                
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



	<!--end wrapper-->
	<!--start switcher-->

	<!--end switcher-->
	<!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
<script>

    $(document).ready(function() {
        var rows = $("#example tbody tr").get();
        rows.sort(function(a, b) {
    var A = $(a).children('td').eq(1).text();
    var B = $(b).children('td').eq(1).text();
    return new Date(A) - new Date(B);
});

$.each(rows, function(index, row) {
    $("#example").children('tbody').append(row);
});

        var table = $('#example').DataTable({
            lengthChange: false,
            "paging": false,
            "bInfo": false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>
<!--app JS-->
<script src="assets/js/app.js"></script>
</body>
</html>
