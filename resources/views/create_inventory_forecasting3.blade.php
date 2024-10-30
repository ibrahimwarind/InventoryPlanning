@include('header')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
$fg_comp="SELECT company_title FROM `ipl_company_master` where company_code='$company_id'";
$run_comp=mysqli_query($con,$fg_comp);
$compname="";
if(mysqli_num_rows($run_comp) !=0)
{
  $row_comp=mysqli_fetch_array($run_comp);
  $compname=$row_comp['company_title'];
}

$fg_branch="SELECT branch_name FROM `ipl_branch_master` where branch_code='$branch_id'";
$run_branch=mysqli_query($con,$fg_branch);$brname="";
if(mysqli_num_rows($run_branch) !=0)
{
  $row_branch=mysqli_fetch_array($run_branch);
  $brname=$row_branch['branch_name'];
}
?>
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Inventory Forecasting</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-object-group me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 " style="color:#398E3D;text-align: center">Forecasting Successfully Save..!</h5>
								</div>
								<hr style="background-color: #398E3D !important">
                  
                  <div class="col-md-12">
                    <p style="color:black">Company : {{$company_id}} - {{$compname}}</p>
                  </div>
								
									<div class="col-md-12">
										<p style="color:black">Master Forecasting Key : <b>{{$unique_key}}</b></p>
									</div>
                 

                  <div class="col-sm-12">
                    <br>
                    <table border="1" style="width:90%">
                      <tr style="background-color: #398E3D;color:white;border: 1px solid black">
                        <td style="border: 1px solid black"><center>Type</center></td>
                        <td style="border: 1px solid black"><center>Branch</center></td>
                        <td style="border: 1px solid black"><center>Branch Key</center></td>
                        <td style="border: 1px solid black"><center>Version No</center></td>
                        <td style="border: 1px solid black"><center>Group Name</center></td>
                        <td style="border: 1px solid black"><center>Multiply By</center></td>
                        <td style="border: 1px solid black"><center>Total Products</center></td>
                        
                      </tr>

                      <?php 
                    $fg_groupdata="SELECT brdata.branch_id,det.group_detail_id,brdata.forecasting_branchkey,det.multiply_by,ifnull(count(DISTINCT det.product_code),0) as totalproduct,det.formula_id,fm.formula_name,gm.group_name,brdata.version_no FROM `ipl_forecasting_branch_data` brdata inner join ipl_forecasting_detail det on brdata.forecasting_master_id=det.forecasting_id inner join ipl_formula_master fm on fm.formula_id=det.formula_id inner join ipl_group_master gm on det.group_detail_id=gm.group_id where brdata.forecasting_master_id='$forecasting_id' group by det.group_detail_id,brdata.branch_id order by brdata.branch_id";
                    $run_groupdata=mysqli_query($con,$fg_groupdata);
                    while($row_groupdata=mysqli_fetch_array($run_groupdata))
                    {

                      $branch_id=$row_groupdata['branch_id'];
                      $forecasting_branchkey=$row_groupdata['forecasting_branchkey'];
                      $multiply_by=$row_groupdata['multiply_by'];
                      $totalproduct=$row_groupdata['totalproduct'];
                      $formula_name=$row_groupdata['formula_name'];
                      $group_name=$row_groupdata['group_name'];
                      $version_no=$row_groupdata['version_no'];

                      $brnames="";
                      $fg_branchdata="SELECT branch_name FROM `ipl_branch_master` where branch_code='$branch_id'";
                      $run_branchdata=mysqli_query($con,$fg_branchdata);
                      if(mysqli_num_rows($run_branchdata) !=0)
                      {
                        $row_branchdata=mysqli_fetch_array($run_branchdata);
                        $brnames=$row_branchdata['branch_name'];
                      }

                      echo "
              <tr style='border:1px solid black'>
                <td style='border:1px solid black;color:black'>&nbsp;$formula_name</td>
                <td style='border:1px solid black;color:black'>&nbsp;$branch_id - $brnames</td>
                <td style='border:1px solid black;color:black;text-align:center'>$forecasting_branchkey</td>
                <td style='border:1px solid black;color:black;text-align:center'>$version_no</td>
                <td style='border:1px solid black;color:black;'>$group_name</td>
                <td style='border:1px solid black;color:black;text-align:center'>$multiply_by</td>
                <td style='border:1px solid black;color:black;text-align:center'>$totalproduct</td>
              </tr>

                      ";

                    }
                    ?>
                    </table>
                  </div>
 
									



									

									

                           
							
									

								

									
									<div class="col-12">
<br><br>
<a style="background-color: #398E3D;color:white" href="/createforecasting" class="btn btn-primary" >
<- Back to Home
</a>




										
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


	<script type="text/javascript">
			$(document).ready(function(){
// setTimeout(function() { 
//         $('.mobile-toggle-menu').trigger('click');
//     }, 200);


				
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

$('#groupshowhide').hide();
$('#submitbtn').hide();
        
    $('#errordata').hide();

	$('#formula_id').on('change',function(){
    
    var formula_id=$('#formula_id').val();
    $('#formula_det_id').html('');

    $.ajax({
            type:"POST",
            url: "{{ url('getformuladetail') }}",
            data: { formula_id: formula_id},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
              $('#formula_det_id').append("<option value='"+res[i].detail_id+"'>"+res[i].formula_description+"</option>").trigger('chosen:updated');
              }
			// $('#formula_det_id').val(res);
              
           }
        });


    $.ajax({
            type:"POST",
            url: "{{ url('getformulagroup') }}",
            data: { formula_id: formula_id},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
              $('#group_id').append("<option value='"+res[i].group_id+"'>"+res[i].group_name+"</option>").trigger('chosen:updated');
              }
			// $('#formula_det_id').val(res);
              
           }
        });
    
    });



    $('#company_id').on('change',function(){
       checkalreadyversion(); 
   });
    $('#branch_id').on('change',function(){
       checkalreadyversion(); 
   });
    $('#forecasting_month').on('change',function(){
       checkalreadyversion(); 
   });

    function checkalreadyversion()
    {
    	 var company_id=$('#company_id').val();
    var branch_id=$('#branch_id').val();
    var forecasting_month=$('#forecasting_month').val();
    
    $.ajax({
            type:"POST",
            url: "{{ url('checkfirstversionornot') }}",
            data: { company_id: company_id,branch_id:branch_id,forecasting_month:forecasting_month},
            dataType: 'json',
            success: function(res){
              if(res !=0)
              {
              	$('#groupshowhide').show();
              	$('#submitbtn').show();
              	$('#modalbutton').hide();
              	$('.form-check-input').removeAttr('required');
              	
              }
              else{
              	$('#groupshowhide').hide();
              	$('#submitbtn').hide();
              	$('#modalbutton').show();
              	$('.form-check-input').attr('required', true);
              }
              
           }
        });
    }



   $('#modalbutton').click(function(){
       var formula_id=$('#formula_id').val();
       var formula_det_id=$('#formula_det_id').val();
       var company_id=$('#company_id').val();
       var branch_id=$('#branch_id').val();

       if(formula_id == "" || formula_det_id == "" || company_id =="" || branch_id == "") 
       {
          $('#errordata').show();
       }
       else{
       	$('#errordata').hide();
       	$('#exampleModal').modal('show');
       }
       
   });


    $('#group_id').on('change',function(){
    
    var group_id=$('#group_id').val();
    $('#company_id').html('');

    $.ajax({
            type:"POST",
            url: "{{ url('getgroupcompany') }}",
            data: { group_id: group_id},
            dataType: 'json',
            success: function(res){
            	if(group_id == "")
            	{
            		$('#company_id').append("<option value='' hidden>Select Company</option>").trigger('chosen:updated');
            	}
              for(var i=0;i <= res.length;i++)
              { 
              $('#company_id').append("<option value='"+res[i].company_code+"'>"+res[i].company_code+" - "+res[i].company_title+"</option>").trigger('chosen:updated');
              }
			// $('#formula_det_id').val(res);
              
           }
        });
    });


});



$('#multiply_by').keyup(function(){
   var multiply_by=$('#multiply_by').val();
   var formula_detail=$('#formula_detail').val();

   $('#formula_detail').val(formula_detail.replace('$$', multiply_by));

});

 $('#checkAll').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#myTable tbody input[type="checkbox"]').prop('checked', isChecked);
    });


    // Get the current date
    const today = new Date();
    
    // Format dates to YYYY-MM for the input field
    const currentMonth = today.toISOString().slice(0, 7); // e.g., 2024-09
    today.setMonth(today.getMonth() - 1);
    const previousMonth = today.toISOString().slice(0, 7); // e.g., 2024-08

    // Set max and min attributes to restrict selection
    const monthInput = document.getElementById('forecasting_month');
    monthInput.max = currentMonth;
    monthInput.min = previousMonth;

    // Set default value to current month
    monthInput.value = currentMonth;

    // Optional: Add validation to ensure only allowed months are selected
    monthInput.addEventListener('input', function() {
        if (monthInput.value !== currentMonth && monthInput.value !== previousMonth) {
            alert('Please select the current or previous month only.');
            monthInput.value = currentMonth; // Reset to current month if invalid
        }
    });
	</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
<script type="text/javascript">
	$('#branch_id').chosen();
	$('#group_id').chosen();
	$('#company_id').chosen();
	$('#formula_id').chosen();
	$('#formula_det_id').chosen();

	function myFunction() {
  var input, filter, table, tr, td, i, j, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
    tr[i].style.display = "none"; // Initially hide the row
    td = tr[i].getElementsByTagName("td");
    for (j = 0; j < td.length; j++) {
      if (td[j]) {
        txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
          break; // No need to check other columns if one matches
        }
      }
    }
  }
}
   
</script>