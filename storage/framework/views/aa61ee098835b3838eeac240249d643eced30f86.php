

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/forms/select/select2.min.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<form method="post" action="<?php echo e(route('admin.publisher-login-banner.update', $data->id)); ?>" enctype='multipart/form-data'>

    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="card card-outline card-pink">
        <div class="card-header">
            <h4 class="card-title">Add Image</h4>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-xl-4 col-md-4 col-12 mb-1">
                    <label>Image</label>
                    <input type="file" name="img" class="form-control"/>
                </div>
                <div class="col-xl-12 col-md-12 col-12 mb-1">
                    <button type="submit" class="btn btn-primary mt-2">Submit <i data-feather="check"></i></button>
                </div>
            </div>


        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('app-assets/vendors/js/forms/select/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('app-assets/js/scripts/forms/form-select2.js')); ?>"></script>

<script>
    <?php if(Session::has('message')): ?>
    var type = "<?php echo e(Session::get('alert-type', 'info')); ?>";
    switch(type){
        case 'info':
            toastr.info("<?php echo e(Session::get('message')); ?>", "Information!", { timeOut:10000 , progressBar : true});
            break;

        case 'warning':
            toastr.warning("<?php echo e(Session::get('message')); ?>", "Warning!", { timeOut:10000 , progressBar : true});
            break;

        case 'success':
            toastr.success("<?php echo e(Session::get('message')); ?>", "Success!", { timeOut:10000 , progressBar : true});
            break;

        case 'error':
            toastr.error("<?php echo e(Session::get('message')); ?>", "Failed!", { timeOut:10000 , progressBar : true});
            break;
    }
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/publisher-login-banner/edit.blade.php ENDPATH**/ ?>