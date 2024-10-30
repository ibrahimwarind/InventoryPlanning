@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME); ?>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">
                <input type="hidden" id="csrf_token" value="{{csrf_token()}}">

					<div class="col">
						<h6 class="mb-0 text-uppercase">Min Booking Amount per Customer</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-user me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 text-primary">Add Min Booking Amount</h5>
								</div>
								<hr>

								<form method="get" action="<?php echo PROJECTURL ?>/minbooking_amount2" enctype= "multipart/form-data" class="row g-3">
									@csrf
                                    <div class="col-md-3">
										<label for="inputLastName" class="form-label">Business Line</label>
										<br>
										<select class="form-control form-select" style="" name="bus_line" id="bus_line" required="">
											<option value="" hidden="">Select Business Line</option>
                                            <option value="0">All Business Line</option>
									        @foreach($groupdata as $grpdata)
								            <option value="{{$grpdata->supply_groupid}}">{{$grpdata->supply_groupid}} - {{$grpdata->supply_groupname}}</option>

								            @endforeach
							            </select>
									</div>
                                    <div class="col-md-3">
										<label for="inputFirstName" class="form-label">Min Booking Amount(per customer)</label>
										<input type="text" class="form-control"   name="min_booking_amount" id="min_booking_amount" required="">
									</div>
                                    <div class="col-md-3">
										<label for="inputFirstName" class="form-label">Max Booking Amount(per customer)</label>
										<input type="text" class="form-control" value="999999999"  name="max_booking_amount" id="max_booking_amount" required="">
									</div>

									
                                  	

								

									
									<div class="col-3">
<br>
										<button type="submit" class="btn btn-primary px-5">Add</button>
                                        
									</div>
                                    <div class="col-sm-12">
                                        <?php
                                        if($data == "Max Booking has been successfully set")
                                        {
                             echo "<p class='text-success'>$data</p>";
                                        } 
                                        else{
                                            echo "<p class='text-danger'>$data</p>";
                                        }
                                        ?>
                                    
                                    </div>
								</form>

                                <div class="col-sm-12">
                                <table id="example" class="table table-striped table-bordered" >
								<thead>
									<tr>
                                    <th style="text-align:center">Branch</th>
										<th style="text-align:center">Business Line</th>
										<th style="text-align:center">Max Booking Amount</th>
										<th style="text-align:center">Min Booking Amount</th>
										
										<th style="text-align:center">Action</th>

									</tr>
								</thead>
								<tbody>
									@foreach($restricteddata as $user)
									<?php 
									$compid=$user->branch_code;
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
                                 
                                    

									?>
									<tr>
										<td style='text-align:center'>{{$compid}} - {{$branch_name}}</td>
                                        <td >{{$user->supply_groupid}} - {{$user->supply_groupname}}</td>
										<td style='text-align:center'>{{$user->max_booking_amount}}</td>
										<td style='text-align:center'>{{$user->min_booking_amount}}</td>
                                        

										
										<td style="width:60px !important">
											<center>
											<a href="<?php echo PROJECTURL ?>/deleterestriction/{{$user->restric_id}}/{{$user->supply_groupname}}"  style='display: inline-block;' onclick="return confirm('Are you sure You Want To Delete This?')">
												<i class="fa fa-trash text-danger" aria-hidden="true"></i>
											</a>
											
											
										</center>
											
										</td>
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
                                    <th style="text-align:center">Branch</th>
										<th style="text-align:center">Business Line</th>
										<th style="text-align:center">Max Booking Amount</th>
										<th style="text-align:center">Min Booking Amount</th>
										
										<th style="text-align:center">Action</th>
									</tr>
								</tfoot>
							</table>

                                </div>

							</div>
						</div>
					</div>

					

				</div>
				<!--end row-->
				
				<!--end row-->
			</div>
		</div>
		<!--end page wrapper -->
		@extends('footer')
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
	
    <script>
        $('#bus_line').chosen();
        
        $(document).ready(function(){

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('#csrf_token').val()
}
});


$('#u_branch').on('change',function(){
		var u_branch=$('#u_branch').val();
		 $('#bus_line').html("<option value=''>Select Business Line</option>");
	
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchbusinessline') }}",
            data: { id: u_branch},
            dataType: 'json',
            success: function(res){
				getbusinesslinedata();
             for(var i=0;i <= res.length;i++)
              { 
              	$('#bus_line').append("<option value='"+res[i].supply_groupid+"'>"+res[i].supply_groupname +"</option>").trigger('chosen:updated');

              }
			  
            	
           }
        });
    });


function getbusinesslinedata()
{
	var u_code=$('#u_code').val();
    var u_branch=$('#u_branch').val();

	$.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });
	$.ajax({
            type:"POST",
            url: "{{ url('getsupervisorgroup') }}",
            data: {u_code:u_code,u_branch:u_branch},
            dataType: 'json',
            success: function(res){
				
              $('#dc_data').html("");
              var output="";
               for(var i=0;i <= res.length;i++)
            	{

            		output="<div class='col-sm-2'><a href='booster.b2bpremier.com/removesupervisorgroup/"+res[i].auto_id+"'><i class='fa fa-trash dells  text-danger' aria-hidden='true' data-id='"+res[i].auto_id+"'></i></a> "+res[i].supply_groupname+"</div>";
            		$('#dc_data').append(output);
            		
            	}
            	
               // $('#dc_data').html(res);
            
           }
        });
	
}
// getcompanydata();
// getbrickdata();
  //change status
$('#add_company').on('click',function(){

var bus_line=$('#bus_line').val();
var u_code=$('#u_code').val();
var u_branch=$('#u_branch').val();

if(u_code !="" && bus_line !="")
{
$.ajax({
    type:"POST",
    url: "{{ url('supervisoraddgroup') }}",
    data: { u_code: u_code,u_branch:u_branch,bus_line:bus_line},
    dataType: 'json',
    success: function(res){
		
       if(res == "Business Line Added")
	   {
		getbusinesslinedata();
		$('#bus_line').val("").trigger('chosen:updated');
	   }
	   else{
        alert('This Business Line Already Added');
	   }
        // getcompanydata();
    
   }
});
}
else
{
    alert('please select Business Line & Supervisor Code');
}
});

});
    </script>
