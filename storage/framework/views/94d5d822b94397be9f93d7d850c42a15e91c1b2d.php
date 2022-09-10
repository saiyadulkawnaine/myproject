<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<?php echo $__env->first(['System.Layout.head', 'Defult.System.Layout.head'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<body class="easyui-layout" id="LayoutMaster">
     <?php echo $__env->first(['System.Layout.header', 'Defult.System.Layout.header'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     <?php echo $__env->first(['System.Layout.left_sidebar', 'Defult.System.Layout.left_sidebar'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     <?php echo $__env->first(['System.Layout.right_sidebar', 'Defult.System.Layout.right_sidebar'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     <?php echo $__env->first(['System.Layout.container', 'Defult.System.Layout.container'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     
</body>
</html>