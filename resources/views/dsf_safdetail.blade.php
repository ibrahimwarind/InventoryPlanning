@include('header')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
?>
	<link href="https://codervent.com/synadmin/demo/vertical/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
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
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3" style="margin-top: -20px">
					<div class="breadcrumb-title pe-3">DSF SAF Detail</div>
					
					<div class="ms-auto">
						<div class="btn-group">
							
							
							
						</div>
						<span id="shownotice">
								<br>
							<!-- 	<p class="text-danger" style="margin-top: 5px !important">Please Wait for a while, the file was generated...</p> -->
							</span>
					</div>
				</div>
				<hr/>

				<!--end breadcrumb-->
				<h6 class="mb-0 text-uppercase">
					<form method="get" action="<?php echo PROJECTURL ?>/dsfsafdetail2">
						@csrf
					<div class="row">
						
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_branch" id="pro_branch">
								<?php 
								if($pro_branch == "")
								{
                            echo "<option value='' hidden=''>Select Branch</option>";
								}
								else
								{
                            echo "<option value='$pro_branch'>$pro_branch - $pro_branch_name</option>";
								}?>
								@foreach($branchmaster as $branch)
								<option value="{{$branch->branch_code}}">{{$branch->branch_code}} - {{$branch->branch_name}}</option>

								@endforeach
							</select>
						</div>
						
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_group" id="pro_group">
								<?php 
								if($pro_group == "")
								{
                            echo "<option value='' hidden=''>Select Group</option>";
								}
								else
								{
                            echo "<option value='$pro_group'>$pro_group - $pro_group_name</option>";
								}?>
								
							</select>
						</div>
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_dsf" id="pro_dsf">
								<?php 
								if($pro_dsf == "")
								{
                            echo "<option value='' hidden=''>Select DSF</option>";
								}
								else
								{
                            echo "<option value='$pro_dsf'>$pro_dsf - $pro_dsf_name</option>";
								}?>
								
							</select>
						</div>
						<div class="col-sm-2 col-6"  style="">
							<select class="form-control" style="" name="pro_day" id="pro_day">
								<?php 
								if($dayname == "")
								{
                            echo "<option value='' hidden=''>Select Day</option>";
								}
								else
								{
                            echo "<option value='' hidden=''>$dayname</option>";
								}?>
								
								<option>MONDAY</option>
								<option>TUESDAY</option>
								<option>WEDNESDAY</option>
								<option>THURSDAY</option>
								<option>FRIDAY</option>
								<option>SATURDAY</option>
								<option>SUNDAY</option>
							</select>
						</div>
					
						<div class="col-sm-2  col-12">
							
							<input type="submit" class="btn btn-info" value="Search" name="usearch" style="padding:2px !important;font-size:15px;height:30px;color:white">
							<a href="<?php echo PROJECTURL ?>/dsfsafdetail" class="btn btn-primary" style="padding:2px !important;font-size:15px;height:30px">Clear</a>
						</div>
					</div>
				   </form>
				</h6>
				<hr/>
<!-- 				status , proname id code , brand , company  -->
				<div class="card">
					<div class="card-body">
						<div class="">
							<table id="" class="table table-striped table-bordered" >
								<thead>
									<tr>
									<th style="text-align:center;width: 300px">Branch Name</th>
									<th style="text-align:center">Group Name</th>
									<th style="text-align:center">DSF Name</th>
									<th style="text-align:center">Customer</th>
									<th style="text-align:center">Day Name</th>
										

									</tr>
								</thead>
								<tbody>
			<?php $oldbranch="";$oldgroup="";$olddayname="";$daytotal=0;?>
			@foreach($dsfsafdata as $user)
			<?php 



            $brname="";
            $fg_br="SELECT branch_name FROM `sb_branch_master` where branch_code='$user->branch_code'";
            $run_br=mysqli_query($con,$fg_br);
            if(mysqli_num_rows($run_br) !=0)
            {
            	$row_br=mysqli_fetch_array($run_br);
            	$brname=$row_br['branch_name'];
            }

            $gpname="";
            $fg_gp="SELECT supply_groupname FROM `sb_business_line` where supply_groupid='$user->supply_groupid' AND branch_code='$user->branch_code'";
            $run_gp=mysqli_query($con,$fg_gp);
            if(mysqli_num_rows($run_gp) !=0)
            {
            	$row_gp=mysqli_fetch_array($run_gp);
            	$gpname=$row_gp['supply_groupname'];
            }

            $dfname="";
            $fg_df="SELECT dsf_name FROM `sb_dsf_master` where dsf_code='$user->dsf_code'";
            $run_df=mysqli_query($con,$fg_df);
            if(mysqli_num_rows($run_df) !=0)
            {
            	$row_df=mysqli_fetch_array($run_df);
            	$dfname=$row_df['dsf_name'];
            }

            $csname="";
            $fg_cs="SELECT customer_name FROM `sb_customers` where customer_code='$user->customer_code'";
            $run_cs=mysqli_query($con,$fg_cs);
            if(mysqli_num_rows($run_cs) !=0)
            {
            	$row_cs=mysqli_fetch_array($run_cs);
            	$csname=$row_cs['customer_name'];
            }

if($oldbranch !=$user->branch_code)
{
$bcode=$user->branch_code;
//get last sync
date_default_timezone_set("Asia/Karachi");
$currentdate=date('Y-m-d');
$fg_lst="SELECT status_code FROM `sb_dsf_saf_detail_log` where (api_name='insertSafDetailsEnd' or api_name='manualinsertSafDetails') AND branch_code='$bcode' AND DATE_FORMAT(posted_date,'%Y-%m-%d')='$currentdate'";
$run_lst=mysqli_query($con,$fg_lst);
$message="<span style='float:right;color:#800000'>Data Not Sync Today! &nbsp;&nbsp;&nbsp;<a class='statuschange btn btn-info' data-bcode='$bcode' data-bname='$brname' style='height:28px;font-size:13px'><span style='margin-top:-10px'>Sync Now <i class='fas fa-sync-alt'></i></span></a></span>";
if(mysqli_num_rows($run_lst) !=0)
{
$row_lst=mysqli_fetch_array($run_lst);
$status_code=$row_lst['status_code'];

if($status_code == "200")
{
$message="<span style='float:right;color:white'>Data Sync Successfully &nbsp;&nbsp;&nbsp;<a class='statuschange btn btn-info' data-bcode='$bcode' data-bname='$brname' style='height:28px;font-size:13px'><span style='margin-top:-10px !important'>Sync Now <i class='fas fa-sync-alt'></i></span></a></span>";
}	
else{
$message="<span style='float:right;color:#800000'>Data Not Sync Today! &nbsp;&nbsp;&nbsp;<a class='statuschange btn btn-info' data-bcode='$bcode'  data-bname='$brname'  style='height:28px;font-size:13px'><span style='margin-top:-10px'>Sync Now <i class='fas fa-sync-alt'></i></span></a></span>";
}

}

	echo "
<tr style='background-color:#679149'>
<th colspan='5'>Branch : ".$user->branch_code." - ".$brname."".$message."</th>
</tr>
	";
} 
if($oldgroup !=$user->supply_groupid)
{
	echo "
<tr style='background-color:lightgray'>
<th colspan='5'>Group : ".$user->supply_groupid." - ".$gpname."</th>
</tr>
	";
} 
if($olddayname !=$user->day_name && $olddayname !="")
{
	echo "
<tr style='background-color:lightgray'>
<th colspan='5'><span style='float:right;'>".$olddayname." Total Customer : ".$daytotal."</span></th>
</tr>
	";
	$daytotal=0;
} 
?>						
<tr>
								<td style="font-size:12px">{{$user->branch_code}} - {{$brname}} </td>
								<td style="font-size:12px">{{$user->supply_groupid}} - {{$gpname}}</td>
								<td style="font-size:12px">{{$user->dsf_code}} - {{$dfname}}</td>	
								<td style="font-size:12px">{{$user->customer_code}} - {{$csname}}</td>	
								<td style="font-size:12px">{{$user->day_name}}</td>	
								
								
					
									</tr>
<?php $oldbranch=$user->branch_code;$oldgroup=$user->supply_groupid;$olddayname=$user->day_name;
if($user->customer_code !=""){
$daytotal=$daytotal + 1;}?>

									@endforeach
<?php

	echo "
<tr style='background-color:lightgray'>
<th colspan='5'><span style='float:right;'>".$olddayname." Total Customer : ".$daytotal."</span></th>
</tr>
	";
?>									
								</tbody>
								<tfoot>
									<tr>
									<th style="text-align:center;width: 300px">Branch Name</th>
									<th style="text-align:center">Group Name</th>
									<th style="text-align:center">DSF Name</th>
									<th style="text-align:center">Customer</th>
									<th style="text-align:center">Day Name</th>
									</tr>
								</tfoot>
							</table>
                            <div style="margin-top: 10px">
							{{$dsfsafdata->links()}}
							</div>
							
						</div>
					</div>
<style>
	.w-5{
		display: none;
	}
</style>
 
				</div>
				
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2023. All right reserved.</p>


		</footer>
	</div>

<!-- <button class="open-button" onclick="openForm()">Open Form</button> -->

<div class="form-popup" id="myForm">
  <div  class="form-container" style="padding: 20px">
     
     <i class="fa fa-check text-success" style="font-weight: bold;font-size: 29px;" aria-hidden="true" ></i> &nbsp;&nbsp;<span style="font-weight: bold;font-size: 20px;">Successfully Updated</span>
    
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"><span id="brname"></span> Data Sync !
	</h6>
        
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/sync.gif') }}" style="height: 150px;width:150px">
           	<h5 style="margin-top: -18px">DSF SAF Detail Synchronizing Please Wait !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"> Data Sync Success !
	</h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/checked.png') }}" >
           	<h5 ><br>Synchronize Successfully Done !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" data-bs-backdrop="static" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title dsfname" id="exampleModalLabel"> Data Sync Failed !
	</h6>
        <button type="button" id="closebtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <div class="row">
          
           <div class="col-sm-12">
           	<center><img src="{{ URL::asset('assets/warning.png') }}" >
           	<h5  class="text-danger"><br>Synchronize Failed !</h5>
           </center>

           </div>
		 
     
        
       </div>
     </div>


      </div>
      
    </div>
  </div>
</div>

	<!--end wrapper-->
	<!--start switcher-->

	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
	<script>
$('#pro_branch').chosen();
$('#pro_group').chosen();
$('#pro_dsf').chosen();
$('#pro_day').chosen();



  
		$(document).ready(function() {

        	var table = $('#example').DataTable( {
				lengthChange: false,
				"paging": false ,
               "bInfo" : false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example_wrapper .col-md-6:eq(0)' );



// $('#example').DataTable({
//     lengthChange: false,
//     "paging": false ,
//     "bInfo" : false,
//     buttons: [ 'copy', 'excel', 'pdf', 'print']

// });
		  } );

  $(document).ready(function(){
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

          //change status
	$('#pro_branch').on('change',function(){
		var pro_branch=$('#pro_branch').val();
		$('#pro_group').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchgroup') }}",
            data: { branchcode: pro_branch},
            dataType: 'json',
            success: function(res){
            	$('#pro_group').append("<option value='' hidden>Select Group</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_group').append("<option value='"+res[i].supply_groupid+"'>"+res[i].supply_groupname+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });


	$('#pro_branch').on('change',function(){
		var pro_branch=$('#pro_branch').val();
		var pro_group=$('#pro_group').val();
		$('#pro_dsf').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchdsf') }}",
            data: { branchcode: pro_branch,pro_group:pro_group},
            dataType: 'json',
            success: function(res){
            	$('#pro_dsf').append("<option value='' hidden>Select Dsf</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });


	$('#pro_group').on('change',function(){
		var pro_branch=$('#pro_branch').val();
		var pro_group=$('#pro_group').val();
		$('#pro_dsf').html("");
		$.ajax({
            type:"POST",
            url: "{{ url('getbranchdsf') }}",
            data: { branchcode: pro_branch,pro_group:pro_group},
            dataType: 'json',
            success: function(res){
            	$('#pro_dsf').append("<option value='' hidden>Select Dsf</option>").trigger('chosen:updated');
             for(var i=0;i <= res.length;i++)
              { 
              	$('#pro_dsf').append("<option value='"+res[i].dsf_code+"'>"+res[i].dsf_name+"</option>").trigger('chosen:updated');

              }
            	
           }
        });
        });


	//sync now
	$('.statuschange').on('click',function(){
		var bcode=$(this).data('bcode');
		var bname=$(this).data('bname');
		$('#brname').text(bname);
		
		$('#exampleModal2').modal('show');
		$.ajax({
            type:"POST",
            url: "{{ url('dsfsafbranchwisesync') }}",
            data: { bcode: bcode },
            dataType: 'json',
            success: function(res){
              
              $('#exampleModal2').modal('hide');
              if(res.success == true)
              {
              	$('#exampleModal3').modal('show');
              }
              else
              {
              	$('#exampleModal4').modal('show');
              }
              

            
           }
        });
    });

		});

 $('#closebtn').click(function(){
 location.reload(true);
 
 });
	</script>
	<script>
		

 
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>


</html>
