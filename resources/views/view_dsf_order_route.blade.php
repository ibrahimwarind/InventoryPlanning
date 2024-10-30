
  <?php
  include("../function/function.php");
  session_start();
  $signup_type=mysqli_real_escape_string($con,$_POST['signup_type']);
  $signup_user_id=mysqli_real_escape_string($con,$_POST['signup_user_id']);
  $signup_fullname=mysqli_real_escape_string($con,$_POST['signup_fullname']);
  $signup_password=mysqli_real_escape_string($con,md5($_POST['signup_password']));
  $signup_password_confirm=mysqli_real_escape_string($con,$_POST['signup_password_confirm']);
  $signup_email=mysqli_real_escape_string($con,$_POST['signup_email']);
  $signup_phone=mysqli_real_escape_string($con,$_POST['signup_phone']);
  $signup_address=mysqli_real_escape_string($con,$_POST['signup_address']);
  $signup_country=mysqli_real_escape_string($con,$_POST['signup_country']);
  $signup_state=mysqli_real_escape_string($con,$_POST['signup_state']);
  $signup_town=mysqli_real_escape_string($con,$_POST['signup_town']);
  $signup_zip=mysqli_real_escape_string($con,$_POST['signup_zip']);
  $b_date=mysqli_real_escape_string($con,$_POST['b_date']);
  $signature1=mysqli_real_escape_string($con,$_POST['signature']);
  $signature2=mysqli_real_escape_string($con,$_POST['signature2']);
 $signup_token=md5(time());

 $folderPath = "uploads/";

    // // Signature 1
    // $signature1 = $_POST['signature1'];
    // $imageParts1 = explode(";base64,", $signature1);
    // $imageTypeAux1 = explode("image/", $imageParts1[0]);
    // $imageType1 = $imageTypeAux1[1];
    // $imageBase641 = base64_decode($imageParts1[1]);
    // $fileName1 = uniqid() . '.' . $imageType1;
    // $file1 = $folderPath . $fileName1;
    // file_put_contents($file1, $imageBase641);

    // // Signature 2
    // $signature2 = $_POST['signature2'];
    // $imageParts2 = explode(";base64,", $signature2);
    // $imageTypeAux2 = explode("image/", $imageParts2[0]);
    // $imageType2 = $imageTypeAux2[1];
    // $imageBase642 = base64_decode($imageParts2[1]);
    // $fileName2 = uniqid() . '.' . $imageType2;
    // $file2 = $folderPath . $fileName2;
    // file_put_contents($file2, $imageBase642);


  if(isset($_SESSION['user_email']))
  {
    $fg_checkuser="SELECT * FROM `tt_signup` where signup_email='$signup_email'";
    $run_checkuser=mysqli_query($con,$fg_checkuser);
    if(mysqli_num_rows($run_checkuser) == 0)
    {
      $ins_insert="INSERT INTO tt_signup(signup_type,signup_fullname,signup_address,signup_zip,signup_email,signup_password,signup_password_confirm,signup_state,signup_country,signup_user_id,signup_token,signup_status,signup_createdon,signup_town,signup_phone,main_date,signup_signature1,signup_signature2)VALUES('$signup_type','$signup_fullname','$signup_address','$signup_zip','$signup_email','$signup_password','$signup_password_confirm','$signup_state','$signup_country','$signup_user_id','$signup_token',1,NOW(),'$signup_town','$signup_phone','$b_date','$signature1','$signature2')";
    $run_insert=mysqli_query($con,$ins_insert);


    $fg_getsignupid="SELECT signup_id FROM `tt_signup` order by signup_id desc limit 1";
    $run_signupid=mysqli_query($con,$fg_getsignupid);
    $row_signupid=mysqli_fetch_array($run_signupid);
    $signup_id=$row_signupid['signup_id'];


    $data=explode(" ",$signup_fullname);
$first=substr($data[0],0,1);
$second=substr($data[1],0,1);
$overall=$first."".$second;
$memberid="HPC-".$overall."-".$signup_id;

$fg_getvp="SELECT signup_fullname FROM `tt_signup` where signup_id='$signup_user_id'";
$run_getvp=mysqli_query($con,$fg_getvp);
$row_getvp=mysqli_fetch_array($run_getvp);
$vpfullname=$row_getvp['signup_fullname'];



    if($run_insert)
    {

$to = $signup_email; 

// Subject of the email
$subject = "Welcome to Home Protection Club!";

// Message body
$message = "
<html>
<head>
<title>Welcome to Home Protection Club!</title>
</head>
<body>
<p>Dear $signup_fullname,</p>
<p>Warm greetings and welcome to Home Protection Club! We are thrilled to announce your selection as the Business Development Manager under the guidance of $vpfullname.</p>
<p><strong>Dashboard Credentials:</strong></p>
<ul>
<li><strong>Username:</strong> $signup_email</li>
<li><strong>Password:</strong> $signup_password_confirm</li>
</ul>
<p>You can access the portal by visiting the following link: <a href='https://homeprotectclub.com/'>Home Protection Club Login</a>.</p>
<p>Once again, welcome aboard! We’re excited to have you on our team.</p>
<br>
<p>Best regards,<br>
Support Team<br>
Home Protection Club<br>
<a href='mailto:support@homeprotectclub.com'>support@homeprotectclub.com</a><br>
Contact: +923323324424 (WhatsApp)</p>
</body>
</html>
";

// To send HTML mail, you need to set the Content-type header
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
$headers .= 'From: Home Protection Club <support@homeprotectclub.com>' . "\r\n";
mail($to, $subject, $message, $headers);


      echo "<script>alert('Business Manager has succcesfully Added')</script>";
      echo "<script>window.open('add_bdm.php','_self')</script>";
    }
    else{
      echo "<script>alert('Business Manager has succcesfully Not Added')</script>";
      echo "<script>window.open('add_bdm.php','_self')</script>";
    }

    }
    else{
      echo "<script>alert('Email Address or Phone Number Already Exist')</script>";
      echo "<script>window.open('add_bdm.php','_self')</script>";
    }

    

  
  }
  else{
      echo "<script>alert('Your Session has expire please login again')</script>";
      echo "<script>window.open('user_login.php','_self')</script>";
  }




  ?>