@extends('layouts.master')
@section('body')
<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $data['PageName'] }}</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="{{ route('company.view') }}">{{ $data['PageMenu'] }}</a></li>
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
				<h4 class="card-title mb-4">Edit Company</h4>
				<form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="myForm">
					@csrf
					<div class="form-group row mb-4">
						<label for="name" class="col-sm-3 col-form-label">Name <span class="required">*</span></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required value="{{ $ResultData->name }}">
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="email" class="col-sm-3 col-form-label">Email</label>
						<div class="col-sm-9">
							<input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{ $ResultData->email }}">
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="logo" class="col-sm-3 col-form-label">Logo</label>
						<div class="col-sm-9">
							<div class="custom-file">
								<input type="file" class="custom-file-input" accept=".webp" id="logo" name="logo">
								<label class="custom-file-label" for="logo">Choose file</label>
							</div>
							<label class="alertLabel">Accept .webp only</label>
						</div>
					</div>
					<div class="form-group row mb-4">
						<label for="website" class="col-sm-3 col-form-label">Website</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="website" id="website" placeholder="Enter Website" value="{{ $ResultData->website }}">
						</div>
					</div>
					<div class="form-group row justify-content-end">
						<div class="col-sm-9">
							<div>
								<input type="hidden" name="company_id" id="company_id" value="{{ $ResultData->company_id }}">
								<button type="submit" class="btn btn-primary w-md">Update</button>
								<a href="{{ route('company.view') }}" class="btn btn-danger w-md">Back</a>
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