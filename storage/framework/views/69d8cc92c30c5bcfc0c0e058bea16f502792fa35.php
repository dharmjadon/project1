
<?php
    $routeName = \Route::current()->getName();
    if ($routeName == 'events' || $routeName == 'event') {
        # code...
        $url = 'event';
    } elseif ($routeName == 'directory' || $routeName == 'directory-list') {
        # code...
        $url = 'directory';
    } elseif ($routeName == 'cryptocoin' || $routeName == 'cryptocoin-list') {
        # code...
        $url = 'cryptocoin';
    } elseif ($routeName == 'it' || $routeName == 'it-list') {
        # code...
        $url = 'it';
    } elseif ($routeName == 'education' || $routeName == 'education-list') {
        # code...
        $url = 'education';
    } elseif ($routeName == 'tickets') {
        # code...
        $url = 'tickets';
    } elseif ($routeName == 'venue' || $routeName == 'venue-list') {
        # code...
        $url = 'venue-detail';
    } elseif ($routeName == 'jobs' || $routeName == 'job-list') {
        # code...
        $url = 'Jobs';
    } elseif ($routeName == 'buy-and-sells') {
        $url = 'buy-and-sell';
    } else {
        $url = null;
    }
?>
<div class="heading-module">
    <h2>Just Joined My Finder</h2>
    <ul class="btn-heading">
        
    </ul>
</div>
<div class="swiper-slick">
    <div class="swiper <?php if($type == 'jobs'): ?> joined-jobs-slick <?php else: ?> joinfp-slick <?php endif; ?>">
        <div class="swiper-wrapper">
            <?php $__empty_1 = true; $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($row->images)) {
                    # code...
                    $images = json_decode($row->images);
                } elseif (isset($row->media)) {
                    # code...
                    $images = json_decode($row->media);
                }

                ?>

                <?php if($type == 'jobs'): ?>
                    <div class="swiper-slide">
                        <div class="itembox ib-job">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h3><a href="<?php echo e(route('job-details', $row->slug)); ?>"><?php echo e($row->job_title); ?></a>
                                    </h3>
                                    <p class="ib-search-btn"><a href="<?php echo e(route('job-details', $row->slug)); ?>"><i
                                                class="party-icon icon-search"></i><?php echo e($row->job_title); ?>(23)</a>
                                    </p>
                                    <h4 class="ib-price">AED <?php echo e($row->min_salary); ?> -
                                        <?php echo e($row->max_salary); ?> <small>Per Month</small></h4>
                                    <ul>
                                        <li><i class="party-icon icon-time"></i>
                                            <?php if($row->job_type == 'full_time'): ?>
                                                Full Time Job
                                            <?php elseif($row->job_type == 'part_time'): ?>
                                                Part Time Job
                                            <?php elseif($row->job_type == 'freelancer'): ?>
                                                Freelencer Job
                                            <?php else: ?>
                                                Full Time Job
                                            <?php endif; ?>
                                        </li>
                                        <li><i class="party-icon icon-experience"></i>
                                            <?php echo e($row->experience); ?> Years
                                            Experience
                                        </li>
                                        <li><i class="party-icon icon-location"></i><a
                                                href="https://maps.google.com/?q=<?php echo e($row->lat); ?>,<?php echo e($row->long); ?>"
                                                target="_blank">
                                                <?php echo e(\Illuminate\Support\Str::limit($row->location, 30)); ?>

                                            </a></li>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <div class="ib-wishlist"><a href="#" id="<?php echo e($row->id ?? ''); ?>"
                                            data-major-category="7"><i class="party-icon icon-heart"></i></a>
                                    </div>
                                    <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                            <?php echo e($row->views); ?></span>
                                        <span><?php echo \Carbon\Carbon::parse($row->created_at)->tz(config('app.timezone'))->ago(); ?></span>
                                    </div>
                                    <img src="<?php echo e($row->storedImage($row->logo)); ?>" alt="<?php echo e($row->job_title); ?>">
                                </div>
                            </div>
                            <div class="hover-itembox">
                                <div class="btn-itembox">
                                    <a href="<?php echo e(route('job-details', $row->slug)); ?>" class="btn btn-primary">View
                                        More</a>
                                    <a href="<?php echo e(route('apply-job-form', $row)); ?>" data-bs-toggle="modal"
                                        data-bs-target="#ajax-modal" role="button" class="btn btn-secondary"
                                        rel="nofollow">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="swiper-slide">
                        <div class="itembox ib-join">
                            <div class="imgeffect">
                                <a href="#">
                                    <img src="<?php if($row->featureImage): ?> <?php echo e($row->getStoredImage($row->featureImage->image, $row->featureImage->image_type)); ?> <?php elseif($row->feature_iamge): ?><?php echo e($row->storedImage($row->feature_iamge) ?? ''); ?> <?php endif; ?>" alt="<?php if($row->featureImage): ?> <?php echo e($row->featureImage->alt_text_en ?? ''); ?> <?php else: ?> Feature Image <?php endif; ?>"></a>
                                <div class="ib-wishlist"><a href="#"><i class="party-icon icon-heart"></i></a>
                                </div>
                                <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                        <?php if($row->views): ?>
                                            <?php echo e($row->views ?? ''); ?>

                                        <?php elseif($row->view_count): ?>
                                            <?php echo e($row->view_count ?? ''); ?>

                                        <?php endif; ?>
                                    </span> <span><?php echo \Carbon\Carbon::parse($row->created_at)->tz(config('app.timezone'))->ago(); ?></span></div>
                            </div>
                            <div class="itembox-info">
                                <h3><a href="#">
                                        <?php if($row->name): ?>
                                            <?php echo e($row->name ?? ''); ?>

                                        <?php elseif($row->product_name): ?>
                                            <?php echo e($row->product_name ?? ''); ?>

                                        <?php elseif($row->title): ?>
                                            <?php echo e($row->title ?? ''); ?>

                                        <?php endif; ?>
                                    </a></h3>

                                <p class="ib-search-btn">
                                    <a href=""><i class="party-icon icon-search"></i>
                                        <?php echo e($row->subCategory->name); ?>

                                     <?php if($type == 'buysell'): ?>
                                        (<?php echo e($row->subCategory->buysells->count()); ?>)
                                    <?php elseif($type == 'educations'): ?>
                                        (<?php echo e($row->subCategory->education->count()); ?>)
                                    <?php elseif($type == 'directory'): ?>
                                        (<?php echo e($row->subCategory->directory->count()); ?>)
                                        <?php endif; ?>
                                    </a>
                                </p>
                                <ul>
                                    <li style="min-height: 69px;"><i class="party-icon icon-location"></i>
                                        <?php if($row->location): ?>
                                            <?php echo e(\Illuminate\Support\Str::limit($row->location ?? '', 50)); ?>

                                        <?php elseif($row->location_name): ?>
                                            <?php echo e(\Illuminate\Support\Str::limit($row->location_name ?? '', 50)); ?>

                                        <?php elseif($row->lcoation_name): ?>
                                            <?php echo e(\Illuminate\Support\Str::limit($row->lcoation_name ?? '', 50)); ?>

                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="hover-itembox">
                                <div class="review-itembox">
                                    <span><img src="/v2/images/icons/google.svg" alt="google"
                                            ondragstart="return false"> <?php echo e($row->map_rating); ?> <i
                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                            class="fas fa-star"></i> <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i></span>
                                    <p><?php echo e($row->map_review); ?> Reviews</p>
                                </div>
                                <div class="review-itembox">
                                    <span>
                                        <img class="lazyload" data-src="/v2/images/favicon.png" alt="favicon"
                                               ondragstart="return false">
                                        <?php echo e(round($row->approvedReviews->avg('rating'), 1)); ?>

                                        <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                                    </span>
                                    <p><?php echo e($row->approvedReviews->count()); ?> Reviews</p>
                                </div>
                                <div class="btn-itembox"><a href="<?php echo e($url ? route($url, $row->slug) : '#'); ?>"
                                        class="btn btn-primary">View More</a>
                                    <a href="#enquiry-modal" data-bs-toggle="modal" data-id="<?php echo e($row->id); ?>" data-major-cat="<?php echo e($major_category->id); ?>"
                                        class="btn btn-secondary">Enquire Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="swiper-slide">
                    <div class="itembox ib-join">
                        <div class="imgeffect"> <a href="#"><img src="/v2/images/module/join-1.jpg"
                                    alt="demo"></a>
                            <div class="ib-wishlist"><a href="#"><i class="party-icon icon-heart"></i></a>
                            </div>
                            <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                    2445</span> <span>1 Day</span></div>
                        </div>
                        <div class="itembox-info">
                            <h3><a href="#">Noor Tour & Travel</a></h3>
                            <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                            <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                    Tourism</a></p>
                        </div>
                        <div class="hover-itembox">
                            <div class="review-itembox"><span><img src="/v2/images/icons/google.svg" alt="google"
                                        ondragstart="return false"> 3.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i>
                                    <i class="fas fa-star"></i></span>
                                <p>7823 Reviews</p>
                            </div>
                            <div class="review-itembox"><span><img src="/v2/images/favicon.png" alt="favicon"
                                        ondragstart="return false"> 4.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i>
                                    <i class="fas fa-star"></i></span>
                                <p>873 Reviews</p>
                            </div>
                            <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a> <a
                                    href="#enquiry-modal" data-bs-toggle="modal" data-id="0"
                                    class="btn btn-secondary">Enquire Now</a></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="itembox ib-join">
                        <div class="imgeffect"> <a href="#"><img src="/v2/images/module/join-2.jpg"
                                    alt="demo"></a>
                            <div class="ib-wishlist"><a href="#"><i class="party-icon icon-heart"></i></a>
                            </div>
                            <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                    2445</span> <span>1 Day</span></div>
                        </div>
                        <div class="itembox-info">
                            <h3><a href="#">Noor Tour & Travel</a></h3>
                            <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                            <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                    Tourism</a></p>
                        </div>
                        <div class="hover-itembox">
                            <div class="review-itembox"><span><img src="/v2/images/icons/google.svg" alt="google"
                                        ondragstart="return false"> 3.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>7823 Reviews</p>
                            </div>
                            <div class="review-itembox"><span><img src="/v2/images/favicon.png" alt="favicon"
                                        ondragstart="return false"> 4.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>873 Reviews</p>
                            </div>
                            <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a> <a
                                    href="#enquiry-modal" data-bs-toggle="modal" data-id="0"
                                    class="btn btn-secondary">Enquire Now</a></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="itembox ib-join">
                        <div class="imgeffect"> <a href="#"><img src="/v2/images/module/join-3.jpg"
                                    alt="demo"></a>
                            <div class="ib-wishlist"><a href="#"><i class="party-icon icon-heart"></i></a>
                            </div>
                            <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                    2445</span> <span>1 Day</span></div>
                        </div>
                        <div class="itembox-info">
                            <h3><a href="#">Noor Tour & Travel</a></h3>
                            <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                            <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                    Tourism</a></p>
                        </div>
                        <div class="hover-itembox">
                            <div class="review-itembox"><span><img src="/v2/images/icons/google.svg" alt="google"
                                        ondragstart="return false"> 3.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>7823 Reviews</p>
                            </div>
                            <div class="review-itembox"><span><img src="/v2/images/favicon.png" alt="favicon"
                                        ondragstart="return false"> 4.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>873 Reviews</p>
                            </div>
                            <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a> <a
                                    href="#enquiry-modal" data-bs-toggle="modal" data-id="0"
                                    class="btn btn-secondary">Enquire Now</a></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="itembox ib-join">
                        <div class="imgeffect"> <a href="#"><img src="/v2/images/module/join-4.jpg"
                                    alt="demo"></a>
                            <div class="ib-wishlist"><a href="#"><i class="party-icon icon-heart"></i></a>
                            </div>
                            <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                    2445</span> <span>1 Day</span></div>
                        </div>
                        <div class="itembox-info">
                            <h3><a href="#">Noor Tour & Travel</a></h3>
                            <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                            <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                    Tourism</a></p>
                        </div>
                        <div class="hover-itembox">
                            <div class="review-itembox"><span><img src="/v2/images/icons/google.svg" alt="google"
                                        ondragstart="return false"> 3.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>7823 Reviews</p>
                            </div>
                            <div class="review-itembox"><span><img src="/v2/images/favicon.png" alt="favicon"
                                        ondragstart="return false"> 4.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>873 Reviews</p>
                            </div>
                            <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a> <a
                                    href="#enquiry-modal" data-bs-toggle="modal" data-id="0"
                                    class="btn btn-secondary">Enquire Now</a></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="itembox ib-join">
                        <div class="imgeffect"> <a href="#"><img src="/v2/images/module/join-5.jpg"
                                    alt="demo"></a>
                            <div class="ib-wishlist"><a href="#"><i class="party-icon icon-heart"></i></a>
                            </div>
                            <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                    2445</span> <span>1 Day</span></div>
                        </div>
                        <div class="itembox-info">
                            <h3><a href="#">Noor Tour & Travel</a></h3>
                            <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                            <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                    Tourism</a></p>
                        </div>
                        <div class="hover-itembox">
                            <div class="review-itembox"><span><img src="/v2/images/icons/google.svg" alt="google"
                                        ondragstart="return false"> 3.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>7823 Reviews</p>
                            </div>
                            <div class="review-itembox"><span><img src="/v2/images/favicon.png" alt="favicon"
                                        ondragstart="return false"> 4.5 <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                        class="fas fa-star"></i> <i class="fas fa-star"></i></span>
                                <p>873 Reviews</p>
                            </div>
                            <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a> <a
                                    href="#enquiry-modal" data-bs-toggle="modal" data-id="0"
                                    class="btn btn-secondary">Enquire Now</a></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="swiper-arrow">
        <div class="swiper-button-prev joinfp-prev"></div>
        <div class="swiper-button-next joinfp-next"></div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/just-joined.blade.php ENDPATH**/ ?>