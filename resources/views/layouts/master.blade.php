<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ config('app_settings.app_title') }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/png" href="{{ asset('assets/app-images/fav-icon.png') }}" sizes="30x30">
	<link href="{{ asset('assets\css\custom-theme.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\css\bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\css\icons.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\css\app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\libs\datatables.net-bs4\css\dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\libs\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\libs\magnific-popup\magnific-popup.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets\libs\sweetalert2\sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@stack('style')
</head>
<body data-sidebar="white" class="vertical-collpsed">
	<div id="preloader">
		<div id="status">
			<div class="spinner-chase">
				<div class="chase-dot"></div>
				<div class="chase-dot"></div>
				<div class="chase-dot"></div>
				<div class="chase-dot"></div>
				<div class="chase-dot"></div>
				<div class="chase-dot"></div>
			</div>
		</div>
	</div>
	<div id="layout-wrapper">
		<header id="page-topbar">
			<div class="navbar-header">
				<div class="d-flex">
					<div class="navbar-brand-box">
						<a href="{{ route('dashboard') }}" class="logo logo-dark">
							<span class="logo-sm">
								<img src="{{ asset('assets/app-images/app-icon-dark.png') }}" alt="{{ config('app.name') }}" height="22">
							</span>
							<span class="logo-lg">
								<img src="{{ asset('assets/app-images/app-logo-dark.png') }}" alt="{{ config('app.name') }}" height="17">
							</span>
						</a>
						<a href="{{ route('dashboard') }}" class="logo logo-light">
							<span class="logo-sm">
								<img src="{{ asset('assets/app-images/app-icon-light.png') }}" alt="{{ config('app.name') }}" height="22">
							</span>
							<span class="logo-lg">
								<img src="{{ asset('assets/app-images/app-logo-light.png') }}" alt="{{ config('app.name') }}" height="19">
							</span>
						</a>
					</div>
					<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
						<i class="fa fa-fw fa-bars"></i>
					</button>
				</div>
				<div class="d-flex">
					<div class="dropdown d-none d-lg-inline-block ml-1">
						<button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
							<i class="bx bx-fullscreen"></i>
						</button>
					</div>
					<div class="dropdown d-inline-block">
						<button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img class="rounded-circle header-profile-user" src="{{ asset('assets\images\users\avatar-1.jpg') }}" alt="Header Avatar">
							<span class="d-none d-xl-inline-block ml-1">{{ Auth::user()->name }}</span>
							<i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> Logout</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
						</div>
					</div>
				</div>
			</div>
		</header>
		<div class="vertical-menu">
			<div data-simplebar="" class="h-100">
				@include('layouts.side-menu')
		    </div>
		</div>
		<div class="main-content">
			<div class="page-content">
				@yield('body')
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">{{ date('Y') }} &copy; {{ config('app.name') }}
						</div>
						<div class="col-sm-6">
							<div class="text-sm-right d-none d-sm-block"></div>
						</div>
					</div>
				</div>
			</footer>
		</div>

		@include('layouts.right-menu')

		<script src="{{ asset('assets\libs\jquery\jquery.min.js') }}"></script>
		<script src="{{ asset('assets\libs\bootstrap\js\bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('assets\libs\metismenu\metisMenu.min.js') }}"></script>
		<script src="{{ asset('assets\libs\simplebar\simplebar.min.js') }}"></script>
		<script src="{{ asset('assets\libs\node-waves\waves.min.js') }}"></script>
		<script src="{{ asset('assets\js\app.js') }}"></script>
		<script src="{{ asset('assets\libs\datatables.net\js\jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('assets\libs\datatables.net-bs4\js\dataTables.bootstrap4.min.js') }}"></script>
		<script src="{{ asset('assets\libs\datatables.net-responsive\js\dataTables.responsive.min.js') }}"></script>
		<script src="{{ asset('assets\libs\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets\libs\magnific-popup\jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('assets\libs\sweetalert2\sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets\libs\bs-custom-file-input\bs-custom-file-input.min.js') }}"></script>
		@stack('scripts')
		<script>
			bsCustomFileInput.init();
			$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
			function viewImage(imageUrl)
			{
				Swal.fire({
					html: '<img src="'+imageUrl+'" class="viewImagePopup">',
					showConfirmButton: false
				});
			}

			function showPreloader() {
			    $("#preloader").show();
			    $("#status").show();
			}

			function hidePreloader() {
			    $("#preloader").hide();
			    $("#status").hide();
			}
		</script>
	</body>
</html>