
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card card-outline card-pink">
        <div class="card-header">
            <h4 class="card-title">All Career Job List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">


                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="space_div" data-toggle="tab" href="#spaces"
                           aria-controls="spaces"
                           role="tab" aria-selected="true">Property</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="motor_div" data-toggle="tab" href="#motors" aria-controls="motors"
                           role="tab" aria-selected="false">Motors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="event_div" data-toggle="tab" href="#event" aria-controls="event"
                           role="tab" aria-selected="false">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="buysell_div" data-toggle="tab" href="#buysell" aria-controls="buysell"
                           role="tab" aria-selected="false">Buy & Sell</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="directory_div" data-toggle="tab" href="#directory"
                           aria-controls="directory"
                           role="tab" aria-selected="false">Directory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="conciger_div" data-toggle="tab" href="#conciger"
                           aria-controls="conciger"
                           role="tab" aria-selected="false">Concierge</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="venue_div" data-toggle="tab" href="#venue" aria-controls="venue"
                           role="tab" aria-selected="false">Venues</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="crypto_div" data-toggle="tab" href="#crypto" aria-controls="crypto"
                           role="tab" aria-selected="false">Crypto</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="spaces" aria-labelledby="space_div" role="tabpanel">
                        <?php if(!empty($space_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$space_data,'type'=>'space'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane" id="motors" aria-labelledby="motor_div" role="tabpanel">
                        <?php if(!empty($motor_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$motor_data,'type'=>'motor'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane" id="event" aria-labelledby="event_div" role="tabpanel">
                        <?php if(!empty($event_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$event_data,'type'=>'event'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane" id="buysell" aria-labelledby="buysell_div" role="tabpanel">
                        <?php if(!empty($buysell_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$buysell_data,'type'=>'buysell'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane" id="directory" aria-labelledby="directory_div" role="tabpanel">
                        <?php if(!empty($directory_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$directory_data,'type'=>'directory'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

                    </div>
                    <div class="tab-pane" id="conciger" aria-labelledby="conciger_div" role="tabpanel">
                        <?php if(!empty($conciger_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$conciger_data,'type'=>'conciger'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

                    </div>
                    <div class="tab-pane" id="venue" aria-labelledby="venue_div" role="tabpanel">
                        <?php if(!empty($venue_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$venue_data,'type'=>'venue'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

                    </div>
                     <div class="tab-pane" id="crypto" aria-labelledby="crypto_div" role="tabpanel">
                        <?php if(!empty($crypto_data)): ?>
                            <?php echo $__env->make('admin.career.career-table.career_table',['data'=>$crypto_data,'type'=>'crypto'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

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
        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var currentTab = $(e.target).attr('id'); // get current tab
                var split_ab = currentTab;
                if (split_ab == "venue-tab") {
                    var table = $('#datatable1').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "crypto-tab") {
                    var table = $('#datatable16').DataTable();
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
                } else if (split_ab == "influencer-tab") {
                    var table = $('#datatable6').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "job-tab") {
                    var table = $('#datatable7').DataTable();
                    $('#container').css('display', 'block');

                    table.columns.adjust().draw();
                } else if (split_ab == "attraction-tab") {
                    var table = $('#datatable9').DataTable();
                    $('#container').css('display', 'block');
                    table.columns.adjust().draw();
                } else if (split_ab == "ticket-tab") {
                    var table = $('#datatable10').DataTable();
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
        $(document).ready(function () {

            var table = $('table').DataTable({
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
                },],
            });


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
        $(document).on("click", ".modal-btn", function () {
            var itemid = $(this).attr('data-id');
            $("input[name='id']").val(itemid);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/career/index.blade.php ENDPATH**/ ?>