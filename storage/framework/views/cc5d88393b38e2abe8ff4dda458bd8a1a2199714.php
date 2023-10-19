
<div class="popular-search">
    <ul class="tab-menu">
        <li data-tab="tab-top-search" class="active"><?php echo e($top_search_tab); ?></li>
        <li data-tab="tab-popular-search"><?php echo e($popular_search_tab); ?></li>
        <a href="<?php echo e(route($route_name, ['all'])); ?>" class="btn btn-secondary">View All</a>
    </ul>
    <div id="tab-top-search" class="tab-content active">
        <div class="popular-search-slick">
            <?php $__currentLoopData = $top_searches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top_search): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($top_search->top_search_count): ?>
                    <div class="menu-popular-search">
                        <h3><?php echo e($top_search->name); ?></h3>
                        <ul>
                            <?php $__currentLoopData = $top_search->topSearch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $search_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route($route_name, $search_item->slug)); ?>"><?php echo e($search_item->name); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div id="tab-popular-search" class="tab-content">
        <div class="popular-search-slick">
            <?php $__currentLoopData = $popular_searches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popular_search): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($popular_search->popular_search_count): ?>
                    <div class="menu-popular-search">
                        <h3><?php echo e($popular_search->name); ?></h3>
                        <ul>
                            <?php $__currentLoopData = $popular_search->popularSearch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popular_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route($route_name, $popular_item->slug)); ?>"><?php echo e($popular_item->name); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/top-popular-search-tabs.blade.php ENDPATH**/ ?>