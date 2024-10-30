<?php
define('PROJECTURL','http://127.0.0.1:8000');
?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta name="description" content="Sales Booster - Admin Panel - Preimier Group Of Companies">
    <meta name="author" content="Premier Group Of Companies">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicons.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>{{session('titlename')}}</title>
</head>
 
<body>
	<!--wrapper-->
	<div class="wrapper">
		<div class="authentication-header" style="background-color: #398E3D !important"></div>
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="mb-4 text-center" style="background-color: #398E3D !important">
							<img src="assets/images/logo-img.png" width="180" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="p-4 rounded">
									<div class="text-center">
										<img src="{{ URL::asset('assets/images/premierlogo.png') }}" style="height:100px;width:220px" >
										<h3 class="">Inventory Planning Panel</h3>
										
									</div>
								
									<div class="login-separater text-center mb-4"> <span>Enter your credentials</span>
										<hr/>
									</div>
									<div class="form-body">
										<form method="post" action="<?php echo PROJECTURL ?>/login" class="row g-3">
											<div class="col-12">
												  @csrf
												<label for="inputEmailAddress" class="form-label">Enter Username</label>
												<input type="text" class="form-control" id="uname" name="uname" placeholder="User Name">
												<span class="text-danger">@error('uname'){{$message}} @enderror</span>
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Enter Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="upass"  value="" placeholder="Enter Password" name="upass"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>

												</div>
													<span class="text-danger">@error('upass'){{$message}} @enderror</span>
											</div>
											<div class="col-md-12">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked style="background-color: #398E3D">
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
												</div>
												<p class="text-danger" style="font-size:13px">{{$data}}</p>
											</div>
											
											<div class="col-12" style="margin-top: -5px">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary" style="background-color: #398E3D"><i class="bx bxs-lock-open"></i>Sign in</button>
												</div>
												<p style="margin-top: 5px;text-align: center;font-size: 12px">By continuing, you're confirming that you've read our <span style="color:#398E3D">Terms & Conditions</span> and <span style="color:#398E3D">Cookie Policy </span></p>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});

		const rmCheck = document.getElementById("flexSwitchCheckChecked"),
    emailInput = document.getElementById("uname"),
    passInput = document.getElementById("upass");

if (localStorage.checkbox && localStorage.checkbox !== "") {
  rmCheck.setAttribute("checked", "checked");
  emailInput.value = localStorage.username;
  passInput.value = localStorage.userpass;
} else {
  rmCheck.removeAttribute("checked");
  emailInput.value = "";
  passInput.value = "";
}

$('#flexSwitchCheckChecked').change(function(){
   if (rmCheck.checked && emailInput.value !== "") {
    localStorage.username = emailInput.value;
    localStorage.userpass = passInput.value;
    localStorage.checkbox = rmCheck.value;
  } else {
    localStorage.username = "";
    localStorage.userpass = "";
    localStorage.checkbox = "";
  }
});

	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


<!-- Mirrored from codervent.com/synadmin/demo/vertical/authentication-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Sep 2022 05:32:45 GMT -->
</html>