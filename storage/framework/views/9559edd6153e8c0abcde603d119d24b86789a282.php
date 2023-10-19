<?php $arrBG = [1,2,3,4,5,6,7,8,9];?>
<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="item-topcategory topcategory-bg<?php echo e(array_rand($arrBG) + 1); ?>">
        <a href="<?php echo e(route($url, ['category_slug' => $category->slug])); ?>"><?php echo e($category->name); ?></a>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/topcategory-bg.blade.php ENDPATH**/ ?>