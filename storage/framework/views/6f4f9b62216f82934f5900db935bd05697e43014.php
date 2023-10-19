
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header"></div>
<div class="content-body">
    <div class="card">
        <div class="card-body">



            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Job Title</th>
                        <th>Date & Time</th>
                        <th>Qualification</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sl_no = 1; ?>
                    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($sl_no); ?></td>
                            <td><?php echo e($job->company); ?></td>
                            <td><?php echo e($job->job_title); ?></td>
                            <td><?php echo e($job->date_time); ?></td>
                            <td><?php echo e($job->qualification); ?></td>

                            <td>
                                <a  href="<?php echo e(route('client.applied_detail',$job->id)); ?>"  target="_blank" type="button" class="btn btn-sm btn-success">
                                    <i data-feather='eye'></i>
                                </a>
                            </td>
                        </tr>
                        <?php $sl_no++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120" aria-hidden="true">
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
                <form method="post" action="<?php echo e(route('admin.job.delete')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="id">
                    <button type="submit" class="btn btn-danger">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
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
            'use strict';

            $('#datatable').DataTable( {
                "aaSorting": [[0, 'asc']],
                "pageLength": 10,
                "scrollX": true,
                dom:
                '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [
                    {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                    buttons: [
                        {
                        extend: 'csv',
                        text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                        className: 'dropdown-item',
                        exportOptions: { modifier: { page : 'all', } }
                        },
                        {
                        extend: 'print',
                        text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                        },
                        {
                        extend: 'excel',
                        text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                        },
                        {
                        extend: 'copy',
                        text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                        }
                    ],
                    },
                ],
            });
        });
    </script>
    <script>
        $(".change_status").change(function(){
            var id = $(this).attr('id');
            if($(this).prop("checked") == true){
                var status = '1';
            }else{
                var status = '0';
            }
            $.ajax({
                url: "<?php echo e(route('admin.update_status_jobs')); ?>",
                method: 'POST',
                data: {id: id, _token: "<?php echo e(csrf_token()); ?>",status:status},
                success: function(response) {

                }
            });
        });

        $('.modal-btn').click(function() {
            var id = $(this).attr('data-id');
            $('#id').val(id);
        });
    </script>
    <script>
        <?php if(Session::has('message')): ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('client.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/client/applied-jobs/all_applied_jobs.blade.php ENDPATH**/ ?>