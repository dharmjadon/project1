
<?php $__env->startSection('css'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/vendors/css/forms/select/select2.min.css')); ?>">
    <link rel="stylesheet" href="/intl-tel-input/css/intlTelInput.min.css"/>
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
                    <h1>Add Directory</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/admin/directories">Directory List</a></li>
                        <li class="breadcrumb-item active">Add Directory</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <!-- directorys add start -->
        <?php echo Form::open(['url' => route('admin.directories.store'), 'id' => 'form_create_directory', 'files' => true]); ?>

        <section class="app-user-edit">
            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h2 class="card-title">Create Directory</h2>
                    <div class="card-tools">
                        <select class="select2 form-control" name="lang" id="lang">
                            <option value="en" selected>English</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="main_category">Main Category</label>
                                <?php echo Form::select('main_category', $main_categories, null, array('placeholder' => 'Main Category', 'id' => 'main_category', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sub_category_id">Select Sub Category</label>
                                <select class="select2 form-control" name="sub_category_id" id="sub_category_id">
                                    <option value="" disabled selected>please select option</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="title" class="control-label">Directory Name</label>
                                <?php echo Form::text('title', null, array('placeholder' => 'Directory Name', 'id' => 'title', 'class' => 'form-control', 'required')); ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="slug" class="control-label">SEO URL</label>
                                <?php echo Form::text('slug', null, array('placeholder' => 'URI', 'id' => 'slug', 'class' => 'form-control', 'required')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="founded_date">Founded Date <b>(optional)</b></label>
                                <?php echo Form::date('founded_date', null, array('placeholder' => 'Founded Date', 'id' => 'founded_date', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_feature', 'Is Featured Directory?'); ?>

                                <?php echo Form::select('is_feature',['No', 'Yes'], null, array('placeholder' => 'Is Featured Directory?', 'id' => 'is_feature', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_popular', 'Is Popular Directory?'); ?>

                                <?php echo Form::select('is_popular',['No', 'Yes'], null, array('placeholder' => 'Is Popular Directory?', 'id' => 'is_popular', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('online_market', 'UAE Online Market?'); ?>

                                <?php echo Form::select('online_market', ['No', 'Yes'], null, array('placeholder' => 'UAE Online Market?', 'id' => 'online_market', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_trending', 'Is Trending Directories?'); ?>

                                <?php echo Form::select('is_trending',['No', 'Yes'], null, array('placeholder' => 'Is Trending Directories?', 'id' => 'is_trending', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_hot', 'Is Hot Directories?'); ?>

                                <?php echo Form::select('is_hot',['No', 'Yes'], null, array('placeholder' => 'Is Hot Directories?', 'id' => 'is_hot', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="discount_offer">Discount/Offers (in %)</label>
                            <?php echo Form::number('discount_offer', null, array( 'id' => 'discount_offer', 'class' => 'form-control', 'max' => 100)); ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('enquiry_email', 'Email (optional)'); ?>

                                <?php echo Form::email('enquiry_email', null, array('placeholder' => 'Email', 'id' => 'enquiry_email', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('whatsapp', 'Whatsapp No (optional)'); ?>

                                <?php echo Form::tel('whatsapp', null, array('placeholder' => 'Whatsapp No', 'id' => 'whatsapp', 'class' => 'form-control')); ?>

                                <span id="valid-msg-whatsapp" class="hide text-success"></span>
                                <span id="error-msg-whatsapp" class="hide text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('contact', 'Mobile No (optional)'); ?>

                                <?php echo Form::tel('quick_contacts', null, array('placeholder' => 'Mobile No', 'id' => 'contact', 'class' => 'form-control')); ?>

                                <span id="valid-msg-contact" class="hide text-success"></span>
                                <span id="error-msg-contact" class="hide text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Address <b>(optional)</b></label>
                                <?php echo Form::text('address', null, array('placeholder' => 'Address', 'id' => 'address', 'class' => 'form-control')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="website">Website <b>(optional)</b></label>
                                <?php echo Form::url('website', null, array('placeholder' => 'Website URL', 'id' => 'website', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="facebook_links">Youtube Link <b>(optional)</b></label>
                                <?php echo Form::url('social_links[1]', null, array('placeholder' => 'Youtube Link', 'id' => 'youtube_link', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="facebook_link">Facebook Link <b>(optional)</b></label>
                                <?php echo Form::url('social_links[2]', null, array('placeholder' => 'Facebook Link', 'id' => 'facebook_link', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instagram_link">Instagram Link <b>(optional)</b></label>
                                <?php echo Form::url('social_links[3]', null, array('placeholder' => 'Instagram Link', 'id' => 'instagram_link', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Location</label>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <input id="pac-input" class="form-control" type="text"
                                               placeholder="Enter Location" name="location" required>
                                        <div id="map-canvas" style="width:100%;height:350px;margin-top: 10px;"></div>
                                        <input type="hidden" id="cityLat" name="citylat"/>
                                        <input type="hidden" id="cityLng" name="citylong"/>
                                        <input type="hidden" id="map_review" name="map_review"/>
                                        <input type="hidden" id="map_rating" name="map_rating"/>
                                        <input type="hidden" id="country" name="country"/>
                                        <input type="hidden" id="city" name="city"/>
                                        <input type="hidden" id="area" name="area"/>
                                    </div>
                                </div>
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
                            <div class="form-group">
                                <div class="featured-img-dropzone custom-file">
                                    <?php echo Form::label('feature_image', 'Profile Image (800 x 475)', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="feature_image" name="feature_image[]"
                                           data-url="/admin/directories/upload-photos/feature_image"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="feature_image_loading"></div>
                                <div class="row py-2" id="feature_image_list"></div>
                                <input type="hidden" name="feature_image_ids" id="feature_image_ids" value="">
                            </div>
                        </div>

                        <div class="col-md-3 form-group">
                            <div class="fp-img-dropzone custom-file">
                                <?php echo Form::label('logo', 'Logo (240 X 200) (optional)', ['class' => 'form-label']); ?>

                                <input type="file" class="form-control" id="logo" name="logo[]"
                                       data-url="/admin/directories/upload-photos/logo"
                                       accept="image/jpg,image/jpeg,image/gif,image/png">
                            </div>
                            <div id="logo_loading"></div>
                            <div class="row py-2" id="logo_list"></div>
                            <input type="hidden" name="logo_ids" id="logo_ids" value="">
                        </div>
                        <div class="col-md-3 form-group">
                            <div class="menu-img-dropzone custom-file">
                                <?php echo Form::label('qr_code', 'QR Code (optional)', ['class' => 'form-label']); ?>

                                <input type="file" class="form-control" id="qr_code" name="qr_code[]"
                                       data-url="/admin/directories/upload-photos/qr_code"
                                       accept="image/jpg,image/jpeg,image/gif,image/png">
                            </div>
                            <div id="menu_loading"></div>
                            <div class="row py-2" id="qr_code_list"></div>
                            <input type="hidden" name="qr_code_ids" id="qr_code_ids" value="">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="basicInput">Video Youtube Link Or Image <b>(optional)</b></label><br>

                            <input type="radio" id="test2" name="youtube_img" value="1">
                            <label for="test2"><b> Youtube Link </b></label><br>

                            <input type="radio" id="test3" name="youtube_img" value="2">
                            <label for="test3"><b> Image</b></label>
                        </div>
                        <div class="col-md-4 form-group youtube" style="display: none">
                            <input type="text" class="form-control " placeholder="Youtube Link" name="video"
                                   id="video"/>
                        </div>
                        <div class="col-md-6 form-group img_you" style="display: none">
                            <div class="main-img-dropzone custom-file">
                                <input type="file" class="form-control" id="main_image" name="main_image[]"
                                       data-url="/admin/directories/upload-photos/main_image"
                                       accept="image/jpg,image/jpeg,image/gif,image/png">
                            </div>
                            <div id="main_image_loading"></div>
                            <div class="row py-2" id="main_image_list"></div>
                            <input type="hidden" name="main_image_ids" id="main_image_ids" value="">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="images-dropzone form-group custom-file">
                                    <?php echo Form::label('images', 'Select 4 Images (800 X 475) (optional)', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="images" name="images[]" multiple
                                           data-url="/admin/directories/upload-photos/images"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="images_loading"></div>
                                <div class="row py-2" id="images_list"></div>
                                <input type="hidden" name="images_ids" id="images_ids" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="stories-dropzone form-group custom-file">
                                    <?php echo Form::label('stories', 'Story Images (800 X 475) (optional)', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="stories" name="stories[]" multiple
                                           data-url="/admin/directories/upload-photos/stories"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="stories_loading"></div>
                                <div class="row py-2" id="stories_list"></div>
                                <input type="hidden" name="stories_ids" id="stories_ids" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-pink">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo Form::label('description', 'Description'); ?>

                            <?php echo Form::textarea('description', null, array('placeholder' => 'Description', 'id' => 'description', 'class' => 'form-control editor', 'required')); ?>

                        </div>

                        <div class="col-md-6">
                            <?php echo Form::label('status_text', 'Status Text'); ?>

                            <?php echo Form::textarea('status_text', null, array('placeholder' => 'Status Text', 'id' => 'status_text', 'class' => 'form-control editor', 'required')); ?>

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
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='<?php echo e(route('admin.directories.index')); ?>'">Cancel</button>
                </div>
            </div>
        </section>

        <?php echo Form::close(); ?>

    </div>
    <!-- directorys add ends -->
    <div class="overlay"></div>
    

    <!-- Button trigger modal -->
    <button type="button" id="more-info-modal-button" class="btn btn-outline-primary d-none"
            data-toggle="modal" data-target="#xlarge">
        click button
    </button>
    <!-- Modal -->
    <div class="modal fade text-left" id="xlarge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel16">Do you want to add more information? (optional)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('admin.directories_save_more_info')); ?>" method="POST" enctype='multipart/form-data'>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="primary_id" name="primary_id" value="<?php echo e($primary_id ?? ''); ?>">
                    <div class="modal-body">
                        <?php echo $__env->make('admin.common.common_component',get_defined_vars(), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <?php echo $__env->make('admin.common-scripts', ['section' => 'admin', 'module_name' => 'directories', 'amenties' => [], 'landmarks' => []], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        $(function () {
            const summernoteDescription = $('#description');
            const summernoteStatus = $('#status_text');
            $('#slug').slugify('#title');

            const directoryValidator = $("#form_create_directory").validate({
                ignore: ':hidden:not(.editor),.note-editable.card-block',
                rules: {
                    title: "required",
                    main_category: "required",
                    sub_category_id: "required",
                    location: "required",
                    enquiry_email: {email: true},
                    directory_capacity: {digits: true},
                    whatsapp: {intlTelNumber: true},
                    contact: {intlTelNumber: true},
                    website: {url: true},
                    facebook_link: {url: true},
                    instagram_link: {url: true},
                    youtube_link: {url: true},
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
                                toastr.clear();
                                toastr.success(data.msg);
                                $("#primary_id").val(data.primary_id);
                                $("#xlarge").modal('show');
                            } else {
                                toastr.clear();
                                toastr.error(data.msg);
                                $('#submit_request').removeAttr('disabled');
                            }
                            $('#prepage').hide();
                        },
                        error: function (jqXhr, json, errorThrown) {
                            toastr.clear();
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
                        directoryValidator.element(summernoteDescription);
                    },
                }
            });
            summernoteStatus.summernote({
                height: 300,
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteStatus.val(summernoteStatus.summernote('isEmpty') ? "" : contents);
                        directoryValidator.element(summernoteStatus);
                    },
                }
            });
            $('#main_category, #sub_category_id, #meta_tags, #slug, select.select2').on('change', function () {
                $(this).valid();
            });
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
                <?php if(Session::has('primary_id')): ?>
                $("#more-info-modal-button").click();
                <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/directories/create.blade.php ENDPATH**/ ?>