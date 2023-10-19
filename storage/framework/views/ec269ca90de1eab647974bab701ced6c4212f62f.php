
<?php $__env->startSection('css'); ?>
   <link rel="stylesheet" href="<?php echo e(asset('assets/css/cropper-style.css')); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
     <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
  
   
    <style>
        .margin_top_cls {
            margin-top: 28px;
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

        form .error {
            color: #ff0000;
        }

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Manufacturer - <?php echo e($manufacturer->title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/admin/motor/manufacturer">Manufacturers List</a></li>
                        <li class="breadcrumb-item active">Edit Manufacturer</li>
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
        <?php echo Form::model($manufacturer, ['id' => 'form_edit_manufacturer', 'method' => 'PATCH', 'url' => route('admin.manufacturer.update', $manufacturer), 'files' => true]); ?>

        <section class="app-user-edit">
            <div class="card card-outline card-pink">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sub_category_id">Manufacturer Name</label>
                                <?php echo Form::text('title', null, array('placeholder' => 'Manufacturer Name', 'class' => 'form-control', 'id' => 'title')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sub_category_id">Manufacturer Slug</label>
                                <?php echo Form::text('slug', null, array('placeholder' => 'Manufacturer Slug', 'class' => 'form-control', 'id' => 'slug')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h4 class="card-title">Media Upload</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                           
                            <label for="basicInput">Logo (240 X 200)</label>
                            <input type="file" class="form-control" id="logoInput" placeholder="Enter Logo" /><br>
                            <input type="hidden" class="logo-img-base" id="logo-img-base" name="logo" value=""/>
                            <?php if($manufacturer->logo): ?>
                            <a href="<?php echo e($manufacturer->storedImage($manufacturer->logo)); ?>" target="_blank"><img src="<?php echo e($manufacturer->storedImage($manufacturer->logo)); ?>" width="50px" height="50px"/></a>
                            <?php endif; ?>
                       
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="alt_title">Image Alt Title</label>
                            <?php echo Form::text('alt_title', null, array('placeholder' => 'Image Alt Title', 'class' => 'form-control', 'id' => 'alt_title')); ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo Form::label('description', 'Description'); ?>

                            <?php echo Form::textarea('description', null, array('placeholder' => 'Description', 'id' => 'description', 'class' => 'form-control editor', 'required')); ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h4 class="card-title">SEO</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-12 mb-1">
                            <?php echo Form::label('meta_title', 'Meta Title'); ?>

                            <?php echo Form::text('meta_title', null, array('placeholder' => 'Meta Title', 'id' => 'meta_title', 'class' => 'form-control', 'required')); ?>

                        </div>

                        <div class="col-xl-6 col-md-6 col-12 mb-1">
                            <?php echo Form::label('meta_tags', 'Meta Tags'); ?>

                            <?php echo Form::text('meta_tags', null, array('placeholder' => 'Meta Keywords', 'id' => 'meta_tags', 'data-role'=>'tagsinput', 'class' => 'form-control', 'required')); ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8 col-md-6 col-12 mb-1">
                            <?php echo Form::label('meta_description', 'Meta Description'); ?>

                            <?php echo Form::textarea('meta_description', null, array('placeholder' => 'Meta Description', 'class' => 'form-control', 'required')); ?>

                        </div>
                    </div>
                    <!-- users edit account form ends -->

                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                    <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1" id="submit_request">Submit</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='<?php echo e(route('admin.manufacturer.index')); ?>'">Cancel</button>
                </div>
            </div>
        </section>

        <?php echo Form::close(); ?>

    </div>
    <!-- events add ends -->
    <div class="overlay"></div>
    <div class="modal fade" id="modalLogo" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Cropper
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="logo-img-preview"></div>
                <div id="logo-galleryImages"></div>
                <div id="cropper"></div>
                <canvas id="logo-cropperImg" width="0" height="0"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Done</button>
                <button type="button" class="btn btn-primary cropImageBtn" id="logo-cropImageBtn">Crop</button>
            </div>
        </div>
    </div>
</div>
    

    
    

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <?php echo $__env->make('admin.common-scripts', ['section' => 'admin', 'module_name' => 'BuySell', 'amenties' => [], 'landmarks' => []], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
     <script src="<?php echo e(asset('assets/custom-js/logo-cropper.js')); ?>"></script>
    <script>
        $(function () {
            const summernoteDescription = $('#description');
          
            $('#slug').slugify('#title');
            const buysellValidator = $("#form_edit_manufacturer").validate({
                ignore: [],
                rules: {
                    title: "required",
                    slug: "required",
                },
                messages: {
                    // main_category: "main category field is required",
                    // sub_category_id: "sub category field is required",
                    // city_id: "city field is required",
                },
                submitHandler: function (form) {
                    //form.submit();
                    var formData = new FormData(form);
                    $('#submit_request').attr('disabled', 'disabled');
                    $('#prepage').show();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: form.action,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (data) {
                            if (!data.error) {
                                toastr.success(data.msg);
                                window.location.href = '/admin/motor/manufacturer';
                                toastr.clear();
                            } else {
                                toastr.clear();
                                toastr.error(data.msg);
                                $('#submit_request').removeAttr('disabled');
                            }
                            $('#prepage').hide();
                        },
                        error: function (jqXhr, json, errorThrown) {
                            toastr.clear();
                            console.log(jqXhr);
                            //toastr.error("Something went wrong. Please try later. error");
                            var errors = jqXhr.responseJSON;
                            var errorsHtml = '';
                            $.each(errors.errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            toastr.error(errorsHtml, "Error " + jqXhr.status + ': ' + errorThrown);
                            $('#prepage').hide();
                            $('#submit_request').removeAttr('disabled');
                        },
                        fail: function () {
                            toastr.clear();
                            toastr.error("Something went wrong. Please try later.dsffa");
                            $('#prepage').hide();
                            $('#submit_request').removeAttr('disabled');
                        }
                    });
                    return false;
                }
            });
            summernoteDescription.summernote({
                height: 300,
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteDescription.val(summernoteDescription.summernote('isEmpty') ? "" : contents);
                        summernoteDescription.element(summernoteDescription);
                    },
                }
            });
            summernoteStatus.summernote({
                height: 300,
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteStatus.val(summernoteStatus.summernote('isEmpty') ? "" : contents);
                        summernoteStatus.element(summernoteStatus);
                    },
                }
            });
            $('#main_category, #sub_category_id, #meta_tags, #slug, #popular_type, select.select2').on('change', function () {
                $(this).valid();
            });

        });
    </script>

    <script>
        $("#is_popular").change(function () {
            var selected_v = $(this).val();
            if (selected_v == "1") {
                $(".pupular_type_div").show();
                $("#popular_type").prop("required", true);
            } else {
                $(".pupular_type_div").hide();
                $("#popular_type").prop("required", false);
            }
        });

        $("#weeklySuggestion").change(function () {
            if (this.checked) {
                $("#routineRadio").show();
            } else {
                $("#routineRadio").hide();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/manufacturer/edit.blade.php ENDPATH**/ ?>