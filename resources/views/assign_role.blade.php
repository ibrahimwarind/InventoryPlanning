@include('header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<?php 
						$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
						$fg_mem="select * from admin where admin_id='$uid'";
						$run_mem=mysqli_query($con,$fg_mem);
						$row_mem=mysqli_fetch_array($run_mem);
						$admin_name=$row_mem['admin_name'];

						?>
						<h6 class="mb-0 text-uppercase"><?php echo $admin_name ?> -  Role Assign</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<form method="get" action="<?php echo PROJECTURL ?>/assignrole2" enctype= "multipart/form-data" class="row g-3">
									@csrf
									<input type="hidden" value="{{$uid}}" name="uid">
								<?php 
								$fg_menu="select * from sb_main_menu";
								$run_menu=mysqli_query($con,$fg_menu);
								while($row_menu=mysqli_fetch_array($run_menu))
								{
									$menu_id=$row_menu['menu_id'];
									$menu_name=$row_menu['menu_name'];
									?>
									
							
									
									<p class="mb-0" style="color:black !important;border-bottom:1px solid black">{{$menu_name}}</p>
						
							
								<?php 
								$fg_sub="select * from sb_sub_menu where menu_id='$menu_id'";
								$run_sub=mysqli_query($con,$fg_sub);
								while($row_sub=mysqli_fetch_array($run_sub))
									{ 
										$sub_menu_name=$row_sub['sub_menu_name'];
										$sub_id=$row_sub['sub_id'];
										?>

						
								<div class="col-md-3">
									<div class="form-check">
										<?php 
			$checks="select * from sb_user_rights where user_id='$uid' AND sub_menu_id='$sub_id'";
			$run_checks=mysqli_query($con,$checks);
			$row_checks=mysqli_num_rows($run_checks);
			if($row_checks !=0){ ?>							
  <input class="form-check-input" type="checkbox" value="{{$sub_id}}" name="menuid[]" id="s{{$sub_id}}" checked>
							<?php }	else {?>
<input class="form-check-input" type="checkbox" value="{{$sub_id}}" name="menuid[]" id="s{{$sub_id}}">
<?php } ?>

  <label class="form-check-label" for="s{{$sub_id}}">
    {{$sub_menu_name}}
  </label>
</div>
								</div>
										<?php } ?>

								<?php }
								?>

							 		

								
									

									

									
									<div class="col-12">
										<br>
										<button type="submit" class="btn btn-primary px-5">Assign</button>
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
