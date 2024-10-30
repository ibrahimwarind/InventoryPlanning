@include('header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Edit Dsf Data (Name : {{$dsfname}})</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-user me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 text-primary">Edit Dsf Data</h5>
								</div>
								<hr>

								<form method="get" action="<?php echo PROJECTURL ?>/updatedsfpassword" enctype= "multipart/form-data" class="row g-3">
									@csrf
									@foreach($dsfdata as $detail)
									<input type="hidden" value="{{$detail->dsf_id}}" name="did">

									
									<div class="col-md-4">
										<label for="inputFirstName" class="form-label">Password</label>
										<input type="text" class="form-control" id="inputFirstName"  name="u_password" required="" value="{{$detail->loginpass}}">
									</div>
									

									
									<div class="col-12">
										<button type="submit" class="btn btn-primary px-5">Save</button>
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
	<!-- <script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script> -->
	<script src="assets/js/jquery.min.js"></script>