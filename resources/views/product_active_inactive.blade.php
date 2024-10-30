@include('header')
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
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Product Active/InActive Management</h6>
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
<div class="form-popup" id="myForm">
  <div  class="form-container" style="padding: 20px">
     
     <i class="fa fa-check text-success" style="font-weight: bold;font-size: 29px;" aria-hidden="true" ></i> &nbsp;&nbsp;<span style="font-weight: bold;font-size: 20px;">Successfully Updated</span>
    
  </div>
</div>

	<script type="text/javascript">

     function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
	setTimeout(function() { 
        document.getElementById("myForm").style.display = "none";
    }, 1500);
 
}
	$(document).ready(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
        }
    });

    $(document).on('change', '.statuschange', function() {
    var selectedValue = $(this).val();
    var productCode = $(this).data('id');

    $.ajax({
            type:"POST",
            url: "{{ url('productstatuschange') }}",
            data: { id: productCode,status:selectedValue },
            dataType: 'json',
            success: function(res){
             openForm();
             closeForm();
            	
           }
    });


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

            if (res[i].status == 1) {
                options = "<option value='1' selected>Active</option><option value='0'>In-Active</option>";
            } else {
                options = "<option value='0' selected>In-Active</option><option value='1'>Active</option>";
            }

              $('#productdata').append("<tr style='border:1px solid black'><td style='border:1px solid black;color:black;'>"+res[i].product_code+" - "+res[i].product_name+"</td><td style='border:1px solid black;color:black'><center><select class='form-control statuschange' name='pro_status' id='pro_status' data-id='"+res[i].product_code+"'>"+options+"</center><input type='hidden' value='"+res[i].product_code+"' id='productcode"+i+"' name='productcode"+i+"'></td></tr>");
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