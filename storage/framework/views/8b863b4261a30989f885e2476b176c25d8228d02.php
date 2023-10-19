<h2><strong>Browse by Categories</strong></h2>
<div class="accordion" id="browse-categories">
    <?php $__currentLoopData = $browseCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $browseCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="item-accordion">
        <h3 class="accordion-header" id="heading-mc-<?php echo e($browseCategory->id); ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-mc-<?php echo e($browseCategory->id); ?>" aria-expanded="true" aria-controls="collapse-mc-<?php echo e($browseCategory->id); ?>">
                <?php echo e($browseCategory->name); ?>

            </button>
        </h3>
        <div id="collapse-mc-<?php echo e($browseCategory->id); ?>" class="accordion-collapse collapse" aria-labelledby="heading-mc-<?php echo e($browseCategory->id); ?>"
             data-bs-parent="#browse-categories">
            <div class="accordion-body">
                <ul>
                    <?php $__currentLoopData = $browseCategory->mainCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $browseMainCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="/<?php echo e($browseCategory->slug); ?>?main_cat=<?php echo e($browseMainCategory->id); ?>"><?php echo e($browseMainCategory->name); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="show-more">
        <button class="btn-show-more">Show more <i class="fa-solid fa-chevron-down"></i></button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/browse-category.blade.php ENDPATH**/ ?>