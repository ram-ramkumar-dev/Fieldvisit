<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>{{ config('app.name', 'TRUE COLLECT') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS --> 
          <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
      
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
       
    <title>@yield('title', $title)</title>
</head>

<body class=""> 
    @include('partials/loader')
    <div class="wrapper">  
             @yield('content') 
    </div>  
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
    <script src="{{ asset('assets/vendor/emoji-picker-element/index.js')}}" type="module"></script>
    <script src="{{ asset('assets/js/app.js')}}"></script> 
    </body>
</body>

</html>