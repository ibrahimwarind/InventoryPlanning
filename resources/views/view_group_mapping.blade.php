@include('header')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);

?>
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">View Group Mapping
						<a href="/groupmappinglist" class="btn btn-primary" style="float: right !important;margin-top: -10px">View List</a>
					</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								<div class="row">
								@foreach($groupdata as $grp)

								<?php 
								$fmname="";
							$fg_grpdata="SELECT formula_name FROM `ipl_formula_master` where formula_id='$grp->formula_id'";
							$run_grpdata=mysqli_query($con,$fg_grpdata);
							if(mysqli_num_rows($run_grpdata) !=0)
							{
                             $row_grpdata=mysqli_fetch_array($run_grpdata);
                             $fmname=$row_grpdata['formula_name'];
							}

							$fmdetail="";
							$fg_grpdetail="SELECT formula_description FROM `ipl_formula_detail` where detail_id='$grp->formula_detail_id'";
							$run_grpdetail=mysqli_query($con,$fg_grpdetail);
							if(mysqli_num_rows($run_grpdetail) != 0)
							{
                             $row_grpdetail=mysqli_fetch_array($run_grpdetail);
                             $fmdetail=$row_grpdetail['formula_description'];
							}

							$compname="";
							$fg_compdata="SELECT company_title FROM `ipl_company_master` where company_code='$grp->company_id'";
							$run_compdata=mysqli_query($con,$fg_compdata);
							if(mysqli_num_rows($run_compdata) !=0)
							{
								$row_compdata=mysqli_fetch_array($run_compdata);
								$compname=$row_compdata['company_title'];
							}
							$grptype="";
							if($grp->permanent_temporary == "1")
							{
                             $grptype="Permanent";
							}
							else{
                            $grptype="Temporary";
							}
							?>
 
									<div class="col-md-2">
										<label for="inputLastName" class="form-label">Formula Type</label>
										<br>
										<p style="color:black;font-size: 16px">{{$fmname}}</p>
										
									</div>

									<div class="col-md-5">
										<label for="inputLastName" class="form-label">Formula Name</label>
										<br>
										<p style="color:black;font-size: 16px">{{$fmdetail}}</p>
										
									
							      <!--  </select> -->
									</div>

									<div class="col-md-4">
										<label for="inputFirstName" class="form-label">Group Name</label>
										<p style="color:black;font-size: 16px">{{$grp->group_name}}</p>
									</div>
									<div class="col-md-2">
										<label for="inputFirstName" class="form-label">Group Type</label>
										<p style="color:black;font-size: 16px">{{$grptype}}</p>
									</div>

									<div class="col-md-4">
										<label for="inputLastName" class="form-label">Company</label>
										<br>
										<p style="color:black;font-size: 16px">{{$compname}}</p>

									</div>
									

							@endforeach
							 <form method="get" action="<?php echo PROJECTURL ?>/addproductingroup">
							 	@csrf
                            <div class="col-sm-12">
                            	<div class="row">
                           

                            	<input type="hidden" value="{{$groupid}}" name="groupid">
                            	<input type="hidden" value="{{$grp->permanent_temporary}}" name="per_temp">
                            <div class="col-sm-3">
                            	<select class="form-control" name="prod_code" id="prod_code" required="">
                            		<option value="" hidden="">Select Product</option>
                            		@foreach($productdata as $prod)
                            		 <option value="{{$prod->product_code}}">{{$prod->product_code}} - {{$prod->product_name}}</option>

                            		@endforeach
                            	</select>
        
                            </div>

                             <div class="col-sm-6">
                             	<input type="submit" class="btn" style="background-color: #398E3D;color:white" value="Add Product">
                             </div>
                     
                            <div class="col-sm-3">
                            	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Product here.." title="Search here" style="border: 1px solid black;margin-top: 10px" class="form-control">

                            </div>

                        </div>
                          
                         </div>
                           </form>
						

							<div class="col-md-12">
								
							   <table border="1" id="myTable" style="width:100%;margin-top: 15px">
							   	<thead>
							   		<tr style="border: 1px solid black;background-color: lightgray;color:black">
							   			<th style="text-align: center;border:1px solid black">Product Name</th>
							   			<th style="text-align: center;border:1px solid black">Action 

							   			</th>
							   		</tr>
							   	</thead>
							   	<tbody id="productdata">
							   		<?php 
							   		$fg_grpproduct="SELECT prod.product_code,prod.product_name,map.mapping_id FROM `ipl_group_mapping` map inner join ipl_products prod on map.product_code=prod.product_code where map.group_id='$groupid'";
							   		$run_grpproduct=mysqli_query($con,$fg_grpproduct);
							   		while($row_grpproduct=mysqli_fetch_array($run_grpproduct))
							   		{
							   			$product_code=$row_grpproduct['product_code'];
							   			$product_name=$row_grpproduct['product_name'];
							   			$mapping_id=$row_grpproduct['mapping_id'];
							   			echo "<tr id='rowno$mapping_id'>
                                         <td style='border:1px solid black;color:black;padding:2px'>$product_code - $product_name</td>
                                         <td style='border:1px solid black'><center><a class='text-danger statuschange' id='removeproduct' data-id='$mapping_id' data-name='$product_name' >Remove</a></center></td>
							   			</tr>";
							   		}
 
							   		?>
							   		
							   	</tbody>

							   	
							   </table>

							</div>		
									
									

								

									
									
							

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

    $(document).on('click', '.statuschange', function() {
    var mappingid = $(this).data('id');
    var productname = $(this).data('name');
    var userConfirmation = confirm("Are you sure you want to Remove - "+productname+"?");

    // Proceed only if the user clicks 'OK'
    if (userConfirmation) {
    $.ajax({
            type:"POST",
            url: "{{ url('removegroupproduct') }}",
            data: { mappingid: mappingid},
            dataType: 'json',
            success: function(res){
               $('#rowno'+mappingid).hide();
           }
    });
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
                
                if(res[i].is_mapped == 0)
                {
   options="<input type='checkbox' class='heckbox' data-id='"+res[i].product_code+"'  id='entry"+res[i].product_code+"' name='entry[]' value='"+res[i].product_code+"' style='display:inline-block !important'><label for='entry"+res[i].product_code+"' style='display:inline-block !important;height:10px !important;'></label>";
                }
                else{
   options="<span class='text-success'>Already in Other Group</span>";
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
	$('#prod_code').chosen();

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