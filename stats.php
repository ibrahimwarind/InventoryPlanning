<!DOCTYPE html>
<html>
<head>
	<title>Salesbooster Stats</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<?php 
$con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");

$curdate=date('d-m-Y');
$curdate2=date('Y-m-d');

$fg_markatten="SELECT * FROM `sb_attendance_mark` where mark_date='$curdate2'";
$run_markatten=mysqli_query($con,$fg_markatten);
$row_markatten=mysqli_num_rows($run_markatten);


$fg_stats2="SELECT * FROM sb_order_master where order_date >= '$curdate2' AND day_over='Y'";
$run_stats2=mysqli_query($con,$fg_stats2);
$row_stats2=mysqli_num_rows($run_stats2);

$fg_stats3="SELECT * FROM sb_order_master where order_date >= '$curdate2' AND day_over='N'";
$run_stats3=mysqli_query($con,$fg_stats3);
$row_stats3=mysqli_num_rows($run_stats3);

$fg_stats5="SELECT * FROM sb_order_master where order_date >= '$curdate2' group by branch_code";
$run_stats5=mysqli_query($con,$fg_stats5);
$row_stats5=mysqli_num_rows($run_stats5);

$fg_stats4="SELECT * FROM `sb_attendance_mark` where mark_date='$curdate2' group by dsf_branch";
$run_stats4=mysqli_query($con,$fg_stats4);
$row_stats4=mysqli_num_rows($run_stats4);

$fg_stats6="SELECT * FROM `export_bmsexcel_log` where export_datetime >= '$curdate2' group by unique_key";
$run_stats6=mysqli_query($con,$fg_stats6);
$row_stats6=mysqli_num_rows($run_stats6);

$fg_stats7="SELECT * FROM `sb_order_history_log` where upload_datetime >= '$curdate2'";
$run_stats7=mysqli_query($con,$fg_stats7);
$row_stats7=mysqli_num_rows($run_stats7);


$connection_string = 'DRIVER={ODBC Driver 18 for SQL Server};SERVER=tcp:202.142.180.146,9438;DATABASE=DeliveryManagementDB;TrustServerCertificate=yes;';
        $conn = odbc_connect( $connection_string,  'sa', 'Database123$' ) or die (odbc_errormsg());
        
		// $query = "SELECT DISTINCT det.custcode,det.custname,res.reason_name FROM DeliverOrdersDetail as det inner join mobile_timing as mst on mst.userid=det.userid 
		// inner join Reason_setup res on det.cancelreason=res.id where mst.userid=25219  AND det.iscancel=0 and CAST(mst.starttime AS Date)='2024-05-31'";

		$query = "SELECT DISTINCT det.custcode,det.custname FROM DeliverOrdersDetail as det inner join mobile_timing as mst on mst.userid=det.userid 
		 where mst.userid=25219  AND det.iscancel=0 and CAST(mst.starttime AS Date)='2024-05-31'";

		$queryResult = odbc_exec($conn,$query);
		$results = [];
        while(odbc_fetch_row( $queryResult )) {
            // $reason_name = odbc_result($queryResult, "reason_name");
			$custcode = odbc_result($queryResult, "custcode");
			$custname = odbc_result($queryResult, "custname");

			echo $custcode."<br>";
			
		}



?>
</head>
<body>
   <div class="container-fluid">
     
     <div class="row">
     	<div class="col-sm-2 col-12"></div>
     	<div class="col-sm-8 col-12 ">
     		<center><br><h2>Sales Booster Dashboard Stats - Date : <?php echo $curdate2 ?></h2></center>
     		<table border="1" style="width:100%;border:1px solid black">

     			<thead style="background-color: lightgray;color:black;border:1px solid black">
     				<tr style="border:1px solid black">
     					<th>Stats Name </th>
     					<th>Count</th>
     				</tr>
     			</thead>

     			<tbody>
     				<tr style="color:black;padding: 12px;border:1px solid black">
     				<td style="border:1px solid black;padding: 12px;color:black;font-size:19px">Mark Attendance</td>
     				<td style="border:1px solid black;padding: 12px;color:black;font-size:19px"><span id="markcount"></span></td>

     			    </tr>

     			    <tr style="color:black;padding: 12px;border:1px solid black">
     				<td style="border:1px solid black;padding: 12px;font-size:19px">Upload Data With Day End</td>
     				<td style="border:1px solid black;padding: 12px;font-size:19px"><?php echo number_format($row_stats2); ?></td>

     			    </tr>

     			    <tr style="color:black;padding: 12px;border:1px solid black">
     				<td style="border:1px solid black;padding: 12px;font-size:19px">Upload Data WithOut Day End</td>
     				<td style="border:1px solid black;padding: 12px;font-size:19px"><?php echo number_format($row_stats3); ?></td>

     			    </tr>

     			    <tr style="color:black;padding: 12px;border:1px solid black">
     				<td  style="border:1px solid black;padding: 12px;font-size:19px">Mark Attendance Branch Count</td>
     				<td  style="border:1px solid black;padding: 12px;font-size:19px"><?php echo number_format($row_stats4); ?></td>

     			    </tr>

     			    <tr style="color:black;padding: 12px;border:1px solid black">
     				<td  style="border:1px solid black;padding: 12px;font-size:19px">Upload Data Branch Count</td>
     				<td style="border:1px solid black;padding: 12px;font-size:19px"><?php echo number_format($row_stats5); ?></td>

     			    </tr>

     			    <tr style="color:black;padding: 12px;border:1px solid black">
     				<td style="border:1px solid black;padding: 12px;font-size:19px">Export Excel Through Dashboard</td>
     				<td style="border:1px solid black;padding: 12px;font-size:19px"><?php echo number_format($row_stats6); ?></td>

     			    </tr>


     			    <tr style="color:black;padding: 12px;border:1px solid black">
     				<td style="border:1px solid black;padding: 12px;font-size:19px">Import Order Excel Through Dashboard</td>
     				<td style="border:1px solid black;padding: 12px;font-size:19px"><?php echo number_format($row_stats7); ?></td>

     			    </tr>




     			</tbody>
     			
     		</table>
     	</div>
     </div>
   </div>
</body>
</html>
<script>
	function getdata(){
	 $.ajax({
      url:"ajax/getdashboardstats.php",
      method:"GET",
      dataType:"json",
      success:function(data){
    
		$('#markcount').text(data[0]);
     
      }
      
    });
}
getdata();
Pusher.logToConsole = true;

    var pusher = new Pusher('6f0daf4b35545c98ad31', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
		getdata();
    //    if(data  && data.author && data.title)
    //    {
    //     toastr.success('New Post Created','Author : ' + data.author + "<br> title : " + data.title,{
    //        timeOut:0,
    //        extendedTimeOut:0
    //     });
    //    }
       
      // alert(JSON.stringify(data));
    });

</script>
<?php 

// echo "<script>$('#markcount').text($row_markatten);</script>";
?>