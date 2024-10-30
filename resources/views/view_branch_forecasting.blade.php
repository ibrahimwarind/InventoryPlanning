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
						<h6 class="mb-0 text-uppercase">{{$key}} - Inventory Forecasting
             <span style="float: right !important"><a href="/exportforcasting2/{{$fid}}/{{$key}}/{{$branchid}}" class="text-success"><i class="fas fa-file-excel" style="font-size:28px"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								
								
                @foreach($forecastingdetail as $detail)
                <div class="row">
                  <div class="col-md-4">
                    <p style="color:black">Formula : {{$detail->formula_name}}</p>
                  </div>
                  <!-- <div class="col-md-4">
                    <p style="color:black">Branch : {{$detail->branch_name}} </p>
                  </div> -->
                  <div class="col-md-4">
                    <p style="color:black">Company : {{$detail->company_title}}</p>
                  </div>
               <!--    <div class="col-md-6">
                    <p style="color:black">Shelf Life : {{$detail->shelf_life}}</p>
                  </div> -->
								
									<div class="col-md-4">
										<p style="color:black">Forecasting Key : <b>{{$key}}</b></p>
									</div>
                  <div class="col-md-4">
                    <p style="color:black">Version No : <b>{{$detail->version_no}}</b></p>
                  </div>
                  <div class="col-md-4">
                    <p style="color:black;display: inline-block;">Show Only Difference : <b></b>
                     <select  id="show_only_diff" style="display: inline-block;width:100px;height: 30px">
                       <option value="0">No</option>
                       <option value="1">Yes</option>
                     </select>
                    </p>
                  </div>

                  @endforeach
                  <?php 
          $fg_detail="SELECT sale_month1,sale_month2,sale_month3,current_stockmonth FROM `ipl_forecasting_detail` where forecasting_id=$fid";
          $run_detail=mysqli_query($con,$fg_detail);
          $row_detail=mysqli_fetch_array($run_detail);
          $sale_month1=$row_detail['sale_month1'];
          $sale_month2=$row_detail['sale_month2'];
          $sale_month3=$row_detail['sale_month3'];
          $current_stockmonth=$row_detail['current_stockmonth'];
                  ?>

                  <div class="col-sm-12">
                 
          
               @foreach($forecastingmaster as $mst) 
   <br>
                    <table border="1" style="width:100%" class="forecastingTable">
                       
                      <tr style="background-color: lightgray;color:black;">
                          <th colspan="20" style="padding:10px;border: 1px solid black">{{$mst->branch_row_id}} - {{$mst->branch_name}}</th>
                        </tr>
                      <tr style="background-color: #398E3D;color:white;border: 1px solid black;font-size:12px">

                          <td style="border: 1px solid white"><center>Product Name</center></td>
                          <td style="border: 1px solid white"><center>CTN<br>Size</center></td>
                          <td style="border: 1px solid white"><center id="monthname1">{{date("M", strtotime($sale_month1))}} Sales</center></td>
                          <td style="border: 1px solid white"><center id="monthname2">{{date("M", strtotime($sale_month2))}} Sales</center></td>
                          <td style="border: 1px solid white"><center id="monthname3">{{date("M", strtotime($sale_month3))}} Sales</center></td>
                          <td style="border: 1px solid white"><center>Avg Sale</center></td>
                          <td style="border: 1px solid white"><center>Max Sales</center></td>
                          <td style="border: 1px solid white"><center>{{date("M", strtotime($current_stockmonth))}} Stock</center></td>
                          <td style="border: 1px solid white"><center>Br Int</center></td>
                          <td style="border: 1px solid white"><center>Comp Int</center></td>
                          <td style="border: 1px solid white"><center>Net Stock</center></td>
                          <td style="border: 1px solid white"><center>Suggested<br>Plan</center></td>
                          <td style="border: 1px solid white"><center>Demand</center></td>
                          <td style="border: 1px solid white"><center>Diff</center></td>
                          <td style="border: 1px solid white"><center>Final<br>Plan Qty</center></td>
                          <td style="border: 1px solid white"><center>Final<br>Plan Value</center></td>
                          <td style="border: 1px solid white"><center>Final<br>Plan <br>(In Carton)</center></td>

                          
                          
                          <td style="border: 1px solid white"><center>Group Name</center></td>
                          <td style="border: 1px solid white"><center>Multiple By</center></td>
                          <td style="border: 1px solid white"><center>Remarks</center></td>

                        </tr>
                   
                    <?php 
                    $fg_groupdata="SELECT det.*,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' AND branch_row_id='$mst->branch_row_id'";
                    $run_groupdata=mysqli_query($con,$fg_groupdata);
                    while($row_groupdata=mysqli_fetch_array($run_groupdata))
                    {

                      $detail_id=$row_groupdata['detail_id'];
                      $product_code=$row_groupdata['product_code'];
                      $product_name=$row_groupdata['product_name'];
                      $sale_qty1=$row_groupdata['sale_qty1'];
                      $sale_qty2=$row_groupdata['sale_qty2'];
                      $sale_qty3=$row_groupdata['sale_qty3'];
                      $avg_sales=$row_groupdata['avg_sales'];
                      $max_sales=$row_groupdata['max_sales'];
                      $current_stock=$row_groupdata['current_stock'];
                      $current_stockmonth=$row_groupdata['current_stockmonth'];
                      $in_transit_stock=$row_groupdata['in_transit_stock'];
                      $manual_stock=$row_groupdata['manual_stock'];
                      $net_stock=$row_groupdata['net_stock'];
                      $pr_diff=$row_groupdata['pr_diff'];
                      $demand=$row_groupdata['demand'];
                      $pspl_diff=$row_groupdata['pspl_diff'];
                      $multiply_by=$row_groupdata['multiply_by'];
                      $remarks=$row_groupdata['remarks'];
                      $group_detail_id=$row_groupdata['group_detail_id'];
                      $final_planoutput=$row_groupdata['final_planoutput'];
                      $final_output_packingsize=$row_groupdata['final_output_packingsize'];
                      $packing_size=$row_groupdata['packing_size'];
                      $price=$row_groupdata['price'];
                      $final_output_value=$row_groupdata['final_output_value'];


                      $fg_grpdata="SELECT group_name FROM `ipl_group_master` where group_id='$group_detail_id'";
                      $grpname="";
                      $run_grpdata=mysqli_query($con,$fg_grpdata);
                      if(mysqli_num_rows($run_grpdata) !=0)
                      {
                        $row_grpdata=mysqli_fetch_array($run_grpdata);
                        $grpname=$row_grpdata['group_name'];
                      }
                      


                      echo "
              <tr style='border:1px solid black;font-size:12px'>
                <td style='border:1px solid black;color:black'>$product_code - $product_name</td>
                 <td style='border:1px solid black;color:black;text-align:center'>".$packing_size."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty1)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty2)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty3)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($avg_sales)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($max_sales)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($current_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($in_transit_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($manual_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($net_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center;background-color: lightgray'>".number_format($pr_diff)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($demand)."</td>
                <td style='border:1px solid black;color:black;text-align:center' class='diff-column'>".number_format($pspl_diff)."</td>
                <td style='border:1px solid black;color:black;text-align:center;background-color: lightgray' >".number_format($final_planoutput)."</td>
                <td style='border:1px solid black;color:black;text-align:center;background-color: lightgray' >".number_format($final_output_value)."</td>
                <td style='border:1px solid black;color:black;text-align:center;background-color: lightgray' >".number_format($final_output_packingsize)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".$grpname."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($multiply_by)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".$remarks."</td>
                </tr>

                      ";

                    }
                    ?>
                    </table>
                    @endforeach

                 

                

                  </div>
  </div>
									





                           
							
									

								

									
									<div class="col-12">
<br><br>
<a style="background-color: #398E3D;color:white" href="/branchwiseforecastinglist" class="btn btn-primary" >
<- Back to List
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
setTimeout(function() { 
        $('.mobile-toggle-menu').trigger('click');
    }, 200);


				
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
   


 
$('#show_only_diff').on('change', function() {
        var showOnlyDiff = $(this).val(); // Get the value of the dropdown (0 or 1)

        $('.forecastingTable tbody tr').each(function() {
            var diffValue = parseInt($(this).find('.diff-column').text()); // Get the value in the Diff column

            if (showOnlyDiff == '1') { 
                // If 'Yes' is selected, show only rows where Diff is not zero
                if (diffValue === 0) {
                    $(this).hide(); // Hide rows where Diff is zero
                } else {
                    $(this).show(); // Show rows where Diff is not zero
                }
            } else {
                // If 'No' is selected, show all rows
                $(this).show();
            }
        });
    });   
</script>