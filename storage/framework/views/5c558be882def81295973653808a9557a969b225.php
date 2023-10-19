
<?php $__env->startSection('page_title', $major_category->meta_title); ?>
<?php $__env->startSection('meta_description', $major_category->meta_description); ?>
<?php $__env->startSection('meta_keywords', $major_category->meta_tags); ?>
<?php $__env->startSection('og_title', $major_category->meta_title); ?>
<?php $__env->startSection('og_description', $major_category->meta_description); ?>
<?php $__env->startSection('og_image', !empty($major_category->banner_images) ? otherImage($major_category->banner_images[0]['image']) :
    '/user-asset/images/logo.png'); ?>
<?php $__env->startSection('og_type', 'Webpage'); ?>
<?php $__env->startSection('canonical_url', route('buy-and-sells')); ?>
<?php $__env->startSection('meta'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/module.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/module-more.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/static.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/landing-module.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/slick.css"/>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="buysell-landingp module-landingp">

        <div class="categories-module">
            <ul>
                <li class="more-categories-module">
                    <a href="<?php echo e(route('buy-sell-list', ['all'])); ?>" title="View All Buy & Sell Products">View All</a>
                </li>
                <?php if($categoryForSidebar): ?>
                    <?php $__currentLoopData = $categoryForSidebar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catKey => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li <?php if($catKey > 7): ?> class="d-none more-category" <?php endif; ?>>

                            <a
                                href="<?php echo e($category->buysells_count ? route('buy-sell-list', ['category_slug' => $category->slug]) : '#'); ?>">

                                <img class="lazyload img-fluid" data-src="<?php echo e($category->storedImage($category->icon)); ?>"
                                     width="75" alt="buysell - <?php echo e($category->name); ?>"></a>

                            <h3><a
                                    href="<?php echo e($category->buysells_count ? route('buy-sell-list', ['category_slug' => $category->slug]) : '#'); ?>"><?php echo e($category->name); ?></a>
                            </h3>

                            <p><?php echo e($category->buysells_count ?? ''); ?></p>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(isset($catKey) && $catKey >= 7): ?>
                        <li class="more-categories-module">
                            <a id="icon-more-categories" data-tot-count="<?php echo e($categoryForSidebar->count()); ?>">
                                <i class="party-icon icon-more"></i>
                                <span>+<?php echo e($categoryForSidebar->count() - 8); ?></span>
                                More
                            </a>
                        </li>
                    <?php endif; ?>
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
                                    <img src="<?php echo e(otherImage($story_images[0])); ?>" alt="Stories">
                                    <small>Stories</small>
                                </a>
                            <?php else: ?>
                                <a href="#story-modal" data-bs-toggle="modal" role="button">
                                    <img class="lazyload" data-src="/v2/images/image-placeholder.jpeg" alt="stories">
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
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        <div class="modal-body">
                                            <div class="swiper story-slick">
                                                <div class="swiper-wrapper">
                                                    <?php $__currentLoopData = $story_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story_image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="swiper-slide"><img
                                                                src="<?php echo e(otherImage($story_image)); ?>"
                                                                alt="Buysell Story Image"></div>
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
                            <form method="">
                                <div class="wraper-searchbar-module">

                                    <div class="field-searchbar-module text-field-searchbar-module">
                                        <input type="text"
                                               value="<?php echo e(isset($quick_search) ? $quick_search : ''); ?>"
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
                                        <select name="area" id="area">
                                            <option value="all" selected>Area</option>
                                            <?php if($states): ?>
                                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($state); ?>">
                                                        <?php echo e(\Illuminate\Support\Str::limit($state, 15)); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="field-searchbar-module">
                                        <select>
                                            <option selected="">0 KM</option>
                                            <option value="0 KM">0 KM</option>
                                        </select>
                                    </div>


                                </div>
                                <button><i class="party-icon icon-search"></i></button>
                            </form>
                        </div>
                        <?php if($major_category->searchLinksBottom): ?>
                            <div class="category-tab-search">
                                <ul>
                                    <?php $__currentLoopData = $major_category->searchLinksBottom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bottomLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="<?php echo e($bottomLink->url); ?>" target="_blank"
                                               title="<?php echo e($bottomLink->link_name); ?>"><?php echo e($bottomLink->link_name); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-1">
                        <div class="stories btn-help-landing"><a href="#how-it-works" data-bs-toggle="modal"
                                                                 role="button"> <img src="/v2/images/job/need-help.svg"
                                                                                     alt="Need Help"> <small>Need
                                    Help?</small></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="subcategory-module">
            <div class="swiper subcategory-module-slick">
                <div class="swiper-wrapper">
                    <?php $__currentLoopData = $main_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <a href="<?php echo e(route('buy-sell-list', ['category_slug' => $category->slug])); ?>"><i
                                    class="party-icon icon-search"></i>
                                <?php echo e(\Illuminate\Support\Str::limit($category->name, 15)); ?>

                                (<?php echo e($category->buysells_count); ?>)
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
                                    <li><?php echo e(number_format($stats->stat_value, 0, '', ',')); ?>+
                                        <small><?php echo e($stats->stat_name); ?></small></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="module-btn">
                        <div class="swiper module-btn-slick">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <a href="<?php echo e(route('user-signup', ['user_type' => 'profile'])); ?>"
                                       class="blue-module-btn"><img src="/v2/images/icons/register-job.svg"
                                                                    alt="Register With Us">Register With Us!</a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="#how-it-works" data-bs-toggle="modal" role="button"
                                       class="pink-module-btn"><img src="/v2/images/icons/how-it-works.png"
                                                                    class="icon-how-work" alt="How it Works">How it
                                        Works?</a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="#" class="blue-module-btn"><img src="/v2/images/icons/upload-resume.svg"
                                                                             alt="Advertise With Us">Advertise With Us!</a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="<?php echo e(route('book_table')); ?>" class="gray-module-btn"><img
                                            src="/v2/images/icons/book-table.svg" alt="Book a Table"> Book a Table</a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="<?php echo e(route('user-signup', ['user_type' => 'profile'])); ?>"
                                       class="pink-module-btn"><img src="/v2/images/icons/become-partner.svg"
                                                                    alt="Become a Partner"> Become a Partner</a>
                                </div>
                            </div>
                        </div>
                        
                        <?php echo $__env->make('user.common.pop_modal', ['popup_title' => 'Buy Sell'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                    </div>

                    <div class="topcategory-module">
                        <div class="heading-module">
                            <h2>Top Buy & Sell Products</h2>
                            <ul class="btn-heading pz-btn-heading">
                                <li><a href="<?php echo e(route('publisher.buy-sell.create')); ?>" class="btn btn-primary">Add
                                        Listing</a>
                                    <a href="<?php echo e(route('buy-sell-list', ['all'])); ?>" class="btn btn-secondary">View
                                        All</a>
                                </li>
                            </ul>
                        </div>

                        <div class="topcategory-slick">
                            <?php echo $__env->make('user.common.topcategory-bg', [
                                'categories' => $categoryForSidebar,
                                'url' => 'buy-sell-list',
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="thumbox-wraper">
                            <div class="row">
                                <?php if($deals_link): ?>
                                    <div class="col-sm-4">
                                        <div class="thumbox-module thumbox-bg1">
                                            <h3>
                                                <a href="/buy-and-sells/view-listings/<?php echo e($deals_link->slug); ?>"><?php echo e($deals_link->link_title); ?></a>
                                            </h3>
                                            <a href="/buy-and-sells/view-listings/<?php echo e($deals_link->slug); ?>">
                                                <img
                                                    src="<?php echo e(!empty($deals_link->link_image) ? otherImage($deals_link->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                    alt="<?php echo e($deals_link->link_title); ?>"></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if($verified_suppliers_link): ?>
                                    <div class="col-sm-4">
                                        <div class="thumbox-module thumbox-bg2">
                                            <h3>
                                                <a href="/buy-and-sells/view-listings/verified-suppliers"><?php echo e($verified_suppliers_link->link_title); ?></a>
                                            </h3>
                                            <a href="/buy-and-sells/view-listings/verified-suppliers">
                                                <img
                                                    src="<?php echo e(!empty($verified_suppliers_link->link_image) ? otherImage($verified_suppliers_link->link_image) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                    alt="<?php echo e($verified_suppliers_link->link_title); ?>"></a>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-sm-4">
                                    <div class="thumbox-module thumbox-bg3">
                                        <h3><a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>">Register
                                                New Supplier</a></h3>
                                        <a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>"><img
                                                src="/v2/images/fintech.png" alt="Register New Supplier"></a></div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="thumbox-module thumbox-bg4">
                                        <h3><a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>">Register
                                                Your Items for Sale</a></h3>
                                        <a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>"><img
                                                src="/v2/images/infrastructure-and-smart-cities.png"
                                                alt="Register Your Items for Sale"></a>
                                    </div>
                                </div>
                                <?php echo $__env->make('user.common.dynamic-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                        <div class="feature-module">
                            <div class="heading-module">
                                <h2>Buy & Sell Products</h2>
                                <ul class="btn-heading">
                                    <li>
                                        <?php if($feature_buysells->count()): ?>
                                            <a href="#featured_buysell" class="btn btn-outline-secondary">Feature</a>
                                        <?php endif; ?>
                                        <?php if($popular_buysells->count()): ?>
                                            <a href="#popular_buysell" class="btn btn-secondary">Popular</a>
                                        <?php endif; ?>
                                    </li>
                                    <li><a href="<?php echo e(route('publisher.buy-sell.create')); ?>" class="btn btn-primary">Add
                                            Listing</a>
                                        <a href="<?php echo e(route('buy-sell-list', ['all'])); ?>" class="btn btn-secondary">View
                                            All</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="swiper-slick">
                                <div class="swiper feature-module-slick">
                                    <div class="swiper-wrapper">
                                        <?php if($all_buysells): ?>
                                            <?php $__currentLoopData = $all_buysells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="swiper-slide">
                                                    <div class="itembox ib-buysell">
                                                        <div class="imgeffect"><a
                                                                href="<?php echo e(route('buy-and-sell', $row->slug)); ?>"><img
                                                                    class="lazyload"
                                                                    data-src="<?php echo e($row->featureImage ? $row->getStoredImage($row->featureImage->image, $row->featureImage->image_type) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                                    alt="<?php echo e($row->featureImage->alt_texts['en'] ?? $row->title . ' Feature Image'); ?>"></a>
                                                            <div class="ib-wishlist"><a href="javascript:void(0)"
                                                                                        id="<?php echo e($row->id); ?>"
                                                                                        data-major-category="3"
                                                                                        class="wish_save_btn"><i
                                                                        class="party-icon icon-heart"></i></a></div>
                                                            <div class="ib-date"><span><i
                                                                        class="party-icon icon-eye"></i>
                                                                    <?php echo e($row->view_count); ?></span>
                                                                <span><?php echo \Carbon\Carbon::parse($row->created_at)->tz(config('app.timezone'))->ago(); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="itembox-info">
                                                            <h3><a href="#"><?php echo e($row->product_name); ?></a></h3>
                                                            <p class="ib-search-btn">
                                                                <?php if($row->subCategory): ?>
                                                                    <a
                                                                        href="<?php echo e(route('buy-and-sells')); ?>?sub_category_id=<?php echo e($row->subCategory->id); ?>"><i
                                                                            class="party-icon icon-search"></i><?php echo e(\Illuminate\Support\Str::limit($row->subCategory->name, 15)); ?>

                                                                        (<?php echo e($row->subCategory->buysells->count()); ?>)
                                                                    </a>
                                                                <?php endif; ?>
                                                            </p>
                                                            <ul>
                                                                <li><img src="/v2/images/icons/aed.svg" alt="aed"
                                                                         class="icon-aed"> AED <?php echo e($row->price); ?></li>
                                                                <li>
                                                                    <i class="party-icon icon-location"></i> <?php echo e(\Illuminate\Support\Str::limit($row->location, 50)); ?>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="hover-itembox">
                                                            <div class="review-itembox"><span><img
                                                                        src="/v2/images/icons/google.svg" alt="google"
                                                                        ondragstart="return false"> <?php echo e($row->map_rating); ?>

                                                                    <i class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i></span>
                                                                <p><?php echo e($row->map_review); ?> Reviews</p>
                                                            </div>
                                                            <div class="review-itembox"><span><img
                                                                        src="/v2/images/favicon.png" alt="favicon"
                                                                        ondragstart="return false">
                                                                    <?php echo e(round($row->approvedReviews->avg('rating'), 1)); ?> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i></span>
                                                                <p><?php echo e($row->approvedReviews->count()); ?> Reviews</p>
                                                            </div>
                                                            <div class="btn-itembox"><a
                                                                    href="<?php echo e(route('buy-and-sell', $row->slug)); ?>"
                                                                    class="btn btn-primary">View More</a>
                                                                <a href="#enquiry-modal" data-bs-toggle="modal"
                                                                   data-id="<?php echo e($row->id); ?>" data-major-cat="3"
                                                                   class="btn btn-secondary">Enquire Now</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="swiper-arrow">
                                    <div class="swiper-button-prev feature-module-prev"></div>
                                    <div class="swiper-button-next feature-module-next"></div>
                                </div>
                            </div>
                        </div>

                        <?php echo $__env->make('user.common.dynamic-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="briefbox-module">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2>Sell On My Finder</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                                        euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
                                    <a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>"
                                       class="btn btn-primary">Register Now</a>
                                </div>
                                <div class="col-sm-6">
                                    <img src="/v2/images/it-landing.jpg" alt="it">
                                </div>
                            </div>
                        </div>
                        
                        <?php echo $__env->make('user.common.banner-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <?php if($feature_buysells->count()): ?>
                            <div class="feature-module" id="feature_buysell">
                                <div class="heading-module">
                                    <h2>Featured Buy & Sell Products</h2>
                                    <ul class="btn-heading">
                                        <li>
                                            <?php if($feature_buysells->count()): ?>
                                                <a href="#featured_buysell"
                                                   class="btn btn-outline-primary">Feature</a>
                                            <?php endif; ?>
                                            <?php if($popular_buysells->count()): ?>
                                                <a href="#popular_buysell" class="btn btn-secondary">Popular</a>
                                            <?php endif; ?>
                                        </li>
                                        <li><a href="<?php echo e(route('publisher.buy-sell.create')); ?>"
                                               class="btn btn-primary">Add
                                                Listing</a>
                                            <a href="<?php echo e(route('buy-sell-list', ['all'])); ?>"
                                               class="btn btn-secondary">View
                                                All</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="swiper-slick">
                                    <div class="swiper feature-module-slick">
                                        <div class="swiper-wrapper">

                                            <?php $__currentLoopData = $feature_buysells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="swiper-slide">
                                                    <div class="itembox ib-buysell">
                                                        <div class="imgeffect"><a
                                                                href="<?php echo e(route('buy-and-sell', $row->slug)); ?>">
                                                                <img class="lazyload"
                                                                     data-src="<?php echo e($row->featureImage ? $row->getStoredImage($row->featureImage->image, $row->featureImage->image_type) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                                     alt="<?php echo e($row->featureImage->alt_texts['en'] ?? $row->title . ' Feature Image'); ?>"></a>
                                                            <div class="ib-wishlist"><a href="javascript:void(0)"
                                                                                        id="<?php echo e($row->id); ?>"
                                                                                        data-major-category="3"
                                                                                        class="wish_save_btn"><i
                                                                        class="party-icon icon-heart"></i></a></div>
                                                            <div class="ib-date"><span><i
                                                                        class="party-icon icon-eye"></i>
                                        <?php echo e($row->view_count); ?></span>
                                                                <span><?php echo \Carbon\Carbon::parse($row->created_at)->tz(config('app.timezone'))->ago(); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="itembox-info">
                                                            <h3><a href="#"><?php echo e($row->product_name); ?></a></h3>
                                                            <p class="ib-search-btn">
                                                                <?php if($row->subCategory): ?>
                                                                    <a
                                                                        href="<?php echo e(route('buy-and-sells')); ?>?sub_category_id=<?php echo e($row->subCategory->id); ?>"><i
                                                                            class="party-icon icon-search"></i><?php echo e(\Illuminate\Support\Str::limit($row->subCategory->name, 15)); ?>

                                                                        (<?php echo e($row->subCategory->buysells->count()); ?>)
                                                                    </a>
                                                                <?php endif; ?>
                                                            </p>
                                                            <ul>
                                                                <li><img src="/v2/images/icons/aed.svg" alt="aed"
                                                                         class="icon-aed"> AED <?php echo e($row->price); ?>

                                                                </li>
                                                                <li>
                                                                    <i class="party-icon icon-location"></i> <?php echo e(\Illuminate\Support\Str::limit($row->location, 50)); ?>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="hover-itembox">
                                                            <div class="review-itembox"><span><img
                                                                        src="/v2/images/icons/google.svg"
                                                                        alt="google"
                                                                        ondragstart="return false"> <?php echo e($row->map_rating); ?>

                                        <i class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i></span>
                                                                <p><?php echo e(round($row->approvedReviews->avg('rating'), 1)); ?>

                                                                    Reviews</p>
                                                            </div>
                                                            <div class="review-itembox"><span><img
                                                                        src="/v2/images/favicon.png" alt="favicon"
                                                                        ondragstart="return false">
                                            class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i></span>
                                                                <p><?php echo e($row->approvedReviews->count()); ?> Reviews</p>
                                                            </div>
                                                            <div class="btn-itembox"><a
                                                                    href="<?php echo e(route('buy-and-sell', $row->slug)); ?>"
                                                                    class="btn btn-primary">View More</a>
                                                                <a href="#enquiry-modal" data-bs-toggle="modal"
                                                                   data-id="<?php echo e($row->id); ?>" data-major-cat="3"
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
                        <?php endif; ?>
                        <?php if($popular_buysells->count()): ?>
                            <div class="popular-module" id="popular_buysell">
                                <div class="heading-module">
                                    <h2>Popular Buy & Sell Products</h2>
                                    <ul class="btn-heading">
                                        <li>
                                            <?php if($feature_buysells->count()): ?>
                                                <a href="#featured_buysell"
                                                   class="btn btn-outline-secondary">Feature</a>
                                            <?php endif; ?>
                                            <?php if($popular_buysells->count()): ?>
                                                <a href="#popular_buysell" class="btn btn-primary">Popular</a>
                                            <?php endif; ?>
                                        </li>
                                        <li><a href="<?php echo e(route('publisher.buy-sell.create')); ?>"
                                               class="btn btn-primary">Add
                                                Listing</a>
                                            <a href="<?php echo e(route('buy-sell-list', ['all'])); ?>"
                                               class="btn btn-secondary">View
                                                All</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="swiper-slick">
                                    <div class="swiper feature-module-slick">
                                        <div class="swiper-wrapper">
                                            <?php $__currentLoopData = $popular_buysells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="swiper-slide">
                                                    <div class="itembox ib-buysell">
                                                        <div class="imgeffect"><a
                                                                href="<?php echo e(route('buy-and-sell', $row->slug)); ?>"><img
                                                                    class="lazyload"
                                                                    data-src="<?php echo e($row->featureImage ? $row->getStoredImage($row->featureImage->image, $row->featureImage->image_type) : '/v2/images/image-placeholder.jpeg'); ?>"
                                                                    alt="<?php echo e($row->featureImage->alt_texts['en'] ?? $row->title . ' Feature Image'); ?>"></a>
                                                            <div class="ib-wishlist"><a href="javascript:void(0)"
                                                                                        id="<?php echo e($row->id); ?>"
                                                                                        data-major-category="3"
                                                                                        class="wish_save_btn"><i
                                                                        class="party-icon icon-heart"></i></a></div>
                                                            <div class="ib-date"><span><i
                                                                        class="party-icon icon-eye"></i>
                                        <?php echo e($row->view_count); ?></span>
                                                                <span><?php echo \Carbon\Carbon::parse($row->created_at)->tz(config('app.timezone'))->ago(); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="itembox-info">
                                                            <h3><a href="#"><?php echo e($row->product_name); ?></a></h3>
                                                            <p class="ib-search-btn">
                                                                <?php if($row->subCategory): ?>
                                                                    <a
                                                                        href="<?php echo e(route('buy-and-sells')); ?>?sub_category_id=<?php echo e($row->subCategory->id); ?>"><i
                                                                            class="party-icon icon-search"></i><?php echo e(\Illuminate\Support\Str::limit($row->subCategory->name, 15)); ?>

                                                                        (<?php echo e($row->subCategory->buysells->count()); ?>)
                                                                    </a>
                                                                <?php endif; ?>
                                                            </p>
                                                            <ul>
                                                                <li><img src="/v2/images/icons/aed.svg" alt="aed"
                                                                         class="icon-aed"> AED <?php echo e($row->price); ?>

                                                                </li>
                                                                <li>
                                                                    <i class="party-icon icon-location"></i> <?php echo e(\Illuminate\Support\Str::limit($row->location, 50)); ?>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="hover-itembox">
                                                            <div class="review-itembox"><span><img
                                                                        src="/v2/images/icons/google.svg"
                                                                        alt="google"
                                                                        ondragstart="return false">
                                        <?php echo e($row->map_rating); ?>

                                        <i class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i></span>
                                                                <p><?php echo e($row->map_review); ?> Reviews</p>
                                                            </div>
                                                            <div class="review-itembox"><span><img
                                                                        src="/v2/images/favicon.png" alt="favicon"
                                                                        ondragstart="return false">
                                        <?php echo e(round($row->approvedReviews->avg('rating'), 1)); ?> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i> <i
                                                                        class="fas fa-star"></i></span>
                                                                <p><?php echo e($row->approvedReviews->count()); ?> Reviews</p>
                                                            </div>
                                                            <div class="btn-itembox"><a
                                                                    href="<?php echo e(route('buy-and-sell', $row->slug)); ?>"
                                                                    class="btn btn-primary">View More</a>
                                                                <a href="#enquiry-modal" data-bs-toggle="modal"
                                                                   data-id="<?php echo e($row->id); ?>" data-major-cat="3"
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
                        <?php endif; ?>
                        <?php echo $__env->make('user.common.top-popular-search-tabs', ['route_name' => 'buy-sell-list', 'top_search_tab' => 'Top Search Buy and Sells in UAE', 'popular_search_tab' => 'Popular Buy and Sells Search in UAE'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="col-sm-1 text-center"><a href="#" class="sticky-body sticky-whats"> <i
                            class="fa-solid fa-plus"></i> What's New </a></div>
            </div>
        </div>
        <?php $__env->stopSection(); ?>
        <?php $__env->startSection('scripts'); ?>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                $(function () {

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
                    })
                });
            </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/buysell/index.blade.php ENDPATH**/ ?>