<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FAMKAM') }} || ERP</title>
    <link rel="shortcut icon" type="image/ico" href="{{{ asset('images/favicon.ico') }}}"/>
<!--===============================================================================================-->
     <link rel="stylesheet" href="<?php echo url('/');?>/css/login/util.css">
     <link rel="stylesheet" href="<?php echo url('/');?>/css/login/main.css">
<!--===============================================================================================-->
</head>
<body>
 @yield('content')   
    
<!--===============================================================================================-->
<script src="{{ asset('js/app.js') }}"></script>
    <script src="<?php echo url('/');?>/js/login/main.js"></script>
</body>
</html>