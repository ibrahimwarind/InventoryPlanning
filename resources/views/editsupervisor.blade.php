@include('header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">
                <input type="hidden" id="csrf_token" value="{{csrf_token()}}">

					<div class="col">
						<h6 class="mb-0 text-uppercase">Edit Supervisor</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-user me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 text-primary">Edit Supervisor</h5>
								</div>
								<hr>

								<form method="get" action="<?php echo PROJECTURL ?>/updatesupervisor" enctype= "multipart/form-data" class="row g-3">
									@csrf
                                    @foreach($userdetail as $detail)
                                    <input type="hidden" value="{{$detail->admin_id}}" name="u_id">
                                    <div class="col-md-2">
										<label for="inputFirstName" class="form-label">Supervisor Code</label>
										<input type="text" class="form-control" value="{{$detail->user_code}}" readonly name="u_code" id="u_code" required="">
									</div>

									<div class="col-md-4">
										<label for="inputFirstName" class="form-label">Name</label>
										<input type="text" class="form-control" id="inputFirstName" value="{{$detail->user_name}}"  name="u_name" required="">
									</div>
                                    <div class="col-md-3">
										<label for="inputFirstName" class="form-label">Login name (Don't use space)</label>
										<input type="text" class="form-control" id="inputFirstName" value="{{$detail->admin_name}}" placeholder="firstname.lastname" name="l_name" required="">
									</div>
									<div class="col-md-3">
										<label for="inputFirstName" class="form-label">Password (Don't use space)</label>
										<input type="text" class="form-control" id="inputFirstName" value="{{$detail->admin_password}}" placeholder="123" name="u_password" required="">
									</div>
									
							
									<div class="col-md-3">
										<label for="inputLastName" class="form-label">Branch</label>
										<br>
                                        <?php 
											$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
											$brid=$detail->branch_id;
											$fg_branch="select * from b2b_branch where id='$brid'";
											$run_branch=mysqli_query($con,$fg_branch);
											$cn_branch=mysqli_num_rows($run_branch);
											$branch_name="";
											if($cn_branch !=0)
											{
											$row_branch=mysqli_fetch_array($run_branch);
											$branchname=$row_branch['branch_name'];
											}
											

											?>
										<select class="form-control form-select" style="" name="u_branch" id="u_branch" required="">
											<option value="{{$detail->branch_id}}">{{$detail->branch_id}} - {{$branchname}}</option>
									

								@foreach($branchdata as $brdata)
								<option value="{{$brdata->branch_code}}">{{$brdata->branch_code}} - {{$brdata->branch_name}}</option>

								@endforeach
							       </select>
									</div>
                                    <p class="mb-0" style="color:black;border-bottom:1px solid black;font-weight:bold" >Business Line Information</p>
                                    
                                    <div class="col-md-3">
										<label for="inputLastName" class="form-label">Business Line</label>
										<br>
										<select class="form-control form-select" style="" name="bus_line" id="bus_line" >
											<option value="" hidden="">Select Business Line</option>
                                            <?php 
                                            $fg_sb="SELECT supply_groupid,supply_groupname FROM `sb_business_line` where branch_code='$detail->branch_id' group by supply_groupid";
                                            $run_sb=mysqli_query($con,$fg_sb);
                                            while($row_sb=mysqli_fetch_array($run_sb))
                                            {
                                                $supply_groupid=$row_sb['supply_groupid'];
                                                $supply_groupname=$row_sb['supply_groupname'];
                                                echo "<option value='$supply_groupid'>$supply_groupname</option>";

                                            }
                                            ?>
									
							       </select>
									</div>
                                    <div class="col-2">
										<a id="add_company" name="add_company" class="btn btn-primary" style="margin-top: 23px !important">Add</a>
									</div>
									<div class="col-12" >
										<div class="row" id="dc_data" style="margin-top: 8px">

										</div>
									</div>
                                    

								

									
									<div class="col-12">
										<p class="text-danger">{{$data}}</p>
										<button type="submit" class="btn btn-primary px-5">Update</button>
									</div>

                                    @endforeach
								</form>

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
        $('#u_branch').chosen();
        $('#bus_line').chosen();
        
        $(document).ready(function(){

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('#csrf_token').val()
}
});
getbusinesslinedata();

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
