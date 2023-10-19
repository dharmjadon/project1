
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/pages/dashboard-ecommerce.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')); ?>">
    <style>
        .card-title {
            color: #bf087f
        }

        .statistics .media-body h5 {
            color: #bf087f
        }

        .avatar img {
            border-radius: 0 !important;
        }

        .month-filter,
        .week-filter {
            border-radius: 40px;
            background-color: #D3D3D3;
            color: black;
        }

        /* @media (min-width: 720px) {
            #bar-chart {
                width:600px !important;
                height:200px !important;
            } */
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row match-height">
        <!-- Statistics Card -->
        <div class="col-xl-12 col-md-12 col-12 statistics">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Statistics</h4>
                    <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 mr-25 mb-0">Total Items</p>
                    </div>
                </div>
                <div class="card-body statistics-body">
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-02.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Venues</h5>
                                    <p class="font-small-3 mb-0">Active Venues - <?php echo e($total_venues); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_venues); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Events</h5>
                                    <p class="font-small-3 mb-0">Active Events - <?php echo e($total_events); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_events); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-03.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Educations</h5>
                                    <p class="font-small-3 mb-0">Active Education - <?php echo e($total_educations); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_educations); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-17.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Buy & Sell</h5>
                                    <p class="font-small-3 mb-0">Active Buy & Sell - <?php echo e($total_buysell); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_buysell); ?></p>
                                </div>
                            </div>
                        </div>
                         <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-04.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Concierges</h5>
                                    <p class="font-small-3 mb-0">Active Concierges - <?php echo e($total_concierges); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_concierges); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-05.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text  mb-0">Directories</h5>
                                    <p class="font-small-3 mb-0">Active Directories - <?php echo e($total_directory); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_directory); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-06.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Influencers</h5>
                                    <p class="font-small-3 mb-0">Active Influencers - <?php echo e($total_influencer); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_influencer); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-08.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Jobs</h5>
                                    <p class="font-small-3 mb-0">Active Jobs - <?php echo e($total_jobs); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_jobs); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-19.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Spaces</h5>
                                    <p class="font-small-3 mb-0">Active Spaces - <?php echo e($total_spaces); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_spaces); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-18.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Tickets</h5>
                                    <p class="font-small-3 mb-0">Active Tickets - <?php echo e($total_tickets); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_tickets); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-25.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Attractions</h5>
                                    <p class="font-small-3 mb-0">Active Attractions - <?php echo e($total_attractions); ?></p>
                                    <p class="font-small-3 mb-0">Waiting - <?php echo e($waiting_attractions); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mt-2">
                            <div class="media">
                                <div class="avatar bg-light-secondary mr-2">
                                    <div class="avatar-content">
                                        <img src="<?php echo e(asset('assets/icon/Admin-Panel-09.svg')); ?>" height="20">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h5 class="card-text mb-0">Meetup</h5>
                                    <p class="font-small-3 mb-0">Members - </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Statistics Card -->
    </div>

    

    
    <div class="row justify-content-center">
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h4 class="card-title">Recent Enquiries</h4>
                    </div>
                    <div class="row" style="justify-content: flex-end">
                        
                        <div class="col-lg-2 col-md-3 col-6">
                            <div class="month-dropdown">
                                <select class="form-control month-filter" name="month" id="month">
                                    <option value="1">1 Month</option>
                                    <option value="2">3 Months</option>
                                    <option value="3">6 Months</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <div class="week-dropdown ml-2">
                                <select class="form-control week-filter" name="week" id="week">
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 graph" id="chartReport">
                        <canvas id="bar-chart" width="600" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="row justify-content-center">
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Company Stats</h4>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="venue-tab" data-toggle="tab" href="#venue"
                                aria-controls="venue" role="tab" aria-selected="true">Venues
                                (<?php echo e(count($venues)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="event-tab" data-toggle="tab" href="#event" aria-controls="event"
                                role="tab" aria-selected="false">Events (<?php echo e(count($events)); ?>)</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" id="education-tab" data-toggle="tab" href="#education" aria-controls="education"
                                role="tab" aria-selected="false">Education (<?php echo e(count($educations)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="buy_sell-tab" data-toggle="tab" href="#buy_sell"
                                aria-controls="buy_sell" role="tab" aria-selected="false">Buy & Sell
                                (<?php echo e(count($buysell)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="directory-tab" data-toggle="tab" href="#directory"
                                aria-controls="directory" role="tab" aria-selected="false">Directories
                                (<?php echo e(count($directory)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="concierge-tab" data-toggle="tab" href="#concierge"
                                aria-controls="concierge" role="tab" aria-selected="false">Concierges
                                (<?php echo e(count($concierge)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="influencer-tab" data-toggle="tab" href="#influencer"
                                aria-controls="influencer" role="tab" aria-selected="false">Influencers
                                (<?php echo e(count($influencer)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="job-tab" data-toggle="tab" href="#job" aria-controls="job"
                                role="tab" aria-selected="false">Spaces (<?php echo e(count($accommodation)); ?>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attraction-tab" data-toggle="tab" href="#attraction"
                                aria-controls="attraction" role="tab" aria-selected="false">Attraction
                                (<?php echo e(count($attraction)); ?>)</a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="venue" aria-labelledby="venue-tab" role="tabpanel">

                            <table class="table" id="datatable1">
                                <thead>
                                    <tr>
                                        
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no = 1; ?>
                                    <?php $__currentLoopData = $venues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>

                                        <?php $sl_no++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="event" aria-labelledby="event-tab" role="tabpanel">
                            <table class="table" id="datatable2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no++;?>

                                    <?php $sl_no2 = 1; ?>
                                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no2); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>
                                        <?php $sl_no2++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                         <div class="tab-pane" id="education" aria-labelledby="education-tab" role="tabpanel">
                            <table class="table" id="datatable14">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no++;?>

                                    <?php $sl_no2 = 1; ?>
                                    <?php $__currentLoopData = $educations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no2); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>
                                        <?php $sl_no2++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="buy_sell" aria-labelledby="buy_sell-tab" role="tabpanel">
                            <table class="table" id="datatable3">
                                <thead>
                                    <tr>

                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no3 = 1; ?>
                                    <?php $__currentLoopData = $buysell; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no3); ?></td>
                                            <td><?php echo e($data->product_name); ?></td>
                                            <td><?php echo e($data->view_count); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>
                                        <?php $sl_no3++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="directory" aria-labelledby="directory-tab" role="tabpanel">
                            <table class="table" id="datatable4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no5= 1; ?>
                                    <?php $__currentLoopData = $directory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no5); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views_counter); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>

                                        <?php $sl_no5++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="concierge" aria-labelledby="concierge-tab" role="tabpanel">
                            <table class="table" id="datatable5">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no6 = 1; ?>
                                    <?php $__currentLoopData = $concierge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no6); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>
                                        <?php $sl_no6++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="influencer" aria-labelledby="influencer-tab" role="tabpanel">
                            <table class="table" id="datatable6">
                                <thead>
                                    <tr>
                                        


                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no7 = 1; ?>
                                    <?php $__currentLoopData = $influencer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no7); ?></td>
                                            <td><?php echo e($data->name); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>

                                        <?php $sl_no7++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="job" aria-labelledby="job-tab" role="tabpanel">
                            <table class="table" id="datatable7">
                                <thead>
                                    <tr>

                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no8 = 1; ?>
                                    <?php $__currentLoopData = $accommodation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no8); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>

                                        <?php $sl_no8++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="attraction" aria-labelledby="attraction-tab" role="tabpanel">
                            <table class="table" id="datatable8">
                                <thead>
                                    <tr>

                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Visitors</th>
                                        <th>Enquiry Form</th>
                                        <th>Email</th>
                                        <th>Call</th>
                                        <th>Whatsapp</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl_no9 = 1; ?>
                                    <?php $__currentLoopData = $attraction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($sl_no9); ?></td>
                                            <td><?php echo e($data->title); ?></td>
                                            <td><?php echo e($data->views); ?></td>
                                            <td><?php echo e($data->enquiries_count); ?></td>
                                            <td><?php echo e($data->click_count_email_count); ?></td>
                                            <td><?php echo e($data->click_count_phone_count); ?></td>
                                            <td><?php echo e($data->click_count_whatsapp_count); ?></td>
                                        </tr>

                                        <?php $sl_no9++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>





        </div>
    </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js')); ?>"></script>
    <script>
        // $(window).resize(function() {
        //     if($(window).width() < 1200)
        //     {
        //         var canvas = document.getElementById('bar-chart');
        //         var heightRatio = 1.5;
        //         canvas.height = canvas.width * heightRatio;
        //     }
        // });
        if ($(document).width() < 1200) {
            var canvas = document.getElementById('bar-chart');
            var heightRatio = 1.5;
            canvas.height = canvas.width * heightRatio;
        }
    </script>

    <script>
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var currentTab = $(e.target).attr('id'); // get current tab
                var split_ab = currentTab;
                if (split_ab == "venue-tab") {
                    var table = $('#datatable1').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "event-tab") {
                    var table = $('#datatable2').DataTable();
                    $('#container').css('display', 'block');
                    table.columns.adjust().draw();
                } else if (split_ab == "buy_sell-tab") {
                    var table = $('#datatable3').DataTable();
                    $('#container').css('display', 'block');
                    table.columns.adjust().draw();
                } else if (split_ab == "directory-tab") {
                    var table = $('#datatable4').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "concierge-tab") {

                    var table = $('#datatable5').DataTable();
                    $('#container').css('display', 'block');
                    table.columns.adjust().draw();
                    console.log("m here")
                } else if (split_ab == "influencer-tab") {
                    var table = $('#datatable6').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "job-tab") {
                    var table = $('#datatable7').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "attraction-tab") {
                    var table = $('#datatable8').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "education-tab") {
                    var table = $('#datatable14').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else {
                    var table = $('#datatable-37-1').DataTable();
                    $('#container').css('display', 'block');
                    table.columns.adjust().draw()

                }


            });
        });
    </script>

    <script>
        $(document).ready(function() {

            var table = $('#datatable2').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],
            });
            var table0 = $('#datatable').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],
            });
            var table11 = $('#datatable1').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],
            });

            var table1 = $('#datatable3').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],
            });

            var table2 = $('#datatable4').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],

            });
            var table4 = $('#datatable6').DataTable({
                responsive: true,

                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],

            });
            var table5 = $('#datatable5').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],

            });
             var table13 = $('#datatable13').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],

            });
            var table6 = $('#datatable7').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],

            });
            var table7 = $('#datatable8').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],

            });
            var table9 = $('#datatable9').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],
            });
            var table10 = $('#datatable10').DataTable({
                responsive: true,
                dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({
                        class: 'font-small-4 mr-50'
                    }) + 'Export',
                    buttons: [{
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                modifier: {
                                    page: 'all',
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'copy',
                            text: feather.icons['copy'].toSvg({
                                class: 'font-small-4 mr-50'
                            }) + 'Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ],
                }, ],
            });



            // Show tab when clicked
            $('.nav-tabs a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Recalculate column widths when displaying tab - this will active Responsive
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();
            });
        });
    </script>

    
    

    <script>
        $(".month-filter, .week-filter").on('change', function() {
            var id = $('.month-filter').val();
            var weekId = $('.week-filter').val();
            $.ajax({
                url: "<?php echo e(route('publisher.month-filter')); ?>",
                method: 'POST',
                data: {
                    monthId: id,
                    weekId: weekId,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    var data = response.enquiry
                    var whatsapp = response.whatsapp
                    var email = response.email
                    var phone = response.phone
                    $("canvas").remove();
                    document.querySelector("#chartReport").innerHTML =
                        '<canvas id="bar-chart" width="600" height="200"></canvas>';
                    new Chart(document.getElementById("bar-chart"), {
                        type: 'bar',
                        data: {
                            labels: ["Venues", "Events", "Education", "Buy & Sell", "Directories",
                                "Concierge", "Influencers", "Spaces", "Attractions"
                            ],
                            datasets: [{
                                    label: "Enquiry Form",
                                    backgroundColor: "#000",
                                    data: [data.venue, data.event,data.education, data.buysell, data
                                        .directory, data.concierge, data.influencer,
                                        data.accommodation, data.attraction
                                    ]
                                },
                                {
                                    label: "Whatsapp",
                                    backgroundColor: "#682e82",
                                    data: [whatsapp.venue, whatsapp.event,whatsapp.education, whatsapp.buysell,
                                        whatsapp.directory, whatsapp.concierge, whatsapp
                                        .influencer, whatsapp.accommodation, whatsapp
                                        .attraction
                                    ]
                                },
                                {
                                    label: "Whatsapp",
                                    backgroundColor: "#bf077f",
                                    data: [email.venue, email.event, email.education, email.buysell, email
                                        .directory, email.concierge, email.influencer,
                                        email.accommodation, email.attraction
                                    ]
                                },
                                {
                                    label: "Whatsapp",
                                    backgroundColor: "#ec5983",
                                    data: [phone.venue, phone.event,phone.education, phone.buysell, phone
                                        .directory, phone.concierge, phone.influencer,
                                        phone.accommodation, phone.attraction
                                    ]
                                },

                            ]
                        },
                        options: {
                            legend: {
                                labels: {
                                    usePointStyle: true, // show legend as point instead of box
                                    fontSize: 14 // legend point size is based on fontsize
                                }
                            },
                            title: {
                                display: true,
                                // text: 'Predicted world population (millions) in 2050'
                            },
                            layout: {
                                padding: {
                                    top: 20
                                }
                            },

                            scales: {
                                xAxes: [{
                                    // barThickness: 16,  // number (pixels) or 'flex'
                                    // maxBarThickness: 8, // number (pixels)
                                    categoryPercentage: 0.3,
                                    barPercentage: 1.0,
                                    gridLines: {
                                        display: false
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: false
                                    }
                                }]
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: ["Venues", "Events", "Education", "Buy & Sell", "Directories", "Concierge", "Influencers", "Spaces",
                    "Attractions"
                ],
                datasets: [{
                        label: "Enquiry Form",
                        backgroundColor: "#000",
                        data: [
                            <?php echo e($enquiry['venue']); ?>, 
                            <?php echo e($enquiry['event']); ?>,
                            <?php echo e($enquiry['education']); ?>,
                            <?php echo e($enquiry['buysell']); ?>, 
                            <?php echo e($enquiry['directory']); ?>,
                            <?php echo e($enquiry['concierge']); ?>,
                            <?php echo e($enquiry['influencer']); ?> , 
                            <?php echo e($enquiry['accommodation']); ?>,
                            <?php echo e($enquiry['attraction']); ?>,
                        ]
                    },
                    {
                        label: "Whatsapp",
                        backgroundColor: "#682e82",
                        data: [<?php echo e($whatsapp['venue']); ?>, 
                            <?php echo e($whatsapp['event']); ?>,
                            <?php echo e($whatsapp['education']); ?>

                            <?php echo e($whatsapp['buysell']); ?>, 
                            <?php echo e($whatsapp['directory']); ?>,
                            <?php echo e($whatsapp['concierge']); ?>,
                            <?php echo e($whatsapp['influencer']); ?>, 
                            <?php echo e($whatsapp['accommodation']); ?>,
                            <?php echo e($whatsapp['attraction']); ?>

                        ]
                    },
                    {
                        label: "Email",
                        backgroundColor: "#bf077f",
                        data: [<?php echo e($email['venue']); ?>, <?php echo e($email['event']); ?>, <?php echo e($email['education']); ?>, <?php echo e($email['buysell']); ?>,
                            <?php echo e($email['directory']); ?>, <?php echo e($email['concierge']); ?>,
                            <?php echo e($email['influencer']); ?>, <?php echo e($email['accommodation']); ?>,
                            <?php echo e($email['attraction']); ?>

                            
                        ]
                    },
                    {
                        label: "Phone",
                        backgroundColor: "#ec5983",
                        data: [<?php echo e($phone['venue']); ?>, <?php echo e($phone['event']); ?>, <?php echo e($phone['education']); ?>, <?php echo e($phone['buysell']); ?>,
                            <?php echo e($phone['directory']); ?>, <?php echo e($phone['concierge']); ?>,
                            <?php echo e($phone['influencer']); ?>, <?php echo e($phone['accommodation']); ?>,
                            <?php echo e($phone['attraction']); ?>

                            
                        ]
                    },

                ]
            },
            options: {
                legend: {
                    labels: {
                        usePointStyle: true, // show legend as point instead of box
                        fontSize: 14 // legend point size is based on fontsize
                    }
                },
                title: {
                    display: true,
                    // text: 'Predicted world population (millions) in 2050'
                },
                layout: {
                    padding: {
                        top: 20
                    }
                },

                scales: {
                    xAxes: [{
                        // barThickness: 16,  // number (pixels) or 'flex'
                        // maxBarThickness: 8, // number (pixels)
                        categoryPercentage: 0.3,
                        barPercentage: 1.0,
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('publisher.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/publisher/dashboard.blade.php ENDPATH**/ ?>