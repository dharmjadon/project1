<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php if(config('app.env') === 'production'): ?>
        <meta name="google-site-verification" content="vZm0iHVoXVLEiy_JeCEe0_BEfYP3o9oDGFCnOcCzpcI"/>
    <?php endif; ?>
    <?php if(config('app.env') !== 'production'): ?>
        <meta name="robots" content="noindex"/>
    <?php endif; ?>
    <title><?php echo $__env->yieldContent('page_title', 'The Party Finder - Events Finder - Venues Finder'); ?> - My Finder</title>
    <meta name="description"
          content="<?php echo $__env->yieldContent('meta_description', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>">
    <meta name="keywords"
          content="<?php echo $__env->yieldContent('meta_keywords', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('page_title', 'The Party Finder - Events Finder - Venues Finder'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', config('app.url') . '/v2/images/image-placeholder.jpeg'); ?>">
    <meta property="og:description"
          content="<?php echo $__env->yieldContent('meta_description', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>">
    <meta property="og:type" content="<?php echo $__env->yieldContent('og_type', 'website'); ?>"/>
    <meta property="og:url" content="<?php echo $__env->yieldContent('canonical_url', config('app.url')); ?>">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('page_title', 'The Party Finder - Events Finder - Venues Finder'); ?>">
    <meta name="twitter:description"
          content="<?php echo $__env->yieldContent('meta_description', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>">
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical_url', config('app.url')); ?>">
    <!-- Styles -->
    <link rel="preconnect" href="//fonts.gstatic.com">
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//bootstrapcdn.com">
    <link rel="preconnect" href="//cloudflare.com">
    <link rel='dns-prefetch' href='//jquery.com'/>
    <link rel='dns-prefetch' href='//jsdelivr.net'/>
    <link rel='dns-prefetch' href='//www.google.com'/>
    <link rel="icon" href="/v2/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="/v2/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/swiper.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/bootstrap.min.css"/>

    <link rel="stylesheet" type="text/css" href="/v2/css/all.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/fontello.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/fontello-fp.css"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>"/>
    <?php echo $__env->yieldContent('meta'); ?>
    <link rel="stylesheet" type="text/css" href="/v2/css/custom.css"/>
    <?php echo RecaptchaV3::initJs(); ?>

    <?php if(config('app.env') === 'production'): ?>
        <!-- Google Tag Manager -->
        <!-- End Google Tag Manager -->
    <?php endif; ?>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body>
<DIV id="prepage"
     style="position: fixed; z-index: 999999999;filter:alpha(opacity=60); opacity:0.6; font-family:arial; font-size:16px; left:0px; top:0px; background-color: #ECEDEF; layer-background-color: #ECEDEF; height:100%; width:100%; display:none;">
    <TABLE width="100%" height="100%" align="center">
        <TR>
            <TD width="100%" align="center"><img class="lazyload" data-src="/v2/images/1.gif"
                                                 alt="Please wait..."/>
            </TD>
        </TR>
    </TABLE>
</DIV>
<div class="sticky-sidebar">
    <ul>
        <li><a href="<?php echo e(route('landing_home')); ?>"><i class="party-icon icon-home"></i> Home</a></li>
        <li><a href="#"><i class="party-icon icon-user-guide"></i> User Guide</a></li>
        <li><a href="#"><i class="party-icon icon-support"></i> Support</a></li>
        <li><a href="<?php echo e(route('faqs')); ?>"><i class="party-icon icon-faq"></i> FAQs</a></li>
        <?php if(auth()->guard()->guest()): ?>
            <li><a href="<?php echo e(route('user-signup', ['user_type' => 'profile'])); ?>"><i
                        class="party-icon icon-register"></i> Register with us</a></li>
        <?php endif; ?>
    </ul>
    <ul>
        <li><a href="#"><i class="fa-solid fa-question"></i> Help</a></li>
    </ul>
</div>
<div class="sticky-header">
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="header-wraper">
                        <div class="logo"><a href="/landing"> <img class="lazyload" data-src="/v2/images/logo.svg"
                                                                   alt="My Finder"/> </a></div>
                        <div class="search-header">
                            <form action="<?php echo e(route('search-result')); ?>" method="GET">
                                <div class="search-category"><i class="party-icon icon-category"></i>
                                    <select name="cid">
                                        <option selected>All</option>
                                        <option value="Buy & Sell">Buy & Sell</option>
                                        <option value="Motors">Motors</option>
                                        <option value="Property">Property</option>
                                        <option>Directory</option>
                                        <option>Venues</option>
                                        <option>Events</option>
                                        <option>Education</option>
                                    </select>
                                </div>
                                <div class="search-field search-looking">
                                    <input type="text" name="name" placeholder="I am looking for...">
                                    <img class="lazyload" data-src="/v2/images/icons/mic.svg" alt="mic"
                                         ondragstart="return false">
                                </div>
                                <div class="search-field search-near"><i class="party-icon icon-location"></i>
                                    <input type="text" name="location" id="autocomplete"
                                           placeholder="Near Me">
                                    <a href="javascript:void(0)" onClick="locate()"
                                       id="current_location_button"><i
                                            class="party-icon icon-location-mark"></i></a>
                                </div>
                                <div class="search-field search-km">
                                    <input type="text" name="radius" placeholder="10 KM">
                                </div>
                                <div class="search-field">
                                    <button type="submit"><i class="party-icon icon-search"></i> Search</button>
                                </div>
                            </form>
                        </div>
                        <div class="header-menu" role="navigation">
                            <ul>
                                <?php if(auth()->guard()->check()): ?>
                                    <?php
                                        $result = substr(Auth::user()->name, 0, 4);
                                        $auth = \Illuminate\Support\Facades\Auth::user();
                                    ?>
                                    <li class="dropdown">
                                        <a href="#"><?php echo e($auth->username ?? 'User'); ?>

                                            <i class="party-icon icon-arrow-right"></i>
                                        </a>
                                        <ul class="dropdown">
                                            <?php if($auth->user_type == '4'): ?>
                                                <li>
                                                    <a
                                                        href="<?php echo e(route('publisher.publisher_dashboard')); ?>"><?php echo e($result ?? 'Profile'); ?></a>
                                                </li>
                                            <?php elseif($auth->user_type == '3' || $auth->user_type == '2'): ?>
                                                <li>
                                                    <a
                                                        href="<?php echo e(url('client/applied-job')); ?>"><?php echo e($auth->name ?? 'Profile'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li><a href="<?php echo e(route('logout')); ?>"> Logout</a></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <li class="country-dropdown"><a href="#"><img class="lazyload"
                                                                              data-src="/v2/images/icons/flag-uae.svg"
                                                                              alt="uae"
                                                                              ondragstart="return false"> UAE <i
                                            class="party-icon icon-arrow-right"></i></a>
                                    <ul class="dropdown">
                                        <li><a href="#"><img class="lazyload"
                                                             data-src="/v2/images/flag-uae.png" alt="uae"/> UAE</a>
                                        </li>
                                        <li><a href="#"><img class="lazyload"
                                                             data-src="/v2/images/flag-saudi.png" alt="saudi"/> KSA</a>
                                        </li>
                                        <li><a href="#"><img class="lazyload"
                                                             data-src="/v2/images/flag-qatar.png" alt="qatar"/> QAT</a>
                                        </li>
                                        <li><a href="#"><img class="lazyload"
                                                             data-src="/v2/images/flag-india.png" alt="india"/> IND</a>
                                        </li>
                                        <li><a href="#"><img class="lazyload"
                                                             data-src="/v2/images/flag-china.png" alt="china"/> CHN</a>
                                        </li>
                                        <li><a href="#"><img class="lazyload"
                                                             data-src="/v2/images/flag-uk.png" alt="uk"/> UK</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="language-dropdown"><a href="#"><img class="lazyload"
                                                                               data-src="/v2/images/icons/language.svg"
                                                                               alt="language"
                                                                               ondragstart="return false"> English <i
                                            class="party-icon icon-arrow-right"></i></a>
                                    <ul class="dropdown">
                                        <li><a href="#" class="active">English</a></li>
                                        <li><a href="#">العربية</a></li>
                                        <li><a href="#">简体中文</a></li>
                                    </ul>
                                </li>
                                <li class="wishlist-btn-header">
                                    <a href="#">
                                        <img class="lazyload" data-src="/v2/images/icons/heart.svg"
                                             alt="heart" ondragstart="return false">
                                    </a>
                                </li>
                                <?php if(auth()->guard()->guest()): ?>
                                    <li class="signin-btn-header"><a
                                            href="<?php echo e(route('publisher.publisher_login')); ?>">Sign
                                            In</a> <a
                                            href="<?php echo e(route('user-signup', ['user_type' => 'profile'])); ?>">Sign
                                            Up</a></li>
                                    <li class="register-btn-header"><a
                                            href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>">Register
                                            Your
                                            <strong>Business /
                                                Profile</strong></a></li>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="menubar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <ul>
                        <li class="<?php echo e(request()->is('buy-and-sell*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('buy-and-sells')); ?>">Buy & Sell</a></li>
                        <li class="<?php echo e(request()->is('motor*') ? 'active' : ''); ?>"><a href="<?php echo e(url('motors/buy')); ?>">Motors</a>
                        </li>
                        <li class="<?php echo e(request()->is('property*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('property/buy')); ?>">Property</a></li>
                        <li class="<?php echo e(request()->is('director*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('directories')); ?>">Directory</a></li>
                        <li class="<?php echo e(request()->is('job*') ? 'active' : ''); ?>"><a href="<?php echo e(route('jobs')); ?>">Jobs</a>
                        </li>
                        <li class="<?php echo e(request()->is('venue*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('venues')); ?>">Venues</a></li>
                        <li class="<?php echo e(request()->is('event*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('events')); ?>">Events</a></li>
                        <li class="<?php echo e(request()->is('education*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('educations')); ?>">Education</a></li>
                        <li class="<?php echo e(request()->is('it*') ? 'active' : ''); ?>"><a href="<?php echo e(route('it')); ?>">I.T.</a></li>
                        <li class="<?php echo e(request()->is('attraction*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('attractions')); ?>">Attractions</a></li>
                         <li class="<?php echo e(request()->is('talents*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('talents')); ?>">Talents</a></li>
                        <li class="<?php echo e(request()->is('crypto*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('cryptocoin')); ?>">Coins</a></li>
                        <li class="<?php echo e(request()->is('influencer*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('influencers')); ?>">Influencers</a></li>
                        <li class="<?php echo e(request()->is('ticket*') ? 'active' : ''); ?>"><a href="<?php echo e(route('tickets')); ?>">Tickets</a>
                        </li>
                        <li class="<?php echo e(request()->is('book*') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('book-artist-view')); ?>">Booking</a></li>
                        <li><a href="#">Find Friends</a></li>
                        <li><a href="#">Market Place</a></li>
                        <li class="video-btn-menubar">
                            <a href="#">
                                <img class="lazyload" data-src="/v2/images/icons/play-3.svg" alt="play"
                                     ondragstart="return false">
                                Tutorial Video
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="responsive-menu">
    <input type="checkbox" id="drop-down-cbox"/>
    <label for="drop-down-cbox"> <i class="fa-solid fa-bars"></i> </label>
    <ul class="responsive-menu-nav">
        <label for="drop-down-cbox"><i class="fa-solid fa-xmark"></i> </label>
        <li class="padtop">
            <ul class="mob-col-menu">
                <li><a href="#">My Wishlist</a></li>
                <?php if(auth()->guard()->guest()): ?>
                    <li><a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>">Sign Up</a></li>
                <?php endif; ?>
                <li><a href="#">Order Alcohol</a></li>
                <?php if(auth()->guard()->guest()): ?>
                    <li><a href="<?php echo e(route('publisher.publisher_login')); ?>">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <li>
            <ul class="mob-col-menu">
                <li><a href="<?php echo e(route('venues')); ?>">Venues</a></li>
                <li><a href="<?php echo e(route('events')); ?>">Events</a></li>
                <li><a href="<?php echo e(route('buy-and-sells')); ?>">Buy & Sell</a></li>
                <li><a href="<?php echo e(url('directories')); ?>">Directory</a></li>
                <li><a href="<?php echo e(route('concierge')); ?>">Concierge</a></li>
                <li><a href="<?php echo e(route('influencers')); ?>">Influencers</a></li>
                <li><a href="<?php echo e(route('jobs')); ?>">Jobs</a></li>
                <li><a href="<?php echo e(route('front-accommodation')); ?>">Spaces</a></li>
                <li><a href="<?php echo e(route('tickets')); ?>">Tickets</a></li>
                <li><a href="<?php echo e(route('attractions')); ?>">Attractions</a></li>
            </ul>
        </li>
        <li><a href="#">Find Friends</a></li>
        <li><a href="<?php echo e(route('book-artist-view')); ?>">Find & Book Artists</a></li>
        <li><a href="<?php echo e(route('weekly-suggestion')); ?>">Weekly Events Suggestions</a></li>
        <li><a href="<?php echo e(route('popular-places-around')); ?>">Popular Places Around You</a></li>
        <li><a href="<?php echo e(route('give-away')); ?>">The Party Finder Giveaways</a></li>
        <li><a href="#">Find Party Halls & Ballrooms</a></li>
        <li><a href="#">Upload Your CV</a></li>
    </ul>
</div>

<?php echo $__env->yieldContent('content'); ?>
<div class="modal fade" id="enquiry-modal" aria-hidden="true" aria-labelledby="add-your-module" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                    class="fa-solid fa-xmark"></i></button>
            <div class="modal-body">
                <h3>Send Enquiry</h3>
                <form id="form_enquire_now" method="post" action="<?php echo e(route('enquire-form')); ?>">
                    <input type="hidden" name="major_category" value=""/>
                    <input type="hidden" name="item_id" value=""/>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <input class="form-control" type="text" aria-label="Username" name="name" id="name"
                                   placeholder="Your Name"
                                   required>
                        </div>
                        <div class="col-sm-12">
                            <input class="form-control" type="email" name="email" id="email"
                                   placeholder="info@company.com" required>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <input class="form-control" type="tel" name="mobile" id="mobile" placeholder="1235 452 456"
                                   required>
                        </div>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="message" id="message"
                                      placeholder="Message..."></textarea>
                        </div>
                        <?php echo RecaptchaV3::field('fld_recaptcha'); ?>

                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="submit" name="submit" value="Send">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h4><strong>Company</strong></h4>
                <ul class="company-footer">
                    <li><a href="<?php echo e(route('about-us')); ?>">About My Finder</a></li>
                    <li><a href="<?php echo e(route('sitemap.xml')); ?>">Sitemap</a></li>
                    <li><a href="<?php echo e(route('gallery')); ?>">Gallery</a></li>
                    <li><a href="<?php echo e(route('blogs')); ?>">Blog</a></li>
                    <li><a href="<?php echo e(route('careers')); ?>">Careers</a></li>
                    <li><a href="<?php echo e(route('faqs')); ?>">FAQs</a></li>
                    <li><a href="<?php echo e(route('news')); ?>">News</a></li>
                    <li><a href="<?php echo e(route('contact-us')); ?>">Contact</a></li>
                </ul>
            </div>
            <div class="col-sm-5">
                <h4><strong>Categories</strong></h4>
                <ul class="categories-footer">
                    <li><a href="<?php echo e(route('buy-and-sells')); ?>">Buy & Sell</a></li>
                    <li><a href="<?php echo e(route('jobs')); ?>">Jobs</a></li>
                    <li><a href="<?php echo e(route('educations')); ?>">Education</a></li>
                    <li><a href="<?php echo e(route('attractions')); ?>">Attractions</a></li>
                    <li><a href="<?php echo e(url('property/buy')); ?>">Property</a></li>
                    <li><a href="<?php echo e(route('venues')); ?>">Venues</a></li>
                    <li><a href="<?php echo e(route('it')); ?>">I.T.</a></li>
                    <li><a href="<?php echo e(route('popular-places-around')); ?>">Popular Places</a></li>
                    <li><a href="<?php echo e(url('motors/buy')); ?>">Motors</a></li>
                    <li><a href="<?php echo e(route('events')); ?>">Events</a></li>
                    <li><a href="<?php echo e(route('cryptocoin')); ?>">Crypto</a></li>
                    <li><a href="#">Recommendation</a></li>
                    <li><a href="<?php echo e(route('directories')); ?>">Directory</a></li>
                    <li><a href="<?php echo e(route('tickets')); ?>">Tickets</a></li>
                    <li><a href="<?php echo e(route('influencers')); ?>">Influencers</a></li>
                    <li><a href="#">Order Alcohol</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <h4><strong>Booking</strong></h4>
                <ul>
                    <li><a href="<?php echo e(route('book-artist-view')); ?>">Book an Artists</a></li>
                    <li><a href="<?php echo e(route('book_table')); ?>">Book a Table</a></li>
                    <li><a href="<?php echo e(route('give-away')); ?>">Giveaways</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <h4><strong>Legal</strong></h4>
                <ul>
                    <li><a href="<?php echo e(route('cookie_policy')); ?>">Cookies Policy</a></li>
                    <li><a href="<?php echo e(route('report_fraude')); ?>">Report Fraud</a></li>
                    <li><a href="<?php echo e(route('terms-and-conditions')); ?>">Terms & Conditions</a></li>
                    <li><a href="<?php echo e(route('privacy-policy')); ?>">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="copyright-wraper">
                    <p>&copy; <?php echo e(date('Y')); ?> Copyright MyFinder. All Rights Reserved.</p>
                    <div class="subscribe-newsletter">
                        <p>Subscribe to our newsletter</p>
                        <form action="<?php echo e(route('save_newsletter')); ?>" method="POST" id="new_letter_form">
                            <input type="email" placeholder="Enter your email" required>
                            <input type="submit" value="Subscribe">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a id="backtop" href="" data-placement="left"><i class="fa-solid fa-arrow-right"></i></a>
<div id="ajax-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                    class="fa-solid fa-xmark"></i></button>
            <p class="text-center">
                <img src="/v2/images/1.gif" align="center" class="img-fluid">
            </p>
        </div>
    </div>
</div>


<script src="/v2/js/plugins.js"></script>
<script type="text/javascript" src="/v2/js/tabs.js"></script>

<script src="/v2/js/swiper-script.js"></script>
<script src="/v2/js/slick.js"></script>
<script src="/v2/js/slick-script.js"></script>
<link rel="stylesheet" href="/intl-tel-input/css/intlTelInput.min.css"/>
<script src="/v2/js/scripts.js"></script>
<script src="/intl-tel-input/js/intlTelInput-jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>
<?php echo $__env->yieldContent('scripts'); ?>
<script type="text/javascript">
    $(function () {
        const myClipboard = new ClipboardJS('#btn-copy');
        myClipboard.on('success', function (e) {
            $('.bc-more-info .breadcrumb-menu').append('<div class="text-success text-center d-inline small" id="text_copied">Copied!</div>')
            setTimeout(function(){
                $('#text_copied').remove();
            }, 2000)
        });
        function showTooltip(elem, msg) {
            elem.setAttribute('class', 'btn tooltipped tooltipped-s');
            elem.setAttribute('aria-label', msg);
        }
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.sticky-header').addClass("sticky");
            } else {
                $('.sticky-header').removeClass("sticky");
            }
            $(this).scrollTop() > 599 ? $("#backtop").fadeIn() : $("#backtop").fadeOut()
        });
        $("#backtop").click(function () {
            return $("#backtop").tooltip("hide"),
                $("body,html").animate({
                    scrollTop: 0
                }, 800), !1
        }).tooltip("show");
        $(".btn-show-more").click(function () {
            $(".hide-data").slideToggle("slow");
        });
    });
</script>
<script src="//js.pusher.com/3.1/pusher.min.js"></script>


<script type="text/javascript">
    var notificationsWrapper = $('.admin-dropdown-notification');
    //   var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
    var notificationsCountElem = $(".admin-notification-count").html();
    var notificationsCount = parseInt(notificationsCountElem);
    var notifications = notificationsWrapper.find('.dropdown-menu-admin-list');

    if (notificationsCount <= 0) {

        // $(".header-notification").hide();
    } else {
        $(".header-notification").show();
    }

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('1d89ed9b6027d9112fb0', {
        cluster: 'ap2'
    });

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('my-channel');


    channel.bind("App\\Events\\PublisherEvent", function (data) {
        console.log(data);
    });


    var auth_user_id = $("#auth_user_id").val();


    //  console.log("gadf="+auth_user_id);

    // Bind a function to a Event (the full Laravel class)
    channel.bind('my-event', function (data) {
        var existingNotifications = notifications.html();
        var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
        // var newNotificationHtml = `<a class="d-flex" href="`+data.url_now+`">
        //                             <div class="media d-flex align-items-start">
        //                                 <div class="media-left">
        //                                     <div class="avatar-content">MD</div>
        //                                 </div>
        //                                 <div class="media-body">
        //                                     <p class="media-heading"><span class="font-weight-bolder">`+data.message+`</p>
        //                                         <small class="notification-text">`+data.description+`</small>
        //                                 </div>
        //                             </div>
        //                         </a>`;


        var newNotificationHtml = `<div class="notifications-item"> <img src="https://i.imgur.com/uIgDDDd.jpg" alt="img">
                                    <div class="text">
                                        <a href="` + data.url_now + `">
                                        <h4>` + data.message + `</h4>
                                        <p>` + data.description + `</p>
                                        </a>
                                    </div>
                                </div>`;


        if (data.notification_for == "2" && auth_user_id == data.notify_to) {
            notificationsCount += 1;
            $('.admin-notification-count').text(notificationsCount);


            $(".header-notification").show();
        }


    });
</script>

<script type="text/javascript">
    $(".count_click_cls").click(function () {

        var html_ab = "";

        $.ajax({
            url: "<?php echo e(route('publisher.ajax_publisher_notification')); ?>",
            method: 'GET',
            success: function (response) {
                $.each(response, function (key, value) {

                    html_ab += `<div class="notifications-item"> <img src="https://i.imgur.com/uIgDDDd.jpg" alt="img">
                                    <div class="text">
                                        <a href="` + value.url + `">
                                        <h4>` + value.title + `</h4>
                                        <p>` + value.description + `</p>
                                        </a>
                                    </div>
                                </div>`;


                });
                // alert("sdfsdf");
                $(".append_notify_div").html("");
                $(".append_notify_div").prepend(html_ab);
            }
        });


    });
</script>








<script>
    $(document).ready(function () {
        var down = false;

        $('#bell').click(function (e) {
            var color = $(this).text();
            if (down) {
                $('#box').css('height', '0px');
                $('#box').css('opacity', '0');
                down = false;
                $("#box").css("z-index", "0");
                $("#box").css("display", "none");
            } else {

                $("#box").css("z-index", "9999");

                $('#box').css('height', 'auto');
                $('#box').css('opacity', '1');
                $("#box").css("display", "block");
                down = true;

                // alert("gamer is on the move");

            }

        });

    });
</script>










<script>
    $(".wish_save_btn").click(function () {

        var item_id = $(this).data('id');
        var major_category = $(this).data('major-category');

        $.ajax({
            url: "<?php echo e(route('save_wish_list')); ?>",
            method: 'post',
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                item_id: item_id,
                major_category: major_category,
            },
            success: function (response) {

                if (response == "1") {
                    toastr.error("", "User not Login");
                } else if (response == "2") {
                    toastr.error("", "Already wishlisted");
                } else if (response == "3") {
                    toastr.success("", "Added to wishlist");
                }


            }
        });
    });

</script>




<script type="text/javascript">
    $(document).ready(function () {
        $("a.scroll-down").on('click', function (event) {
            if (this.hash !== "") {
                event.preventDefault();
                var hash = this.hash;
                $('html, body').animate({
                        scrollTop: $(hash).offset().top - 300
                    }, 800,
                    function () {
                        window.location.hash = hash;
                    });
            } // End if
        });
    });
</script>

<script>
    $(document).on("click", ".quickt_contact_cls", function (e) {

        var main_cat = $(this).data('main-category');
        var main_id = $(this).data('main-id');
        var type = $(this).data('type');


        $.ajax({
            type: "GET",
            cache: false,
            url: "<?php echo e(route('save_click_count')); ?>",
            data: {
                main_category_id: main_cat,
                main_id: main_id,
                type: type
            }, // serializes the form's elements.
            success: function (response) {


            }
        });
    });
</script>
<script>
    $(document).on("submit", "#new_letter_form", function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function (response) {
                $("body").removeClass("loading");

                if ($.trim(response) == "success") {
                    $("#email_newsletter").val("");
                    $(".success_info").show();
                    $(".error_info").hide();

                } else {
                    $(".error_info").html(response);
                    $(".error_info").show();
                    $(".success_info").hide();
                }

            }
        });
    });
</script>

<script>
    (function () {
        /*function onTidioChatApiReady() {
            window.tidioChatApi.hide();
        }

        if (window.tidioChatApi) {
            window.tidioChatApi.on("ready", onTidioChatApiReady);
        }

        document.querySelector(".chat-button").addEventListener("click", function() {
            window.tidioChatApi.show();
            window.tidioChatApi.open();
        });*/
    })();
</script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/layout/master.blade.php ENDPATH**/ ?>