
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
                    <h1>Add Motor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/publisher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/publisher/motors">Motor List</a></li>
                        <li class="breadcrumb-item active">Add Motor</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <!-- events add start -->
        <?php echo Form::open(['url' => route('publisher.motors.store'), 'id' => 'form_create_motor', 'files' => true]); ?>

        <section class="app-user-edit">
            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h2 class="card-title">Create Motor</h2>
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="main_category">Manufacturers</label>
                                <?php echo Form::select('manufacturer_id', $manufacturer, null, array('placeholder' => 'Select Manufacturer', 'id' => 'manufacturer_id', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="title" class="control-label">Motor Name</label>
                                <?php echo Form::text('title', null, array('placeholder' => 'Motor Name', 'id' => 'title', 'class' => 'form-control', 'required')); ?>

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
                                <label for="prices">Price <b>(optional)</b></label>
                                 <?php echo Form::text('prices', null, array('placeholder' => 'Price', 'id' => 'prices', 'class' => 'form-control', 'required')); ?>

                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('assign_featured', 'Is Featured Motor?'); ?>

                                <?php echo Form::select('assign_featured',['No', 'Yes'], null, array('placeholder' => 'Is Featured Motor?', 'id' => 'assign_featured', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_popular', 'Is Popular Motor?'); ?>

                                <?php echo Form::select('is_popular',['No', 'Yes'], null, array('placeholder' => 'Is Popular Motor?', 'id' => 'is_popular', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_trending', 'Is Trending Motor?'); ?>

                                <?php echo Form::select('is_trending',['No', 'Yes'], null, array('placeholder' => 'Is Trending Motor?', 'id' => 'is_trending', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_hot', 'Is Hot Motor?'); ?>

                                <?php echo Form::select('is_hot',['No', 'Yes'], null, array('placeholder' => 'Is Hot Motor?', 'id' => 'is_hot', 'class' => 'form-control select2')); ?>

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
                                <?php echo Form::label('email', 'Email (optional)'); ?>

                                <?php echo Form::email('email', null, array('placeholder' => 'Email', 'id' => 'email', 'class' => 'form-control')); ?>

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

                                <?php echo Form::tel('contact', null, array('placeholder' => 'Mobile No', 'id' => 'contact', 'class' => 'form-control')); ?>

                                <span id="valid-msg-contact" class="hide text-success"></span>
                                <span id="error-msg-contact" class="hide text-danger"></span>
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
                    <h4 class="card-title">Motors Accomadation</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="product_type" class="control-label">Select Motor Type <b>(required)</b></label>
                                <?php echo Form::select('accommodation_type', $arr_moter_type,null, array('placeholder' => 'Motor Type', 'id' => 'accommodation_type', 'class' => 'form-control select2', 'required')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('motor_km', 'Motor KM'); ?>

                                <?php echo Form::text('motor_km', null, array('placeholder' => 'Motor KM', 'id' => 'motor_km', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('motor_year', 'Model No'); ?>

                                <?php echo Form::text('motor_year', null, array('placeholder' => 'Model No', 'id' => 'motor_year', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('motor_powers', 'Model Engine'); ?>

                                <?php echo Form::text('motor_powers', null, array('placeholder' => 'Model Engine', 'id' => 'motor_powers', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('motor_seats', 'Model No. Of Seats'); ?>

                                <?php echo Form::text('motor_seats', null, array('placeholder' => 'Model No. Of Seats', 'id' => 'motor_seats', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4 accommodation_class">
                            <div class="form-group">
                                <?php echo Form::label('daily_price', 'Daily Price'); ?>

                                <?php echo Form::text('daily_price', null, array('placeholder' => 'Daily Price', 'id' => 'daily_price', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4 accommodation_class">
                            <div class="form-group">
                                <?php echo Form::label('weekly_price', 'Weekly Price'); ?>

                                <?php echo Form::text('weekly_price', null, array('placeholder' => 'Daily Price', 'id' => 'weekly_price', 'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-4 accommodation_class">
                            <div class="form-group">
                                <?php echo Form::label('yearly_price', 'Yearly Price'); ?>

                                <?php echo Form::text('yearly_price', null, array('placeholder' => 'Yearly Price', 'id' => 'yearly_price', 'class' => 'form-control')); ?>

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
                                           data-url="/publisher/motor/upload-photos/feature_image"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="feature_image_loading"></div>
                                <div class="row py-2" id="feature_image_list"></div>
                                <input type="hidden" name="feature_image_ids" id="feature_image_ids" value="">
                            </div>
                        </div>
                       
                        <div class="col-md-6 form-group">
                            <div class="fp-img-dropzone custom-file">
                                <?php echo Form::label('logo', 'Logo (240 X 200) (optional)', ['class' => 'form-label']); ?>

                                <input type="file" class="form-control" id="logo" name="logo[]"
                                       data-url="/publisher/motor/upload-photos/logo"
                                       accept="image/jpg,image/jpeg,image/gif,image/png">
                            </div>
                            <div id="logo_loading"></div>
                            <div class="row py-2" id="logo_list"></div>
                            <input type="hidden" name="logo_ids" id="logo_ids" value="">
                        </div>
                       
                        <div class="col-md-6 form-group">
                            <label for="basicInput">Video Youtube Link Or Image <b>(optional)</b></label><br>

                            <input type="radio" id="test2" name="youtube_img" value="1">
                            <label for="test2"><b> Youtube Link </b></label><br>

                            <input type="radio" id="test3" name="youtube_img" value="2">
                            <label for="test3"><b> Image</b></label>
                        </div>
                        <div class="col-md-4 form-group youtube" style="display: none">
                            <?php echo Form::url('video', null, array('placeholder' => 'Youtube Link', 'id' => 'video', 'class' => 'form-control')); ?>

                        </div>
                        <div class="col-md-6 form-group img_you" style="display: none">
                            <div class="main-img-dropzone custom-file">
                                <input type="file" class="form-control" id="main_image" name="main_image[]"
                                       data-url="/publisher/motor/upload-photos/main_image"
                                       accept="image/jpg,image/jpeg,image/gif,image/png">
                            </div>
                            <div id="main_image_loading"></div>
                            <div class="row py-2" id="main_image_list"></div>
                            <input type="hidden" name="main_image_ids" id="main_image_ids" value="">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="images-dropzone form-group custom-file">
                                    <?php echo Form::label('images', 'Select 4 Images (800 X 475) (optional):', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="images" name="images[]" multiple
                                           data-url="/publisher/motor/upload-photos/images"
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
                                    <?php echo Form::label('stories', 'Story Images (800 X 475) (optional):', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="stories" name="stories[]" multiple
                                           data-url="/publisher/motor/upload-photos/stories"
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

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-pink">
                        <div class="card-header">
                            <h4 class="card-title">Amenities</h4>
                        </div>
                        <div class="card-body">
                            <div class="append_ament_row" id="module-amenities">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="amentie_icon_0">Select Amenity <b>(optional)</b></label>
                                            <select class="form-control select2" name="amenities[]" id="amentie_icon_0">
                                                <option value="" disabled selected>please select option</option>
                                                <?php $__currentLoopData = $amenties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity_id => $amentity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($amenity_id); ?>"><?php echo e($amentity); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" id="add-amenity"
                                            class="btn btn-sm btn-primary margin_top_cls">
                                        Add More
                                    </button>
                                    <input type="hidden" id="amentie_array" name="amentie_array" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-outline card-pink">
                        <div class="card-header">
                            <h4 class="card-title">Popular Landmarks</h4>
                        </div>
                        <div class="card-body">
                            <div class="append_landmark_row" id="module-landmarks">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="landmark_0_name">Icon <b>(optional)</b></label>
                                            <select class="form-control select2" name="landmark[0][name]"
                                                    id="landmark_0_name">
                                                <option value="" disabled selected>please select option</option>
                                                <?php $__currentLoopData = $landmarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $landmark_id => $landmark_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($landmark_id); ?>"><?php echo e($landmark_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="landmark_0_description">Description <b>(optional)</b></label>
                                            <input type="text" class="form-control" id="landmark_0_description"
                                                   name="landmark[0][description]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" id="add-landmark"
                                            class="btn btn-sm btn-primary margin_top_cls">
                                        Add More
                                    </button>
                                    <input type="hidden" id="landmark_array" name="landmark_array" value="1">
                                </div>
                            </div>
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
                <input type="hidden" name="is_draft" id="is_draft" value="0">
                <div class="col-6">
                    <button type="submit" class="btn btn-primary" id="submit_request">Submit <i
                            data-feather="check"></i></button>

                    <button type="button" id="draft_button" class="btn btn-secondary ms-5">Save as Draft<i
                            data-feather="check"></i></button>
                </div>
            </div>
        </section>

        <?php echo Form::close(); ?>

    </div>
    <!-- events add ends -->
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
                <form action="<?php echo e(route('publisher.motor_save_more_info')); ?>" method="POST" enctype='multipart/form-data'>
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
    <?php echo $__env->make('admin.common-scripts', ['section' => 'publisher', 'module_name' => 'motor', 'amenties' => $amenties, 'landmarks' => $landmarks], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        $(function () {
            $(".accommodation_class").hide();
            const summernoteDescription = $('#description');
            const summernoteStatus = $('#status_text');
            $('#slug').slugify('#title');

            const motorValidator = $("#form_create_motor").validate({
                ignore: ':hidden:not(.editor),.note-editable.card-block',
                rules: {
                    title: "required",
                    main_category: "required",
                    sub_category_id: "required",
                    location: "required",
                    email: {email: true},
                    whatsapp: {intlTelNumber: true},
                    contact: {intlTelNumber: true},
                    video: {youtubeUrl: true}
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
                                //window.location.href = '/publisher/motors';
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
                        motorValidator.element(summernoteDescription);
                    },
                }
            });
            summernoteStatus.summernote({
                height: 300,
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteStatus.val(summernoteStatus.summernote('isEmpty') ? "" : contents);
                        motorValidator.element(summernoteStatus);
                    },
                }
            });
            $('#main_category, #sub_category_id, #meta_tags, #slug, #popular_type, select.select2').on('change', function () {
                $(this).valid();
            });

            var k = 1;
            var addEventBtn = $('.add-more-event');
            var eventWrapper = $('.event-field-wrapper');

            $(addEventBtn).click(function () {

                var eventField = '<div class="row"><div class="col-md-5">'
                eventField += '<div class="form-group">'
                eventField += '<input type="text" name="event[' + k + '][name]" class="form-control" id="basicInput" placeholder="Enter Event Name" />'
                eventField += '</div>'
                eventField += '</div>'
                eventField += '<div class="col-md-5 form-group">'
                eventField += '<input type="datetime-local" name="event[' + k + '][date]" class="form-control" placeholder="Enter Date" id="">'
                eventField += '</div>'
                eventField +=                '<div class="col-md-2">'
                eventField +=                     '<button type="button" id="event_add_row_btn" class="dlt-btn-event btn btn-sm btn-danger">'
                eventField +=     '<i data-feather="minus"></i>'
                eventField +=        '</button>'
                eventField +=  '</div></div>'

                $(eventWrapper).append(eventField);
                feather.replace();
                k++; //Add field html
            });

            $(eventWrapper).on('click', '.dlt-btn-event', function (e) {
                e.preventDefault();
                $(this).parent().parent().remove(); //Remove field html
                k--;
            })
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
         $("#accommodation_type").change(function () {
            var selected_v = $(this).val();
            if (selected_v == "1") {
                $(".accommodation_class").hide();
            } else {
                $(".accommodation_class").show();
            }
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

<?php echo $__env->make('publisher.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/publisher/motors/create.blade.php ENDPATH**/ ?>