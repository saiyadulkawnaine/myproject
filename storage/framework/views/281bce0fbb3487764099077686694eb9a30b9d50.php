<?php $__env->startSection('content'); ?>
<div class="limiter">
        <div class="container-login100" style="background-image: url('images/login/bg-03.jpg');">
            <div class="wrap-login100 p-t-30 p-b-50">
                
                <form class="login100-form validate-form p-b-33 p-t-5" method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo e(csrf_field()); ?>


                    <div class="wrap-input100 validate-input" data-validate = "Enter username">
                        <label>E-Mail</label><input id="email" class="input100" type="text" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                            <?php if($errors->has('email')): ?>
                            <span class="help-block">
                            <strong><?php echo e($errors->first('email')); ?></strong>
                            </span>
                            <?php endif; ?>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <label>Password</label><input id="password" class="input100" type="password" name="password" required>

                        <?php if($errors->has('password')): ?>
                        <span class="help-block">
                        <strong><?php echo e($errors->first('password')); ?></strong>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="container-login100-form-btn m-t-10">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>