@extends('layouts.master')
@section('body')
<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $data['PageName'] }}</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="{{ route('employee.view') }}">{{ $data['PageMenu'] }}</a></li>
					<li class="breadcrumb-item active">{{ $data['PageName'] }}</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title mb-4">Create Employee</h4>
				<form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="myForm">
					@csrf
					<div class="form-group row mb-4">
						<label for="company_id" class="col-sm-3 col-form-label">Company <span class="required">*</span></label>
						<div class="col-sm-9">
							<select class="form-control" name="company_id" required id="company_id">
								<option value="">Select Company</option>
								@foreach($CompanyList as $DT1)
									<option value="{{ $DT1->company_id }}">{{ $DT1->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="first_name" class="col-sm-3 col-form-label">First Name <span class="required">*</span></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" required>
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="last_name" class="col-sm-3 col-form-label">Last Name <span class="required">*</span></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" required>
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="email" class="col-sm-3 col-form-label">Email</label>
						<div class="col-sm-9">
							<input type="email" class="form-control" name="email" id="email" placeholder="Enter Email">
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="phone" class="col-sm-3 col-form-label">Phone</label>
						<div class="col-sm-9">
							<input type="number" class="form-control input-number" name="phone" id="phone" placeholder="Enter Phone">
						</div>
					</div>
					<div class="form-group row justify-content-end">
						<div class="col-sm-9">
							<div>
								<button type="submit" class="btn btn-primary w-md">Create</button>
								<a href="{{ route('employee.view') }}" class="btn btn-danger w-md">Back</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-3"></div>
</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
	$('#myForm').on('submit', function (e) {
		showPreloader();
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: formData,
			processData: false,
			contentType: false,
		}).done(function(data) {
			hidePreloader();
			$('#myForm')[0].reset();
			Swal.fire('Success', data.message, 'success');
		}).fail(function(data) {
			hidePreloader();
			response = JSON.parse(data.responseText);
			if(response.error){
				Swal.fire('Failed', response.message, 'error');
			}else{
				Swal.fire('Error', 'An error occurred.', 'error');
			}
		});
	});
});
</script>
@endpush