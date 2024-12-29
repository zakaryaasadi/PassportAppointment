<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>المواعيد</title>
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">
        @yield('head')
        
    </head>
    <body>
        <div class="content-wrapper" style="margin-left: 0px">
            @yield('content')
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
        <script src="{{asset('js/dashboard_ecommerce.js')}}"></script> 


        @yield('js')
        </body>
    </html>