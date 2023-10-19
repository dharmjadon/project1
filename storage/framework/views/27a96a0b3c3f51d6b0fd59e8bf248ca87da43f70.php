
<?php $__env->startSection('css'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/toastr.css')); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/vendors.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/forms/select/select2.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/component/findcv.css')); ?>">
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

        .label{
            color: #BF087F
        }

        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: rgb(92, 91, 91) !important;
            opacity: 1; /* Firefox */
        }

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-header row"></div>
    <div class="content-body">
        <!-- events add start -->
        <section class="app-user-edit">
            <div class="card card-outline card-pink">
                <div class="card-body">

                    
                    <h4>Find CV's</h4>
                    <div class="row">
                        
                        <div class="col-md-3 form-group">
                            <label class="label">Where</label>
                            <select class="select2 form-control" name="city_id" id="city" required style="border-radius: 28px;">
                                <option value="" selected disabled> Please Select a City</option>
                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="label">What</label>
                            <input type="text" class="form-control" id="keyword" style="border-radius: 28px;" placeholder="Job Title, Keyword or Company">
                        </div>
                        <div class="col-md-3">
                            <div class="price-slider"><span>from
                                    <input type="number" value="10" min="0" max="100" id="minAge" /> to
                                    <input type="number" value="40" min="0" max="100" id="maxAge" /></span>
                                <input value="10" min="0" max="100" step="1" type="range" />
                                <input value="40" min="0" max="100" step="1" type="range" />
                                <svg width="100%" height="24">
                                    <line x1="4" y1="0" x2="300" y2="0" stroke="#212121" stroke-width="12"
                                        stroke-dasharray="1 28"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="col-md-3 mt-2">
                            <button id="submitFilter" class="btn btn-primary">FIND CVs <i data-feather="check"></i></button>
                        </div>
                        
                        <div class="col-md-12">
                            <section id="basic-datatable">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card" id="filteredDatatable">
                                            <table class=" table" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Full Name</th>
                                                        <th>Headline</th>
                                                        <th>Current Position</th>
                                                        <th>Emirates</th>
                                                        <th>City</th>
                                                        <th>Mobile</th>
                                                        <th>Email</th>
                                                        <th>DOB</th>
                                                        <th>CV</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $sl_no = 1; ?>
                                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($sl_no); ?></td>
                                                            <td><?php echo e($user->frist_name); ?>

                                                                <?php echo e($user->last_name); ?>

                                                            </td>
                                                            <td><?php echo e($user->headline); ?>

                                                            </td>
                                                            <td><?php echo e($user->current_position); ?>

                                                            </td>
                                                            <td><?php echo e($user->emirates ? $user->emirates->name : ''); ?>

                                                            </td>
                                                            <td><?php echo e($user->city_detail ? $user->city_detail->name : ''); ?>

                                                            </td>
                                                            <td><?php echo e($user->mobile); ?>

                                                            </td>
                                                            <td><?php echo e($user->email); ?>

                                                            </td>
                                                            <td><?php echo e($user->date_of_birth); ?>

                                                            </td>
                                                            <td>
                                                                <a href="<?php echo e(asset($user->cv)); ?>" download type="button"
                                                                    class="btn btn-sm btn-success">
                                                                    <i data-feather='download'></i>
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
                            </section>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>


    
    <div class="modal fade modal-danger text-left" id="danger" tabindex="-1" role="dialog" aria-labelledby="myModalLabel120"
        aria-hidden="true">
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
                    <form method="post" action="<?php echo e(route('admin.delete_jobseeker_user')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" id="id">
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    


    <!-- events add ends -->
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
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            'use strict';
            initDatatable();

            (function() {

                var parent = document.querySelector(".price-slider");
                if (!parent) return;

                var
                    rangeS = parent.querySelectorAll("input[type=range]"),
                    numberS = parent.querySelectorAll("input[type=number]");

                rangeS.forEach(function(el) {
                    el.oninput = function() {
                        var slide1 = parseFloat(rangeS[0].value),
                            slide2 = parseFloat(rangeS[1].value);

                        if (slide1 > slide2) {
                            [slide1, slide2] = [slide2, slide1];
                        }

                        numberS[0].value = slide1;
                        numberS[1].value = slide2;
                    }
                });

                numberS.forEach(function(el) {
                    el.oninput = function() {
                        var number1 = parseFloat(numberS[0].value),
                            number2 = parseFloat(numberS[1].value);

                        if (number1 > number2) {
                            var tmp = number1;
                            numberS[0].value = number2;
                            numberS[1].value = tmp;
                        }

                        rangeS[0].value = number1;
                        rangeS[1].value = number2;

                    }
                });

            })();

        });

        function initDatatable() {
            $('#datatable').DataTable({
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
                }, ],
            });
        }
    </script>

    <script>
        $('.modal-btn').click(function() {
            var id = $(this).attr('data-id');
            $('#id').val(id);
        });
    </script>

    <script>
        <?php if(Session::has('message')): ?>
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
    <script>
        $("#state").on('change', function() {
            var id = $(this).val();
            $.ajax({
                url: "<?php echo e(route('get-cities')); ?>",
                method: 'POST',
                data: {
                    id: id,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {

                    $('#city').empty().trigger("change");
                    $("#city").append('<option class="" value="0">Select</option>');
                    $.each(response, function(key, value) {
                        $("#city").append('<option class="" value="' + value.id + '">' +
                            value.name + '</option>');
                    });
                }
            });
        });

        $("#submitFilter").on('click', function() {
            var cityId = $('#city').val();
            var keyword = $('#keyword').val();
            var minAge = $('#minAge').val();
            var maxAge = $('#maxAge').val();
            $.ajax({
                url: "<?php echo e(route('admin.filter-all-cv')); ?>",
                method: 'POST',
                data: {
                    city_id: cityId,
                    min_age: minAge,
                    max_age: maxAge,
                    keyword: keyword,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    $('#filteredDatatable').empty();
                    $('#filteredDatatable').append(response.html);
                    initDatatable();
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/job/find-all-cv.blade.php ENDPATH**/ ?>