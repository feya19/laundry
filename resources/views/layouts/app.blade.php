<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Poppins" rel="stylesheet">

    <!-- Style -->
    <link rel="stylesheet" href="{{asset('assets/build/styles/ltr-core.css')}}">
    <link rel="stylesheet" href="{{asset('assets/build/styles/ltr-vendor.css')}}">
    <link rel="stylesheet" href="{{asset('assets/build/styles/main.css')}}">
    <link href="{{asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    @stack('style')
</head>
<body class="theme-light preload-active aside-active aside-mobile-minimized aside-desktop-maximized" id="fullscreen">
    <!-- BEGIN Page Holder -->
    <div class="preload">
		<div class="preload-dialog">
			<!-- BEGIN Spinner -->
			<div class="spinner-border text-primary preload-spinner"></div>
			<!-- END Spinner -->
		</div>
	</div>
    <div class="scrolltop">
        <button class="btn btn-info btn-icon btn-lg">
            <i class="fa fa-angle-up"></i>
        </button>
    </div>
	<div class="holder">
        @includeWhen($sidebar ?? true, 'layouts.sidebar')
        <div class="wrapper {{isset($sidebar) ? 'pl-0' : ''}}">
            @includeWhen($header ?? true, 'layouts.header')
			<div class="content">
                <div class="container-fluid">
                    @include('layouts.message')
                    @yield('content')
                </div>
            </div>
		</div>
	</div>
    <!-- BEGIN Float Button -->
	<div class="float-btn float-btn-right">
		<button class="btn btn-flat-primary btn-icon mb-2" id="theme-toggle" data-toggle="tooltip" data-placement="right" title="Change theme">
			<i class="fa fa-moon"></i>
		</button>
	</div>
	<!-- END Float Button -->
    <script src="{{asset('assets/build/scripts/mandatory.js')}}"></script>
    <script src="{{asset('assets/build/scripts/core.js')}}"></script>
    <script src="{{asset('assets/build/scripts/vendor.js')}}"></script>
    <script src="{{asset('assets/build/scripts/bootbox.min.js')}}"></script>
    <script src="{{asset('assets/build/scripts/axios.min.js')}}"></script>
    <script src="{{asset('assets/build/scripts/jquery.number.min.js')}}"></script>
    <script src="{{asset('assets/build/scripts/locale.js')}}"></script>
    <script src="{{asset('assets/build/scripts/localization.js')}}"></script>
    <script src="{{asset('assets/build/scripts/build.js')}}"></script>
    @stack('script')
</body>
</html>
