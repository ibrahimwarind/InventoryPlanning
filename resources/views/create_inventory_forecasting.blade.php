@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
$fg_checkeverytime="SELECT * FROM `ipl_checklist_question` where every_time=0";
$run_checkeverytime=mysqli_query($con,$fg_checkeverytime);
$count_checkeverytime=mysqli_num_rows($run_checkeverytime);

 ?>
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
   <input type="hidden" id="count_checkeverytime" value="{{$count_checkeverytime}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			 
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">

<input type="hidden" name="isdirect" id="isdirect" value="{{$direct}}">
<input type="hidden" name="branchiddata" id="branchiddata" value="{{$branchiddata}}"> 
					<div class="col">
						<h6 class="mb-0 text-uppercase">Inventory Forecasting</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-object-group me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 " style="color:#398E3D">Create Inventory Forecasting</h5>
								</div>
								<hr style="background-color: #398E3D !important">

								<form method="get" action="<?php echo PROJECTURL ?>/createforecastinglist" enctype= "multipart/form-data" class="row g-3">
									@csrf
<input type="hidden" value="previous" name="stockshowing" id="stockshowing">
<input type="hidden" name="versionprevious" id="versionprevious">
<input type="hidden" name="insertedId" id="insertedId" value='{{$insertedId}}'>

									<div class="col-md-2">
										<label for="inputFirstName" class="form-label">Forecasting Month</label>
										<input type="month" class="form-control" id="forecasting_month"  name="forecasting_month" >
									</div>

                    <div class="col-md-3">
                    <label for="inputLastName" class="form-label">Company Name</label>
                    <br>
                    <select class="form-control form-select" style="" name="company_id" id="company_id" required="">
                      <?php if($direct == 0){ ?>
                      <option value="" hidden="">Select Company</option>
                    <?php } else {
                    $fg_comp="SELECT company_title FROM `ipl_company_master` where company_code='$companyid'";
                    $run_comp=mysqli_query($con,$fg_comp);
                    $row_comp=mysqli_fetch_array($run_comp);
                    $company_title=$row_comp['company_title'];
                    echo "<option value='$companyid'>$company_title</option>";
                      ?>

                    <?php } ?>
                      @foreach($companydata as $cmp)
                      <option value="{{$cmp->company_code}}">{{$cmp->company_code}} - {{$cmp->company_title}}</option>

                      @endforeach
                  
                     </select>
                  </div>
 
									<div class="col-md-2">
										<label for="inputLastName" class="form-label">Formula Type</label>
										<br>
								<select class="form-control form-select" style="" name="formula_id" id="formula_id" required="">
                  <?php if($direct == 0){ ?>
									<option value="" hidden="">Select Type</option>
                <?php } else{ 
                 $fg_formula="SELECT formula_name FROM `ipl_formula_master` where formula_id='$formulaid'";
                 $run_formula=mysqli_query($con,$fg_formula);
                 $row_formula=mysqli_fetch_array($run_formula);
                 $formula_name=$row_formula['formula_name'];
                 echo "<option value='$formulaid'>$formula_name</option>";
                  ?>
       
                <?php } ?>
									

								@foreach($formulamasterdata as $brdata)
								<option value="{{$brdata->formula_id}}">{{$brdata->formula_name}}</option>

								@endforeach
							       </select>
									</div>

									<div class="col-md-4">
										<label for="inputLastName" class="form-label">Default Formula Name</label>
										<br>
										<select class="form-control form-select" style="" name="formula_det_id" id="formula_det_id" required="">
                      <?php if($direct == 0){ ?>
											<option value="" hidden="">Select Formula</option>
                    <?php } else{ 
                 $fg_detail="SELECT detail_id,formula_description FROM `ipl_formula_detail` where detail_id='$formuladetailid'";
                 $run_detail=mysqli_query($con,$fg_detail);
                 $row_detail=mysqli_fetch_array($run_detail);
                 $formula_description=$row_detail['formula_description'];
                 echo "<option value='$formuladetailid'>$formula_description</option>";
                      ?>
                          
                    <?php } ?>
									
							       </select>
									</div>

                  <div class="col-md-2" >
                    <label for="inputLastName" class="form-label">Sort By</label>
                    <br>
                    <select class="form-control form-select" name="sort_by" id="sort_by" >
                      <option>Product Wise</option>
                      <option>Branch Wise</option>
                  
                     </select>
                  </div>

									

								




								

                  

									
                  <div class="col-sm-6"></div>

									
                  <input type="hidden" id="shelf_life" name="shelf_life" >
									<!-- <div class="col-md-2">
										<label for="inputFirstName" class="form-label">Shelf Life</label>
										<input type="number" class="form-control" id="shelf_life"  name="shelf_life" value="<?php if($direct != 0){ echo $shelflife; }?>">
									</div> -->


                  <div class="col-sm-9">
                              <label>
        <input type="checkbox" id="checkAll" style="color:black;font-weight: bold;font-size: 16px;margin-top: 20px"> Check All
    </label>
                            </div>
                            <div class="col-sm-3">
                              <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Branch here.." title="Search here" style="border: 1px solid black;margin-top: 10px" class="form-control">

                            </div>
              <div class="col-md-12">
                
                 <table border="1" id="myTable" style="width:100%;margin-top: -15px">
                  <thead>
                    <tr style="border: 1px solid black;background-color: lightgray;color:black">
                      <th style="text-align: center;border:1px solid black">Branch Name</th>
                      <th style="text-align: center;border:1px solid black">Action 

                      </th>
                    </tr>
                  </thead>
                  <tbody id="productdata">
                    
                  </tbody>

                  
                 </table>

              </div>    
                  

									

									

                           
							
									

								

									
									<div class="col-12">
<a style="background-color: #398E3D;color:white"  class="btn btn-primary" id="checkversion">
 Continue
</a> 

<a style="background-color: #398E3D;color:white"  class="btn btn-primary" id="modalbutton">
 Continue
</a>
<button type="submit" id="submitbtn" class="btn text-light px-5" style="background-color: #398E3D">Continue</button>



<p class="text-danger" id="errordata" style="margin-top: 15px">Please Fill All Required Field Before Conituee</p>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation Required !
        	<br><span style="font-size:12px">Please Check All Question before Proceeding...</span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ">
        <div class="modaldata">
         
       </div>

       <div class="col-md-12" id="groupshowhide">
                    <label for="inputLastName" class="form-label">Do you want to select Group Name ?</label>
                    <br>
                    <select class="form-control form-select" style="" name="group_id" id="group_id" >
                      <option value="" hidden="">Select Group</option>
                  
                     </select>
                  </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       <button type="submit" class="btn text-light px-5" style="background-color: #398E3D">Continue</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Multiple Version Found !
          </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <center>
          <img src="{{ URL::asset('assets/images/warning.png') }}" />
          <br><br>
          <p style="color:black;font-size:16px">The branches you have selected contain multiple versions. Please unselect the branches with multiple versions first</p>
          <br>
          <p style="color:black;font-size:22px">یہ برانچیں جو آپ نے منتخب کی ہیں ان میں ایک سے زیادہ versions موجود ہیں۔ براہ کرم پہلے ان برانچوں کو غیر منتخب کریں جن میں ایک سے زیادہ versions ہیں۔</p>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    
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
$('#modalbutton').hide();

var isdirect=$('#isdirect').val();
if(isdirect == 1)
{
  getcompanybranch();
}
        
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


     //get company product
    $('#company_id').on('change',function(){
    
    var company_id=$('#company_id').val();

    $('#productdata').html('');

    $.ajax({
            type:"POST",
            url: "{{ url('getcompanybranch') }}",
            data: { company_id: company_id},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
                
          
   options="<input type='checkbox' class='heckbox' data-id='"+res[i].branch_code+"'  id='entry"+res[i].branch_code+"' name='entry[]' value='"+res[i].branch_code+"' style='display:inline-block !important'><label for='entry"+res[i].branch_code+"' style='display:inline-block !important;height:10px !important;'></label>";
                

              $('#productdata').append("<tr style='border:1px solid black'><td style='border:1px solid black;color:black;padding:2px'>"+res[i].branch_code+" - "+res[i].branch_name+"</td><td style='border:1px solid black;color:black'><center>"+options+"</center></td></tr>");
              }

      // $('#formula_det_id').val(res);
              
           }

        });
   setTimeout(function() {
        $('#checkAll').trigger('click');
    }, 1500);
    
    });

    function getcompanybranch()
    {
      var company_id=$('#company_id').val();
      var branchiddata=$('#branchiddata').val();

    $('#productdata').html('');
    var branchArray = branchiddata.split(',');

    $.ajax({
            type:"POST",
            url: "{{ url('getcompanybranch') }}",
            data: { company_id: company_id},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
                
          
            var options = "<input type='checkbox' class='heckbox' data-id='" + res[i].branch_code + "' id='entry" + res[i].branch_code + "' name='entry[]' value='" + res[i].branch_code + "' style='display:inline-block !important'>";
                
                // Add a label
                options += "<label for='entry" + res[i].branch_code + "' style='display:inline-block !important;height:10px !important;'></label>";
                
                // Check if branch_code is in branchArray and set checkbox as checked
                if (branchArray.includes(res[i].branch_code.toString())) {
                    options = "<input type='checkbox' class='heckbox' data-id='" + res[i].branch_code + "' id='entry" + res[i].branch_code + "' name='entry[]' value='" + res[i].branch_code + "' style='display:inline-block !important' checked>";
                }

                // Append the row with the branch data and checkbox
                $('#productdata').append("<tr style='border:1px solid black'><td style='border:1px solid black;color:black;padding:2px'>" + res[i].branch_code + " - " + res[i].branch_name + "</td><td style='border:1px solid black;color:black'><center>" + options + "</center></td></tr>");

              }

      // $('#formula_det_id').val(res);
              
           }

        });

   
    }


   //  $('#company_id').on('change',function(){
   //     checkalreadyversion(); 
   // });
   //  $('#branch_id').on('change',function(){
   //     checkalreadyversion(); 
   // });
   //  $('#forecasting_month').on('change',function(){
   //     checkalreadyversion(); 
   // });

 

    function checkalreadyversion()
    {

    var selectedValues = [];
    $('.heckbox:checked').each(function() {
       selectedValues.push($(this).val()); // Push the value into the array
    });
    var branch_id=selectedValues.join(",");
      
    var company_id=$('#company_id').val();
    var forecasting_month=$('#forecasting_month').val();
    var count_checkeverytime=$('#count_checkeverytime').val();
    $('#groupshowhide').hide();
    $.ajax({
            type:"POST",
            url: "{{ url('checkfirstversionornot') }}",
            data: { company_id: company_id,branch_id:branch_id,forecasting_month:forecasting_month},
            dataType: 'json',
            success: function(res){
          
              if(res.status == "False")
              {
                $('#exampleModal2').modal('show');
                  
              }
              else{
              
$('#exampleModal').modal('show');
$('#versionprevious').val(res.oldversionno);
if(res.oldversionno !=0){$('#groupshowhide').show();}
                $('.modaldata').html("");
            $.ajax({
            type:"POST",
            url: "{{ url('geteverytimequestion') }}",
            data: { version_no: res.oldversionno},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
              $('.modaldata').append("<div class='form-check'><input class='form-check-input' type='checkbox' name='selection[]' id='flexCheckDefault"+res[i].question_id+"' value='"+res[i].question_id+"' required=''><label class='form-check-label' for='flexCheckDefault"+res[i].question_id+"'><p style='color:black;font-size:16px'>"+res[i].question_title+"</p></label></div>");
              }
     
             }
           });
                 
              }

            
             
              
           }
        });
    }



   $('#checkversion').click(function(){
       var formula_id=$('#formula_id').val();
       var formula_det_id=$('#formula_det_id').val();
       var company_id=$('#company_id').val();
       $('#checkversion').hide();

       if(formula_id == "" || formula_det_id == "" || company_id =="" ) 
       {
          $('#errordata').show();
          $('#checkversion').show();

       }
       else{
       	$('#errordata').hide();
       	checkalreadyversion();
        $('#checkversion').show();
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
    monthInput.min = currentMonth;
    // monthInput.min = previousMonth;

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
	// $('#group_id').chosen();
	$('#company_id').chosen();
	$('#formula_id').chosen();
	$('#formula_det_id').chosen();
  $('#sort_by').chosen();

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