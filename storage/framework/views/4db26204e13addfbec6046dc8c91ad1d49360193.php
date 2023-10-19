
<?php $__env->startSection('css'); ?>
    <link href="/v2/admin/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/v2/css/bootstrap-multiselect.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
    
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
<?php $__env->startSection('content-header'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Manufacturers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/publisher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manufacturers</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-header row"></div>
    <div class="content-body">
        <!-- events add start -->
        <section class="app-user-edit">
            <div class="card card-outline card-pink">
                <div class="card-body">
                    <a class="btn btn-primary mb-2" href="<?php echo e(route('admin.manufacturer.create')); ?>">Add Manufacturer <i data-feather="plus"></i></a>
                    <div class="row">
                        <div class="col-md-12">
                            <section id="basic-datatable">
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Keyword" name="search" id="search">
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
                                        <div class="card card-outline card-pink">
                                            <table id="buysellsTable"
                                                   class="table table-bordered table-striped table-hover data-table">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    
                                                    
                                                    <?php if(auth()->user()->user_type==1): ?>
                                                        <th>Active</th>
                                                    <?php endif; ?>
                                                    
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                   
                                                   
                                                    <?php if(auth()->user()->user_type==1): ?>
                                                        <th>Active</th>
                                                    <?php endif; ?>
                                                    
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </tfoot>
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
<?php echo $__env->make('admin.partials.modals.remove', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="/v2/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
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

    <script>
        $(function () {
            'use strict';
            
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
                                exportOptions: {columns: [0]}
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0]}
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({class: 'font-small-4 mr-50'}) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0]}
                            }
                        ],
                    },
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/admin/motor/manufacturer",
                    data: function (d) {
                        d.search = $('#search').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                columns: [
                    {data: 'title', name: 'title'},
                   
                    <?php if(auth()->user()->user_type == 1): ?>
                    {
                        data: 'active', name: 'active', orderable: false, searchable: false
                    },
                    <?php endif; ?>
                    {
                        data: 'created_at', name: 'created_at',
                        render: function(data, type) {
                            if ( type === 'display' || type === 'filter' ) {
                                return moment.unix(data).format("DD/MM/Y hh:mm a");
                            }
                            return data;
                        },
                        visible: true
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    
                ],
                order: [[0, 'desc']],
                deferRender: true
            }).on('draw', function(){
                feather.replace();
                $('[data-toggle="switch"]').bootstrapSwitch({
                    size: 'small',
                    onText: $(this).hasClass('buysell-status') ? 'ON' : 'YES',
                    offText: $(this).hasClass('buysell-status') ? 'OFF' : 'NO',
                    onColor: 'green',
                    offColor: 'default',
                    onSwitchChange: function (event, state) {

                        $(this).val(state ? 'on' : 'off');
                        var id = $(this).data('id');
                        var field = '';
                        if($(this).hasClass('buysell-status')) {
                            field = 'status';
                        } else {
                            field = '';
                        }

                        if ($(this).prop("checked") == true) {
                            var value = '1';
                        } else {
                            var value = '0';
                        }
                        $('#prepage').show();
                         $.ajax({
                            url: "<?php echo e(route('admin.manufacturer.update-status-manufacturer')); ?>",
                            type: "POST",
                            dataType: 'json',
                            data: {
                                id: id,
                                field: field,
                                value: value,
                            },
                            success: function (res) {
                                $('#prepage').hide();
                                if (!res.error) {
                                    toastr.success(res.msg);
                                    table.draw();
                                } else {
                                    toastr.error(res.msg);
                                }
                            },
                            error: function (res) {
                                $('#prepage').hide();
                                toastr.error('There was a problem while updating blog. Please try later.');
                            },
                            fail: function (res) {
                                $('#prepage').hide();
                                toastr.error('There was a problem while updating blog. Please try later.');
                            },
                        });
                       
                    }
                });
            });
            window._buysells_table = table;

            $('#search').on('keyup', function () {
                table.draw();
            });
            $('#buysell_status, #main_category, #is_featured').on('change', function () {
                table.draw();
            });

            $('#clearBtn').on('click', function () {
                $('#buysell_status').val('All');
                $('#main_category').val('All');
                $('#is_featured').val('All');
                table.state.clear();
                table.search('');
                table.page.len(10);
                table.draw();
            });
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
    <!-- Dialog show event handler -->
    <script type="text/javascript">
      $('#remove').on('show.bs.modal', function (e) {
          var t=$(e.relatedTarget),
          n=$(this),
          i=t.data("href")||n.find("form").attr("action"),
          a=t.data("message")||"this record",
          o=t.data("return-url")||"";
          n.find("form").attr("action",i),n.find('input[name="return_url"]').val(o);
      });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/manufacturer/index.blade.php ENDPATH**/ ?>