<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TRUE COLLECT') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
      
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    </head>
    <body class=""> 
    @include('partials/loader')
    <div class="wrapper">
            @include('partials/sidebar', ['permissions' => $permissions])
            @include('partials/header')
        <div class="content-page">
             @yield('content')
        </div>
    </div>
        @include('partials/footer') 
    <script src="{{ asset('assets/js/backend-bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/customizer.js')}}"></script>
    <script src="{{ asset('assets/js/sidebar.js')}}"></script>
    <script src="{{ asset('assets/js/flex-tree.min.js')}}"></script>
    <script src="{{ asset('assets/js/tree.js')}}"></script>
    <script src="{{ asset('assets/js/table-treeview.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert.js')}}"></script>
    <script src="{{ asset('assets/js/vector-map-custom.js')}}"></script>
    <script src="{{ asset('assets/js/chart-custom.js')}}"></script>
    <script src="{{ asset('assets/js/charts/01.js')}}"></script>
    <script src="{{ asset('assets/js/charts/02.js')}}"></script>
    <script src="{{ asset('assets/js/slider.js')}}"></script>  
    <script src="{{ asset('assets/js/app.js')}}"></script> 
    </body>
</html>
