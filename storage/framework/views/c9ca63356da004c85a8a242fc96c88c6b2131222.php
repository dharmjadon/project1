
<?php $__env->startSection('meta'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="home-page">
        <div class="breadcrumb"> <img class="lazyload" data-src="/v2/images/bc.jpg" alt="bc">
            <div class="bc-caption">
                <h1>World's Largest Classifieds Website</h1>
                <p>Post unlimited classifieds free!</p>
            </div>
        </div>
        <div class="weather-status">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <ul>
                            <li><img class="lazyload" data-src="/v2/images/icons/location-2.svg" alt="location" ondragstart="return false"> Dubai (UAE)</li>
                            <li><img class="lazyload" data-src="/v2/images/icons/cloud.svg" alt="cloud" class="cloud-i" ondragstart="return false"> 28 0C</li>
                            <li><img class="lazyload" data-src="/v2/images/icons/km.svg" alt="km" class="km-i" ondragstart="return false"> 4.17 km / h</li>
                            <li><img class="lazyload" data-src="/v2/images/icons/fire.svg" alt="fire" ondragstart="return false"> 38 %</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-2"> <a href="#" class="sticky-body sticky-think"> <img class="lazyload" data-src="/v2/images/icons/think.svg" alt="think" ondragstart="return false"> <span>Tell us what you think! <small>Help to improve my finder</small></span> </a> </div>
                <div class="col-sm-8">
                    <div class="browse-categories">
                        <?php echo $__env->make('user.common.browse-category', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="find-latest">
                        <div class="swiper find-latest-slick">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="find-latest-item">
                                        <div class="imgeffect"><img class="lazyload" data-src="/v2/images/Mobile-App.jpg" alt="Mobile-App">
                                            <div class="find-latest-caption"><a href="#" class="btn">Read More <span><i
                                                            class="fa-solid fa-arrow-right"></i></span></a>
                                                <h3><a href="#">Download Our Mobile App</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="find-latest-item">
                                        <div class="imgeffect"><img class="lazyload" data-src="/v2/images/Find-Friends.jpg" alt="Find-Friends">
                                            <div class="find-latest-caption"><a href="#" class="btn">Read More <span><i
                                                            class="fa-solid fa-arrow-right"></i></span></a>
                                                <h3><a href="#">Find Friends</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="find-latest-item">
                                        <div class="imgeffect"><img class="lazyload" data-src="/v2/images/Find-Crypto.jpg" alt="Find-Crypto">
                                            <div class="find-latest-caption"><a href="#" class="btn">Read More <span><i
                                                            class="fa-solid fa-arrow-right"></i></span></a>
                                                <h3><a href="#">Find Crypto</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="attractions-fp">
                        <div class="heading-module">
                            <h2>Find Attractions</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper attractionsfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-attractions">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Attractions-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <h3><a href="#">Skydive</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-attractions">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Attractions-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <h3><a href="#">Desert Safari</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-attractions">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Attractions-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <h3><a href="#">Dubai Frame</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-attractions">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Attractions-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <h3><a href="#">Burj Khalifa</a></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-attractions">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Attractions-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <h3><a href="#">Burj Arab </a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev attractionsfp-prev"></div>
                                <div class="swiper-button-next attractionsfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="property-fp">
                        <div class="heading-module">
                            <h2>Find Property</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper propertyfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-property">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Property-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Downtown</a></h3>
                                                <h4><small>AED</small> 1,499,999</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-property">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Property-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">The Palm Jumeirah</a></h3>
                                                <h4><small>AED</small> 1,499,999</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-property">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Property-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Al Reem Island</a></h3>
                                                <h4><small>AED</small> 1,499,999</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-property">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Property-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Business Bay</a></h3>
                                                <h4><small>AED</small> 1,499,999</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-property">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Property-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">JBR</a></h3>
                                                <h4><small>AED</small> 1,499,999</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev propertyfp-prev"></div>
                                <div class="swiper-button-next propertyfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="motors-fp">
                        <div class="heading-module">
                            <h2>Find Motors</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper motorsfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-motors">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/motors-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Nissan Altima</a></h3>
                                                <h4><small>AED</small> 80,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-motors">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/motors-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Toyota Camry</a></h3>
                                                <h4><small>AED</small> 90,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-motors">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/motors-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Ford Fusion</a></h3>
                                                <h4><small>AED</small> 150,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-motors">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/motors-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">BMW X6</a></h3>
                                                <h4><small>AED</small> 200,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-motors">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/motors-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Honda Civic</a></h3>
                                                <h4><small>AED</small> 90,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev motorsfp-prev"></div>
                                <div class="swiper-button-next motorsfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="buysell-fp">
                        <div class="heading-module">
                            <h2>Find Buy & Sell</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper buysellfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-buysell">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Buy-and-sell-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">iPhone 13 Pro Max</a></h3>
                                                <h4><small>AED</small> 4,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-buysell">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Buy-and-sell-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Guitar</a></h3>
                                                <h4><small>AED</small> 2,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-buysell">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Buy-and-sell-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Laptop Macbook pro</a></h3>
                                                <h4><small>AED</small> 8,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-buysell">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Buy-and-sell-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Dining Table</a></h3>
                                                <h4><small>AED</small> 3,000</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-buysell">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Buy-and-sell-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Honda Civic</a></h3>
                                                <h4><small>AED</small> 1,500</h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev buysellfp-prev"></div>
                                <div class="swiper-button-next buysellfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="jobs-fp">
                        <div class="heading-module">
                            <h2>Find Jobs</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper jobsfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-jobs">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/jobs-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Marketing Manager</a></h3>
                                                <p>Full-Time, Dubai</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-jobs">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/jobs-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Waiter</a></h3>
                                                <p>Part-Time, Dubai</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-jobs">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/jobs-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Teacher</a></h3>
                                                <p>Freelance, Dubai</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-jobs">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/jobs-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Art Director</a></h3>
                                                <p>Full-Time, Dubai</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-jobs">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/jobs-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Civil Engineer</a></h3>
                                                <p>Part-Time, Dubai</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev jobsfp-prev"></div>
                                <div class="swiper-button-next jobsfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="talents-fp">
                        <div class="heading-module">
                            <h2>Find Talents</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper talentsfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-talents">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/talents-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Dancers</a></h3>
                                                <p>Full-Time, Dubai
                                                    </h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-talents">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Talents-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Hostesses</a></h3>
                                                <p>Part-Time, Dubai
                                                    </h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-talents">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Talents-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Violinists</a></h3>
                                                <p>Freelance, Dubai
                                                    </h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-talents">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Talents-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Belly Dancers</a></h3>
                                                <p>Full-Time, Dubai
                                                    </h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-talents">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Talents-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Drummers</a></h3>
                                                <p>Part-Time, Dubai
                                                    </h4>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev talentsfp-prev"></div>
                                <div class="swiper-button-next talentsfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="registration-profile"> <img class="lazyload" data-src="/v2/images/free-registration.jpg" alt="free-registration">
                        <div class="caption-registration-profile">
                            <h2>Find Clients for Your Business, Free Online Marketing</h2>
                            <p>List your business & profile now, get free business!</p>
                            <a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>" class="btn btn-primary">Free Registration</a> </div>
                    </div>
                    <div class="module-list-fp">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Education.jpg" alt="Education">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('educations')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('educations')); ?>">Find Education</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/It.jpg" alt="it">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('it')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('it')); ?>">Find I.T.</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Venues.jpg" alt="Venues">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('venues')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('venues')); ?>">Find Venues</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Events.jpg" alt="Events">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('events')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('events')); ?>">Find Events</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Tickets.jpg" alt="Tickets">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('tickets')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('tickets')); ?>">Find Tickets</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Attractions.jpg" alt="Attractions">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('attractions')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('attractions')); ?>">Find Attractions</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Influencers.jpg" alt="Influencers">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('influencers')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('influencers')); ?>">Find Influencers</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Crypto.jpg" alt="Crypto">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('cryptocoin')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('cryptocoin')); ?>">Find Crypto</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Booking.jpg" alt="Booking">
                                    <div class="caption-module-fp"> <a href="#" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="#">Find Booking</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="imgeffect"> <img class="lazyload" data-src="/v2/images/module-thumb/Giveaways.jpg" alt="Giveaways">
                                    <div class="caption-module-fp"> <a href="<?php echo e(route('give-away')); ?>" class="btn">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                                        <h3><a href="<?php echo e(route('give-away')); ?>">Find Giveaways</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="registration-profile add-profile"> <img class="lazyload" data-src="/v2/images/business-profile.jpg" alt="business-profile">
                        <div class="caption-registration-profile">
                            <h2>Wait! Didn't find? What you were looking for?</h2>
                            <a href="#" class="btn btn-primary">Add Business / Profile</a> </div>
                    </div>
                    <div class="news-fp">
                        <div class="heading-module">
                            <h2>Find News</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-secondary">View All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper newsfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-news">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/news-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Qatar World Cup 2022</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-news">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/News-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Govinda and his wife</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-news">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/News-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Ras Al Khaimah Police</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-news">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/News-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">DP World container</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-news">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/News-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Dubai-born lebanese</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev newsfp-prev"></div>
                                <div class="swiper-button-next newsfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="recommendation-fp">
                        <div class="heading-module">
                            <h2>Find Recommendation</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Recommendation</a> <a href="#"
                                                                                                                   class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper recommendationfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-recommendation">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Recommendation-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Headline</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-recommendation">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Recommendation-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Headline</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-recommendation">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Recommendation-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Headline</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-recommendation">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Recommendation-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Headline</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-recommendation">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Recommendation-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Headline</a></h3>
                                                <p>Lorem ipsum dolor sit ame serect...</p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev recommendationfp-prev"></div>
                                <div class="swiper-button-next recommendationfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-fp">
                        <div class="heading-module">
                            <h2>Find Gallery</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-secondary">View All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper galleryfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-gallery">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Gallery-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-gallery">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Gallery-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-gallery">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Gallery-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-gallery">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Gallery-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-gallery">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/Gallery-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev galleryfp-prev"></div>
                                <div class="swiper-button-next galleryfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="join-fp">
                        <div class="heading-module">
                            <h2>Just Joined My Finder</h2>
                            <div class="btn-heading"><a href="#" class="btn btn-primary">Add Listing</a> <a href="#"
                                                                                                            class="btn btn-secondary">View
                                    All</a></div>
                        </div>
                        <div class="swiper-slick">
                            <div class="swiper joinfp-slick">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="itembox ib-join">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/join-1.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Noor Tour & Travel</a></h3>
                                                <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                                                <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                                        Tourism</a></p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-join">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/join-2.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Noor Tour & Travel</a></h3>
                                                <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                                                <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                                        Tourism</a></p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-join">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/join-3.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Noor Tour & Travel</a></h3>
                                                <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                                                <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                                        Tourism</a></p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-join">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/join-4.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Noor Tour & Travel</a></h3>
                                                <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                                                <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                                        Tourism</a></p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="itembox ib-join">
                                            <div class="imgeffect"><a href="#"><img class="lazyload" data-src="/v2/images/module/join-5.jpg"
                                                                                    alt="demo"></a>
                                                <div class="ib-wishlist"><a href="#"><i
                                                            class="party-icon icon-heart"></i></a></div>
                                                <div class="ib-date"><span><i class="party-icon icon-eye"></i> 2445</span>
                                                    <span>1 Day</span></div>
                                            </div>
                                            <div class="itembox-info">
                                                <h3><a href="#">Noor Tour & Travel</a></h3>
                                                <p><i class="fa-solid fa-location-dot"></i>Mall of Emirates</p>
                                                <p class="ib-search-btn"><a href=""><i class="party-icon icon-search"></i>
                                                        Tourism</a></p>
                                            </div>
                                            <div class="hover-itembox">
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/icons/google.svg"
                                                                                       alt="google"
                                                                                       ondragstart="return false"> 3.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>7823 Reviews</p>
                                                </div>
                                                <div class="review-itembox"><span><img class="lazyload" data-src="/v2/images/favicon.png"
                                                                                       alt="favicon"
                                                                                       ondragstart="return false"> 4.5 <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                                                            class="fas fa-star"></i></span>
                                                    <p>873 Reviews</p>
                                                </div>
                                                <div class="btn-itembox"><a href="#" class="btn btn-primary">View More</a>
                                                    <a href="#enquiry-modal"  data-bs-toggle="modal" data-id="0" class="btn btn-secondary">Enquire Now</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-arrow">
                                <div class="swiper-button-prev joinfp-prev"></div>
                                <div class="swiper-button-next joinfp-next"></div>
                            </div>
                        </div>
                    </div>
                    <div class="support-contact">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="item-support-contact email-support-contact">
                                    <h3>Can't find what you are looking for?</h3>
                                    <ul>
                                        <li><a href="#"><i class="party-icon icon-chat-2"></i> Chat with us</a></li>
                                        <li><a href="#"><i class="party-icon icon-headphone"></i> Call us</a></li>
                                        <li><a href="#"><i class="party-icon icon-email"></i> Email us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="item-support-contact smo-support-contact">
                                    <h3>Connect With Us</h3>
                                    <ul>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/instagram.svg" alt="instagram" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/tiktok.svg" alt="register-tiktok" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/facebook.svg" alt="facebook" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/youtube.svg" alt="youtube" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/twitter.svg" alt="twitter" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/snapchat.svg" alt="snapchat" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/linkedin.svg" alt="linkedin" ondragstart="return false"></a></li>
                                        <li><a href="#"><img class="lazyload" data-src="/v2/images/icons/telegram.svg" alt="telegram" ondragstart="return false"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="item-support-contact chat-support-contact">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3>Quations?</h3>
                                            <p>Chat with our experts for answers.</p>
                                            <h4><a href="#">Not now</a> <a href="#" class="btn btn-primary">Get started</a> </h4>
                                        </div>
                                        <div class="col-sm-4"> <img class="lazyload" data-src="/v2/images/chat-expert.jpg" alt="chat-expert"> <a href="#"><i class="party-icon icon-chat"></i></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 text-center"> <a href="#" class="sticky-body sticky-whats"> <i class="fa-solid fa-plus"></i> What's New </a> </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google.maps_key')); ?>&libraries=places"></script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                $('#autocomplete').val(place.address_components[0].long_name)
                // place.address_components
            });
        }
    </script>
    <script>
        $(function(){
            $("#myModal").modal('show');
            $(".btn-show-more").click(function(){
                $(".hide-data").slideToggle("slow");
            });
        });

    </script>
    <script>
        function displayLocation(latitude, longitude) {
            var request = new XMLHttpRequest();

            var method = 'GET';
            var url =
                'https://maps.googleapis.com/maps/api/geocode/json?key=<?php echo e(config('services.google.maps_key')); ?>&latlng=' +
                latitude + ',' + longitude + '&sensor=true';
            var async = true;

            request.open(method, url, async);
            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    var data = JSON.parse(request.responseText);
                    var address = data.results[0];
                    var city = address.address_components[3].long_name;
                    $('#autocomplete').val(city)
                }
            };
            request.send();
        };

        var successCallback = function(position) {
            var x = position.coords.latitude;
            var y = position.coords.longitude;
            displayLocation(x, y);
        };

        var errorCallback = function(error) {
            var errorMessage = 'Unknown error';
            switch (error.code) {
                case 1:
                    errorMessage = 'Permission denied';
                    break;
                case 2:
                    errorMessage = 'Position unavailable';
                    break;
                case 3:
                    errorMessage = 'Timeout';
                    break;
            }
            document.write(errorMessage);
        };

        var options = {
            enableHighAccuracy: true,
            timeout: 1000,
            maximumAge: 0
        };

        $(".location-select").click(function() {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback, options);
        })
    </script>
    <script>
        $(document).ready(function(e) {
            $(".sub_tag1").click(function() {
                var id = $(this).attr('data-id');
                $('#hidd_sub1').val(id);
                $("#sub_btn1").click();
            })
        });
    </script>
    <script>
        $(document).ready(function(e) {
            $(".sub_tag2").click(function() {
                var id = $(this).attr('data-id');
                $('#hidd_sub2').val(id);
                $("#sub_btn2").click();
            })
        });
    </script>
    
    <script>
        $(document).on("click", "#modalBtn", function () {
            var path = "<?php echo e(config('app.upload_other_path')); ?>"
            var myBookId = $(this).data('id');
            var activeImg = $(this).data('active-img');
            var description = $(this).data('description');
            var images = $(this).data('images');
            $(".active-img img").attr("src", activeImg);
            $("#description").html(description);
            var array = images.split(",");
            var imageArray = array.slice(0, -1);
            //console.log(imageArray)
            $(imageArray).each(function(){
                $(".carousel-inner").append('<div class="carousel-item"><img class="lazyload" data-src="' + path + '/' + val + '" alt="Question"></div>');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/home/index.blade.php ENDPATH**/ ?>