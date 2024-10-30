@include('header');

	<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">User Profile</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Change Password</li>
							</ol>
						</nav>
					</div>
				
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">
							<form method="post" action="<?php echo PROJECTURL ?>/changepassword">
							<div class="col-lg-12">
								 @csrf
								<div class="card">
									<div class="card-body">
										<div class="row mb-3">
											<div class="col-sm-2">
												<h6 class="mb-0">New Password</h6>
											</div>
											<div class="col-sm-9 text-secondary">
												<input type="hidden" value="{{session('adminname')}}" name="uname">
												<input type="text" class="form-control" value="" name="upass" />
												<span class="text-danger">@error('upass'){{$message}} @enderror</span>
											</div>
										</div>
										
										
										<div class="row">
											<div class="col-sm-2"></div>
											<div class="col-sm-9 text-secondary">
												<input type="submit" class="btn btn-primary px-4" value="Update Password" />
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>


		@include('footer');