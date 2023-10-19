
<?php $__env->startSection('page_title', $major_category->meta_title); ?>
<?php $__env->startSection('meta_description', $major_category->meta_description); ?>
<?php $__env->startSection('meta_keywords', $major_category->meta_tags); ?>
<?php $__env->startSection('og_title', $major_category->meta_title); ?>
<?php $__env->startSection('og_description', $major_category->meta_description); ?>
<?php $__env->startSection('og_image', !empty($major_category->banner_images) ? otherImage($major_category->banner_images[0]['image']) : '/user-asset/images/logo.png'); ?>
<?php $__env->startSection('og_type', 'Webpage'); ?>
<?php $__env->startSection('canonical_url', route('events')); ?>
<?php $__env->startSection('meta'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/module.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/static.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/landing-module.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/slick.css"/>
    <link href="/v2/css/daterangepicker.css" id="app-style" rel="stylesheet" type="text/css"/>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="event-landingp module-landingp">
        <div class="categories-module">
            <ul>
                <li class="more-categories-module">
                    <a href="<?php echo e(route('event-list', ['all'])); ?>" title="View All Events">View All</a>
                </li>
                <?php $__currentLoopData = $categoryForSidebar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catKey => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li <?php if($catKey > 7): ?> class="d-none more-category" <?php endif; ?>>
                        <a href="<?php echo e(route('event-list', ['category_slug' => $category->slug])); ?>">
                            <img class="lazyload img-fluid" data-src="<?php echo e($category->storedImage($category->icon)); ?>"
                                 width="75" alt="event - <?php echo e($category->name); ?>"></a>
                        <h3>
                            <a href="<?php echo e(route('event-list', ['category_slug' => $category->slug])); ?>"><?php echo e(Str::limit($category->name, 20)); ?></a>
                        </h3>
                        <p><?php echo e($category->event_count); ?></p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($catKey) && $catKey >= 7): ?>
                    <li class="more-categories-module">
                        <a id="icon-more-categories" data-tot-count="<?php echo e($categoryForSidebar->count()); ?>">
                            <i class="party-icon icon-more"></i>
                            <span>+<?php echo e(($categoryForSidebar->count() - 8)); ?></span>
                            More
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="tab-search-wraper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-1">
                        <div class="stories">
                            <?php if($major_category->images): ?>
                                    <?php $story_images = json_decode($major_category->images); ?>
                                <a href="#story-modal" data-bs-toggle="modal" role="button">
                                    <img class="lazyload" data-src="<?php echo e(otherImage($story_images[0])); ?>"
                                         alt="stories">
                                    <small>Stories</small>
                                </a>
                            <?php else: ?>
                                <a role="button">
                                    <img class="lazyload" data-src="/v2/images/demo-1.jpg" alt="stories">
                                    <small>Stories</small>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if(isset($story_images)): ?>
                            <div class="modal fade modal-slider" id="story-modal" aria-hidden="true"
                                 aria-labelledby="story-modal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            <i class="fa-solid fa-xmark"></i></button>
                                        <div class="modal-body">
                                            <div class="swiper story-slick">
                                                <div class="swiper-wrapper">
                                                    <?php $__currentLoopData = $story_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story_image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="swiper-slide">
                                                            <img class="lazyload img-fluid"
                                                                 data-src="<?php echo e(otherImage($story_image)); ?>"
                                                                 alt="Event Story Image">
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                                <div class="swiper-button-prev story-prev"></div>
                                                <div class="swiper-button-next story-next"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-10">
                        <div class="searchbar-module">
                            <form action="">
                                <div class="wraper-searchbar-module">
                                    <div class="field-searchbar-module text-field-searchbar-module">
                                        <input type="text" value="<?php echo e(isset($quick_search) ? $quick_search : ''); ?>"
                                               name="quick_search" autocomplete="off" placeholder="Keywords">
                                    </div>
                                    <div class="field-searchbar-module">
                                        <select id="main_cat" name="main_cat">
                                            <option value="all" selected>All Categories</option>
                                            <?php $__currentLoopData = $main_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(isset($main_cat)): ?>
                                                    <?php if($main_cat != 'all'): ?>
                                                        <option value="<?php echo e($cat->id); ?>"
                                                            <?php echo e($main_cat == $cat->id ? 'selected' : ''); ?>>
                                                            <?php echo e($cat->name); ?> </option>
                                                    <?php else: ?>
                                                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?>

                                                        </option>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="field-searchbar-module">
                                        <select id="sub_category" name="sub_category">
                                            <option value="all" selected>Subcategory</option>
                                            <?php if(isset($sub_category)): ?>
                                                <?php if($sub_category != 'all'): ?>
                                                    <?php $__currentLoopData = $sub_cat_search; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($cat->id); ?>"
                                                            <?php echo e($sub_category == $cat->id ? 'selected' : ''); ?>>
                                                            <?php echo e($cat->name); ?> </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="field-searchbar-module">
                                        <select name="location" name="location">
                                            <option value="all" selected>City</option>
                                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city => $city_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(isset($location)): ?>
                                                    <?php if($location != 'all'): ?>
                                                        <option value="<?php echo e($city_name); ?>"
                                                            <?php echo e(urldecode($location) == $city_name ? 'selected' : ''); ?>>
                                                            <?php echo e($city_name); ?> </option>
                                                    <?php else: ?>
                                                        <option value="<?php echo e($city_name); ?>"><?php echo e($city_name); ?></option>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <option value="<?php echo e($city_name); ?>"><?php echo e($city_name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="field-searchbar-module">
                                        <select>
                                            <option selected="">Area</option>
                                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($state->id); ?>">
                                                    <?php echo e(\Illuminate\Support\Str::limit($state->name, 15)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="field-searchbar-module">
                                        <select>
                                            <option selected="">0 KM</option>
                                            <option value="AED 100">AED 100</option>
                                        </select>
                                    </div>
                                    <div class="field-searchbar-module" id="datefilter">
                                        <i class="party-icon icon-calendar"></i>&nbsp;
                                        <span  class="text-muted small">Date</span>
                                        <input type="hidden" name="date_from" id="date_from"/>
                                        <input type="hidden" name="date_to" id="date_to"/>
                                    </div>
                                    
                                </div>

                                <button><i class="party-icon icon-search"></i></button>
                            </form>
                        </div>
                        <?php if($major_category->searchLinksBottom): ?>
                            <div class="category-tab-search">
                                <ul>
                                    <?php $__currentLoopData = $major_category->searchLinksBottom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bottomLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e($bottomLink->url); ?>" target="_blank" title="<?php echo e($bottomLink->link_name); ?>"><?php echo e($bottomLink->link_name); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-1">
                        <div class="stories btn-help-landing"><a href="#how-it-works" data-bs-toggle="modal" role="button"> <img src="/v2/images/job/need-help.svg"
                                                                                alt="Need Help"> <small>Need
                                    Help?</small></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="subcategory-module">
            <div class="swiper subcategory-module-slick">
                <div class="swiper-wrapper">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <a href="<?php echo e(route('event-list', ['category_slug' => $category->slug])); ?>"><i
                                    class="party-icon icon-search"></i>
                                <?php echo e(\Illuminate\Support\Str::limit($category->name, 15)); ?>

                                (<?php echo e($category->event_count); ?>)
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-10 offset-1">
                    <?php if($major_category->statistics): ?>
                        <div class="facts-module">
                            <ul>
                                <?php $__currentLoopData = $major_category->statistics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e(number_format($stats->stat_value, 0, '', ',')); ?>+ <small><?php echo e($stats->stat_name); ?></small></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="module-btn">
                        <div class="swiper module-btn-slick">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><a
                                        href="<?php echo e(route('user-signup', ['user_type' => 'profile'])); ?>"
                                        class="blue-module-btn"><img
                                            src="/v2/images/icons/register-job.svg" alt="Register Now"> Register Now</a>
                                </div>
                                <div class="swiper-slide"><a href="#how-it-works" data-bs-toggle="modal" role="button"
                                                             class="pink-module-btn"><img
                                            src="/v2/images/icons/how-it-works.png"
                                            class="icon-how-work" alt="How it Works"> How it Works?</a></div>
                                <div class="swiper-slide"><a href="<?php echo e(route('register-job-seeker')); ?>" class="gray-module-btn"><img
                                            src="/v2/images/icons/upload-resume.svg" alt="Upload Resume"> Upload Your CV
                                        / Resume</a></div>
                                <div class="swiper-slide"><a href="#add-your-module" data-bs-toggle="modal"
                                                             role="button" class="blue-module-btn"><img
                                            src="/v2/images/icons/add-your-module.png" alt="add-your-module"> Add Your
                                        Event for FREE</a></div>
                                <div class="swiper-slide"><a
                                        href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>"
                                        class="pink-module-btn"><img
                                            src="/v2/images/icons/become-partner.svg" alt="Become a Partner"> Become a
                                        Partner</a></div>
                            </div>
                        </div>

                        
                        <?php echo $__env->make('user.common.pop_modal', ['popup_title' => 'Event'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                    </div>
                    <div class="topcategory-module">
                        <div class="heading-module">
                            <h2>Top Events</h2>
                            <ul class="btn-heading pz-btn-heading">
                                <li>
                                    <a href="<?php echo e(route('publisher.event.create')); ?>" class="btn btn-primary">Add Listing</a>
                                    <a href="<?php echo e(route('event-list', ['all'])); ?>" class="btn btn-secondary">View All</a>
                                </li>
                            </ul>
                        </div>
                        <div class="topcategory-slick">
                            <?php echo $__env->make('user.common.topcategory-bg', ['categories' => $categoryForSidebar, 'url' => 'event-list'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                    
                    <?php echo $__env->make('user.common.dynamic-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="feature-module">
                        <div class="heading-module">
                            <h2>Find Events</h2>
                            <ul class="btn-heading">
                                
                                <li>
                                    <a href="<?php echo e(route('publisher.event.create')); ?>" class="btn btn-primary">Add Listing</a>
                                    <a href="<?php echo e(route('event-list', ['all'])); ?>" class="btn btn-secondary">View All</a>
                                </li>
                            </ul>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper feature-module-slick">
                                <div class="swiper-wrapper">
                                    <?php $__currentLoopData = $all_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="swiper-slide">
                                            <div class="itembox ib-event">
                                                <div class="imgeffect">
                                                    <a href="<?php echo e(route('event', $row->slug)); ?>">
                                                        <?php if($row->featureImage): ?>
                                                        <img
                                                            class="lazyload"
                                                            data-src="<?php echo e($row->getStoredImage($row->featureImage->image, 'feature_image')); ?>"
                                                            alt="<?php echo e(isset($row->featureImage->alt_texts) && isset($row->featureImage->alt_texts['en']) ? $row->featureImage->alt_texts['en'] : $row->featureImage->alt_text_en ?? $row->title); ?>">
                                                        <?php else: ?>
                                                            <img
                                                                class="lazyload"
                                                                data-src="/v2/images/image-placeholder.jpeg"
                                                                alt="<?php echo e($row->title); ?> - Feature Image">
                                                        <?php endif; ?>
                                                    </a>
                                                    <div class="ib-wishlist"><a href="javascript:void(0)"
                                                                                data-id="<?php echo e($row->id); ?>"
                                                                                data-major-category="2" class="wish_save_btn"><i
                                                                class="party-icon icon-heart"></i></a></div>
                                                    <div class="ib-date"><span><i class="party-icon icon-eye"></i>
                                                            <?php echo e($row->views); ?></span>
                                                        <span><?php echo \Carbon\Carbon::parse($row->created_at)->tz(config('app.timezone'))->ago(); ?></span>
                                                    </div>
                                                </div>
                                                <div class="itembox-info">
                                                    <h3><a href="#"><?php echo e($row->title); ?></a></h3>
                                                    <p class="ib-search-btn">
                                                        <?php if($row->subCategory): ?>
                                                            <a
                                                                href="<?php echo e(route('buy-and-sells')); ?>?sub_category_id=<?php echo e($row->subCategory->id); ?>"><i
                                                                    class="party-icon icon-search"></i><?php echo e(\Illuminate\Support\Str::limit($row->subCategory->name, 15)); ?>

                                                                (<?php echo e($row->subCategory->event->count()); ?>)
                                                            </a>
                                                        <?php endif; ?>
                                                    </p>
                                                    <ul>
                                                        <li><i class="party-icon icon-label"></i>
                                                            <?php echo e($row->event_capacity); ?> Max Capacity
                                                        </li>
                                                        <li><i class="party-icon icon-location"></i>
                                                            <?php echo e(\Illuminate\Support\Str::limit($row->location, 50)); ?>

                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="hover-itembox">
                                                    <div class="review-itembox"><span><img
                                                                src="/v2/images/icons/google.svg" alt="google"
                                                                ondragstart="return false"> <?php echo e($row->map_rating); ?> <i
                                                                class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                                class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                                class="fas fa-star"></i></span>
                                                        <p><?php echo e($row->map_review); ?> Reviews</p>
                                                    </div>
                                                    <div class="review-itembox"><span><img src="/v2/images/favicon.png"
                                                                                           alt="favicon"
                                                                                           ondragstart="return false">
                                                            <?php echo e(round($row->approvedReviews->avg('rating'), 1)); ?> <i class="fas fa-star"></i> <i
                                                                class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                                class="fas fa-star"></i> <i
                                                                class="fas fa-star"></i></span>
                                                        <p><?php echo e($row->approvedReviews->count()); ?> Reviews</p>
                                                    </div>
                                                    <div class="btn-itembox"><a href="<?php echo e(route('event', $row->slug)); ?>"
                                                                                class="btn btn-primary">View More</a>
                                                        <a href="#enquiry-modal" data-bs-toggle="modal"
                                                           data-id="<?php echo e($row->id); ?>" data-major-cat="2"
                                                           class="btn btn-secondary">Enquire Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev feature-module-prev"></div>
                                <div class="swiper-button-next feature-module-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="thumbox-wraper">
                        <div class="row">
                            <?php if($popular_links): ?>
                                <div class="col-sm-4">
                                    <div class="thumbox-module thumbox-bg4">
                                        <h3><a href="/events/view-listings/popular-events"><?php echo e($popular_links->link_title); ?></a></h3>
                                        <a href="/events/view-listings/popular-events">
                                            <img src="<?php echo e(!empty($popular_links->link_image) ? otherImage($popular_links->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                 alt="<?php echo e($popular_links->link_title); ?>"></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($trending_links): ?>
                                <div class="col-sm-4">
                                    <div class="thumbox-module thumbox-bg5">
                                        <h3><a href="/events/view-listings/trending-events"><?php echo e($trending_links->link_title); ?></a></h3>
                                        <a href="/events/view-listings/trending-events">
                                            <img src="<?php echo e(!empty($trending_links->link_image) ? otherImage($trending_links->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                 alt="<?php echo e($trending_links->link_title); ?>"></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($hot_links): ?>
                                <div class="col-sm-4">
                                    <div class="thumbox-module thumbox-bg6">
                                        <h3><a href="/events/view-listings/hot-events"><?php echo e($hot_links->link_title); ?></a></h3>
                                        <a href="/events/view-listings/hot-events">
                                            <img src="<?php echo e(!empty($hot_links->link_image) ? otherImage($hot_links->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                 alt="<?php echo e($hot_links->link_title); ?>"></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="briefbox-module">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2><?php echo $major_category->register_heading; ?></h2>
                                <p><?php echo $major_category->register_summary; ?></p>
                                <a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>"
                                   class="btn btn-primary">Register Now</a>
                            </div>
                            <div class="col-sm-6"> <img src="<?php echo e($major_category->register_image ? otherImage($major_category->register_image) : '/v2/images/job/job-seeker.jpg'); ?>" alt="<?php echo e($major_category->register_heading); ?>"> </div>
                        </div>
                    </div>
                    
                    <?php echo $__env->make('user.common.banner-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
                    <?php echo $__env->make('user.common.top-popular-search-tabs', ['route_name' => 'event-list', 'top_search_tab' => 'Top Search Events in UAE', 'popular_search_tab' => 'Popular Events Search in UAE'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="col-sm-1 text-center"><a href="#" class="sticky-body sticky-whats"> <i
                            class="fa-solid fa-plus"></i> What's New </a></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/v2/js/moment.min.js"></script>
    <script src="/v2/js/daterangepicker.js"></script>
    <script>
        $(function () {
            var startDate = moment().startOf('month');
            var endDate = moment().endOf('month');

            $('#datefilter').daterangepicker({
                /*autoUpdateInput: false,
                autoApply: true,
                "alwaysShowCalendars": true,*/
                startDate: startDate,
                endDate: endDate,
                showDropdowns: true,
                minYear: 2021,
                maxYear: parseInt(moment().format('YYYY'), 0),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1,
                        'month').endOf('month')],
                    'Next 3 Months': [moment().add(1, 'month').startOf('month'), moment().add(3,
                        'month').endOf('month')]
                }
            }, function (start, end) {
                $('#datefilter span.small, #datefilter small').html(start.format('MMM D, YYYY') + ' - ' + end.format(
                    'MMM D, YYYY'));
                $('#date_from').val(start.format('YYYY-MM-DD'));
                $('#date_to').val(end.format('YYYY-MM-DD'));
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            //cb(start, end);
            $('.fa-calendar').on('click', function (e) {
                e.preventDefault();
                $('input[name="datefilter"]').focus();
            });
            <?php if(isset($sub_cate_id)): ?>
            var id = "<?php echo e(request()->get('sub_cate_id')); ?>";
            $('#hidd_sub').val(id);
            $("#sub_btn").click();
            $(".sub_tag").click(function () {
                var id = $(this).attr('data-id');
                $('#hidd_sub').val(id);
                $("#sub_btn").click();
            })
            <?php endif; ?>
            $(".col-sm-3").slice(0, 10).show();
            $("body").on('click touchstart', '.load-more', function (e) {
                e.preventDefault();
                $(".col-sm-3:hidden").slice(0, 5).slideDown();
                if ($(".col-sm-3:hidden").length == 0) {
                    $(".load-more").css('visibility', 'hidden');
                }

            });
            $(".btn-show-more").click(function () {
                $(".hide-data").slideToggle("slow");
            });
            $("#sort_by").change(function () {
                $("#sortForm").submit()
            })
            $("#sortByMobile").change(function () {
                $("#sortFormMobile").submit()
            });
            $(".sub_tag3").click(function () {
                var id = $(this).attr('data-id');
                $('#hidd_sub3').val(id);
                $("#sub_btn3").click();
            })
            $(".sub_tag2").click(function () {
                var id = $(this).attr('data-id');
                $('#hidd_sub2').val(id);
                $("#sub_btn2").click();
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/event/index.blade.php ENDPATH**/ ?>