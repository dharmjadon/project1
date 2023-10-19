<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>The Party Finder</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link rel="apple-touch-icon" href="<?php echo e(asset('app-assets/images/ico/apple-icon-120.png')); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('assets/admin-logo-small.svg')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
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
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>" />

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user-asset/css/all.css')); ?>" />
    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <!-- END: Custom CSS-->
    <?php echo $__env->yieldContent('css'); ?>

    <style>
        .navigation-main .active a {
            background-color:#bf087f !important;
        }
        /* .vertical-layout.vertical-menu-modern.menu-collapsed .main-menu:not(.expanded) .navigation li.active a {
            background:whitesmoke !important
        } */
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">

                <li class="nav-item dropdown dropdown-notification  admin-dropdown-notification mr-25"><a class="nav-link admin_notify_click" href="javascript:void(0);" data-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge badge-pill badge-danger badge-up admin-notification-count"><?php echo e($admin_notifcation_count); ?></span></a>
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

                
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder"> <?php echo e(auth()->user()->name); ?>  </span><span class="user-status"><?php echo e(auth()->user()->user_type==1 ? 'Admin':'Client'); ?></span></div><span class="avatar"><img class="round" src="<?php echo e(otherImage(auth()->user()->profile_picture)); ?>" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="javascript:void(0)" id="profile_btn"><i class="mr-50" data-feather="user"></i> Profile</a>
                        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"><i class="mr-50" data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist d-none">
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Files</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/xls.png')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/jpg.png')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd Developer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/pdf.png')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital Marketing Manager</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
                <div class="d-flex">
                    <div class="mr-75"><img src="<?php echo e(asset('app-assets/images/icons/doc.png')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                    </div>
                </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
            </a></li>
        <li class="d-flex align-items-center"><a href="javascript:void(0);">
                <h6 class="section-label mt-75 mb-0">Members</h6>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-8.jpg')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-1.jpg')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd Developer</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-14.jpg')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing Manager</small>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
                <div class="d-flex align-items-center">
                    <div class="avatar mr-75"><img src="<?php echo e(asset('app-assets/images/portrait/small/avatar-s-6.jpg')); ?>" alt="png" height="32"></div>
                    <div class="search-data">
                        <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="mr-75" data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->

    <div class="modal fade text-left" id="profile_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel120">Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <button class="btn btn-primary" disabled style="opacity: 1;">Profile Settings</button>&nbsp;&nbsp;<a href="javascript:void(0)" id="password_reset" style="color: black;">Change Password</a>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Email ID</label>
                                <input type="email" name="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" placeholder="Enter Email Id" id="">
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Profile Image</label>
                                <div class="icon-wrapper">
                                    <img src="<?php echo e(otherImage(auth()->user()->profile_picture)); ?>" width="100px" style="border-radius: 50%;">&nbsp;
                                    <input id="fileid" type="file" name="profile_pic" hidden/>
                                    <input id="buttonid" type="button" class="btn btn-secondary btn-sm" value="Change Profile Picture" />
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Company Name</label>
                                <input type="text" name="company_name" value="<?php echo e(auth()->user()->company_name); ?>" class="form-control" placeholder="Company Name" id="">

                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Address</label>
                                <textarea id="" class="form-control" value="<?php echo e(auth()->user()->address); ?>" name="address" placeholder="<?php echo e(auth()->user()->address ?? 'Address...'); ?>"></textarea>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Mobile Number</label>
                                <input type="text" name="mobile" value="<?php echo e(auth()->user()->mobile_no); ?>" class="form-control" placeholder="Enter Mobile Number" id="">
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

    <div class="modal fade text-left" id="password_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel120">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?php echo e(route('admin.reset_password')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Enter Current Password" id="old_psd" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">New Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Enter New Password" id="new_psd" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="">Repeat Password</label>
                                <input type="password" name="repeat_password" class="form-control" placeholder="Enter Repeat Password" id="re_psd" required>
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
                        <h2 class="brand-text" style="color:#000"><img src="<?php echo e(asset('assets/admin-logo-crop.png')); ?>" height="50"></h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">


                <li class=" nav-item <?php echo e((request()->is('client/dashboard*')) ? 'active' : ''); ?>"><a class="d-flex align-items-center" href="<?php echo e(route('client.client-dashboard')); ?>"><img <?php if(request()->is('client/dashboard*')): ?> src="<?php echo e(asset('assets/icon/001-01.png')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-01.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="Invoice"> Dashboard</span></a></li>
                <hr style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">






                <li class="nav-item <?php echo e((request()->is('client/reservation-details*')) ? 'active' : ''); ?>"><a class="d-flex align-items-center"  href="<?php echo e(route('client.reservation_details')); ?>"><img <?php if(request()->is('client/reservation-details*')): ?> src="<?php echo e(asset('assets/icon/001-05.png')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-05.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="Invoice">Reservation Details</span></a></li>



                <li class="nav-item <?php echo e((request()->is('client/applied*')) ? 'active' : ''); ?>"><a class="d-flex align-items-center" href="<?php echo e(route('client.client_applied_job')); ?>"><img <?php if(request()->is('client/applied*')): ?> src="<?php echo e(asset('assets/icon/001-04.png')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="File Manager">Applied Jobs</span></a></li>

                <li class="nav-item <?php echo e((request()->is('client/give-away-claim*')) ? 'active' : ''); ?>"><a class="d-flex align-items-center" href="<?php echo e(route('client.give-away-claim.index')); ?>"><img <?php if(request()->is('client/give-away-claim*')): ?> src="<?php echo e(asset('assets/icon/001-04.png')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="File Manager">GiveAway Claims</span></a></li>



               <hr style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">



              <li class=" nav-item <?php echo e((request()->is('client/enquiry')) ? 'active' : ''); ?>"><a class="d-flex align-items-center" href="<?php echo e(route('client.client_enquiry')); ?>"><img <?php if(request()->is('client/enquiry*')): ?> src="<?php echo e(asset('assets/icon/001-14.svg')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-14.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="File Manager">Enquiry</span></a>



              <li class=" nav-item <?php echo e((request()->is('client/review')) ? 'active' : ''); ?>"><a class="d-flex align-items-center" href="<?php echo e(route('client.client_review')); ?>"><img <?php if(request()->is('client/review*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="File Manager">Review</span></a>

                <li class=" nav-item <?php echo e((request()->is('client/wishlist')) ? 'active' : ''); ?>"><a class="d-flex align-items-center" href="<?php echo e(route('client.client_wishlist')); ?>"><img <?php if(request()->is('client/wishlist*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?> height="20"><span class="menu-title text-truncate ml-1" data-i18n="File Manager">Wish list</span></a>




            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2022<a class="ml-25" href="#" target="_blank">The Party Finder</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo e(asset('app-assets/vendors/js/vendors.min.js')); ?>"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?php echo e(asset('app-assets/js/core/app-menu.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/js/core/app.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->
    <?php echo $__env->yieldContent('script'); ?>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
    <script>
        $('#profile_btn').click(function() {
            $('#profile_modal').modal('show');
        });

        $('#password_reset').click(function() {
            $('#password_modal').modal('show');
        });
    </script>
    <script>
        document.getElementById('buttonid').addEventListener('click', openDialog);

        function openDialog() {
        document.getElementById('fileid').click();
        }
    </script>
    <script>
        $('#new_psd, #re_psd').on('keyup', function () {
            if ($('#new_psd').val() != $('#re_psd').val()) {
                $('#message').html('Not Matching').css('color', 'red');
            }else {
                $('#message').html('');
            }
        });
    </script>
    <script>
        <?php if(Session::has('message_password')): ?>
        var type = "<?php echo e(Session::get('alert-type', 'info')); ?>";
        switch(type){
            case 'info':
                toastr.info("<?php echo e(Session::get('message')); ?>", "Information!", { timeOut:10000 , progressBar : true});
                break;

            case 'warning':
                toastr.warning("<?php echo e(Session::get('message')); ?>", "Warning!", { timeOut:10000 , progressBar : true});
                break;

            case 'success':
                toastr.success("<?php echo e(Session::get('message')); ?>", "Success!", { timeOut:10000 , progressBar : true});
                break;

            case 'error':
                toastr.error("<?php echo e(Session::get('message')); ?>", "Failed!", { timeOut:10000 , progressBar : true});
                break;
        }
        <?php endif; ?>
    </script>





<script src="//js.pusher.com/3.1/pusher.min.js"></script>


<script type="text/javascript">
  var notificationsWrapper   = $('.admin-dropdown-notification');
//   var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
  var notificationsCountElem = $(".admin-notification-count").html();
  var notificationsCount     = parseInt(notificationsCountElem);
  var notifications          = notificationsWrapper.find('.dropdown-menu-admin-list');

  if (notificationsCount <= 0) {
    notificationsWrapper.hide();
  }

  // Enable pusher logging - don't include this in production
  Pusher.logToConsole = true;

  var pusher = new Pusher('1d89ed9b6027d9112fb0', {
    cluster: 'ap2'
  });

  // Subscribe to the channel we specified in our Laravel Event
  var channel = pusher.subscribe('my-channel');



 channel.bind("App\\Events\\PublisherEvent", function(data) {
     console.log(data);
 });

 var auth_user_id  = "<?php echo e(auth()->user()->id); ?>";

 console.log("gadf="+auth_user_id);

  // Bind a function to a Event (the full Laravel class)
  channel.bind('my-event', function(data) {
    var existingNotifications = notifications.html();
    var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
    var newNotificationHtml = `<a class="d-flex" href="`+data.url_now+`">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar-content">MD</div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">`+data.message+`</p>
                                            <small class="notification-text">`+data.description+`</small>
                                    </div>
                                </div>
                            </a>`;


                          if(data.notification_for=="2" && auth_user_id==data.notify_to){
                                // notifications.html(newNotificationHtml + existingNotifications);
                                 notificationsCount += 1;
                                // notificationsCountElem.attr('data-count', notificationsCount);
                                notificationsWrapper.find('.admin-notification-count').text(notificationsCount);
                                notificationsWrapper.show();
                            }


  });
</script>

<script type="text/javascript">

    $(".admin_notify_click").click(function(){

    var html_ab = "";

        $.ajax({
            url: "<?php echo e(route('publisher.ajax_publisher_notification')); ?>",
            method: 'GET',
            success: function (response) {
                $.each(response, function(key,value){

                     html_ab += `<a class="d-flex" href="`+value.url+`">
                                <div class="media d-flex align-items-start">
                                    <div class="media-left">
                                        <div class="avatar-content">TPH</div>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading"><span class="font-weight-bolder">`+value.title+`</p>
                                            <small class="notification-text">`+value.description+`</small>
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











</body>
<!-- END: Body-->

</html>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/client/layout/app.blade.php ENDPATH**/ ?>