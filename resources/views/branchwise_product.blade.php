@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME); ?>
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
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">

					<div class="col">
						
						<h6 class="mb-0 text-uppercase">
							<div class="row">
								<div class="col-sm-6">
									<p style="color:black;margin-top: 10px">Product Name : {{$pcode}} - {{$pname}}</p>
								</div>
								<div class="col-sm-5">
                                <p class="btn btn-info" style="color:white;font-size: 13px">In Branch Count : 
                                <?php $incount=0; 
                            foreach($totalbranch as $br)
                            {
                            	$incount=$incount + 1; 
                            }
                            echo $incount;
                                ?>
									</p>
									<p class="btn btn-info" style="color:white;font-size: 13px">Active In Branch  :
									<?php $incount=0; 
                            foreach($totalactivebranch as $br)
                            {
                            	$incount=$incount + 1; 
                            }
                            echo $incount;?>
                             </p>
									<p class="btn btn-info" style="color:white;font-size: 13px">In-Active In Branch  :
									<?php $incount=0; 
                            foreach($totalinactivebranch as $br)
                            {
                            	$incount=$incount + 1; 
                            }
                            echo $incount;?>
                             </p>
                               
                               </div>
								<div class="col-sm-2">
									
								</div>
								<div class="col-sm-2">
									
								</div>
							</div> 

						</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="col-sm-12">
									<table id="example" class="table table-striped table-bordered" >
								<thead>
									<tr>
									<th style="text-align:center;width: 380px">Branch Name</th>
									
									<th style="text-align:center">Action</th>	

									</tr>
								</thead>
								<tbody>
									@foreach($productbranchdata as $user)
									<?php 
         

            $brname="";
            $fg_br="SELECT branch_name FROM `sb_branch_master` where branch_code='$user->branch_code'";
            $run_br=mysqli_query($con,$fg_br);
            if(mysqli_num_rows($run_br) !=0)
            {
            	$row_br=mysqli_fetch_array($run_br);
            	$brname=$row_br['branch_name'];
            }
									?>
									<tr>
								<td style="text-align: center">{{$user->branch_code}} - {{$brname}}</td>
								
								<td style="width:100px">
				<center>
				<select class="form-control statuschange" name="" id="pro_status" data-bcode='{{$user->branch_code}}' data-pcode='{{$pcode}}' style='display: inline-block;font-size:14px;'>
				{{
				$status=$user->status;}}
				@if($status==1)
				<option value="1">Live</option>
				<option value="0">Block</option>
				@elseif($status==0)
				<option value="0">Block</option>
				<option value="1">Live</option>
				@endif
             	</select>
			</center>
				</td>
								
								
										
										
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center">Branch Name</th>
									<th style="text-align:center" >Action</th>
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
		<div class="form-popup" id="myForm">
  <div  class="form-container" style="padding: 20px">
     
     <i class="fa fa-check text-success" style="font-weight: bold;font-size: 29px;" aria-hidden="true" ></i> &nbsp;&nbsp;<span style="font-weight: bold;font-size: 20px;">Successfully Updated</span>
    
  </div>
</div>
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		
		 $(document).ready(function(){
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

          //change status
		$('.statuschange').on('change',function(){
		var bcode=$(this).data('bcode');
		var pcode=$(this).data('pcode');
		var status=$(this).val();
		

		$.ajax({
            type:"POST",
            url: "{{ url('productstatuschange') }}",
            data: { bcode: bcode,pcode:pcode,status:status },
            dataType: 'json',
            success: function(res){
               openForm();
            	closeForm();
            
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
