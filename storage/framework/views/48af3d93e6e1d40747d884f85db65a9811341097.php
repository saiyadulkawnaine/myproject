<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'FAMKAM')); ?> || ERP</title>
    <link rel="shortcut icon" type="image/ico" href="<?php echo e(asset('images/favicon.ico')); ?>"/>
<!--===============================================================================================-->
     <link rel="stylesheet" href="<?php echo url('/');?>/css/login/util.css">
     <link rel="stylesheet" href="<?php echo url('/');?>/css/login/main.css">
<!--===============================================================================================-->
</head>
<body>
 <?php echo $__env->yieldContent('content'); ?>   
    
<!--===============================================================================================-->
<script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <script src="<?php echo url('/');?>/js/login/main.js"></script>
</body>
</html>