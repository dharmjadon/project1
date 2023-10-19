<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $__env->yieldContent('page_title', 'The Party Finder - Events Finder - Venues Finder'); ?></title>
    <?php if(config('app.env') === 'production'): ?>
        <meta name="google-site-verification" content="vZm0iHVoXVLEiy_JeCEe0_BEfYP3o9oDGFCnOcCzpcI"/>
    <?php endif; ?>
    <?php if(config('app.env') !== 'production'): ?><meta name="robots" content="noindex" /><?php endif; ?>
    <meta name="description"
          content="<?php echo $__env->yieldContent('meta_description', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>">
    <meta name="keywords"
          content="<?php echo $__env->yieldContent('meta_keywords', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>"/>
    <meta property="og:title" content="<?php echo $__env->yieldContent('page_title', 'The Party Finder - Events Finder - Venues Finder'); ?>"/>
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', config('app.url').'/v2/images/logo.svg'); ?>"/>
    <meta property="og:description"
          content="<?php echo $__env->yieldContent('meta_description', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>"/>
    <meta property="og:type" content="<?php echo $__env->yieldContent('og_type', 'website'); ?>"/>
    <meta property="og:url" content="<?php echo $__env->yieldContent('canonical_url', config('app.url')); ?>"/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('page_title', 'The Party Finder - Events Finder - Venues Finder'); ?>"/>
    <meta name="twitter:description"
          content="<?php echo $__env->yieldContent('meta_description', 'The Party Finder, events finder, venues finder, property finder, motors buy, motors rent, property buy'); ?>"/>

    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical_url', config('app.url')); ?>"/>
    <!-- Styles -->
    <link rel="preconnect" href="//fonts.gstatic.com">
    <link rel="preconnect" href="//fonts.googleapis.com">
    <link rel="preconnect" href="//bootstrapcdn.com">
    <link rel="preconnect" href="//cloudflare.com">
    <link rel='dns-prefetch' href='//jquery.com'/>
    <link rel='dns-prefetch' href='//jsdelivr.net'/>
    <link rel='dns-prefetch' href='//www.google.com'/>
    <link rel="icon" href="/v2/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="/v2/css/landing.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/bootstrap.min.css"/>

    <link rel="stylesheet" type="text/css" href="/v2/css/all.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/fontello.css"/>
    <link rel="stylesheet" type="text/css" href="/v2/css/fontello-fp.css"/>
    <?php echo $__env->yieldContent('meta'); ?>
    <?php echo RecaptchaV3::initJs(); ?>

    <?php if(config('app.env') === 'production'): ?>
        <!-- Google Tag Manager -->
        <!-- End Google Tag Manager -->
    <?php endif; ?>

    <?php echo $__env->yieldContent('meta'); ?>
</head>
<body class="landing-page">
<div class="sticky-sidebar">
    <ul>
        <li><a href="<?php echo e(route('landing_home')); ?>"><i class="party-icon icon-home"></i> Home</a></li>
        <li><a href="#"><i class="party-icon icon-user-guide"></i> User Guide</a></li>
        <li><a href="#"><i class="party-icon icon-support"></i> Support</a></li>
        <li><a href="<?php echo e(route('faqs')); ?>"><i class="party-icon icon-faq"></i> FAQs</a></li>
        <li><a href="<?php echo e(route('user-signup', ['user_type' => 'profile'])); ?>"><i class="party-icon icon-register"></i> Register with us</a></li>
    </ul>
    <ul>
        <li><a href="#"><i class="fa-solid fa-question"></i> Help</a></li>
    </ul>
</div>

<div class="topbar">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="topnav" role="navigation">
                    <ul>
                        <li class="video-btn-topnav"><a href="#"><img class="lazyload" data-src="/v2/images/icons/play-2.svg" alt="play"
                                                                      ondragstart="return false"> Tutorial Video</a>
                        </li>
                        <li class="country-topnav"><a href="#"><span><img class="lazyload" data-src="/v2/images/icons/flag-uae.svg" alt="uae"
                                                                          ondragstart="return false"></span> UAE <i
                                    class="party-icon icon-arrow-right"></i></a>
                            <ul class="dropdown">
                                <li><a href="#"><img class="lazyload" data-src="/v2/images/flag-uae.png" alt="uae"> UAE</a></li>
                                <li><a href="#"><img class="lazyload" data-src="/v2/images/flag-saudi.png" alt="saudi"> KSA</a></li>
                                <li><a href="#"><img class="lazyload" data-src="/v2/images/flag-qatar.png" alt="qatar"> QAT</a></li>
                                <li><a href="#"><img class="lazyload" data-src="/v2/images/flag-india.png" alt="india"> IND</a></li>
                                <li><a href="#"><img class="lazyload" data-src="/v2/images/flag-china.png" alt="china"> CHN</a></li>
                                <li><a href="#"><img class="lazyload" data-src="/v2/images/flag-uk.png" alt="uk"> UK</a></li>
                            </ul>
                        </li>
                        <li class="language-topnav"><a href="#"><span><img class="lazyload" data-src="/v2/images/icons/language.svg"
                                                                           alt="language"
                                                                           ondragstart="return false"></span> English <i
                                    class="party-icon icon-arrow-right"></i></a>
                            <ul class="dropdown">
                                <li><a href="#" class="active">English</a></li>
                                <li><a href="#">العربية</a></li>
                                <li><a href="#">简体中文</a></li>
                            </ul>
                        </li>
                        <li class="wishlist-btn-topnav"><a href="#"><img class="lazyload" data-src="/v2/images/icons/heart.svg" alt="heart"
                                                                         ondragstart="return false"></a></li>
                        <li><a href="<?php echo e(route('publisher.publisher_login')); ?>">Sign In</a></li>
                        <li class="register-btn-topnav"><a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>">Register Your Business / Profile
                                <span>Sign Up</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="header-wraper">
                    <div class="logo"><a href="<?php echo e(route('landing_home')); ?>"> <img class="lazyload" data-src="/v2/images/logo-mf.svg" alt="My Finder"/>
                        </a></div>
                    <div class="menubar">
                        <ul>
                            <li><a href="<?php echo e(route('buy-and-sells')); ?>">Buy & Sell</a></li>
                            <li><a href="<?php echo e(url('motors/buy')); ?>">Motors</a></li>
                            <li><a href="<?php echo e(url('property/buy')); ?>">Property</a></li>
                            <li><a href="<?php echo e(route('directories')); ?>">Directory</a></li>
                            <li><a href="<?php echo e(route('venues')); ?>">Venues</a></li>
                            <li><a href="<?php echo e(route('events')); ?>">Events</a></li>
                            <li><a href="<?php echo e(route('educations')); ?>">Education</a></li>
                            <li>|</li>
                            <li><a href="<?php echo e(route('jobs')); ?>">Jobs</a></li>
                            <li><a href="<?php echo e(route('attractions')); ?>">Attractions</a></li>
                            <li><a href="#">Find Friends</a></li>
                        </ul>
                    </div>


                    <div class="responsive-menu">
                        <input type="checkbox" id="drop-down-cbox"/>
                        <label for="drop-down-cbox"> <i class="fa-solid fa-bars"></i> </label>
                        <ul class="responsive-menu-nav">
                            <label for="drop-down-cbox"><i class="fa-solid fa-xmark"></i> </label>
                            <li class="padtop">
                                <ul class="mob-col-menu">
                                    <li><a href="#">My Wishlist</a></li>
                                    <li><a href="<?php echo e(route('user-signup', ['user_type' => 'business'])); ?>">Sign Up</a></li>
                                    <li><a href="#">Order Alcohol</a></li>
                                    <li><a href="<?php echo e(route('publisher.publisher_login')); ?>">Sign In</a></li>
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
                            <li><a href="#">Find & Book Artists</a></li>
                            <li><a href="<?php echo e(route('weekly-suggestion')); ?>">Weekly Events Suggestions</a></li>
                            <li><a href="<?php echo e(route('popular-places-around')); ?>">Popular Places Around You</a></li>
                            <li><a href="<?php echo e(route('give-away')); ?>">The Party Finder Giveaways</a></li>
                            <li><a href="#">Find Party Halls & Ballrooms</a></li>
                            <li><a href="#">Upload Your CV</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>

<?php echo $__env->yieldContent('content'); ?>

<div class="modal fade modal-slider" id="myModal" aria-hidden="true"
     aria-labelledby="story-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <i
                    class="fa-solid fa-xmark"></i></button>
            <img data-src="<?php echo e(asset('v2/images/launching-popup.jpeg')); ?>" class="img-fluid lazyload" alt="slider">
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p>&copy; <?php echo date('Y'); ?> Copyright myfinder.com. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<a id="backtop" href="" data-placement="left"><i class="fa-solid fa-arrow-right"></i></a>


<script src="/v2/js/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="/v2/js/lazysizes.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>
<script type="text/javascript">
    $(function () {
        $("#myModal").modal('show');
        $(window).scroll(function () {
            $(this).scrollTop() > 599 ? $("#backtop").fadeIn() : $("#backtop").fadeOut()
        }),
            $("#backtop").click(function () {
                return $("#backtop").tooltip("hide"),
                    $("body,html").animate({scrollTop: 0}, 800), !1
            }),
            $("#backtop").tooltip("show")
    });
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/layout/landing-page.blade.php ENDPATH**/ ?>