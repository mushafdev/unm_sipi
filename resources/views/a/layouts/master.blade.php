<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="id">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#007bff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}} - {{identity()['singkat']}} </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{asset('app/assets/static/images/logo/favicon.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('app/assets/compiled/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/compiled/css/app-dark.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/compiled/css/iconly.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/compiled/css/cst.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/extensions/datatables.net-bs5/css/responsive.bootstrap5.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/extensions/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('app/assets/extensions/select2/select2.min.css')}}">
    <script src="{{asset('app/assets/extensions/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('app/assets/static/js/helper/setup.js')}}"></script>
    <script src="{{asset('app/assets/extensions/datatables.net-bs5/js/dataTables.js')}}"></script>
    <script src="{{asset('app/assets/extensions/datatables.net-bs5/js/dataTables.responsive.js')}}"></script>
    <script src="{{asset('app/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.js')}}"></script>
    <script src="{{asset('app/assets/extensions/autonumeric/autoNumeric.min.js')}}"></script>
</head>

<body>
    <script src="{{asset('app/assets/static/js/initTheme.js')}}"></script>
    <div id="app">
        @include('a.layouts.sidebar')
        <div id="main" class='layout-navbar navbar-fixed'>
            @include('a.layouts.header')
            
            <div id="main-content">
            @yield('content')
            </div>

            @include('a.layouts.footer')
        </div>
    </div>
    <script src="{{asset('app/assets/static/js/components/dark.js')}}"></script>
    <script src="{{asset('app/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('app/assets/compiled/js/app.js')}}"></script>
    <script src="{{asset('app/assets/extensions/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('app/assets/extensions/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('app/assets/extensions/select2/select2.min.js')}}"></script>
    <script src="{{asset('app/assets/static/js/helper/global.js')}}"></script>

    

</body>

</html>