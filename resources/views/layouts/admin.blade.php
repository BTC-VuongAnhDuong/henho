<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="{{env('description')}}" name="description" />
	<meta content="Coderthemes" name="author" />

	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

	<!-- Bootstrap core CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<!-- MetisMenu CSS -->
	<link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet">
	<!-- Icons CSS -->
	<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

	<!-- Sweet Alert -->
	<link href="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">

	<!-- Custom styles for this template -->
	<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

	<script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>

	<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>

	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-timepicker.min.css') }}">
	<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
	<script>
		var url = '{{ asset('') }}';
	</script>
	@yield('header')
</head>
<body>
	<div id="page-wrapper">

		<!-- Top Bar Start -->
		@include('layouts.admin.topbar')
		<!-- Top Bar End -->


		<!-- Page content start -->
		<div class="page-contentbar">

			<!--left navigation start-->
			<aside class="sidebar-navigation">@include('layouts.admin.sidebar')</aside>
			<!--left navigation end-->

			<!-- START PAGE CONTENT -->
			<div id="page-right-content">
				@yield('content')
				<div class="footer">@include('layouts.admin.footer')</div>
				<!-- end footer -->

			</div>
			<!-- End #page-right-content -->

		</div>
		<!-- end .page-contentbar -->
	</div>
	<!-- End #page-wrapper -->


	<!-- Scripts -->
	<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

	<!-- App Js -->
	<script src="{{ asset('assets/js/jquery.app.js') }}"></script>
</body>
</html>
