<?php
define('PROJECTURL','http://127.0.0.1:8000');
define('DATABASEHOST','127.0.0.1');
define('DATABASEUSER','root');
define('DATABASEPASS','');
define('DATABASENAME','db_ipl');
?>
<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor3 color-header headercolor1">


<!-- Mirrored from codervent.com/synadmin/demo/vertical/index3.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Sep 2022 05:23:43 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="description" content="Inventory Planning - Admin Panel - Preimier Group Of Companies">
    <meta name="author" content="Premier Group Of Companies">
	<!--favicon-->
	<link rel="icon" href="{{ URL::asset('assets/images/favicons.png') }}" type="image/png" />
<link href="{{ URL::asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
<!-- 	<link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" /> -->
	<!-- loader-->
	<link href="{{ URL::asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ URL::asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ URL::asset('assets/css/dark-theme.css') }}" />
	<link rel="stylesheet" href="{{ URL::asset('assets/css/semi-dark.css') }}" />
	<link rel="stylesheet" href="{{ URL::asset('assets/css/header-colors.css') }}" />
	<!-- <script src="https://kit.fontawesome.com/51238610b6.js" crossorigin="anonymous"></script> -->
	<script src="https://kit.fontawesome.com/4485c64441.js" crossorigin="anonymous"></script>
<!-- 	    <script src="https://kit.fontawesome.com/51238610b6.js" crossorigin="anonymous"></script> -->
<link rel="manifest"  href="{{ URL::asset('manifest.json') }}">

	<title>{{session('titlename')}}</title>
	<!-- 	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script> -->
</head>

<body>

	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true" >
			<div class="sidebar-header" style="background-color: black !important">
			
					<center style="margin-left: 20px !important;"><img src="{{ URL::asset('assets/images/premierlogo2.png') }}"  alt="Uraan Dashboard" style="vertical-align: center !important;height: 79px;width: 170px">
						
					</center>
					<br><br>

					<hr style="background-color: white;margin-top: 20px;height: 2px">
				<!-- <div>
					<h4 class="logo-text"></h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
				</div> -->
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu" style="background-color: black !important">
				<?php if(session('admintype') == "superadmin"){ ?>
				<li>
					<a href="/index" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home' ></i>
						</div>
						<div class="menu-title" style="font-size: 13px !important">Dashboard</div>
					</a>
				
					
				</li>
				<?php } ?>
				<?php 
				//$con=mysqli_connect(Config::get('app.databasehost'),Config::get('app.databaseusername'),Config::get('app.databaseuserpass'),Config::get('app.databasedatabasename'));
				$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME); 
				$fg_mem="select * from sb_main_menu order by menu_id asc";
				 $run_mem=mysqli_query($con,$fg_mem);
				 while($row_mem=mysqli_fetch_array($run_mem))
				 {
				 	$menu_id=$row_mem['menu_id'];
				 	$menu_name=$row_mem['menu_name'];
				 	$adminid=session('adminid');

				 	$fg_subcheck="select * from sb_user_rights where user_id='$adminid' AND main_menu_id='$menu_id'";
								$run_subcheck=mysqli_query($con,$fg_subcheck);
								$row_subcheck=mysqli_num_rows($run_subcheck);
								if($row_subcheck !=0){

				 	?>
                 <li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-spa'  ></i>
						</div>
						<div class="menu-title" style="font-size: 12px !important"><?php echo $menu_name ?>
					
					</div>
					</a>
					<ul>
						<?php 
						$sub_men="select * from sb_sub_menu where menu_id='$menu_id' order by sub_menu_name asc";
						$run_men=mysqli_query($con,$sub_men);
						while($row_men=mysqli_fetch_array($run_men))
							{ 
								$sub_menu_name=$row_men['sub_menu_name'];
								$page_link=$row_men['page_link'];
								$sub_id=$row_men['sub_id'];

								$fg_subcheck="select * from sb_user_rights where user_id='$adminid' AND sub_menu_id='$sub_id'";
								$run_subcheck=mysqli_query($con,$fg_subcheck);
								$row_subcheck=mysqli_num_rows($run_subcheck);
								if($row_subcheck !=0){

								?>
				<li> <a href="<?php echo PROJECTURL ?>/<?php echo $page_link ?>" style="font-size: 13px !important"><i class="bx bx-right-arrow-alt"></i><?php echo $sub_menu_name ?></a></li>
						<?php }  }?>
					</ul>
				</li>

				<?php } }
				
				?>
				
		
			
			
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
			<header >
			<div class="topbar d-flex align-items-center" style="background-color: #398E3D !important">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="top-menu-left d-none d-lg-block">
						<?php 
						if(session('admintype') == "superadmin")
						{
	// 						$fg_get="SELECT count(verify_id) as crequest FROM `tbl_mondelez_verification` where verify_status ='Pending'";
	// 						$run_get=mysqli_query($con,$fg_get);
	// 						$cn_get=mysqli_num_rows($run_get);
	// 						if($cn_get !=0)
	// 						{
	// echo "<p style='float: left !important;color:white;margin-top:8px'>You Have <a href='/mondelez_verification'><span class='text-danger'>0$cn_get</span></a> Mondelez Verification Request.</p>";							
	// 						}
						}

						?>
						
						<ul class="nav">
						 
					  </ul>
					 </div>
					<div class="search-bar flex-grow-1">
						<p style="float: left !important">afasf</p>
						<div class="position-relative search-bar-box">
							
						</div>
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item mobile-search-icon">
								<a class="nav-link" href="#">	
								</a>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<div class="row row-cols-3 g-3 p-3">
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
											</div>
											<div class="app-title">Teams</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
											</div>
											<div class="app-title">Projects</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
											</div>
											<div class="app-title">Tasks</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
											</div>
											<div class="app-title">Feeds</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
											</div>
											<div class="app-title">Files</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-filter-alt'></i>
											</div>
											<div class="app-title">Alerts</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
												ago</span></h6>
													<p class="msg-info">5 new user registered</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
												ago</span></h6>
													<p class="msg-info">You have recived new orders</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
												ago</span></h6>
													<p class="msg-info">The pdf files generated</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
												ago</span></h6>
													<p class="msg-info">5.1 min avarage time response</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Product Approved <span
												class="msg-time float-end">2 hrs ago</span></h6>
													<p class="msg-info">Your new product has approved</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">New customer comments recived</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
												ago</span></h6>
													<p class="msg-info">Successfully shipped your item</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
												ago</span></h6>
													<p class="msg-info">24 new authors joined last week</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
												ago</span></h6>
													<p class="msg-info">45% less alerts last 4 weeks</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Messages</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-message-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
												sec ago</span></h6>
													<p class="msg-info">Many desktop publishing packages</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
												ago</span></h6>
													<p class="msg-info">Duis aute irure dolor in reprehenderit</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">The passage is attributed to an unknown</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">The point of using Lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
												ago</span></h6>
													<p class="msg-info">If you are going to use a passage</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="https://codervent.com/synadmin/demo/vertical/assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
												ago</span></h6>
													<p class="msg-info">All the Lorem Ipsum generators</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
				<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="{{ URL::asset('assets/images/avatars/men.png') }}" class="user-img" alt="user avatar">
							<div class="user-info ps-3">
								<p class="user-name mb-0">{{session('adminname')}}</p>
								<!-- <p class="designattion mb-0">
									

								{{session('branchname')}}</p> -->
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="/changepassword"><i class="bx bx-user"></i><span>Change Password</span></a>
							</li>
						
							<li><a class="dropdown-item" href="/logout"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</li>
							
							
						</ul>
				</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		
		<!--end page wrapper -->
		<!--start overlay-->
		
