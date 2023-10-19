<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php if(config('app.env') !== 'production'): ?><meta name="robots" content="noindex" /><?php endif; ?>
    <title><?php echo $__env->yieldContent('page_title', "Register & Login | My Finder"); ?></title>
    <link rel="icon" href="<?php echo e(asset('/v2/images/favicon.png')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/register.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/style.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/bootstrap.min.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/all.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/fontello.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/fontello-fp.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/v2/css/custom.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>"/>
    <?php echo RecaptchaV3::initJs(); ?>

</head>
<body>
<DIV id="prepage" style="position: fixed; z-index: 99999999; filter:alpha(opacity=60); opacity:0.6; font-family:arial; font-size:16px; left:0px; top:0px; background-color: #ECEDEF; layer-background-color: #ECEDEF; height:100%; width:100%; display:none;">
    <TABLE width="100%" height="100%" align="center"><TR><TD width="100%" align="center"><img class="lazyload" data-src="/v2/images/1.gif" alt="Please wait..." /></TD></TR></TABLE>
</DIV>
<div class="registerp">
    <header>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="header-wraper">
                        <div class="logo"><a href="/"> <img class="lazyload" src="/v2/images/logo.png" alt="My Finder"/></a>
                        </div>
                        <a href="#" class="btn-help">Help?</a></div>
                </div>
            </div>
        </div>
    </header>
    <?php echo $__env->yieldContent('content'); ?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <p>&copy; <?php echo date('Y'); ?> Copyright myfinder.com. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo e(asset('/v2/js/jquery-3.6.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/v2/js/bootstrap.min.js')); ?>"></script>
    <script type="text/javascript" src="/v2/js/lazysizes.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
    <a id="backtop" href="" data-placement="left"><i class="fa-solid fa-arrow-right"></i></a></div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/layout/auth.blade.php ENDPATH**/ ?>