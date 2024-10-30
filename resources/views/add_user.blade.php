@include('header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">

 
					<div class="col">
						<h6 class="mb-0 text-uppercase">Add User<a href="/userlist" class="btn btn-success" style="float: right !important;margin-top: -10px">View List</a></h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-user me-1 font-22" style="color:#398E3D"></i>
									</div>
									<h5 class="mb-0" style="color:#398E3D">Add User</h5>
								</div>
								<hr>

								<form method="get" action="adduser2" enctype= "multipart/form-data" class="row g-3">
									@csrf

									<div class="col-md-4">
										<label for="inputFirstName" class="form-label">UserName</label>
										<input type="text" class="form-control" id="inputFirstName"  name="u_name" required="">
									</div>
									<div class="col-md-4">
										<label for="inputFirstName" class="form-label">Password</label>
										<input type="text" class="form-control" id="inputFirstName"  name="u_password" required="">
									</div>
							
								<div class="col-sm-4 col-6">
									<label for="inputFirstName" class="form-label">Select Branch Name</label>
							<select  class='form-control' name="branch_name[]" id="branch_name" multiple>
					            
                                    <option value="">All Branch</option>
								    @foreach($branchdata as $brdata)
								    <option value="{{$brdata->branch_code}}">{{$brdata->branch_code}} - {{$brdata->branch_name}}</option>

								    @endforeach
								
                                
							</select>
						</div>
								
								

									
									<div class="col-12">
										<p class="text-danger">{{$data}}</p>
										<button type="submit" class="btn btn px-5" style="background-color: #398E3D;color:white">Save</button>
									</div>
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
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
   $('#branch_name').select2();

</script>
