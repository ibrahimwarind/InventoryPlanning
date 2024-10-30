@include('header')
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Group Mapping
						<a href="/groupmappinglist" class="btn btn-primary" style="float: right !important;margin-top: -10px">View List</a>
					</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-object-group me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 " style="color:#398E3D">Create Group Mapping</h5>
								</div>
								<hr style="background-color: #398E3D !important">

								<form method="get" action="<?php echo PROJECTURL ?>/create_groupmapping2" enctype= "multipart/form-data" class="row g-3">
									@csrf
 
									<div class="col-md-2">
										<label for="inputLastName" class="form-label">Formula Type</label>
										<br>
										<select class="form-control form-select" style="" name="formula_id" id="formula_id" required="">
											<option value="" hidden="">Select Type</option>
									

								@foreach($formuladata as $brdata)
								<option value="{{$brdata->formula_id}}">{{$brdata->formula_name}}</option>

								@endforeach
							       </select>
									</div>

									<div class="col-md-3">
										<label for="inputLastName" class="form-label">Formula Name</label>
										<br>
										<select class="form-control form-select" style="" name="formula_det_id" id="formula_det_id" required="">
											<option value="" hidden="">Select Formula</option>
									
							       </select>
									</div>

									<div class="col-md-2">
										<label for="inputFirstName" class="form-label">Group Name</label>
										<input type="text" class="form-control" id="group_name"  name="group_name"  required="">
									</div>

									<div class="col-md-3">
										<label for="inputLastName" class="form-label">Company</label>
										<br>
										<select class="form-control form-select" style="" name="company_id" id="company_id" required="">
											<option value="" hidden="">Select Company</option>
									

								@foreach($companydata as $brdata)
								<option value="{{$brdata->company_code}}">{{$brdata->company_code}} - {{$brdata->company_title}}</option>

								@endforeach
							       </select>
									</div>

									<div class="col-md-2">
										<label for="inputLastName" class="form-label">Permanent/Temporary</label>
										<br>
										<select class="form-control form-select" style="" name="permanent_temporary" id="permanent_temporary" required="">
											<option value="1">Permanent</option>
											<option value="2">Temporary</option>
									
							       </select>
									</div>

                            <div class="col-sm-9">
                            	<label>
        <input type="checkbox" id="checkAll" style="color:black;font-weight: bold;font-size: 16px;margin-top: 20px"> Check All
    </label>
                            </div>
                            <div class="col-sm-3">
                            	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Product here.." title="Search here" style="border: 1px solid black;margin-top: 10px" class="form-control">

                            </div>
							<div class="col-md-12">
								
							   <table border="1" id="myTable" style="width:100%;margin-top: -15px">
							   	<thead>
							   		<tr style="border: 1px solid black;background-color: lightgray;color:black">
							   			<th style="text-align: center;border:1px solid black">Product Name</th>
							   			<th style="text-align: center;border:1px solid black">Action 

							   			</th>
							   		</tr>
							   	</thead>
							   	<tbody id="productdata">
							   		
							   	</tbody>

							   	
							   </table>

							</div>		
									
									

								

									
									<div class="col-12">
										<?php if($data == "Group Successfully Created")
										{
                    echo "<p class='text-success'>$data</p>";
										}
										else if($data == "Group Name Already Exist"){
                    echo "<p class='text-danger'>$data</p>";
										} ?>
										
										<button type="submit" class="btn text-light px-5" style="background-color: #398E3D">Create Group</button>
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
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

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
              $('#formula_det_id').append("<option value='"+res[i].detail_id+"'>"+res[i].formula_description+"</option>");
              }
			// $('#formula_det_id').val(res);
              
           }
        });
    
    });
 

    //get company product
    $('#company_id').on('change',function(){
    
    var company_id=$('#company_id').val();
    var permanent_temporary=$('#permanent_temporary').val();

    $('#productdata').html('');

    $.ajax({
            type:"POST",
            url: "{{ url('getproductdetail') }}",
            data: { company_id: company_id,permanent_temporary:permanent_temporary},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
                
                if(res[i].is_mapped == 0)
                {
   options="<input type='checkbox' class='heckbox' data-id='"+res[i].product_code+"'  id='entry"+res[i].product_code+"' name='entry[]' value='"+res[i].product_code+"' style='display:inline-block !important'><label for='entry"+res[i].product_code+"' style='display:inline-block !important;height:10px !important;'></label>";
                }
                else{
   options="<span class='text-success'><a href='viewgroupmapping/"+res[i].group_id+"/"+permanent_temporary+"' target='_blank'>"+res[i].group_name+"</a></span>";
                }

              $('#productdata').append("<tr style='border:1px solid black'><td style='border:1px solid black;color:black;padding:2px'>"+res[i].product_code+" - "+res[i].product_name+"</td><td style='border:1px solid black;color:black'><center>"+options+"</center></td></tr>");
              }
			// $('#formula_det_id').val(res);
              
           }
        });
    
    });


    //get company product
    $('#permanent_temporary').on('change',function(){
    
    var company_id=$('#company_id').val();
    var permanent_temporary=$('#permanent_temporary').val();

    $('#productdata').html('');

    $.ajax({
            type:"POST",
            url: "{{ url('getproductdetail') }}",
            data: { company_id: company_id,permanent_temporary:permanent_temporary},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
                
                  if(res[i].is_mapped == 0)
                {
   options="<input type='checkbox' class='heckbox' data-id='"+res[i].product_code+"'  id='entry"+res[i].product_code+"' name='entry[]' value='"+res[i].product_code+"' style='display:inline-block !important'><label for='entry"+res[i].product_code+"' style='display:inline-block !important;height:10px !important;'></label>";
                }
                else{
   options="<span class='text-success'><a href='viewgroupmapping/"+res[i].group_id+"/"+permanent_temporary+"' target='_blank'>"+res[i].group_name+"</a></span>";
                }

              $('#productdata').append("<tr style='border:1px solid black'><td style='border:1px solid black;color:black;padding:2px'>"+res[i].product_code+" - "+res[i].product_name+"</td><td style='border:1px solid black;color:black'><center>"+options+"</center></td></tr>");
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

	</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
<script type="text/javascript">
	$('#company_id').chosen();

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