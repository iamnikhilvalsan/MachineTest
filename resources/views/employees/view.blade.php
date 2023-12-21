@extends('layouts.master')
@section('body')
<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $data['PageName'] }}</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="javascript: void(0);">{{ $data['PageMenu'] }}</a></li>
					<li class="breadcrumb-item active">{{ $data['PageName'] }}</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row mb-2">
					<div class="col-sm-12">
						<div class="text-sm-right">
							<a href="{{ route('employee.create') }}" class="btn btn-success waves-effect waves-light mb-2"><i class="mdi mdi-plus mr-1"></i> Create New</a>
						</div>
					</div>
				</div>
				<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
					<thead>
						<tr>
							<th class="w50">#</th>
							<th>Name</th>
							<th>Company</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Created&nbsp;At</th>
							<th class="w50">Actions</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(function() {
	FetchData();
});

function FetchData()
{
	$('#datatable').DataTable({
		destroy: true,
		ordering: false,
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('employee.list') }}",
			dataType: "json",
			type: "POST",
		},
		columns: [
			{data: 'id', name: 'id'},
			{data: 'name', name: 'name'},
			{data: 'company', name: 'company'},
			{data: 'email', name: 'email'},
			{data: 'phone', name: 'phone'},
			{data: 'created_at', name: 'created_at'},
			{data: 'actions', name: 'actions'},
		],
	});
}

function deleteRow(deleteId) {
	Swal.fire({
		title: 'Are you sure?',
		text: 'You won\'t be able to revert this!',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!'
	}).then(function (e) {
		if (e.value === true) {
			showPreloader();
			$.ajax({
				url: "{{ route('employee.delete') }}",
				method: 'POST',
				data: { deleteId: deleteId},
			}).done(function(data) {
				hidePreloader();
				FetchData();
				Swal.fire('Success', data.message, 'success');
			}).fail(function(data) {
				hidePreloader();
				FetchData();
				response = JSON.parse(data.responseText);
				if(response.error){
					Swal.fire('Failed', response.message, 'error');
				}else{
					Swal.fire('Error', 'An error occurred.', 'error');
				}
			});
		}
	});
}
</script>
@endpush