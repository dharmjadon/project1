<?php if($major_category->bannerLinksTop->count()): ?>
    <div class="wrapbar-module">
        <?php $__currentLoopData = $major_category->bannerLinksTop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bannerLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($bannerLink->url); ?>" rel="nofollow, noopener" target="_blank">
            <div class="item-wrapbar">
                <div>
                    <h2><?php echo e($bannerLink->banner_title); ?></h2>
                    <p><?php echo e($bannerLink->banner_text); ?></p>
                </div>
                <img class="img-fluid lazyload" data-src="<?php echo e(otherImage($bannerLink->banner_image)); ?>" alt="<?php echo e($bannerLink->banner_title); ?>">
            </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php if($major_category->bannerLinksLeft->count() || $major_category->bannerLinksRight->count()): ?>
    <div class="search-made-module">
        <div class="row">
            <?php if($major_category->bannerLinksLeft->count()): ?>
                <?php $__currentLoopData = $major_category->bannerLinksLeft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $linkLeft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-6">
                        <a href="<?php echo e($linkLeft->url); ?>" rel="nofollow, noopener" target="_blank">
                        <div class="item-search-made">
                            <div class="pic-search-made">
                                <img class="img-fluid lazyload" data-src="<?php echo e(otherImage($linkLeft->banner_image)); ?>"
                                     alt="<?php echo e($linkLeft->banner_title); ?>">
                            </div>
                            <h3><?php echo e($linkLeft->banner_title); ?></h3>
                            <p> <?php echo e($linkLeft->banner_text); ?></p>
                        </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if($major_category->bannerLinksRight->count()): ?>
                <?php $__currentLoopData = $major_category->bannerLinksRight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $linkRight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-6">
                        <a href="<?php echo e($linkRight->url); ?>" rel="nofollow, noopener" target="_blank">
                        <div class="item-search-made">
                            <div class="pic-search-made">
                                <img class="img-fluid lazyload" data-src="<?php echo e(otherImage($linkRight->banner_image)); ?>"
                                     alt="<?php echo e($linkRight->banner_title); ?>">
                            </div>
                            <h3><?php echo e($linkRight->banner_title); ?></h3>
                            <p> <?php echo e($linkRight->banner_text); ?></p>
                        </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/banner-links.blade.php ENDPATH**/ ?>