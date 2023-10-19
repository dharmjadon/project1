<div class="thumbox-wraper">
    <div class="row">
        <?php if($popular_links): ?>
            <div class="col-sm-4">
                <div class="thumbox-module thumbox-bg1">
                    <h3>
                        <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($popular_links->slug); ?>"><?php echo e($popular_links->link_title); ?></a>
                    </h3>
                    <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($popular_links->slug); ?>">
                        <img
                            src="<?php echo e(!empty($popular_links->link_image) ? otherImage($popular_links->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                            alt="<?php echo e($popular_links->link_title); ?>"></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if($trending_links): ?>
            <div class="col-sm-4">
                <div class="thumbox-module thumbox-bg2">
                    <h3>
                        <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($trending_links->slug); ?>"><?php echo e($trending_links->link_title); ?></a>
                    </h3>
                    <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($trending_links->slug); ?>">
                        <img
                            src="<?php echo e(!empty($trending_links->link_image) ? otherImage($trending_links->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                            alt="<?php echo e($trending_links->link_title); ?>"></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if($hot_links): ?>
            <div class="col-sm-4">
                <div class="thumbox-module thumbox-bg3">
                    <h3>
                        <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($hot_links->slug); ?>"><?php echo e($hot_links->link_title); ?></a>
                    </h3>
                    <a href="/<?php echo e($major_category->slug); ?>/view-listings/<?php echo e($hot_links->slug); ?>">
                        <img
                            src="<?php echo e(!empty($hot_links->link_image) ? otherImage($hot_links->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                            alt="<?php echo e($hot_links->link_title); ?>"></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/popular-trending-hot-links.blade.php ENDPATH**/ ?>