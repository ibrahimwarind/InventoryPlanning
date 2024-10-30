@include('header')
   <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			
				<!--end breadcrumb-->
				<div class="row row-cols-1 row-cols-1">


					<div class="col">
						<h6 class="mb-0 text-uppercase">Add CheckList Question
<a href="/checklistquestionlist" class="btn btn-primary" style="float: right !important;margin-top: -10px">View List</a>
						</h6>
						<hr/>
						<div class="card border-top border-0 border-4 border-primary">
							<div class="card-body p-5">
								

								<form method="get" action="<?php echo PROJECTURL ?>/checklistquestion2" enctype= "multipart/form-data" class="row g-3">
									@csrf
  
									

									<div class="col-md-5">
										<label for="inputFirstName" class="form-label">Checklist Question Title</label>
										<input type="text" class="form-control" id="checklist_title"  name="checklist_title"  required="">
									</div>

									<div class="col-md-3">
										<label for="inputFirstName" class="form-label">First/Every Version ?</label>
										<select required="" class="form-control" name="every_time" id="every_time">
											<option value="" hidden="">Select</option>
											<option value="0">In Every Version</option>
											<option value="1">In First Version</option>
										</select>
									</div>
									
									
								
									

								

									
									<div class="col-12">
										<?php if($data == "Question Successfully Added")
										{
                    echo "<p class='text-success'>$data</p>";
										}
										else if($data == "Question Already Exist"){
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

$('#multiply_by').keyup(function(){
   var multiply_by=$('#multiply_by').val();
   var formula_detail=$('#formula_detail').val();

   $('#formula_detail').val(formula_detail.replace('$$', multiply_by));

});

	</script>
