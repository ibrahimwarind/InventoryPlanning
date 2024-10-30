@include('header')
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Packing Size Management</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								
								<form method="get"  enctype= "multipart/form-data" class="row g-3">
									@csrf
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
									<div class="col-sm-9">
                            	
                            </div>

                            <div class="col-sm-9">
                            	
                            </div>
                            <div class="col-sm-3">
                            	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Product here.." title="Search here" style="border: 1px solid black;margin-top: 10px" class="form-control">

                            </div>
							<div class="col-md-12">
								
							   <table border="1" id="myTable" style="width:100%;margin-top: -15px">
							   	<thead>
							   		<tr style="border: 1px solid black;background-color: lightgray;color:black">
							   			<th style="text-align: center;border:1px solid black">Product Name</th>
							   			<th style="text-align: center;border:1px solid black">Packing Size 

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
										
										<button type="submit" name="btn_submit" class="btn text-light px-5" style="background-color: #398E3D">Save Data</button>
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

	


    //get company product
    $('#company_id').on('change',function(){
    
    var company_id=$('#company_id').val();
    $('#productdata').html('');

    $.ajax({
            type:"POST",
            url: "{{ url('getproductdetail') }}",
            data: { company_id: company_id},
            dataType: 'json',
            success: function(res){
              for(var i=0;i <= res.length;i++)
              { 
              $('#productdata').append("<tr style='border:1px solid black'><td style='border:1px solid black;color:black;padding:2px'>"+res[i].product_code+" - "+res[i].product_name+"</td><td style='border:1px solid black;color:black'><center><input type='text' class='form-control' name='packingqty"+i+"' style='height:30px' value='"+res[i].packing_size+"'></center><input type='hidden' value='"+res[i].product_code+"' id='productcode"+i+"' name='productcode"+i+"'></td></tr>");
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

<?php 

if(isset($_GET['btn_submit']))
{
 
   $company_id=$_GET['company_id'];
   $con=mysqli_connect("localhost","root","","db_ipl");

   $productcount=0;
   $productdata="SELECT count(product_id) as totalproduct FROM ipl_products where company_code='$company_id'";
   $run_productdata=mysqli_query($con,$productdata);
   while($row_productdata=mysqli_fetch_array($run_productdata))
   {
   	$productcount=$row_productdata['totalproduct'];
   }

   for($i=0;$i <= $productcount -1;$i++)
        {
            $productcode=$_GET["productcode{$i}"];
            $packingqty=$_GET["packingqty{$i}"]; 

            
            $update_packing="update `ipl_products` set packing_size='$packingqty' where product_code='$productcode'";
            $run_updatepacking=mysqli_query($con,$update_packing);

        }     

  
echo "<script>alert('Packing Size Successfully Updated')</script>";
echo "<script>window.open('/productpackingsize','_self')</script>";

}

?>