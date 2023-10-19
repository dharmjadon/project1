
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="/v2/css/bootstrap-multiselect.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/vendors.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/forms/select/select2.min.css')); ?>">
    <style>
        .margin_top_cls {
            margin-top: 25px;
        }

        .validation {
            color: red;
        }

        .hide_cls {
            display: none;
        }

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

        #basic-addon2 {
            cursor: pointer;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-header row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>Manage Motors</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/publisher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Motors List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <!-- motors add start -->
        <section class="app-user-edit">
            <div class="card">
                <div class="card-body">

                    <a class="btn btn-primary mb-2" href="<?php echo e(route('publisher.motors.create')); ?>">Add Motor <i
                            data-feather="plus"></i></a>
                    <div class="row">
                        <div class="col-md-12">
                            <section id="basic-datatable">
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Keyword" name="search"
                                               id="search">
                                    </div>
                                    <div class="col ">
                                        <select class="form-control selectpicker" name="main_category"
                                                id="main_category"
                                                data-live-search="true" multiple data-header="All Categories"
                                                title="All Cateogries"
                                                data-selected-text-format="count" data-actions-box="true">
                                            <?php $__currentLoopData = $main_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c_id => $cname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c_id); ?>"><?php echo e($cname); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <div id="datefilter" class="form-control text-dark">
                                            <i class="fa fa-calendar"></i>&nbsp; <span class="small">Created on</span>
                                        </div>
                                        <input type="hidden" name="date_from" id="date_from"/>
                                        <input type="hidden" name="date_to" id="date_to"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <table id="motorsTable"
                                                   class="table table-bordered table-striped table-hover data-table">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Main Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Status</th>
                                                    <th>Is Draft</th>
                                                    <th>Action</th>
                                                    <th>Created At</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
   
    <div class="overlay"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="/v2/js/bootstrap-multiselect.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>
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
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
    <script>
        $(function () {
            'use strict';
            var start = moment().subtract(364, 'days');
            var end = moment();

            function cb(start, end) {
                $('#datefilter span.small').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#date_from').val(start.format('YYYY-MM-DD'));
                $('#date_to').val(end.format('YYYY-MM-DD'));
            }

            $('#datefilter').daterangepicker({
                /*autoUpdateInput: false,
                autoApply: true,
                "alwaysShowCalendars": true,*/
                startDate: start,
                endDate: end,
                showDropdowns: true,
                minYear: 2021,
                maxYear: parseInt(moment().format('YYYY'), 0),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 365 Days': [moment().subtract(364, 'days'), moment()]
                }
            }, function (start, end) {
                $('#datefilter span.small').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                $('#date_from').val(start.format('YYYY-MM-DD'));
                $('#date_to').val(end.format('YYYY-MM-DD'));
                table.draw();
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            cb(start, end);
            $('.fa-calendar').on('click', function (e) {
                e.preventDefault();
                $('input[name="datefilter"]').focus();
            });

            var table = $('.data-table').DataTable({
                dom: '<"d-flex align-items-center mt-2 top-filters"<"p-2"l><"p-2"B>>rt<"d-flex justify-content-between"ip><"clear">',
                //dom: '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle',
                        text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'Export',
                        buttons: [
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: {modifier: {page: 'all',}}
                            },
                            {
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'Print',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({class: 'font-small-4 mr-50'}) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                            }
                        ],
                    },
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/publisher/motors",
                    data: function (d) {
                        d.search = $('#search').val();
                        d.main_category = $('#main_category').val();
                        d.is_featured = $('#is_featured').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'prices', name: 'prices'},
                    {data: 'main_category', name: 'main_category'},
                    {data: 'sub_category', name: 'sub_category'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'is_draft', name: 'is_draft', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {
                        data: 'created_at', name: 'created_at',
                        render: function (data, type) {
                            if (type === 'display' || type === 'filter') {
                                return moment.unix(data).format("DD/MM/Y hh:mm a");
                            }
                            return data;
                        },
                        visible: false
                    },
                ],
                order: [[7, 'desc']],
                deferRender: true
            }).on('draw', function () {
                feather.replace();
            });

            $('#search').on('keyup', function () {
                table.draw();
            });
            $('#motor_status, #main_category, #is_featured').on('change', function () {
                table.draw();
            });

            $('#clearBtn').on('click', function () {
                $('#motor_status').val('All');
                $('#main_category').val('All');
                $('#is_featured').val('All');
                table.state.clear();
                table.search('');
                table.page.len(10);
                table.draw();
            });
        });
     
        $(document).ready(function () {
            'use strict';

            /*$('#datatable').DataTable({
                "aaSorting": [[0, 'asc']],
                "pageLength": 10,
                "scrollX": true,
                dom:
                    '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle',
                        text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'Export',
                        buttons: [
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: {modifier: {page: 'all',}}
                            },
                            {
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'Print',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({class: 'font-small-4 mr-50'}) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4, 5]}
                            }
                        ],
                    },
                ],
            });*/
        });
    </script>
    <script>
        

        $('.modal-btn').click(function () {
            var id = $(this).attr('data-id');
            $('#id').val(id);
        });
    </script>
    <script>
        <?php if(Session::has('message')): ?>
        var type = "<?php echo e(Session::get('alert-type', 'info')); ?>";
        switch (type) {
            case 'info':
                toastr.info("<?php echo e(Session::get('message')); ?>", "Information!", {timeOut: 10000, progressBar: true});
                break;

            case 'warning':
                toastr.warning("<?php echo e(Session::get('message')); ?>", "Warning!", {timeOut: 10000, progressBar: true});
                break;

            case 'success':
                toastr.success("<?php echo e(Session::get('message')); ?>", "Success!", {timeOut: 10000, progressBar: true});
                break;

            case 'error':
                toastr.error("<?php echo e(Session::get('message')); ?>", "Failed!", {timeOut: 10000, progressBar: true});
                break;
        }
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('publisher.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/publisher/motors/index.blade.php ENDPATH**/ ?>