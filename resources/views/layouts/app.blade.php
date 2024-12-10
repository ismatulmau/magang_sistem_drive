<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap -->
    <link href="{{ asset('style/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('style/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset('style/build/css/custom.min.css') }}" rel="stylesheet">
</head>
<body class="login">    
    @yield('content')
    <!-- Scripts -->
    <script src="{{ asset('style/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('style/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('style/build/js/custom.min.js') }}"></script>
</body>
</html>
