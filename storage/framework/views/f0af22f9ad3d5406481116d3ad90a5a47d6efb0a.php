<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" type="image/x-icon" href="/assets/admin-logo-small.svg">
    <title>The Party Finder</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/v2/admin/plugins/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
          rel="stylesheet">
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>

    <!-- Theme style -->
    <link rel="stylesheet" href="/v2/admin/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>"/>
    
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/components.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/colors.css')); ?>">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.6.0/bootstrap-tagsinput.min.css">
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <link href="/v2/css/daterangepicker.css" id="app-style" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>"/>
    <?php echo $__env->yieldContent('css'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<DIV id="prepage"
     style="position: fixed; z-index: 99999999; filter:alpha(opacity=60); opacity:0.6; font-family:arial; font-size:16px; left:0px; top:0px; background-color: #ECEDEF; layer-background-color: #ECEDEF; height:100%; width:100%; display:none;">
    <TABLE width="100%" height="100%" align="center">
        <TR>
            <TD width="100%" align="center"><B>
                    <center><img src="/v2/images/1.gif"/></center>
                </B></TD>
        </TR>
    </TABLE>
</DIV>
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                        class="fas fa-bars"></i></a>
            </li>
            
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown admin-dropdown-notification" id="gamer_one">
                <a class="nav-link admin_notify_click" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span
                        class="badge badge-warning navbar-badge admin-notification-count"><?php echo e($admin_notifcation_count); ?></span>
                </a>
                <div
                    class="scrollable-container dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-menu-admin-list">
                        <span class="dropdown-header"><span
                                class="admin-notification-count"><?php echo e($admin_notifcation_count); ?></span>
                            Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo e(otherImage(auth()->user()->profile_picture)); ?>"
                         class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline"><?php echo e(ucwords(auth()->user()->name)); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">

                    <li class="user-header bg-primary">
                        <img src="<?php echo e(otherImage(auth()->user()->profile_picture)); ?>" class="img-circle elevation-2"
                             alt="User Image">
                        <p>
                            <?php echo e(auth()->user()->name); ?> - <?php echo e(auth()->user()->user_type == 1 ? 'Admin' : 'Client'); ?>

                            <small>Member since Nov. 2012</small>
                        </p>
                    </li>

                    

                    <li class="user-footer">
                        <a href="javascript:void(0)" class="btn btn-default btn-flat" id="profile_btn">Profile</a>
                        <a href="<?php echo e(route('logout')); ?>" class="btn btn-default btn-flat float-right">Sign out</a>
                    </li>
                </ul>
            </li>

            
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-pink elevation-4">
        <!-- Brand Logo -->
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
            <img src="/v2/images/logo.png" alt="<?php echo e(config('app.name')); ?> Logo" class="brand-logo img-fluid"
                 style="opacity: .8">
            
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">

                    <?php if(auth()->user()->user_type == 1 || auth()->user()->user_type == 3): ?>

                    <?php $array_venue = array('venue-view'); ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_venue)): ?>
                        <li class=" nav-item">
                            <a class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/venue*')) ? 'active' : ''); ?>" href="<?php echo e(route('admin.venue.index')); ?>">
                                <img <?php if(request()->is('admin/venue*')): ?> src="<?php echo e(asset('assets/icon/001-02.png')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-02.svg')); ?>" <?php endif; ?> height="20">
                                <span class="menu-title text-truncate ml-1" data-i18n="File Manager"> Venues</span>
                            </a>
                        </li>
                    <?php endif; ?>
                        <?php $array_event = array('event-view'); ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_event)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link"
                                <?php echo e((request()->is('admin/event*')) ? 'active' : ''); ?> href="<?php echo e(route('admin.event.index')); ?>"><img
                                    <?php if(request()->is('admin/even*')): ?> src="<?php echo e(asset('assets/icon/001-03.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" <?php endif; ?> height="20"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">Events</span></a></li>
                    <?php endif; ?>
                        <?php $array_education = array('education-view'); ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_education)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link"
                                <?php echo e((request()->is('admin/education*')) ? 'active' : ''); ?> href="<?php echo e(route('admin.education.index')); ?>"><img
                                    <?php if(request()->is('admin/education*')): ?> src="<?php echo e(asset('assets/icon/001-03.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" <?php endif; ?> height="20"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">Education</span></a></li>
                    <?php endif; ?>

                    <?php $array_buysell = ['buy-sell-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_buysell)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/buy_and_sell*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.buy-sell.index')); ?>"><img
                                    <?php if(request()->is('admin/buy_and_sell*')): ?> src="<?php echo e(asset('assets/icon/001-17.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="Invoice">Buy & Sell</span></a>
                        </li>
                    <?php endif; ?>

                    <?php $array_crypto = ['crypto-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_crypto)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/buy_and_sell*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.crypto.index')); ?>"><img
                                    <?php if(request()->is('admin/crypto*')): ?> src="<?php echo e(asset('assets/icon/001-17.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="Invoice">Crypto</span></a>
                        </li>
                    <?php endif; ?>

                    <?php $array_brand = ['brand-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_brand)): ?>
                    <li class="nav-item">
                        <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/brand*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.brand.index')); ?>">
                            <img <?php if(request()->is('admin/brand*')): ?> src="<?php echo e(asset('assets/icon/001-17.svg')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" <?php endif; ?> height="20">
                            <span class="menu-title text-truncate ml-1" data-i18n="Invoice">Brands</span>
                        </a>
                    </li>
                    <?php endif; ?>

                   


                        <?php $array_directories = ['directory-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_directories)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/directories*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.directories.index')); ?>"><img
                                    <?php if(request()->is('admin/directories*')): ?> src="<?php echo e(asset('assets/icon/001-05.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-05.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="Invoice">Directories</span></a>
                        </li>
                    <?php endif; ?>

                        <?php $array_concierge = ['concierge-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_concierge)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/concierge') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.concierge.index')); ?>"><img
                                    <?php if(request()->is('admin/concierge')): ?> src="<?php echo e(asset('assets/icon/001-04.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Concierge</span></a>
                        </li>
                    <?php endif; ?>



                        <?php $array_influencer = ['influencer-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_influencer)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/influencers*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.influencers.index')); ?>"><img
                                    <?php if(request()->is('admin/influencers*')): ?> src="<?php echo e(asset('assets/icon/001-06.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-06.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Influencers</span></a>
                    <?php endif; ?>


                    <?php $array_talent = ['talent-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_talent)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/talents*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.talents.index')); ?>"><img
                                    <?php if(request()->is('admin/talents*')): ?> src="<?php echo e(asset('assets/icon/001-06.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-06.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Talents</span></a>
                    <?php endif; ?>

                        <?php $array_it = ['it-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_it)): ?>
                        <li class="nav-item">
                            <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/it*') ? 'active' : ''); ?>"
                               href="<?php echo e(route('admin.it.index')); ?>"><img
                                    <?php if(request()->is('admin/it*')): ?> src="<?php echo e(asset('assets/icon/001-11.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/001-11.png')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">IT</span></a>
                        </li>
                    <?php endif; ?>

                        <?php $array_job = ['job-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_job)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/job/*') || request()->is('admin/job') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.job.index')); ?>"><img
                                    <?php if(request()->is('admin/job/*')): ?> src="<?php echo e(asset('assets/icon/001-07.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-07.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Jobs</span></a>
                    <?php endif; ?>
                            <?php $array_job = ['company-view']; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_job)): ?>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/job-company*') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.job-company.index')); ?>"><img
                                        <?php if(request()->is('admin/job-company*')): ?> src="<?php echo e(asset('assets/icon/001-07.png')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-07.svg')); ?>" <?php endif; ?>
                                        height="20"><span class="menu-title text-truncate ml-1"
                                                          data-i18n="File Manager">Job Companies</span></a>
                        <?php endif; ?>
                        <?php $array_book_artist = ['book-artist-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_book_artist)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/book-artist*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.book-artist.index')); ?>"><img
                                    <?php if(request()->is('admin/book-artist*')): ?> src="<?php echo e(asset('assets/icon/001-26.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-26.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Book Artists</span></a>
                    <?php endif; ?>

                        <?php $array_ticket = ['ticket-view', 'ticket-add']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_ticket)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/tickets') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.tickets.index')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-18.svg')); ?>" height="20"><span
                                    class="menu-title text-truncate  ml-1" data-i18n="Invoice">Tickets</span><i
                                    class="right fas fa-angle-right"></i></a>
                            <ul class="nav nav-treeview">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ticket-view')): ?>
                                    <li class="nav-item"><a
                                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/tickets') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('admin.tickets.index')); ?>"><i
                                                data-feather="circle"></i><span class="menu-item text-truncate"
                                                                                data-i18n="List">Tickets</span></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ticket-add')): ?>
                                    <li class="nav-item"><a
                                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/tickets/create') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('admin.tickets.create')); ?>"><i
                                                data-feather="circle"></i><span class="menu-item text-truncate"
                                                                                data-i18n="List">Create Tickets</span></a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/ticket-banner') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.ticket-banner')); ?>"><i
                                            data-feather="circle"></i><span class="menu-item text-truncate"
                                                                            data-i18n="List">Ticket Banner</span></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                        <?php $array_accomadation = ['accomadation-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_accomadation)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/accommodation*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.accommodation.index')); ?>"><img
                                    <?php if(request()->is('admin/accommodation*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager"> Property</span></a>
                        </li>
                    <?php endif; ?>
                    <?php $array_motors = ['motors-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_motors)): ?>
                       
                    <?php endif; ?>

                    <?php $array_category = ['motors-view','motors-manufacturer-view', 'motors-company-view', 'motors-agent-view','motors-specification-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_category)): ?>
                    <li class="nav-item">
                        <a class="d-flex align-items-center nav-link" href="#">
                            <img src="<?php echo e(asset('assets/icon/Admin-Panel-09.svg')); ?>" height="20">
                            <span class="menu-title text-truncate ml-1" data-i18n="Invoice">Motors</span>
                            <i class="right fas fa-angle-right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('motors-view')): ?>
                             <li class=" nav-item">
                                <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/motors*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.motors.index')); ?>">
                                    <img <?php if(request()->is('admin/motors*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?>
                                    height="20">
                                    <span class="menu-title text-truncate ml-1" data-i18n="File Manager"> Motors</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('motors-manufacturer-view')): ?>
                            <li class="nav-item">
                                <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/motor/manufacturers*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.manufacturer.index')); ?>">
                                    <img <?php if(request()->is('admin/motor/manufacturer*')): ?> src="<?php echo e(asset('assets/icon/001-17.svg')); ?>" <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" <?php endif; ?> height="20">
                                    <span class="menu-title text-truncate ml-1"  data-i18n="Invoice">Manufacturers</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('motors-company-view')): ?>
                            <li class="nav-item">
                                <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/motor/companies*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.company.index')); ?>">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Preview">Companies</span>
                                </a>
                            </li>
                            <?php endif; ?>


                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('motors-agent-view')): ?>
                            <li class="nav-item"><a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/motor/agents*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.agent.index')); ?>">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Edit">Agents</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('motors-specification-view')): ?>
                            <li class="nav-item"><a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/motor/specification*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.specification.index')); ?>">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Edit">Sub Category</span>
                                </a>
                            </li>
                            <?php endif; ?>
                           
                        </ul>
                    </li>
                    <?php endif; ?>

                        <?php $array_career = ['career-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_career)): ?>
                        <li class=" nav-item">
                            <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/career*') ? 'active' : ''); ?>"
                               href="<?php echo e(url('admin/career/list')); ?>">
                                <img <?php if(request()->is('admin/career*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                                     <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?>
                                     height="20">
                                <span class="menu-title text-truncate ml-1" data-i18n="File Manager"> Career
                                            List</span>
                            </a>
                        </li>
                    <?php endif; ?>
                        <?php $array_recommendation = ['recommendation-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_recommendation)): ?>
                        <li class=" nav-item">
                            <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/recommendation*') ? 'active' : ''); ?>"
                               href="<?php echo e(url('admin/recommendation/list')); ?>">
                                <img
                                    <?php if(request()->is('admin/recommendation*')): ?> src="<?php echo e(asset('assets/icon/001-19.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" <?php endif; ?>
                                    height="20">
                                <span class="menu-title text-truncate ml-1" data-i18n="File Manager">
                                            Recommendation</span>
                            </a>
                        </li>
                    <?php endif; ?>
                        <?php $array_attractions = ['attractions-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_attractions)): ?>
                        <li class=" nav-item <?php echo e(request()->is('admin/attractions*') ? 'active' : ''); ?>"><a
                                class="d-flex align-items-center nav-link"
                                href="<?php echo e(route('admin.tickets.index')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-18.svg')); ?>" height="20"><span
                                    class="menu-title text-truncate  ml-1" data-i18n="Invoice">Attraction</span>
                                <i class="right fas fa-angle-right"></i></a>
                            <ul class="nav nav-treeview">
                                <li class=" nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/attractions*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.attractions.index')); ?>"><img
                                            <?php if(request()->is('admin/attractions*')): ?> src="<?php echo e(asset('assets/icon/001-25.svg')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-25.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager"> Attractions</span></a></li>
                                <li class=" nav-item">
                                    <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/search-page-attraction') ? 'active' : ''); ?>"
                                       href="<?php echo e(route('admin.search-page-attraction.index')); ?>"><img
                                            <?php if(request()->is('admin/search-page-attraction')): ?> src="<?php echo e(asset('assets/icon/001-25.svg')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-25.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager"> Attractions Search Page</span></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                        <?php $array_popular_types = ['popular-places-types']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_popular_types)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/popular-places-type*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.popular-places-type.index')); ?>"><img
                                    <?php if(request()->is('admin/attractions*')): ?> src="<?php echo e(asset('assets/icon/001-25.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-25.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Poplular Places Types</span></a></li>
                    <?php endif; ?>


                        <?php $array_popular_types = ['top-trends']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_popular_types)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/top-trend*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.top-trend.index')); ?>"><img
                                    <?php if(request()->is('admin/attractions*')): ?> src="<?php echo e(asset('assets/icon/001-25.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-25.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Top Trends Popular</span></a>
                        </li>
                    <?php endif; ?>



                        <?php $array_book_table = ['book-table-view']; ?>
                    
                    <?php endif; ?>
                    <hr
                        style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">

                    <?php $array_category = ['main-category-view', 'sub-category-view', 'major-category-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_category)): ?>
                    <li class="nav-item">
                        <a class="d-flex align-items-center nav-link" href="#">
                            <img src="<?php echo e(asset('assets/icon/Admin-Panel-09.svg')); ?>" height="20">
                            <span class="menu-title text-truncate ml-1" data-i18n="Invoice">Category</span>
                            <i class="right fas fa-angle-right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('major-category-view')): ?>
                            <li class="nav-item">
                                <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/major-category*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.major-category.index')); ?>">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="List">Major Category</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('main-category-view')): ?>
                            <li class="nav-item">
                                <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/main-category*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.main-category.index')); ?>">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Preview">Main Category</span>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub-category-view')): ?>
                            <li class="nav-item"><a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/sub-category*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.sub-category.index')); ?>">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="Edit">Sub Category</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                        <?php $array_amenitie = ['amenity-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_amenitie)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/amenties*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.amenties.index')); ?>"><img
                                    <?php if(request()->is('admin/amenties*')): ?> src="<?php echo e(asset('assets/icon/001-10.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-10.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="Invoice">Amenities</span></a></li>
                    <?php endif; ?>

                        <?php $array_dynamic_category = ['dynamic-major-category-view']; ?>


                        <?php $array_user_manage = ['user-manage']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_user_manage)): ?>
                        <li class="nav-item ">
                            <a class="d-flex align-items-center nav-link" href="#"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-12.svg')); ?>" height="20"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">User Management</span>
                                <i class="right fas fa-angle-right"></i></a>
                            <ul class="nav nav-treeview">
                                
                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/create-role') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.create-role')); ?>"><i data-feather="circle"></i><span
                                            class="menu-item text-truncate" data-i18n="List">Create Role</span></a>
                                </li>

                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/create-user') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.create-user')); ?>"><i data-feather="circle"></i><span
                                            class="menu-item text-truncate" data-i18n="List">Create User</span></a>
                                </li>


                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/users') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.users')); ?>"><i data-feather="circle"></i><span
                                            class="menu-item text-truncate" data-i18n="List">All Admin
                                                Users</span></a>
                                </li>

                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/client-users') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.client-users')); ?>"><i data-feather="circle"></i><span
                                            class="menu-item text-truncate" data-i18n="List">All Client
                                                Users</span></a>
                                </li>

                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/publish-user    ') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.publish-user')); ?>"><i data-feather="circle"></i><span
                                            class="menu-item text-truncate" data-i18n="List">All Publish
                                                Users</span></a>
                                </li>

                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/job_seeker_user') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.job_seeker_user')); ?>"><i
                                            data-feather="circle"></i><span class="menu-item text-truncate"
                                                                            data-i18n="List">All Job Sekeer Users</span></a>
                                </li>

                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/newsletter_list') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.newsletter_list')); ?>"><i
                                            data-feather="circle"></i><span class="menu-item text-truncate"
                                                                            data-i18n="List">All Newsletter</span></a>
                                </li>

                                <li class="nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/guest_users') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.guest_users')); ?>"><i data-feather="circle"></i><span
                                            class="menu-item text-truncate" data-i18n="List">All Guest
                                                Users</span></a>
                                </li>

                            </ul>
                        </li>
                    <?php endif; ?>

                    <hr
                        style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">

                        <?php $array_gallery = ['gallery-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_gallery)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/gallery*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.gallery.index')); ?>"><img
                                    <?php if(request()->is('admin/gallery*')): ?> src="<?php echo e(asset('assets/icon/001-13.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-13.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Gallery</span></a>
                    <?php endif; ?>


                        <?php $array_enquiry = ['enquiry-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_enquiry)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/enquiry') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.enquiry.index')); ?>"><img
                                    <?php if(request()->is('admin/enquiry*')): ?> src="<?php echo e(asset('assets/icon/001-14.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-14.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Enquiry</span></a>
                    <?php endif; ?>

                        <?php $array_reveiew = ['view-review']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_reveiew)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/review') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.review.index')); ?>"><img
                                    <?php if(request()->is('admin/review*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Review</span></a>
                    <?php endif; ?>

                        <?php $array_reveiew = ['view-wishlist']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_reveiew)): ?>
                        <li class=" nav-item <?php echo e(request()->is('admin/wishlists') ? 'active' : ''); ?>"><a
                                class="d-flex align-items-center nav-link"
                                href="<?php echo e(route('admin.wishlists')); ?>"><img
                                    <?php if(request()->is('admin/wishlists*')): ?> src="<?php echo e(asset('assets/icon/001-16.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-16.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Wishlists</span></a>
                    <?php endif; ?>


                    <li class="nav-item">
                        <a class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/banner') ? 'active' : ''); ?> <?php echo e(request()->is('admin/contact') ? 'active' : ''); ?>"
                           href="#"><img src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>"
                                         height="20"><span class="menu-title text-truncate ml-1"
                                                           data-i18n="Invoice">All Banner</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/banner') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.banner.index')); ?>"><img
                                        <?php if(request()->is('admin/banner*')): ?> src="<?php echo e(asset('assets/icon/001-15.svg')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-15.svg')); ?>" <?php endif; ?>
                                        height="20"><span class="menu-title text-truncate ml-1"
                                                          data-i18n="File Manager">Banners</span></a>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link  <?php echo e(request()->is('admin/contact') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.contact.index')); ?>"><img
                                        <?php if(request()->is('admin/contact*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                        height="20"><span class="menu-title text-truncate ml-1"
                                                          data-i18n="File Manager">Static Banner</span></a>
                        </ul>
                    </li>


                        <?php $array_blogs = ['blog-view']; ?>




                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_blogs)): ?>
                        <li class="nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/blog*') ? 'active' : ''); ?>"
                                href="#"><img src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>"
                                              height="20"><span class="menu-title text-truncate ml-1"
                                                                data-i18n="Invoice">Blog</span>
                                <i class="right fas fa-angle-right"></i></a>
                            <ul class="nav nav-treeview">
                                <li class=" nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/blogs') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.blogs.index')); ?>"><img
                                            <?php if(request()->is('admin/blogs')): ?> src="<?php echo e(asset('assets/icon/001-08.png')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager">All Posts</span></a>
                                <li class=" nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/blogs/create*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.blogs.create')); ?>"><img
                                            <?php if(request()->is('admin/blogs/create*')): ?> src="<?php echo e(asset('assets/icon/001-08.png')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager">Add New</span></a>
                                <li class=" nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/blog-category*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.blog-category.index')); ?>"><img
                                            <?php if(request()->is('admin/blog-category*')): ?> src="<?php echo e(asset('assets/icon/001-08.png')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager">Categories</span></a>
                                <li class=" nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/tag-blog*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.tag-blog.index')); ?>"><img
                                            <?php if(request()->is('admin/tag-blog*')): ?> src="<?php echo e(asset('assets/icon/001-08.png')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager">Tag</span></a>
                                <li class=" nav-item"><a
                                        class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/blog-comments*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.blog-comments.index')); ?>"><img
                                            <?php if(request()->is('admin/blog-comments*')): ?> src="<?php echo e(asset('assets/icon/001-08.png')); ?>"
                                            <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" <?php endif; ?>
                                            height="20"><span class="menu-title text-truncate ml-1"
                                                              data-i18n="File Manager">Comments</span></a>

                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_blogs)): ?>
                        <li class=" nav-item <?php echo e(request()->is('admin/give-away*') ? 'active' : ''); ?>"><a
                                class="d-flex align-items-center nav-link"
                                href="<?php echo e(route('admin.give-away.index')); ?>"><img
                                    <?php if(request()->is('admin/give-away*')): ?> src="<?php echo e(asset('assets/icon/001-27.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-27.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Give Away</span></a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_blogs)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/home-section-content*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.home-section-content.create')); ?>"><img
                                    <?php if(request()->is('admin/home-section-content*')): ?> src="<?php echo e(asset('assets/icon/001-27.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-27.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Home Section Content</span></a>
                    <?php endif; ?>




                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_blogs)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/claim-give-away*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.claim-give-away.index')); ?>"><img
                                    <?php if(request()->is('admin/claim-give-away*')): ?> src="<?php echo e(asset('assets/icon/001-27.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-27.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Give Away Claim</span></a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_blogs)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/influencer-reviews*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.influencer-reviews.index')); ?>"><img
                                    <?php if(request()->is('admin/influencer-reviews*')): ?> src="<?php echo e(asset('assets/icon/001-27.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-27.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Influencer Review</span></a>
                    <?php endif; ?>

                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/news*') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.news.index')); ?>"><img
                                <?php if(request()->is('admin/news*')): ?> src="<?php echo e(asset('assets/icon/001-08.png')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" <?php endif; ?>
                                height="20"><span class="menu-title text-truncate ml-1"
                                                  data-i18n="File Manager">News</span></a>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_dynamic_category)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/dynamic*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.dynamic-major-category')); ?>"><img
                                    <?php if(request()->is('admin/dynamic*')): ?> src="<?php echo e(asset('assets/icon/001-10.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-10.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Dynamic Category</span></a>
                        </li>
                    <?php endif; ?>

                        <?php $array_city = ['city-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_city)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/city*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.city.index')); ?>"><img
                                    <?php if(request()->is('admin/city*')): ?> src="<?php echo e(asset('assets/icon/001-02.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-02.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">City</span></a>
                    <?php endif; ?>

                        <?php $array_landmark = ['landmark-view']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_landmark)): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/landmark*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.landmark.index')); ?>"><img
                                    <?php if(request()->is('admin/landmark*')): ?> src="<?php echo e(asset('assets/icon/001-02.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-02.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Landmark</span></a>
                    <?php endif; ?>

                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/alert-news*') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.alert-news.index')); ?>"><img
                                <?php if(request()->is('admin/alert-news*')): ?> src="<?php echo e(asset('assets/icon/001-10.png')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-10.svg')); ?>" <?php endif; ?>
                                height="20"><span class="menu-title text-truncate ml-1"
                                                  data-i18n="File Manager">Alert News</span></a>

                        <hr
                            style="margin: 0.5em auto;height:1px;border-width:0;color:#cfced1;background-color:#cfced1;width:70%;">


                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('about-us')): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/about_us') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.about_us.index')); ?>"><img
                                    <?php if(request()->is('admin/about_us*')): ?> src="<?php echo e(asset('assets/icon/001-20.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">About Us</span></a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('career')): ?>
                        <li class=" nav-item <?php echo e(request()->is('admin/career') ? 'active' : ''); ?>"><a
                                class="d-flex align-items-center nav-link" href="<?php echo e(route('admin.career')); ?>"><img
                                    <?php if(request()->is('admin/career*')): ?> src="<?php echo e(asset('assets/icon/001-21.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-21.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Careers</span></a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('privacy-policy')): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/privacy-policy') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.privacy-policy')); ?>"><img
                                    <?php if(request()->is('admin/privacy-policy*')): ?> src="<?php echo e(asset('assets/icon/001-22.svg')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-22.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Privacy Policy</span></a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('terms-conditions')): ?>
                        <li class=" nav-item"><a
                                class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/terms-conditions') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.terms-conditions')); ?>"><img
                                    <?php if(request()->is('admin/terms-conditions*')): ?> src="<?php echo e(asset('assets/icon/001-10.png')); ?>"
                                    <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-10.svg')); ?>" <?php endif; ?>
                                    height="20"><span class="menu-title text-truncate ml-1"
                                                      data-i18n="File Manager">Terms & Conditions</span></a>
                    <?php endif; ?>

                        <?php $array_faqs = ['faqs-view', 'faqs-add']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_faqs)): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center nav-link"
                                                 href="<?php echo e(url('admin.faqs.index')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-24.svg')); ?>" height="20"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">FAQs</span>
                                <i class="right fas fa-angle-right"></i></a>
                            <ul class="nav nav-treeview">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faqs-view')): ?>
                                    <li class="nav-item"><a
                                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/faqs') ? 'active' : ''); ?>"
                                            href="<?php echo e(url('admin/faqs')); ?>"><i data-feather="circle"></i><span
                                                class="menu-item text-truncate ml-1" data-i18n="List">FAQs
                                                    Category</span></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faqs-add')): ?>
                                    <li class="nav-item"><a
                                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/faqs_q_and_a') ? 'active' : ''); ?>"
                                            href="<?php echo e(url('admin/faqs_q_and_a')); ?>"><i data-feather="circle"></i><span
                                                class="menu-item text-truncate ml-1"
                                                data-i18n="List">FAQs Q&A</span></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>




                        <?php $array_city_guide = ['city_guides']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_city_guide)): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center nav-link"
                                                 href="<?php echo e(url('admin/city_guide')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-21.svg')); ?>" height="20"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">City Guide</span></a>
                        </li>
                    <?php endif; ?>

                        <?php $array_report_fraude = ['report_fraude']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_report_fraude)): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center nav-link"
                                                 href="<?php echo e(url('admin/report_fraude')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-23.svg')); ?>" height="20"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">Fraud Report</span></a>
                        </li>
                    <?php endif; ?>

                        <?php $array_cookies_policy = ['cookies_policy']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_cookies_policy)): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center nav-link"
                                                 href="<?php echo e(url('admin/cookies_policy')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-22.svg')); ?>" height="22"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">Cookies
                                        Policy</span></a>
                        </li>
                    <?php endif; ?>

                        <?php $array_investor_relation = ['investor_relation']; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($array_investor_relation)): ?>
                        <li class=" nav-item"><a class="d-flex align-items-center nav-link"
                                                 href="<?php echo e(url('admin/investor_relation')); ?>"><img
                                    src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                    class="menu-title text-truncate ml-1" data-i18n="Invoice">Investor
                                        Relation</span></a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Reservation</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/reservation/event') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/reservation/event')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1" data-i18n="Invoice">Event
                                            Reservation</span></a>
                            </li>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/reservation/venue') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/reservation/venue')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1" data-i18n="Invoice">Venue
                                            Reservation</span></a>
                            </li>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/reservation/concierge') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/reservation/concierge')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1" data-i18n="Invoice">Concierge
                                            Reservation</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item <?php echo e(request()->is('admin/whatsapp-text') ? 'active' : ''); ?>"><a
                            class="d-flex align-items-center nav-link"
                            href="<?php echo e(route('admin.whatsapp-text.index')); ?>"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-23.svg')); ?>" height="22"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Whatsapp Text</span></a>
                    </li>

                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Footer</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/social-media') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/social-media')); ?>"><i data-feather="circle"></i><span
                                        class="menu-item text-truncate ml-1" data-i18n="List">Social
                                            Media</span></a>
                            </li>
                            <li class="nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/play-store') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/play-store')); ?>"><i data-feather="circle"></i><span
                                        class="menu-item text-truncate ml-1" data-i18n="List">Play Store
                                            Link</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/invoice-generator') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.invoice-generator.index')); ?>"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-23.svg')); ?>" height="22"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Invoice
                                    Generator</span></a>
                    </li>
                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Login Banner</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/publisher-login-banner') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.publisher-login-banner.index')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1" data-i18n="Invoice">Publisher Login
                                            Banner</span></a>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/user-signup-banner') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.user-signup-banner.index')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1" data-i18n="Invoice">User Signup
                                            Banner</span></a>
                        </ul>
                    </li>

                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/publisher-faq') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.publisher-faq.index')); ?>"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" height="22"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Publisher FAQ</span></a>
                    </li>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/find-all-cv') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.find-all-cv')); ?>"><img
                                <?php if(request()->is('admin/find-all-cv*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                height="20"><span class="menu-title text-truncate ml-1"
                                                  data-i18n="File Manager">Find CV's</span></a>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/inbound-seo.index') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.inbound-seo.index')); ?>"><img
                                <?php if(request()->is('admin/contact*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                height="20"><span class="menu-title text-truncate ml-1"
                                                  data-i18n="File Manager">Inbound SEO</span></a>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/list-recommendation') ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.list-recommendation')); ?>"><img
                                <?php if(request()->is('admin/list-recommendation*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                height="20"><span class="menu-title text-truncate ml-1"
                                                  data-i18n="File Manager">Recommendation</span></a>

                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Popular Place
                                    Venue</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/popular-place-venue') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.popular-place-venue.index')); ?>"><img
                                        <?php if(request()->is('admin/list-recommendation*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                        height="20"><span class="menu-title text-truncate ml-1"
                                                          data-i18n="File Manager">Popular Place Venue</span></a>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e(request()->is('admin/popular-place-suggestion') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.popular-place-suggestion')); ?>"><img
                                        <?php if(request()->is('admin/list-recommendation*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?>
                                        height="20"><span class="menu-title text-truncate ml-1"
                                                          data-i18n="File Manager">Popular Place Suggestion</span></a>
                        </ul>
                    </li>


                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Reservation</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/reservation/event')) ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/reservation/event')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="Invoice">Event Reservation</span></a>
                            </li>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/reservation/venue')) ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/reservation/venue')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="Invoice">Venue Reservation</span></a>
                            </li>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/reservation/concierge')) ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/reservation/concierge')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="Invoice">Concierge Reservation</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item <?php echo e((request()->is('admin/whatsapp-text')) ? 'active' : ''); ?>"><a
                            class="d-flex align-items-center nav-link"
                            href="<?php echo e(route('admin.whatsapp-text.index')); ?>"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-23.svg')); ?>" height="22"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Whatsapp Text</span></a>
                    </li>

                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Footer</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/social-media')) ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/social-media')); ?>"><i
                                        data-feather="circle"></i><span class="menu-item text-truncate ml-1"
                                                                        data-i18n="List">Social Media</span></a>
                            </li>
                            <li class="nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/play-store')) ? 'active' : ''); ?>"
                                    href="<?php echo e(url('admin/play-store')); ?>"><i
                                        data-feather="circle"></i><span class="menu-item text-truncate ml-1"
                                                                        data-i18n="List">Play Store Link</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/invoice-generator')) ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.invoice-generator.index')); ?>"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-23.svg')); ?>" height="22"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Invoice Generator</span></a>
                    </li>
                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Login Banner</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/publisher-login-banner')) ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.publisher-login-banner.index')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="Invoice">Publisher Login Banner</span></a>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/user-signup-banner')) ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.user-signup-banner.index')); ?>"><img
                                        src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" height="22"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="Invoice">User Signup Banner</span></a>
                        </ul>
                    </li>

                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/publisher-faq')) ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.publisher-faq.index')); ?>"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" height="22"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Publisher FAQ</span></a>
                    </li>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/find-all-cv')) ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.find-all-cv')); ?>"><img
                                <?php if(request()->is('admin/find-all-cv*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?> height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="File Manager">Find CV's</span></a>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/inbound-seo.index')) ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.inbound-seo.index')); ?>"><img
                                <?php if(request()->is('admin/contact*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?> height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="File Manager">Inbound SEO</span></a>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/list-recommendation')) ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.list-recommendation')); ?>"><img
                                <?php if(request()->is('admin/list-recommendation*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?> height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="File Manager">Recommendation</span></a>

                    <li class="nav-item"><a class="d-flex align-items-center nav-link" href="#"><img
                                src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="Invoice">Popular Place Venue</span>
                            <i class="right fas fa-angle-right"></i></a>
                        <ul class="nav nav-treeview">
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/popular-place-venue')) ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.popular-place-venue.index')); ?>"><img
                                        <?php if(request()->is('admin/list-recommendation*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?> height="20"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="File Manager">Popular Place Venue</span></a>
                            <li class=" nav-item"><a
                                    class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/popular-place-suggestion')) ? 'active' : ''); ?>"
                                    href="<?php echo e(route('admin.popular-place-suggestion')); ?>"><img
                                        <?php if(request()->is('admin/list-recommendation*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                        <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?> height="20"><span
                                        class="menu-title text-truncate ml-1"
                                        data-i18n="File Manager">Popular Place Suggestion</span></a>
                        </ul>
                    </li>
                    <li class=" nav-item"><a
                            class="d-flex align-items-center nav-link <?php echo e((request()->is('admin/home-trend-banner')) ? 'active' : ''); ?>"
                            href="<?php echo e(route('admin.home-trend-banner.index')); ?>"><img
                                <?php if(request()->is('admin/trend-banner*')): ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>"
                                <?php else: ?> src="<?php echo e(asset('assets/icon/Admin-Panel-20.svg')); ?>" <?php endif; ?> height="20"><span
                                class="menu-title text-truncate ml-1" data-i18n="File Manager">Home Trend Banner</span></a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php echo $__env->yieldContent('content-header'); ?>

        <section class="content">
            <div class="container-fluid">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </section>
        <!-- Content Header (Page header) -->
        
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; <?php echo e(date('Y')); ?> <a href="https://myfinder.com">The Party
                Finder</a>.</strong> All rights
        reserved.
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
</div>
<div class="modal fade text-left" id="profile_modal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel120" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel120">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo e(route('admin.update_profile')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <button class="btn btn-primary" disabled style="opacity: 1;">Profile
                                Settings
                            </button>&nbsp;&nbsp;<a href="javascript:void(0)" id="password_reset"
                                                    style="color: black;">Change Password</a>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Email ID</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?php echo e(auth()->user()->email); ?>" placeholder="Enter Email Id"
                                   id="">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Profile Image</label>
                            <div class="icon-wrapper">
                                <img src="<?php echo e(otherImage(auth()->user()->profile_picture)); ?>" width="100px"
                                     style="border-radius: 50%;">&nbsp;
                                <input id="fileid" type="file" name="profile_pic" hidden/>
                                <input id="buttonid" type="button" class="btn btn-secondary btn-sm"
                                       value="Change Profile Picture"/>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Company Name</label>
                            <input type="text" name="company_name"
                                   value="<?php echo e(auth()->user()->company_name); ?>" class="form-control"
                                   placeholder="Company Name" id="">

                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Address</label>
                            <textarea id="" class="form-control" value="<?php echo e(auth()->user()->address); ?>" name="address"
                                      placeholder="<?php echo e(auth()->user()->address ?? 'Address...'); ?>"></textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Mobile Number</label>
                            <input type="text" name="mobile" value="<?php echo e(auth()->user()->mobile_no); ?>"
                                   class="form-control" placeholder="Enter Mobile Number" id="">
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

<div class="modal fade text-left" id="password_modal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel120" aria-hidden="true">
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
                            <input type="password" name="current_password" class="form-control"
                                   placeholder="Enter Current Password" id="old_psd" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">New Password</label>
                            <input type="password" name="new_password" class="form-control"
                                   placeholder="Enter New Password" id="new_psd" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Repeat Password</label>
                            <input type="password" name="repeat_password" class="form-control"
                                   placeholder="Enter Repeat Password" id="re_psd" required>
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
<div id="ajaxModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <p class="text-center">
                <img src="/v2/images/1.gif" align="center" class="img-responsive">
            </p>
        </div>
    </div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/v2/admin/plugins/jquery/jquery.min.js"></script>
<script src="/v2/js/moment.min.js"></script>
<script src="/v2/js/daterangepicker.js"></script>
<!-- Bootstrap 4 -->
<script src="/v2/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/v2/admin/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- AdminLTE App -->
<script src="/v2/admin/dist/js/adminlte.min.js"></script>

<!-- END: Theme JS-->


<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
<script>
    toastr.options = {
        "positionClass": "toast-top-center"
    };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".main-menu-content").scroll(function () {

        var offset = $(".main-menu-content").scrollTop();

        if (offset > 95) {
            $("#search_bar_li").addClass("sitcky_search_bar");
            // alert("sdfsdf");
        } else {
            $("#search_bar_li").removeClass("sitcky_search_bar");

        }

        console.log("fsdf", offset);

    });
    $('body #ajaxModal').on('hidden.bs.modal', function(e) {
        $(e.target).removeData('bs.modal').find('.modal-content').html('<p class="text-center">\n' +
            '                <img src="/v2/images/1.gif" align="center" class="img-responsive">\n' +
            '            </p>');
    });
    $('#ajaxModal').on('show.bs.modal', function (e) {
        var $this = $(this);
        $.ajax({
            url: e.relatedTarget.href,
            success: function(res) {
                $(e.currentTarget).find('.modal-content').html(res);
            },
            error:function(request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });

    });
</script>

<!-- BEGIN: Page JS-->
<!-- END: Page JS-->
<?php echo $__env->yieldContent('script'); ?>
<script>
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
<script>
    $('#profile_btn').click(function () {
        $('#profile_modal').modal('show');
    });

    $('#password_reset').click(function () {
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
            toastr.info("<?php echo e(Session::get('message')); ?>", "Information!", {
                timeOut: 10000,
                progressBar: true
            });
            break;

        case 'warning':
            toastr.warning("<?php echo e(Session::get('message')); ?>", "Warning!", {
                timeOut: 10000,
                progressBar: true
            });
            break;

        case 'success':
            toastr.success("<?php echo e(Session::get('message')); ?>", "Success!", {
                timeOut: 10000,
                progressBar: true
            });
            break;

        case 'error':
            toastr.error("<?php echo e(Session::get('message')); ?>", "Failed!", {
                timeOut: 10000,
                progressBar: true
            });
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
    // Pusher.logToConsole = true;

    var pusher = new Pusher('1d89ed9b6027d9112fb0', {
        cluster: 'ap2'
    });

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('my-channel');

    // Bind a function to a Event (the full Laravel class)
    channel.bind('my-event', function (data) {
        var existingNotifications = notifications.html();
        var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
        var newNotificationHtml = `<a href="` + data.url_now + `" class="dropdown-item">
                        <p><span class="font-weight-bolder">` + data.message + `</p>
                                            <small class="notification-text">` + data.description + `</small>
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>`;
        if (data.notification_for == "0") {
            // notifications.html(newNotificationHtml + existingNotifications);
            notificationsCount += 1;
            // notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.admin-notification-count').text(notificationsCount);
            // notificationsWrapper.show();
        }

    });
</script>

<script type="text/javascript">
    $(".admin_notify_click").click(function () {

        var html_ab = "";

        $.ajax({
            url: "<?php echo e(route('admin.ajax_admin_notification')); ?>",
            method: 'GET',
            success: function (response) {
                $.each(response, function (key, value) {

                    html_ab += `<a href="` + value.url + `" class="dropdown-item">
                        <p><span class="font-weight-bolder">` + value.title + `</p>
                                            <small class="notification-text">` + value.description + `</small>
                        <!--<span class="float-right text-muted text-sm">3 mins</span>-->
                    </a>
                    <div class="dropdown-divider"></div>`;

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
                $(this).hide(400); // MY CHANGE
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show(400); // MY CHANGE
                count++;
            }
        });
    });

    $(".menu-search").focus(function () {
        $(this).css('backgroundImage', 'none')
    });

    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
    });
</script>
<script>
    feather.replace()
</script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/layout/app.blade.php ENDPATH**/ ?>