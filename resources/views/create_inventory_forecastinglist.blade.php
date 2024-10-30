@include('header')
<?php $con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
$fg_checkeverytime="SELECT * FROM `ipl_checklist_question` where every_time=0";
$run_checkeverytime=mysqli_query($con,$fg_checkeverytime);
$count_checkeverytime=mysqli_num_rows($run_checkeverytime);

 ?>
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">

		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			 
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">



					<div class="col">
						<h6 class="mb-0 text-uppercase">Inventory Forecasting (Step 02)</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								
							

								<form method="get" action="<?php echo PROJECTURL ?>/savefinalforecasting" enctype= "multipart/form-data" class="row g-3">
									@csrf
<input type="hidden" id="formula_id" value="{{$formula_id}}" name="formula_id">
<input type="hidden" id="formula_det_id" value="{{$formula_det_id}}" name="formula_det_id">
<input type="hidden" id="group_id" value="{{$group_id}}" name="group_id">
<input type="hidden" id="company_id" value="{{$company_id}}" name="company_id">
<input type="hidden" id="shelf_life" value="{{$shelf_life}}" name="shelf_life">
<input type="hidden" id="forecasting_month" value="{{$forecasting_month}}" name="forecasting_month">
<input type="hidden" id="stockshowing" value="{{$stockshowing}}" name="stockshowing">
<input type="hidden" id="versionprevious" value="{{$versionprevious}}" name="versionprevious">
<input type="hidden" id="sort_by" value="{{$sort_by}}" name="sort_by">
<input type="hidden" id="questionids" value="{{$questionids}}" name="questionids">
<input type="hidden" id="branch_id" value="{{$branch_id}}" name="branch_id">


<input type="hidden" id="modalshowstatus" value="{{$modalshowstatus}}" name="modalshowstatus">
<input type="hidden" id="notsetpackingproduct" value="{{$notsetpackingproduct}}" name="notsetpackingproduct">

<input type="hidden" id="insertedId" value="{{$insertedId}}" name="insertedId">

									

								<?php $rowcount=0; ?>

                  <div class="col-sm-9">
                             
                            </div>
                            <div class="col-sm-3">
                              <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Branch here.." title="Search here" style="border: 1px solid black;" class="form-control">

                            </div>
              <div class="col-md-12">
                <?php if($sort_by == "Branch Wise"){ ?>
                 <table border="1" id="myTable" style="width:100%;">
                  <thead>
                    <tr style="border: 1px solid black;background-color: lightgray;color:black">
                      <th style="text-align: center;border:1px solid black">Branch Name</th>
                      <th style="text-align: center;border:1px solid black">Action 

                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($forecastingdata as $frdata)

                     <tr style="border:1px solid black">
                       <td style="color:black;border:1px solid black">&nbsp;{{$frdata->branch_code}} - {{$frdata->branch_name}}</td>
                       <?php if($group_id ==""){$group_id=0;}if($shelf_life == ""){$shelf_life=0;} ?>
                       <td style="border:1px solid black">
                         <center id='create{{$frdata->branch_code}}'><a href="/createforecasting2/{{$formula_id}}/{{$formula_det_id}}/{{$group_id}}/{{$company_id}}/{{$shelf_life}}/{{$forecasting_month}}/{{$stockshowing}}/{{$versionprevious}}/{{$sort_by}}/{{$questionids}}/{{$frdata->branch_code}}/0/{{$insertedId}}" target="_blank" style="text-decoration: underline;" class="text-success">Create Forecasting</a></center>
                       </td>
                     </tr>
<?php $rowcount=$rowcount + 1; ?>

                    @endforeach
                    
                  </tbody>

                  
                 </table>

               <?php } else{ ?>
              <table border="1" id="myTable" style="width:100%;">
                  <thead>
                    <tr style="border: 1px solid black;background-color: lightgray;color:black">
                      <th style="text-align: center;border:1px solid black">Product Name</th>
                      <th style="text-align: center;border:1px solid black">Action 

                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($forecastingdata as $frdata)

                     <tr style="border:1px solid black">
                       <td style="color:black;border:1px solid black">&nbsp;{{$frdata->product_code}} - {{$frdata->product_name}}</td>
                       <?php if($group_id ==""){$group_id=0;}if($shelf_life == ""){$shelf_life=0;} ?>
                       <td style="border:1px solid black">
                         <center id='createforecasting{{$frdata->product_code}}'><a href="/createforecasting2/{{$formula_id}}/{{$formula_det_id}}/{{$group_id}}/{{$company_id}}/{{$shelf_life}}/{{$forecasting_month}}/{{$stockshowing}}/{{$versionprevious}}/{{$sort_by}}/{{$questionids}}/{{$branch_id}}/{{$frdata->product_code}}/{{$insertedId}}" target="_blank" style="text-decoration: underline;" class="text-success">Create Forecasting</a></center>
                       </td>
                     </tr>

<?php $rowcount=$rowcount + 1; ?>
                    @endforeach
                    
                  </tbody>

                  
                 </table>

               <?php } ?>

              </div>    
                  
<input type="hidden" value="{{$rowcount}}" id='rowcount' name='rowcount'>
									

									

                           
							
									

								

									
									<div class="col-12">

<button type="submit" id="submitbtn" class="btn text-light px-5" style="background-color: #398E3D">Save</button>



										
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




<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Group Mapping Alert !
          </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <center>
          <img src="{{ URL::asset('assets/images/warning.png') }}" />
          <br><br>
          <p style="color:black;font-size:16px">Some products are not mapped with in this company's group. Before proceeding, please ensure all products are mapped.</p>
          <br>
          <p style="color:black;font-size:22px">اس کمپنی کے گروپ میں کچھ پروڈکٹ شامل نہیں ہیں۔ آگے بڑھنے سے پہلے، براہ کرم یقینی بنائیں کہ تمام پروڈکٹ گروپ میں شامل ہیں۔</p>
      </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Carton Size Missing Alert !
          </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <center>
          <img src="{{ URL::asset('assets/images/warning.png') }}" />
          <br><br>
          <p style="color:black;font-size:16px">Some products belong to the company that have a Carton size of 0. Please update the Carton size first. You can check these products in <a href="/notsetpackingsizeproduct" target="_blank" style="font-weight: bold">'Not Set Carton Size Product.'</a></p>
          <br>
        <p style="color:black;font-size:22px">کچھ پروڈکٹ ان کمپنیوں سے تعلق رکھتے ہیں جن کا کارٹن سائز مقرر نہیں کیا گیا ہے۔ براہ کرم پہلے کارٹن سائز کو اپ ڈیٹ کریں۔</p>

      </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    
      </div>
    </div>
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
var modalshowstatus=$('#modalshowstatus').val();
var notsetpackingproduct=$('#notsetpackingproduct').val();

if(modalshowstatus == "Yes")
{
  $('#exampleModal').modal('show');
}

if(notsetpackingproduct != "0")
{
  $('#exampleModal2').modal('show');
}
		
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });


$('#submitbtn').hide();
function reloadcreatebutton(count)
{
  
  var sort_by = $('#sort_by').val();
    var insertedId = $('#insertedId').val();
  var rowcount=$('#rowcount').val();
  var versionprevious=$('#versionprevious').val();
  var company_id=$('#company_id').val();
  var forecasting_month=$('#forecasting_month').val();
  var branch_id=$('#branch_id').val();
  var group_id=$('#group_id').val();

    if (sort_by == "Branch Wise") {
        var branchCodes = [];

        <?php foreach ($forecastingdata as $frdata) { 
            // Check if branch_code exists
            if (property_exists($frdata, 'branch_code')) { ?>
                branchCodes.push("<?php echo $frdata->branch_code; ?>");
        <?php } } ?>
        $.ajax({
            type: "POST",
            url: "{{ url('getbranchforecastingcheck') }}",
            data: { 
                insertedId: insertedId,
                branchCodes: branchCodes,
                versionprevious:versionprevious,
                company_id:company_id,
                forecasting_month:forecasting_month,
                group_id:group_id
            },
            dataType: 'json',
            success: function(response) {

                var count = response.length;
              
                response.forEach(function(branchCode) {
                  $('#create' + branchCode).text('Already Created');
                });
                if(count == rowcount)
                {
                  $('#submitbtn').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    } 
    else if(sort_by == "Product Wise") {
        //product code
        var productCodes = [];

       <?php foreach ($forecastingdata as $frdata) { 
            // Check if product_code exists
            if (property_exists($frdata, 'product_code')) { ?>
                productCodes.push("<?php echo $frdata->product_code; ?>");
        <?php } } ?>

        $.ajax({
            type: "POST",
            url: "{{ url('getbranchforecastingcheck2') }}",
            data: { 
                insertedId: insertedId,
                productCodes: productCodes,
                versionprevious:versionprevious,
                company_id:company_id,
                forecasting_month:forecasting_month,
                branch_id:branch_id,
                count:count
            },
            dataType: 'json',
            success: function(response) {
           
                var count = response.length;
                
                response.forEach(function(productCode) {

                    $('#createforecasting' + productCode).text('Already Created');
                });
                if(count == rowcount)
                {
                  $('#submitbtn').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
}


reloadcreatebutton(1);
// Listen for localStorage changes
    window.addEventListener('storage', function(event) {
        if (event.key === 'reloadCreate' && event.newValue === 'true') {
            reloadcreatebutton(2);
            localStorage.removeItem('reloadCreate'); // Clean up the localStorage key
        }
    });

    // Optional: Check if the child page has already set the flag
    if (localStorage.getItem('reloadCreate') === 'true') {
        reloadcreatebutton();
        localStorage.removeItem('reloadCreate');
    }

});


	</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
<script type="text/javascript">
	

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