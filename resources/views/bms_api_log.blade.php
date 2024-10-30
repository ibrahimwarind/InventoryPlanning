@include('header')
<?php 
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
$previousMonthAbbr = date('M', strtotime('first day of last month'));
$monthBeforePreviousAbbr = date('M', strtotime('first day of -2 months'));
$monthBeforePreviousAbbr2 = date('M', strtotime('first day of -3 months'));
?>
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">

   <input type="hidden" id="modalshowstatus" value="{{$modalshowstatus}}">
   <input type="hidden" id="notsetpackingproduct" value="{{$notsetpackingproduct}}">
   
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase"><a href="/createforecasting"><i class="fa fa-arrow-left" aria-hidden="true"></i> </a>&nbsp;Inventory Forecasting
							</h6>
						<hr style="background-color: #398E3D !important"/>
						<div class="card border-top border-0 border-4 ">
							<div class="card-body p-5">
								
								

								<form method="get" action="<?php echo PROJECTURL ?>/saveforecasting" enctype= "multipart/form-data" class="row g-3">
									@csrf

 <input type="hidden" value="{{$formula_id}}" name="formula_id" id="formula_id">
 <input type="hidden" value="{{$formula_det_id}}" name="formula_det_id">
 <input type="hidden" value="{{$group_id}}" name="group_id">
 <input type="hidden" value="{{$company_id}}" name="company_id">
 <input type="hidden" value="{{$branch_id}}" name="branch_id" id="branch_id">
 <input type="hidden" value="{{$shelf_life}}" name="shelf_life"> 
 <input type="hidden" value="{{$forecasting_month}}" name="forecasting_month" id="forecasting_month"> 
 <input type="hidden" value="{{$versionprevious}}" name="versionprevious" id="versionprevious"> 
 
 <?php 
 $formulaname="";
 $fg_formulamst="SELECT formula_name FROM `ipl_formula_master` where formula_id='$formula_id'";
 $run_formulamst=mysqli_query($con,$fg_formulamst);
 if(mysqli_num_rows($run_formulamst) !=0)
 {
 	$row_formulamst=mysqli_fetch_array($run_formulamst);
 	$formulaname=$row_formulamst['formula_name'];
 }

  $formuladetail="";$multiply_by=0;
 $fg_formulamst="SELECT formula_description,multiply_by FROM `ipl_formula_detail` where detail_id='$formula_det_id'";
 $run_formulamst=mysqli_query($con,$fg_formulamst);
 if(mysqli_num_rows($run_formulamst) !=0)
 {
 	$row_formulamst=mysqli_fetch_array($run_formulamst);
 	$formuladetail=$row_formulamst['formula_description'];
 	$multiply_by=$row_formulamst['multiply_by'];
 }

 $groupname="";
 $fg_grp="SELECT group_name FROM `ipl_group_master` where group_id='$group_id'";
 $run_grp=mysqli_query($con,$fg_grp);
 if(mysqli_num_rows($run_grp) !=0)
 {
 	$row_grp=mysqli_fetch_array($run_grp);
 	$groupname=$row_grp['group_name'];
 }

 $companyname="";
 $fg_compdata="SELECT company_title FROM `ipl_company_master` where company_code='$company_id'";
 $run_compdata=mysqli_query($con,$fg_compdata);
 if(mysqli_num_rows($run_compdata) !=0)
 {
 	$row_compdata=mysqli_fetch_array($run_compdata);
 	$companyname=$row_compdata['company_title'];
 }

 $brname="";
 $fg_brmaster="SELECT branch_name FROM `ipl_branch_master` where branch_code='$branch_id'";
 $run_brmaster=mysqli_query($con,$fg_brmaster);
 if(mysqli_num_rows($run_brmaster) !=0)
 {
 	$row_brmaster=mysqli_fetch_array($run_brmaster);
 	$brname=$row_brmaster['branch_name'];
 }

 ?>
 <input type="hidden" value="{{$multiply_by}}" id="multiply_by" name="multiply_by">
 <input type="hidden" value="{{$formulaname}}" id="formulaname" name="formulaname">



									<div class="col-md-3">
										<p style="color:black;font-size: 15px"><b>Formula : </b> {{$formulaname}}</p>
									</div>
									<div class="col-md-6">
										<p style="color:black;font-size: 15px"><b>Formula Detail : </b> {{$formuladetail}}</p>
									</div>
									
									<div class="col-md-3" >
										<p style="color:black;font-size: 15px"><b>Company : </b> {{$companyname}}</p>
									</div>
									<div class="col-md-3" style="margin-top: -15px">
										<p style="color:black;font-size: 15px"><b>Branch : </b> {{$brname}}</p>
									</div>
									<div class="col-md-2" style="margin-top: -15px">
										<p style="color:black;font-size: 15px"><b>Shelf : </b> {{$shelf_life}} Days</p>
									</div>
									<div class="col-sm-6"></div>
									<div class="col-md-3" >
										<p style="color:black;font-size: 15px"><b>Stock Showing : </b> 
                                     <select  name="stockshowing" id="stockshowing" style="display: inline-block;height: 30px;width:200px">
                                     	 <?php if($stockshowing == "previous"){ ?>
<option value="previous"><?php echo(new DateTime('last day of previous month'))->format('Y-m-d'); ?></option>
                                     	<option value="current">Current</option>
                                     <?php } else { ?>
                                     		<option value="current">Current</option>
                                     	<option value="previous"><?php echo(new DateTime('last day of previous month'))->format('Y-m-d'); ?></option>
                                     
                                     <?php } ?>

                                     	
                                     </select>
										</p>
									</div>

									<!-- <div class="col-md-3">
										<label for="inputLastName" class="form-label">Company Name</label>
										<br>
										<select class="form-control form-select" style="" name="new_company_id" id="new_company_id" >
											<option value="" hidden="">Select Company</option>
											@foreach($companydata as $cmp)
											<option value="{{$cmp->company_code}}">{{$cmp->company_code}} - {{$cmp->company_title}}</option>

											@endforeach
									
							       </select>
									</div>

									<div class="col-md-3">
										<label for="inputLastName" class="form-label">Product Name</label>
										<br>
										<select class="form-control form-select" style="" name="new_product_id" id="new_product_id" >
										<option value="" hidden="">Select Product</option>
											
									
							       </select>
									</div>

									<div class="col-md-3">
										<br>
										<a class="btn" style="background-color: #398E3D;color:white" id="btn_add" name="btn_add">+Add</a>
									
							      
									</div> -->


<hr style="background-color: #398E3D !important;">				


									<div class="col-sm-12">
										<br>
										<table border="1" id="myTable" style="width: 100%">
											<thead style="border: 1px solid white;background-color: #398E3D;color:white;font-size: 13px">
												<tr style="border: 1px solid white">
													<td style="border: 1px solid white"><center>Product Name</center></td>
													<td style="border: 1px solid white"><center id="monthname1">{{$monthBeforePreviousAbbr2}} Sales</center></td>
													<td style="border: 1px solid white"><center id="monthname2">{{$monthBeforePreviousAbbr}} Sales</center></td>
													<td style="border: 1px solid white"><center id="monthname3">{{$previousMonthAbbr}} Sales</center></td>
													<td style="border: 1px solid white"><center>Avg Sale</center></td>
													<td style="border: 1px solid white"><center>Max Sales</center></td>
													<td style="border: 1px solid white"><center>{{$previousMonthAbbr}} Stock</center></td>
													<td style="border: 1px solid white"><center>Int Qty</center></td>
													<td style="border: 1px solid white"><center>Bal Intransit</center></td>
													<td style="border: 1px solid white"><center>Net Stock</center></td>
													<td style="border: 1px solid white"><center>PR Diff</center></td>
													
													<td style="border: 1px solid white"><center>Demand</center></td>
													<td style="border: 1px solid white"><center>PSPL Diff</center></td>
													<td style="border: 1px solid white"><center>Formula</center></td>
													<td style="border: 1px solid white"><center>Multiple By</center></td>
												</tr>
											</thead>
											<tbody id="tablebody">
												<?php $indexcount=1;$rowcount=0; ?>
											@foreach($productdata as $prod)
<?php 
$prodcode=$prod->product_code;
$sales_qty1=0;$month_year1="";$sales_qty2=0;$month_year2="";$sales_qty3=0;$month_year3="";
$in_stock=0;$in_stock_monthyear="";$in_transitstock=0;
$fg_getstock="SELECT sales_qty1,month_year1,sales_qty2,month_year2,sales_qty3,month_year3,in_stock,in_stock_monthyear,in_transitstock,current_date_stock FROM `ipl_branchwise_product_stock` where branch_code=$branch_id AND product_code=$prodcode";
$run_getstock=mysqli_query($con,$fg_getstock);

if(mysqli_num_rows($run_getstock) !=0)
{
$row_getstock=mysqli_fetch_array($run_getstock);
$sales_qty1=$row_getstock['sales_qty1'];
$month_year1=$row_getstock['month_year1'];
$sales_qty2=$row_getstock['sales_qty2'];
$month_year2=$row_getstock['month_year2'];
$sales_qty3=$row_getstock['sales_qty3'];
$month_year3=$row_getstock['month_year3'];
$in_stock=$row_getstock['in_stock'];
$in_stock_monthyear=$row_getstock['in_stock_monthyear'];
$in_transitstock=$row_getstock['in_transitstock'];
$current_date_stock=$row_getstock['current_date_stock'];
if($stockshowing=="previous")
{
	$in_stock=$in_stock;
}
else{
	$in_stock=$current_date_stock;
}



}

//get check is in group or not
$multiply_by=0;$groupid=0;
$fg_checkgroup="SELECT det.multiply_by,grp.formula_detail_id FROM `ipl_group_mapping` map inner join ipl_group_master grp on map.group_id=grp.group_id inner join ipl_formula_detail det on grp.formula_detail_id=det.detail_id
where map.product_code=$prodcode";
$run_checkgroup=mysqli_query($con,$fg_checkgroup);
$count_checkgroup=mysqli_num_rows($run_checkgroup);
if(mysqli_num_rows($run_checkgroup) !=0)
{
	$row_checkgroup=mysqli_fetch_array($run_checkgroup);
	$multiply_by=$row_checkgroup['multiply_by'];
	$groupid=$row_checkgroup['formula_detail_id'];
}


$avgsales=($sales_qty1 + $sales_qty2 + $sales_qty3) / 3;
$maxsales = max($sales_qty1, $sales_qty2, $sales_qty3);
$netstock=$in_stock + $in_transitstock;
$prdiff=($avgsales * $multiply_by) - $netstock;
?>	
<input type="hidden" id="formulaoutput<?php echo $indexcount ?>" name="formulaoutput<?php echo $indexcount ?>" value="<?php echo $prdiff ?>"> 

<input type="hidden" id="productcode<?php echo $indexcount ?>" name="productcode<?php echo $indexcount ?>" value="{{$prod->product_code}}">
<input type="hidden" id="maxsales<?php echo $indexcount ?>" name="maxsales<?php echo $indexcount ?>" value="{{$maxsales}}">
<input type="hidden" id="instock<?php echo $indexcount ?>" name="instock<?php echo $indexcount ?>" value="{{$in_stock}}">
<input type="hidden" id="instockmonth<?php echo $indexcount ?>" name="instockmonth<?php echo $indexcount ?>" value="{{$in_stock_monthyear}}">
<input type="hidden" id="in_transitstock<?php echo $indexcount ?>" name="in_transitstock<?php echo $indexcount ?>" value="{{$in_transitstock}}">


<input type="hidden" id="saleqty1_<?php echo $indexcount ?>" name="saleqty1_<?php echo $indexcount ?>" value="{{$sales_qty1}}"> 
<input type="hidden" id="saleqty2_<?php echo $indexcount ?>" name="saleqty2_<?php echo $indexcount ?>" value="{{$sales_qty2}}"> 
<input type="hidden" id="saleqty3_<?php echo $indexcount ?>" name="saleqty3_<?php echo $indexcount ?>" value="{{$sales_qty3}}"> 

<input type="hidden" id="salemonth_1_<?php echo $indexcount ?>" name="salemonth_1_<?php echo $indexcount ?>" value="{{$month_year1}}">
<input type="hidden" id="salemonth_2_<?php echo $indexcount ?>" name="salemonth_2_<?php echo $indexcount ?>" value="{{$month_year2}}"> 
<input type="hidden" id="salemonth_3_<?php echo $indexcount ?>" name="salemonth_3_<?php echo $indexcount ?>" value="{{$month_year3}}"> 
<input type="hidden" id="groupid<?php echo $indexcount ?>" name="groupid<?php echo $indexcount ?>" value="{{$groupid}}"> 
									
											<tr style="border:1px solid black;color:black" id="tablerow{{$indexcount}}" name="{{$indexcount}}">
												<td style="border: 1px solid black;color:black">
													<!-- <i class="fa fa-trash text-danger" onclick="removerow(<?php echo $indexcount ?>)" aria-hidden="true"></i>  -->
													&nbsp;{{$prod->product_code}} - {{$prod->product_name}}</td>
												<td style="border: 1px solid black;color:black;text-align: center">{{number_format($sales_qty3)}}</td>
												<td style="border: 1px solid black;color:black;text-align: center">{{number_format($sales_qty2)}}</td>
												<td style="border: 1px solid black;color:black;text-align: center">{{number_format($sales_qty1)}}</td>
												 
												<td style="border: 1px solid black;color:black;text-align: center">
                            <input type="hidden" value="{{$avgsales}}" id="avgsales{{$indexcount}}" name="avgsales{{$indexcount}}">
												{{number_format($avgsales)}}</td>

												<td style="border: 1px solid black;color:black;text-align: center">{{number_format($maxsales)}}</td>
												<td style="border: 1px solid black;color:black;text-align: center">{{number_format($in_stock)}}</td>
												<td style="border: 1px solid black;color:black;text-align: center">{{number_format($in_transitstock)}}</td>
												<td style="border: 1px solid black;color:black;text-align: center">
													<input type="number" value="" id="manualstock<?php echo $indexcount ?>" name="manualstock<?php echo $indexcount ?>" style="width:80px" onkeyup="newmanualstock(<?php echo $indexcount ?>)">
												</td>
												<td style="border: 1px solid black;color:black;text-align: center">
													<input type="hidden" value="{{$netstock}}" id="netstock{{$indexcount}}" name="netstock{{$indexcount}}"><span id="stockshowtext{{$indexcount}}">{{number_format($netstock)}}</span></td>
												<td style="border: 1px solid black;color:black;text-align: center;background-color: lightgray" id="prdiff{{$indexcount}}">{{number_format($prdiff)}}
												</td>
												<td style="border: 1px solid black;color:black;text-align: center">
													<input type="number" value="" id="demand<?php echo $indexcount ?>" name="demand<?php echo $indexcount ?>" style="width:80px" onkeyup="calcufinal(<?php echo $indexcount ?>)">
												</td>
												<td style="border: 1px solid black;color:black;text-align: center;background-color: lightgray">
													<input type="number" value="{{round($prdiff)}}" readonly="" id="finaloutput<?php echo $indexcount ?>" name="finaloutput<?php echo $indexcount ?>" style="width:80px;background-color: lightgray">
												</td>
												<td style="border: 1px solid black;color:black">
													<?php if($count_checkgroup !=0){ ?>
													<select name="rowformulaid{{$indexcount}}" id="rowformulaid{{$indexcount}}">
														<option value="{{$formula_id}}">{{$formulaname}}</option>
														@foreach($formulamasterdata as $fm)

														<option value="{{$fm->formula_id}}">{{$fm->formula_name}}</option>

														@endforeach
													</select>
													<?php } else { ?>
													<center>
														<span class="text-danger" style='text-align: center'>Not in Group</span>
													</center>

													<?php } ?>
												</td>
												<td style="border: 1px solid black;color:black">
													<center>
													<select id="new_multiply_by{{$indexcount}}" name="new_multiply_by{{$indexcount}}" onchange='newmultiplyby(<?php echo $indexcount ?>)'>
														<option>{{$multiply_by}}</option>
														@foreach($multiplydata as $multi)

														<option>{{$multi->multiply_by}}</option>

														@endforeach
													</select>
												    </center>

												</td>
												
											</tr>

<?php $indexcount=$indexcount + 1; $rowcount=$rowcount + 1;?>
											@endforeach
											</tbody>
										</table>

									</div>


									
									<div class="col-12">
										<input type="hidden" value="{{$rowcount}}" id="indexcount" name="indexcount">
									<?php if($modalshowstatus == "No" && $notsetpackingproduct == 0){ ?>
									 <button type="submit" class="btn text-light px-5" style="background-color: #398E3D">Save Forcasting</button>
									<?php } ?>
									</div>
<div id="multiplyOptions" style="display: none;">
    @foreach($multiplydata as $multi)
        <option>{{ $multi->multiply_by }}</option>
    @endforeach
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Packing Size Missing Alert !
        	</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<center>
          <img src="{{ URL::asset('assets/images/warning.png') }}" />
          <br><br>
          <p style="color:black;font-size:16px">Some products belong to the company that have a packing size of 0. Please update the packing size first. You can check these products in 'Not Set Packing Size Product.'</p>
          <br>
          <p style="color:black;font-size:22px">کچھ پروڈکٹ ان کمپنیوں سے تعلق رکھتے ہیں جن کا پیکنگ سائز مقرر نہیں کیا گیا ہے۔ براہ کرم پہلے پیکنگ سائز کو اپ ڈیٹ کریں ۔</p>
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

setTimeout(function() { 
        $('.mobile-toggle-menu').trigger('click');
    }, 200);

var modalshowstatus=$('#modalshowstatus').val();
var notsetpackingproduct=$('#notsetpackingproduct').val();

if(modalshowstatus == "Yes")
{
	$('#exampleModal').modal('show');
}
if(notsetpackingproduct != 0)
{
	$('#exampleModal2').modal('show');
}

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


       $('#new_company_id').on('change',function(){
    
    var new_company_id=$('#new_company_id').val();
    $('#new_product_id').html('').trigger('chosen:updated');

    $.ajax({
            type:"POST",
            url: "{{ url('getcompanyproduct') }}",
            data: { new_company_id: new_company_id},
            dataType: 'json',
            success: function(res){
              $('#new_product_id').append("<option value='' hidden>Select Product</option>").trigger('chosen:updated');
              for(var i=0;i <= res.length;i++)
              { 
              $('#new_product_id').append("<option value='"+res[i].product_code+"'>"+res[i].product_code+" - "+res[i].product_name+"</option>").trigger('chosen:updated');
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

function newmultiplyby(id)
{
   var rowid=id;
   var formula_id=$('#formula_id').val();

   var netstock=$('#netstock'+rowid).val();
   var avgsales=$('#avgsales'+rowid).val();
   var demand=$('#demand'+rowid).val();
   var new_multiply_by=$('#new_multiply_by'+rowid).val();
 

   var prdiff=((avgsales * 1) * new_multiply_by) - netstock;
   $('#formulaoutput'+rowid).val(Math.round(prdiff));
   $('#prdiff'+rowid).text(Math.round(prdiff));
   if(demand == 0 || demand == "")
   {
   	$('#finaloutput'+rowid).val(Math.round(prdiff));
   }

   //get group id
   $.ajax({
            type:"POST",
            url: "{{ url('getmultiplygroupid') }}",
            data: { new_multiply_by: new_multiply_by,formula_id:formula_id},
            dataType: 'json',
            success: function(res){
              $('#groupid'+rowid).val(res);
              
           }
        });

  

}


function newmanualstock(id)
{
   var rowid=id;

   var instock=$('#instock'+rowid).val();
   var in_transitstock=$('#in_transitstock'+rowid).val();
   var manualstock=$('#manualstock'+rowid).val();
    
   var netstock=(instock * 1) + (in_transitstock * 1) + (manualstock * 1);
   $('#netstock'+rowid).val(netstock);
   $('#stockshowtext'+rowid).text(netstock);
  newmultiplyby(rowid);

}



 $('#checkAll').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#myTable tbody input[type="checkbox"]').prop('checked', isChecked);
    });


function removerow(indexcount)
{
	let userConfirmed = confirm("Are you sure you want to remove this row no "+indexcount+"? ");
    if (userConfirmed) {
      $('#tablerow'+indexcount).remove();
      var indexcount=$('#indexcount').val();
      $('#indexcount').val(indexcount - 1);
    }
}


$('#btn_add').click(function(){
   var new_product_id=$('#new_product_id').val();
   var branch_id=$('#branch_id').val();

   if(new_product_id !='')
   {
       const table = document.getElementById("myTable");
       var indexcount=0;
       for (let row of table.rows) {
        // Sirf first column (pehla cell) ko check karen
        const firstColumnValue = row.cells[0].innerText;
      
          if (firstColumnValue.includes(new_product_id)) {
            alert('This Product Already in the Table');
            indexcount=1;
            break;
            }
          
        }

        if(indexcount == 0)
        {
            $.ajax({
            type:"POST",
            url: "{{ url('getproductinventorycosting') }}",
            data: { branch_id: branch_id,new_product_id :new_product_id},
            dataType: 'json',
            success: function(res){
                var multiply_by=$('#multiply_by').val();
                var formulaname=$('#formulaname').val();
                var formula_id=$('#formula_id').val();

            	var avgsales=Math.round(((parseInt(res.sales_qty1) * 1) + (parseInt(res.sales_qty2) * 1) + (parseInt(res.sales_qty3) * 1)) / 3);

            	var maxvalues=Math.max(parseInt(res.sales_qty1),parseInt(res.sales_qty2),parseInt(res.sales_qty3));

            	var netstock=(parseInt(res.in_stock) * 1) + (parseInt(res.in_transitstock) * 1);
            	var prdiff=((avgsales * 1) * multiply_by) - netstock;

const table  = document.getElementById("myTable");
 const rows = table.getElementsByTagName("tr");
const lastRow = rows[rows.length - 1];
const lastRowName = lastRow.getAttribute('name');
var newrownumber=(parseInt(lastRowName) * 1) + 1;
   let multiplyOptions = document.getElementById('multiplyOptions').innerHTML;


                

              $('#tablebody').append("<tr id='tablerow"+newrownumber+"' name='"+newrownumber+"'><td style='border: 1px solid black;color:black'><i class='fa fa-trash text-danger' onclick='removerow("+newrownumber+")' aria-hidden='true'></i> &nbsp;"+new_product_id+" - "+res.product_name+"</td><td style='border: 1px solid black;color:black;text-align: center'>"+res.sales_qty3+"<input type='hidden' id='formulaoutput"+newrownumber+"' name='formulaoutput"+newrownumber+"' value='"+prdiff+"'></td><td style='border: 1px solid black;color:black;text-align: center'>"+res.sales_qty2+"</td><td style='border: 1px solid black;color:black;text-align: center'>"+res.sales_qty1+"</td><td style='border: 1px solid black;color:black;text-align: center'>"+avgsales+"<input type='hidden' value='"+avgsales+"' id='avgsales"+newrownumber+"' name='avgsales"+newrownumber+"'></td><td style='border: 1px solid black;color:black;text-align: center'>"+maxvalues+"</td><td style='border: 1px solid black;color:black;text-align: center'>"+res.in_stock+"</td><td style='border: 1px solid black;color:black;text-align: center'>"+res.in_transitstock+"</td><td style='border: 1px solid black;color:black;text-align: center'>"+netstock+"<input type='hidden' value='"+netstock+"' id='netstock"+newrownumber+"' name='netstock"+newrownumber+"'></td><td style='border: 1px solid black;color:black;text-align: center' id='prdiff"+newrownumber+"'>"+prdiff+"</td><td style='border: 1px solid black;color:black;text-align: center'><input type='number'  id='demand"+newrownumber+"' name='demand"+newrownumber+"' style='width:80px' onkeyup='calcufinal("+newrownumber+")'></td><td style='border: 1px solid black;color:black;text-align: center'><input type='number' value='"+prdiff+"' id='finaloutput"+newrownumber+"' name='finaloutput"+newrownumber+"' style='width:80px' readonly></td><td style='border: 1px solid black;color:black;text-align: center'><select><option value='"+formula_id+"'>"+formulaname+"</option></select></td><td style='border: 1px solid black;color:black;text-align: center'><select id='new_multiply_by"+newrownumber+"' name='new_multiply_by"+newrownumber+"' onchange='newmultiplyby("+newrownumber+")' data-id='"+newrownumber+"'><option>"+multiply_by+"</option>"+multiplyOptions+"</select></td></tr>");

      var indexcount=$('#indexcount').val();
      $('#indexcount').val((indexcount *1) + 1);

           
           

            }
           });


        	
        }

   }
   else{
   	alert('please select Product first');
   }
   
});

												
	</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" />
<script type="text/javascript">
	$('#new_product_id').chosen();
	$('#new_company_id').chosen();

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


function calcufinal(ucount)
{
	var demand=$('#demand'+ucount).val();
	if(demand !=0 && demand !='')
	{
      $('#finaloutput'+ucount).val(demand);
	}
	else{
	  var formulaoutput=$('#formulaoutput'+ucount).val();	
	  let value = parseFloat(formulaoutput);
	  $('#finaloutput'+ucount).val(Math.round(value));	
	}
	
}
   

$('#stockshowing').change(function(){

  var stockshowing=$('#stockshowing').val();
  let url = new URL(window.location.href);

// Update the 'stockshowing' parameter to 'current'
url.searchParams.set('stockshowing', stockshowing);

// Reload the page with the updated URL
window.location.href = url.toString();


}); 
</script>