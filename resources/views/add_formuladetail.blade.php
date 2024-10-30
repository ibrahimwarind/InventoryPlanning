@include('header')
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Add Formula Detail
<a href="/formulamanagementlist" class="btn btn-primary" style="float: right !important;margin-top: -10px">View List</a>
						</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-calculator me-1 font-22 text-primary"></i>
									</div>
									<h5 class="mb-0 text-primary">Add Formula</h5>
								</div>
								<hr>

								<form method="get" action="<?php echo PROJECTURL ?>/addformuladetail" enctype= "multipart/form-data" class="row g-3">
									@csrf
  
									<div class="col-md-3">
										<label for="inputLastName" class="form-label">Formula Name</label>
										<br>
										<select class="form-control form-select" style="" name="formula_id" id="formula_id" required="">
											<option value="" hidden="">Select Formula</option>
									

								@foreach($formuladata as $brdata)
								<option value="{{$brdata->formula_id}}">{{$brdata->formula_name}}</option>

								@endforeach
							       </select>
									</div>

									<div class="col-md-5">
										<label for="inputFirstName" class="form-label">Formula Description</label>
										<input type="text" class="form-control" id="formula_detail"  name="formula_detail"  required="">
									</div>
									<div class="col-md-2">
										<label for="inputFirstName" class="form-label">Multiply By</label>
										<input type="number" class="form-control" id="multiply_by" step="0.00000000000000001" name="multiply_by" required="">
									</div>

									<div class="col-md-2">
										<label for="inputLastName" class="form-label">Is Default</label>
										<br>
										<select class="form-control form-select" style="" name="is_default" id="is_default" required="">
											<option value="" hidden="">Select </option>
											<option value="1">Yes</option>
											<option value="0">No</option>
									
							       </select>
									</div>
									
								
									

								

									
									<div class="col-12">
										<?php if($data == "Formula Successfully Added")
										{
                    echo "<p class='text-success'>$data</p>";
										}
										else if($data == "Formula Already Exist"){
                    echo "<p class='text-danger'>$data</p>";
										} ?>
										
										<button type="submit" class="btn btn-primary px-5">Save</button>
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
    $('#formula_detail').val("");

    $.ajax({
            type:"POST",
            url: "{{ url('getformuladescription') }}",
            data: { formula_id: formula_id},
            dataType: 'json',
            success: function(res){
              
			$('#formula_detail').val(res);
              
           }
        });
    
    });
});

// $('#multiply_by').keyup(function(){
//    var multiply_by=$('#multiply_by').val();
//    var formula_detail=$('#formula_detail').val();
//    $('#formula_detail').val(formula_detail.replace('$$', multiply_by));

// });

	</script>
