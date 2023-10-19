<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
          content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>My Finder</title>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <link rel="apple-touch-icon" href="<?php echo e(asset('app-assets/images/ico/apple-icon-120.png')); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('assets/admin-logo-small.svg')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
          rel="stylesheet">
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/vendors.min.css')); ?>">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/bootstrap.css')); ?> ">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/bootstrap-extended.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/colors.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/components.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/themes/dark-layout.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/themes/bordered-layout.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/themes/semi-dark-layout.css')); ?>">


    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>"/>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user-asset/css/all.css')); ?>"/>
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <!-- END: Custom CSS-->
    <link href="/v2/css/daterangepicker.css" id="app-style" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.6.0/bootstrap-tagsinput.min.css">
    <?php echo $__env->yieldContent('css'); ?>
    <?php echo RecaptchaV3::initJs(); ?>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        .navigation-main .active a {
            background-color: #bf087f !important;
        }

        .demo-inline-spacing {
            margin-right: 0.5rem;
            /* margin-top: 0.5rem; */
        }

        .demo-inline-spacing-ab a {
            margin-right: 0.5rem;
            margin-top: 0.5rem;
        }

        .video-div {
            margin-right: 0.5rem;
            margin-top: 0.5rem;
        }

        .demo-inline-spacing > * {
            margin-right: 0rem;
            margin-top: 0rem;
        }

        /* .vertical-layout.vertical-menu-modern.menu-collapsed .main-menu:not(.expanded) .navigation li.active a {
            background:whitesmoke !important
        } */
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click"
      data-menu="vertical-menu-modern" data-col="">
<DIV id="prepage"
     style="position: fixed; z-index: 99999999; filter:alpha(opacity=60); opacity:0.6; font-family:arial; font-size:16px; left:0px; top:0px; background-color: #ECEDEF; layer-background-color: #ECEDEF; height:100%; width:100%; display:none;">
    <TABLE width="100%" height="100%" align="center">
        <TR>
            <TD width="100%" align="center"><B>
                    <center><img src="/assets/loader/1.gif"/></center>
                </B></TD>
        </TR>
    </TABLE>
</DIV>
<!-- BEGIN: Header-->
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                                                                                                   data-feather="menu"></i></a>
                </li>
            </ul>
        </div>


        <ul class="nav navbar-nav align-items-center ml-auto">
            <h4>Select Listing</h4>
            <div class="demo-inline-spacing d-flex justify-content-center demo-inline-spacing-ab ">
                    <span class="video-div">
                        <a href="<?php echo e(route('publisher.venue.create')); ?>" class="btn btn-primary btn-sm d-block">Venue</a>
                        <a href="<?php echo e($major[0]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.event.create')); ?>" class="btn btn-primary btn-sm">Events</a>
                        <a href="<?php echo e($major[1]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.education.create')); ?>" class="btn btn-primary btn-sm">Education</a>
                        <a href="<?php echo e($major[1]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.buy-sell.create')); ?>" class="btn btn-primary btn-sm">Buy & Sell</a>
                        <a href="<?php echo e($major[2]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.directories.create')); ?>"
                           class="btn btn-primary btn-sm">Directories</a>
                        <a href="<?php echo e($major[3]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.concierge.create')); ?>" class="btn btn-primary btn-sm">Concierge</a>
                        <a href="<?php echo e($major[4]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.influencers.create')); ?>"
                           class="btn btn-primary btn-sm">Influencers</a>
                        <a href="<?php echo e($major[5]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.job.create')); ?>" class="btn btn-primary btn-sm">Jobs</a>
                        <a href="<?php echo e($major[6]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.tickets.create')); ?>" class="btn btn-primary btn-sm">Tickets</a>
                        <a href="<?php echo e($major[7]->video); ?>" class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
                
                <span class="video-div">
                        <a href="<?php echo e(route('publisher.office_create_accomadtion')); ?>" class="btn btn-primary btn-sm">Property</a>
                        <a href="<?php echo e(isset($major[9]->video) ?  $major[9]->video : 'javascript:void(0)'); ?>"
                           class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>

                <span class="video-div">
                        <a href="<?php echo e(url('publisher/create-motor')); ?>" class="btn btn-primary btn-sm">Motors</a>
                        <a href="<?php echo e(isset($major[10]->video) ?  $major[10]->video : 'javascript:void(0)'); ?>"
                           class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>

                <span class="video-div">
                        <a href="<?php echo e(route('publisher.book-artist.create')); ?>"
                           class="btn btn-primary btn-sm">Book Artist</a>
                        <a href="<?php echo e(isset($major[10]->video) ?  $major[10]->video : 'javascript:void(0)'); ?>"
                           class="btn btn-sm d-block" target="_blank">Video</a>
                    </span>
            </div>
        </ul>


        <ul class="nav navbar-nav align-items-center ml-auto">

            <li class="nav-item dropdown dropdown-notification  admin-dropdown-notification mr-25"><a
                    class="nav-link admin_notify_click" href="javascript:void(0);" data-toggle="dropdown"><i
                        class="ficon" data-feather="bell"></i><span
                        class="badge badge-pill badge-danger badge-up admin-notification-count"><?php echo e($admin_notifcation_count); ?></span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                            
                        </div>
                    </li>
                    <li class="scrollable-container media-list dropdown-menu-admin-list">

                    </li>

                </ul>
            </li>

            
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link"
                   id="dropdown-user" href="javascript:void(0);"
                   data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="avatar">
                        <img class="round" src="<?php echo e(auth()->user()->storedImage(auth()->user()->profile_picture)); ?>"
                             alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                        <span
                            class="user-name font-weight-bolder"> <?php echo e(auth()->user()->name); ?>  </span><span
                            class="user-status">
                            Publisher
                        </span></div>

                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="javascript:void(0)" id="profile_btn"><i class="mr-50"
                                                                                           data-feather="user"></i>
                        Profile</a>
                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"><i class="mr-50" data-feather="power"></i>
                        Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<ul class="main-search-list-defaultlist d-none">
    <li class="d-flex align-items-center"><a href="javascript:void(0);">
            <h6 class="section-label mt-75 mb-0">Files</h6>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/xls.png')); ?>" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing
                        Manager</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;17kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/jpg.png')); ?>" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
                        Developer</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;11kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/pdf.png')); ?>" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
                        Marketing Manager</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;150kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100"
                                   href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/doc.png')); ?>" alt="png" height="32">
                </div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                </div>
            </div>
            <small class="search-data-size mr-50 text-muted">&apos;256kb</small>
        </a></li>
    <li class="d-flex align-items-center"><a href="javascript:void(0);">
            <h6 class="section-label mt-75 mb-0">Members</h6>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-8.jpg')); ?>"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-1.jpg')); ?>"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
                        Developer</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-14.jpg')); ?>"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing
                        Manager</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100"
                                   href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-6.jpg')); ?>"
                                               alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                </div>
            </div>
        </a></li>
</ul>
<ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion justify-content-between"><a
            class="d-flex align-items-center justify-content-between w-100 py-50">
            <div class="d-flex justify-content-start"><span class="mr-75" data-feather="alert-circle"></span><span>No results found.</span>
            </div>
        </a></li>
</ul>
<!-- END: Header-->

<div class="modal fade text-left" id="profile_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel120">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo e(route('publisher.update_profile')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <button class="btn btn-primary" disabled style="opacity: 1;">Profile Settings</button>&nbsp;&nbsp;<a
                                href="javascript:void(0)" id="password_reset" style="color: black;">Change Password</a>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Email ID</label>
                            <input type="email" name="email" readonly class="form-control"
                                   value="<?php echo e(auth()->user()->email); ?>" placeholder="Enter Email Id" id="">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Profile Image</label>
                            <div class="icon-wrapper">
                                <img src="<?php echo e(auth()->user()->storedImage(auth()->user()->profile_picture)); ?>"
                                     width="100px" style="border-radius: 50%;">&nbsp;
                                <input id="fileid" type="file" name="profile_pic" hidden/>
                                <input id="buttonid" type="button" class="btn btn-secondary btn-sm"
                                       value="Change Profile Picture"/>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Company Name</label>
                            <input type="text" name="company_name" value="<?php echo e(auth()->user()->company_name); ?>"
                                   class="form-control" placeholder="Company Name">

                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Address</label>
                            <textarea id="" class="form-control" name="address"
                                      placeholder="<?php echo e(auth()->user()->address ?? 'Address...'); ?>"><?php echo e(auth()->user()->address); ?></textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Mobile Number</label>
                            <input type="text" name="mobile" value="<?php echo e(auth()->user()->mobile_no); ?>" class="form-control"
                                   placeholder="Enter Mobile Number" id="">
                            Please add number with country code
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" value="<?php echo e(auth()->user()->id); ?>">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="password_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel120">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo e(route('publisher.reset_password')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Current Password</label>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control form-control-merge" id="login-password" type="password"
                                       name="current_password" placeholder="············"
                                       aria-describedby="login-password" tabindex="2"/>
                                <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                            data-feather="eye"></i></span></div>
                            </div>

                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">New Password</label>

                            <div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control form-control-merge" id="new_psd" type="password"
                                       name="new_password" placeholder="············" aria-describedby="new_psd"
                                       tabindex="2"/>
                                <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                            data-feather="eye"></i></span></div>
                            </div>

                            
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Repeat Password</label>

                            <div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control form-control-merge" id="re_psd" type="password"
                                       name="repeat_password" placeholder="············" aria-describedby="re_psd"
                                       tabindex="2"/>
                                <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                            data-feather="eye"></i></span></div>
                            </div>


                            
                            <span id='message'></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" value="<?php echo e(auth()->user()->id); ?>">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="<?php echo e(route('publisher.publisher_dashboard')); ?>">
                    <span class="brand-logo">
                        <img src="<?php echo e(asset('assets/admin-logo-small.svg')); ?>" height="40">
                            </span>
                    <h2 class="brand-text" style="color:#000"><img src="<?php echo e(asset('assets/admin-logo-crop.png')); ?>"
                                                                   height="50"></h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

                <span class="pr-2 pl-2"> <input type="text" class="form form-group mt-1 menu-search" style="width: 60%;border-radius:10px;background-image: url('https://upload.wikimedia.org/wikipedia/commons/5/55/Magnifying_glass_icon.svg'); background-size: 18px;background-position: 10px center;
                    background-repeat: no-repeat;"></span>
            <li class=" nav-item <?php echo e((request()->is('publisher/dashboard*')) ? 'active' : ''); ?>"><a
                    class="d-flex align-items-center" href="<?php echo e(route('publisher.publisher_dashboard')); ?>"><img
                        <?php if(request()->is('publisher/dashboard*')): ?> src="<?php echo e(asset('assets/icon/001-01.png')); ?>"
                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-01.svg')); ?>" <?php endif; ?> height="20"><span
                        class="menu-title text-truncate ml-1" data-i18n="Invoice"> Dashboard</span></a></li>
            <hr style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">
            <?php $array_venu = array('venue-view'); ?>

            <li class=" nav-item"><a class="d-flex align-items-center" href=""><img
                        <?php if(request()->is('publisher/dashboard*')): ?> src="<?php echo e(asset('assets/icon/001-01.png')); ?>"
                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-01.svg')); ?>" <?php endif; ?> height="20"><span
                        class="menu-title text-truncate ml-1" data-i18n="Invoice"> <h5>Your Listing</h5></span></a></li>
            <hr style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">

            <?php $array_user_venue = array('publisher-user-venue'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_venue)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/venue*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.venue.index')); ?>"><img
                            <?php if(request()->is('publisher/venue*')): ?> src="<?php echo e(asset('assets/icon/001-02.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-02.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager"> Venues</span></a></li>
            <?php endif; ?>

                <?php $array_user_education = array('publisher-user-venue'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_education)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/education*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.education.index')); ?>"><img
                            <?php if(request()->is('publisher/education*')): ?> src="<?php echo e(asset('assets/icon/001-02.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-02.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager"> Education</span></a></li>
            <?php endif; ?>


                <?php $array_user_event = array('publisher-user-events'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_event)): ?>
                <li class=" <?php echo e((request()->is('publisher/event*')) ? 'active' : ''); ?> nav-item"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.event.index')); ?>"><img
                            <?php if(request()->is('publisher/event*')): ?> src="<?php echo e(asset('assets/icon/001-03.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="Invoice">Events</span></a></li>
            <?php endif; ?>


                <?php $array_user_buy_sell = array('publisher-user-venue'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_buy_sell)): ?>
                <li class=" <?php echo e((request()->is('publisher/buy-sell*')) ? 'active' : ''); ?> nav-item"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.buy-sell.index')); ?>"><img
                            <?php if(request()->is('publisher/buy-sell*')): ?> src="<?php echo e(asset('assets/icon/001-17.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="Invoice">Buy & Sell</span></a></li>
            <?php endif; ?>
 <?php $array_crypto = array('publisher-user-crypto'); ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_crypto)): ?>
                <li class=" <?php echo e((request()->is('publisher/crypto*')) ? 'active' : ''); ?> nav-item"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.crypto.index')); ?>"><img
                            <?php if(request()->is('publisher/crypto*')): ?> src="<?php echo e(asset('assets/icon/001-17.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="Invoice">Crypto</span></a></li>
            <?php endif; ?>


                <?php $array_user_directories = array('publisher-user-directories'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_directories)): ?>
                <li class="nav-item <?php echo e((request()->is('publisher/directories*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.directories.index')); ?>"><img
                            <?php if(request()->is('publisher/directories*')): ?> src="<?php echo e(asset('assets/icon/001-05.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-05.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="Invoice">Directories</span></a></li>
            <?php endif; ?>


                <?php $array_user_concierge = array('publisher-user-concierge'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_concierge)): ?>
                <li class="nav-item <?php echo e((request()->is('publisher/concierge*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.concierge.index')); ?>"><img
                            <?php if(request()->is('publisher/concierge*')): ?> src="<?php echo e(asset('assets/icon/001-04.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Concierge</span></a></li>
            <?php endif; ?>



                <?php $array_user_influencer = array('publisher-user-infuencers'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_influencer)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/influencers*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.influencers.index')); ?>"><img
                            <?php if(request()->is('admin/influencers*')): ?> src="<?php echo e(asset('assets/icon/001-06.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-06.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Influencers</span></a></li>
            <?php endif; ?>


                <?php $array_user_influencer_review = array('publisher-user-infuencer-review'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_influencer_review)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/influencer-reviews*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.influencer-reviews.index')); ?>"><img
                            <?php if(request()->is('publisher/influencer-reviews*')): ?> src="<?php echo e(asset('assets/icon/001-27.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-27.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Influencer Review</span></a></li>
            <?php endif; ?>

                <?php $array_user_influencer_review = array('publisher-give-away-claim'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_influencer_review)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/give-away-claim*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.give-away-claim.index')); ?>"><img
                            <?php if(request()->is('publisher/give-away-claim*')): ?> src="<?php echo e(asset('assets/icon/001-27.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-27.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">GiveAway Claims</span></a></li>
            <?php endif; ?>
                <?php $array_user_jobs = array('publisher-user-jobs'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_jobs)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/job-company*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.job-company.index')); ?>"><img
                            <?php if(request()->is('publisher/job-company*')): ?> src="<?php echo e(asset('assets/icon/001-07.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-07.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Job Companies</span></a></li>

                <li class=" nav-item <?php echo e((request()->is('publisher/job/*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.job.index')); ?>"><img
                            <?php if(request()->is('publisher/job*')): ?> src="<?php echo e(asset('assets/icon/001-07.png')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-07.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Jobs</span></a></li>
            <?php endif; ?>


                <?php $array_user_ticket = array('publisher-user-tickets'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_ticket)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/tickets*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.tickets.index')); ?>"><img
                            src="<?php echo e(asset('assets/icon/Admin-Panel-18.svg')); ?>" height="20"><span
                            class="menu-title text-truncate  ml-1" data-i18n="Invoice">Tickets</span></a></li>
            <?php endif; ?>


                <?php $array_user_accommodation = array('publisher-user-accommodation'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_accommodation)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/accommodation*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.accommodation.index')); ?>"><img
                            <?php if(request()->is('admin/publisher*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1"
                            data-i18n="File Manager">Property Buy & Sell</span></a></li>
            <?php endif; ?>

                <?php $array_user_motors = array('publisher-user-motors'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_accommodation)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/motors*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.motors.index')); ?>"><img
                            <?php if(request()->is('admin/publisher*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Motors</span></a></li>
            <?php endif; ?>

                <?php $array_user_motors = array('publisher-book-artist'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_accommodation)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/book-artist*')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.book-artist.index')); ?>"><img
                            <?php if(request()->is('admin/publisher*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Book Artist</span></a></li>
            <?php endif; ?>






            


            <hr style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">


                <?php $array_user_any_permission_exist = array('publisher-user-venue', 'publisher-user-events', 'publisher-user-concierge', 'publisher-user-jobs', 'publisher-user-infuencers', 'publisher-user-tickets', 'publisher-user-accommodation', 'publisher-user-buy_sell', 'publisher-user-directories'); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_any_permission_exist)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/enquiry')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.enquiry.index')); ?>"><img
                            <?php if(request()->is('publisher/enquiry*')): ?> src="<?php echo e(asset('assets/icon/001-14.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-14.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Enquiry</span></a></li>
            <?php endif; ?>


            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_any_permission_exist)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/review')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.review.index')); ?>"><img
                            <?php if(request()->is('publisher/review*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Review</span></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_any_permission_exist)): ?>
                <li class=" nav-item <?php echo e((request()->is('publisher/wishlists')) ? 'active' : ''); ?>"><a
                        class="d-flex align-items-center" href="<?php echo e(route('publisher.wishlists')); ?>"><img
                            <?php if(request()->is('publisher/review*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span
                            class="menu-title text-truncate ml-1" data-i18n="File Manager">Wishlist</span></a></li>
            <?php endif; ?>


            <li class=" nav-item <?php echo e((request()->is('publisher/recommendation')) ? 'active' : ''); ?>"><a
                    class="d-flex align-items-center" href="<?php echo e(route('publisher.recommendation')); ?>"><img
                        <?php if(request()->is('publisher/recommendation*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span
                        class="menu-title text-truncate ml-1" data-i18n="File Manager">Recommendation</span></a></li>

            <li class=" nav-item <?php echo e((request()->is('publisher/own-wishlists')) ? 'active' : ''); ?>"><a
                    class="d-flex align-items-center" href="<?php echo e(route('publisher.own_wishlists')); ?>"><img
                        <?php if(request()->is('publisher/review*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span
                        class="menu-title text-truncate ml-1" data-i18n="File Manager">Own Wishlist</span></a></li>


            <li class=" nav-item <?php echo e((request()->is('publisher/career/list')) ? 'active' : ''); ?>"><a
                    class="d-flex align-items-center" href="<?php echo e(route('publisher.publisher_career_list')); ?>"><img
                        <?php if(request()->is('publisher/career/list*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span
                        class="menu-title text-truncate ml-1" data-i18n="File Manager">Career List</span></a></li>
            <li class=" nav-item <?php echo e((request()->is('publisher/find-all-cv')) ? 'active' : ''); ?>"><a
                    class="d-flex align-items-center" href="<?php echo e(route('publisher.find-all-cv')); ?>"><img
                        <?php if(request()->is('publisher/find-all-cv*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span
                        class="menu-title text-truncate ml-1" data-i18n="File Manager">Find CV's</span></a></li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0 mt-4">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2022<a
                class="ml-25" href="#" target="_blank">MyFinder</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span>
    </p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->


<!-- BEGIN: Vendor JS-->
<script src="<?php echo e(asset('app-assets/vendors/js/vendors.min.js')); ?>"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="/v2/admin/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="<?php echo e(asset('app-assets/js/core/app-menu.js')); ?>"></script>
<script src="<?php echo e(asset('app-assets/js/core/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
<script src="/v2/js/moment.min.js"></script>
<script src="/v2/js/daterangepicker.js"></script>

<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<!-- END: Page JS-->
<?php echo $__env->yieldContent('script'); ?>

<script>
    toastr.options = {
        "positionClass": "toast-top-center"
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
    });
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
    $('#profile_btn').click(function () {
        $('#profile_modal').modal('show');
    });

    $('#password_reset').click(function () {
        $('#password_modal').modal('show');
    });

    document.getElementById('buttonid').addEventListener('click', openDialog);

    function openDialog() {
        document.getElementById('fileid').click();
    }

    $('#new_psd, #re_psd').on('keyup', function () {
        if ($('#new_psd').val() != $('#re_psd').val()) {
            $('#message').html('Not Matching').css('color', 'red');
        } else {
            $('#message').html('');
        }
    });
</script>
<script>
    <?php if(Session::has('message_password')): ?>
    var type = "<?php echo e(Session::get('alert-type', 'info')); ?>";
    switch (type) {
        case 'info':
            toastr.info("<?php echo e(Session::get('message_password')); ?>", "Information!", {timeOut: 10000, progressBar: true});
            break;

        case 'warning':
            toastr.warning("<?php echo e(Session::get('message_password')); ?>", "Warning!", {timeOut: 10000, progressBar: true});
            break;

        case 'success':
            toastr.success("<?php echo e(Session::get('message_password')); ?>", "Success!", {timeOut: 10000, progressBar: true});
            break;

        case 'error':
            toastr.error("<?php echo e(Session::get('message_password')); ?>", "Failed!", {timeOut: 10000, progressBar: true});
            break;
    }
    <?php endif; ?>
</script>




<script src="//js.pusher.com/3.1/pusher.min.js"></script>


<script type="text/javascript">
    var notificationsWrapper = $('.admin-dropdown-notification');
    //   var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
    var notificationsCountElem = $(".admin-notification-count").html();
    var notificationsCount = parseInt(notificationsCountElem);
    var notifications = notificationsWrapper.find('.dropdown-menu-admin-list');

    if (notificationsCount <= 0) {
        // notificationsWrapper.hide();
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

    var auth_user_id = "<?php echo e(auth()->user()->id); ?>";

    // Bind a function to a Event (the full Laravel class)
    channel.bind('my-event', function (data) {
        var existingNotifications = notifications.html();
        var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
        var newNotificationHtml = `<a class="d-flex" href="` + data.url_now + `">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar-content">MD</div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">` + data.message + `</p>
                                            <small class="notification-text">` + data.description + `</small>
                                    </div>
                                </div>
                            </a>`;


        if (data.notification_for == "1" && auth_user_id == data.notify_to) {
            // notifications.html(newNotificationHtml + existingNotifications);
            notificationsCount += 1;
            // notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.admin-notification-count').text(notificationsCount);
            notificationsWrapper.show();
        }


    });
</script>

<script type="text/javascript">

    $(".admin_notify_click").click(function () {

        var html_ab = "";

        $.ajax({
            url: "<?php echo e(route('publisher.ajax_publisher_notification')); ?>",
            method: 'GET',
            success: function (response) {
                $.each(response, function (key, value) {

                    html_ab += `<a class="d-flex" href="` + value.url + `">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar-content">TPH</div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">` + value.title + `</p>
                                            <small class="notification-text">` + value.description + `</small>
                                    </div>
                                </div>
                            </a>`;

                });
                // alert("sdfsdf");
                $(".dropdown-menu-admin-list").html("");
                $(".dropdown-menu-admin-list").prepend(html_ab);
            }
        });


    });
</script>

<script>
    $(".menu-search").keyup(function () {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(),
            count = 0;
        // Loop through the comment list
        $('.navigation-main .nav-item').each(function () {
            // If the list item does not contain the text phrase fade it out
            if ($(this).hasClass('search')) {
                return true;
            }
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).hide(400);  // MY CHANGE
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show(400); // MY CHANGE
                count++;
            }
        });
    });

    $(".menu-search").focus(function () {
        $(this).css('backgroundImage', 'none')
    })
</script>



</body>
<!-- END: Body-->

</html>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/publisher/layout/app.blade.php ENDPATH**/ ?>