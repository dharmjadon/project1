<?php if($dynamic_links): ?>
    <?php $arrBG = [1,2,3,4,5,6];?>
    <div class="thumbox-wraper">
        <div class="row">
            <?php $__currentLoopData = $dynamic_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dlink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-4">
                    <div class="thumbox-module thumbox-bg<?php echo e(array_rand($arrBG) + 1); ?>">
                        <h3><a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($dlink->slug); ?>"><?php echo e($dlink->link_title); ?></a></h3>
                        <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($dlink->slug); ?>">
                            <img src="<?php echo e(!empty($dlink->link_image) ? otherImage($dlink->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                                 alt="<?php echo e($dlink->link_title); ?>"></a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/dynamic-links.blade.php ENDPATH**/ ?>