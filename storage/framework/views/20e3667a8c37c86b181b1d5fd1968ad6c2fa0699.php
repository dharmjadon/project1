
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/vendors.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
          href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
          href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
          href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
          href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')); ?>">

    <style>

        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.8) url("<?php echo e(asset('assets/loader/loader_report.gif')); ?>") center no-repeat;
        }

        /* Turn off scrollbar when body element has the loading class */
        body.loading {
            overflow: hidden;
        }

        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay {
            display: block;
        }
    </style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card card-outline card-pink">
        <div class="card-header">
            <h4 class="card-title">All Enquiries</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="venue-tab" data-toggle="tab" href="#venue" aria-controls="venue"
                           role="tab" aria-selected="true">Venues
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Venue'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="event-tab" data-toggle="tab" href="#event" aria-controls="event"
                           role="tab" aria-selected="false">Events
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Events'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="buy_sell-tab" data-toggle="tab" href="#buy_sell"
                           aria-controls="buy_sell"
                           role="tab" aria-selected="false">Buy & Sell
                            (<?php echo e(count($datas->where('item_type', 'App\Models\BuySell'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="directory-tab" data-toggle="tab" href="#directory"
                           aria-controls="directory"
                           role="tab" aria-selected="false">Directories
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Directory'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="concierge-tab" data-toggle="tab" href="#concierge"
                           aria-controls="concierge"
                           role="tab" aria-selected="false">Concierges
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Concierge'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="influencer-tab" data-toggle="tab" href="#influencer"
                           aria-controls="influencer" role="tab" aria-selected="false">Influencers
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Influencer'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="job-tab" data-toggle="tab" href="#job" aria-controls="job"
                           role="tab" aria-selected="false">Property
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Accommodation'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="book-artist-tab" data-toggle="tab" href="#bookArtist"
                           aria-controls="job"
                           role="tab" aria-selected="false">Book Artists
                            (<?php echo e(count($datas->where('item_type', 'App\Models\BookArtist'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="attraction-tab" data-toggle="tab" href="#attraction" aria-controls="job"
                           role="tab" aria-selected="false">Attraction
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Attraction'))); ?>)</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="ticket-tab" data-toggle="tab" href="#ticket" aria-controls="ticket"
                           role="tab" aria-selected="false">Tickets
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Tickets'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="motors-tab" data-toggle="tab" href="#motors" aria-controls="motors"
                           role="tab" aria-selected="false">Motros
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Motors'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="crypto-tab" data-toggle="tab" href="#crypto" aria-controls="crypto"
                           role="tab" aria-selected="false">Crypto
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Crypto'))); ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="talent-tab" data-toggle="tab" href="#talent" aria-controls="talent"
                           role="tab" aria-selected="false">Talent
                            (<?php echo e(count($datas->where('item_type', 'App\Models\Talents'))); ?>)</a>
                    </li>


                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="venue" aria-labelledby="venue-tab" role="tabpanel">

                        <table class="table" id="datatable1">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sl_no = 1; ?>
                            <?php
                                $datas1 = $datas->where('item_type', 'App\Models\Venue');
                            ?>
                            <?php $__currentLoopData = $datas1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($sl_no); ?></td>
                                        <?php $item = substr($data->item_type, 10); ?>
                                    <td><?php echo e($item); ?></td>
                                    <?php if($item == 'BuySell'): ?>
                                        <td><?php echo e($data->item ? $data->item->product_name : ''); ?></td>
                                    <?php else: ?>
                                        <td><?php echo e($data->item ? $data->item->title : ''); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e($data->name); ?></td>
                                    <td><?php echo e($data->email); ?></td>
                                    <td><?php echo e($data->mobile); ?></td>
                                    <td><?php echo e($data->message); ?></td>
                                    <td><?php echo e($data->created_at); ?></td>
                                    <td>
                                        <a data-id="<?php echo e($data->id); ?>" data-target="#danger" data-toggle="modal"
                                           type="button" href="javascript:void(0)"
                                           class="btn btn-sm btn-danger modal-btn">
                                            <i data-feather='trash-2'></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $sl_no++   ; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="event" aria-labelledby="event-tab" role="tabpanel">
                        <table class="table" id="datatable2">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="buy_sell" aria-labelledby="buy_sell-tab" role="tabpanel">
                        <table class="table" id="datatable3">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="directory" aria-labelledby="directory-tab" role="tabpanel">
                        <table class="table" id="datatable4">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="concierge" aria-labelledby="concierge-tab" role="tabpanel">
                        <table class="table" id="datatable5">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="influencer" aria-labelledby="influencer-tab" role="tabpanel">
                        <table class="table" id="datatable6">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="job" aria-labelledby="job-tab" role="tabpanel">
                        <table class="table" id="datatable7">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="bookArtist" aria-labelledby="book-artist-tab" role="tabpanel">
                        <table class="table" id="datatable8">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>

                    </div>

                    <div class="tab-pane" id="attraction" aria-labelledby="attraction-tab" role="tabpanel">
                        <table class="table" id="datatable9">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        </tbody>
                        </table>
                    </div>


                    <div class="tab-pane" id="ticket" aria-labelledby="ticket-tab" role="tabpanel">
                        <table class="table" id="datatable10">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="motors" aria-labelledby="motors-tab" role="tabpanel">
                        <table class="table" id="datatable11">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="crypto" aria-labelledby="crypto-tab" role="tabpanel">
                        <table class="table" id="datatable16">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="talent" aria-labelledby="talent-tab" role="tabpanel">
                        <table class="table" id="datatable17">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Major category</th>
                                <th>Enquired Item</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Date & Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel120" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel120">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are You sure you want to delete?
                </div>
                <div class="modal-footer">
                    <form method="post" action="<?php echo e(route('admin.enquiry.delete')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id">
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="overlay"></div>
    

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js')); ?>"></script>


    <script>

        function ajax_render_table(table_nam, module_name) {
            var token = $("input[name='_token']").val();
            $('#prepage').show();
            $.ajax({
                url: "<?php echo e(route('admin.enquiry_ajax_tabs_table')); ?>",
                method: 'GET',
                data: {module_name: module_name},
                success: function (response) {
                    // $("#datatable").tbody.empty();
                    $('#prepage').hide();
                    var table = $('#' + table_nam).DataTable();
                    table.destroy();
                    var table_name_now = '' + table_nam + ' tbody';

                    $('#' + table_name_now).empty();
                    $('#' + table_name_now).append(response.html);

                    feather.replace();

                    var table = $('#' + table_nam).DataTable(
                        {

                            // responsive: true,
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
                            },],
                            "scrollY": false,
                            "scrollX": true,
                        }
                    );
                    $(".display").css("width", "100%");
                    // $('#datatable_career tbody').css("width","100%");
                    // $('#container').css( 'display', 'block' );
                    table.columns.adjust().draw();
                }
            });
        }

    </script>

    <script>
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var currentTab = $(e.target).attr('id'); // get current tab
                var split_ab = currentTab;
                if (split_ab == "venue-tab") {
                    ajax_render_table("datatable1", "venue");
                } else if (split_ab == "event-tab") {

                    ajax_render_table("datatable2", "event");

                } else if (split_ab == "buy_sell-tab") {

                    ajax_render_table("datatable3", "buysell");

                } else if (split_ab == "directory-tab") {
                    ajax_render_table("datatable4", "directory");

                } else if (split_ab == "concierge-tab") {

                    ajax_render_table("datatable5", "concierge");

                } else if (split_ab == "influencer-tab") {

                    ajax_render_table("datatable6", "influencer");

                } else if (split_ab == "job-tab") {

                    ajax_render_table("datatable7", "accomdation");

                } else if (split_ab == "book-artist-tab") {

                    ajax_render_table("datatable8", "bookartist");

                } else if (split_ab == "attraction-tab") {

                    ajax_render_table("datatable9", "attraction");

                } else if (split_ab == "ticket-tab") {

                    ajax_render_table("datatable10", "ticket");
                } else if (split_ab == "motors-tab") {

                    ajax_render_table("datatable11", "motor");

                }
                else if (split_ab == "crypto-tab") {

                    ajax_render_table("datatable16", "crypto");
                }

                else if (split_ab == "talent-tab") {

                    ajax_render_table("datatable17", "talent");

                } 
                else {
                    var table = $('#datatable-37-1').DataTable();
                    $('#container').css('display', 'block');
                    table.columns.adjust().draw()

                }


            });
        });
    </script>

    <script>
        $(document).ready(function () {

            // Show tab when clicked
            $('.nav-tabs a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Recalculate column widths when displaying tab - this will active Responsive
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            'use strict';

            $('#datatable1').DataTable({
                "aaSorting": [
                    [0, 'asc']
                ],
                "pageLength": 10,
                "scrollX": true,
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
                },],
            });
        });
    </script>

    <script>
        // Add remove loading class on body element depending on Ajax request status
        $(document).on({
            ajaxStart: function () {
                $("body").addClass("loading");
            },
            ajaxStop: function () {
                $("body").removeClass("loading");
            }
        });
    </script>

    <script>
        $(document).on("click", ".modal-btn", function () {
            var itemid = $(this).attr('data-id');
            $("input[name='id']").val(itemid);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/enquiry/index.blade.php ENDPATH**/ ?>