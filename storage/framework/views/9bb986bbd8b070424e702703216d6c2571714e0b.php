
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
                    <h1>Edit Talent - <?php echo e($talent->title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/publisher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/publisher/talents">Talent List</a></li>
                        <li class="breadcrumb-item active">Edit Talent</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-body">
        <!-- talents add start -->
        <?php echo Form::model($talent, ['id' => 'form_edit_talent', 'method' => 'PATCH', 'url' => route('publisher.talents.update', $talent->id), 'files' => true]); ?>

        <section class="app-user-edit">
            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h2 class="card-title">Edit Talent</h2>
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
                                <?php echo Form::select('main_category', $main_categories, $talent->get_subcat ? $talent->get_subcat->mainCategory->id : '', array('placeholder' => 'Main Category', 'id' => 'main_category', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sub_category_id">Select Sub Category</label>
                                <?php echo Form::select('sub_category_id', $subcatgories, null, array('placeholder' => 'Select Sub Category', 'id' => 'sub_category_id', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="title" class="control-label">Talent Name</label>
                                <?php echo Form::text('title', null, array('placeholder' => 'Talent Name', 'id' => 'title', 'class' => 'form-control', 'required')); ?>

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
                                <?php echo Form::number('prices', null, array('placeholder' => 'prices', 'id' => 'price', 'class' => 'form-control', 'required')); ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_featured', 'Is Featured Talent?'); ?>

                                <?php echo Form::select('is_featured',['No', 'Yes'], null, array('placeholder' => 'Is Featured Talent?', 'id' => 'is_featured', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_popular', 'Is Popular Talent?'); ?>

                                <?php echo Form::select('is_popular',['No', 'Yes'], null, array('placeholder' => 'Is Popular Talent?', 'id' => 'is_popular', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_trending', 'Is Trending Talent?'); ?>

                                <?php echo Form::select('is_trending',['No', 'Yes'], null, array('placeholder' => 'Is Trending Talent?', 'id' => 'is_trending', 'class' => 'form-control select2')); ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('is_hot', 'Is Hot Talent?'); ?>

                                <?php echo Form::select('is_hot',['No', 'Yes'], null, array('placeholder' => 'Is Hot Talent?', 'id' => 'is_hot', 'class' => 'form-control select2')); ?>

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
                                <?php echo Form::label('mobile', 'Mobile No (optional)'); ?>

                                <?php echo Form::tel('mobile', null, array('placeholder' => 'Mobile No', 'id' => 'contact', 'class' => 'form-control')); ?>

                                <span id="valid-msg-contact" class="hide text-success"></span>
                                <span id="error-msg-contact" class="hide text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pac-input">Location</label>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <?php echo Form::text('location', null, array('placeholder' => 'Enter Location', 'id' => 'pac-input', 'class' => 'form-control', 'required')); ?>

                                        <div id="map-canvas" style="width:100%;height:350px;margin-top: 10px;"></div>
                                        <input type="hidden" id="cityLat" name="citylat" value="<?php echo e($talent->lat); ?>"/>
                                        <input type="hidden" id="cityLng" name="citylong" value="<?php echo e($talent->lng); ?>"/>
                                        <?php echo Form::hidden('map_review', null, array('id' => 'map_review', 'class' => 'form-control')); ?>

                                        <?php echo Form::hidden('map_rating', null, array('id' => 'map_rating', 'class' => 'form-control')); ?>

                                        <?php echo Form::hidden('country', null, array('id' => 'country', 'class' => 'form-control')); ?>

                                        <?php echo Form::hidden('city', null, array('id' => 'city', 'class' => 'form-control')); ?>

                                        <?php echo Form::hidden('area', null, array('id' => 'area', 'class' => 'form-control')); ?>

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
                                    <?php echo Form::label('feature_image', 'Profile Image (800 X 475)', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="feature_image" name="feature_image[]"
                                           data-url="/publisher/talent/upload-photos/feature_image"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="feature_image_loading"></div>
                                <div class="row py-2" id="feature_image_list">
                                    <?php if($featureImage): ?>
                                        <div class="col-sm-4 my-2 text-center" id="img_<?php echo e($featureImage->id); ?>">
                                            <img
                                                src="<?php echo e($talent->getStoredImage($featureImage->image, $featureImage->image_type)); ?>"
                                                loading="lazy" class="img-fluid" width="200">
                                            <input type="text" name="alt_text_en[<?php echo e($featureImage->id); ?>]"
                                                   id="alt_text_en_<?php echo e($featureImage->id); ?>"
                                                   value="<?php echo e($featureImage->alt_texts['en'] ?? ''); ?>" class="form-control mt-2"
                                                   placeholder="Alt Text English">
                                            <a role="button" class="text-danger delete-image" data-type="feature_image"
                                               data-image-id="<?php echo e($featureImage->id); ?>"><i class="fa fa-trash"></i></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="feature_image_ids" id="feature_image_ids"
                                       value="<?php echo e($featureImage ? $featureImage->id : ''); ?>">
                            </div>
                        </div>


                        <div class="col-md-6 form-group">
                            <div class="fp-img-dropzone custom-file">
                                <?php echo Form::label('logo', 'Logo (240 X 200) (optional)', ['class' => 'form-label']); ?>

                                <input type="file" class="form-control" id="logo" name="logo[]"
                                       data-url="/publisher/talent/upload-photos/logo"
                                       accept="image/jpg,image/jpeg,image/gif,image/png">
                            </div>
                            <div id="logo_loading"></div>
                            <div class="row py-2" id="logo_list">
                                <?php if($logoImage): ?>
                                    <div class="col-sm-4 my-2 text-center" id="img_<?php echo e($logoImage->id); ?>">
                                        <img src="<?php echo e($talent->getStoredImage($logoImage->image, $logoImage->image_type)); ?>"
                                             loading="lazy" class="img-fluid" width="200">
                                        <input type="text" name="alt_text_en[<?php echo e($logoImage->id); ?>]"
                                               id="alt_text_en_<?php echo e($logoImage->id); ?>" value="<?php echo e($logoImage->alt_texts['en'] ?? ''); ?>"
                                               class="form-control mt-2" placeholder="Alt Text English">
                                        <a role="button" class="text-danger delete-image" data-type="logo"
                                           data-image-id="<?php echo e($logoImage->id); ?>"><i class="fa fa-trash"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="logo_ids" id="logo_ids"
                                   value="<?php echo e($logoImage ? $logoImage->id : ''); ?>">
                        </div>

                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="images-dropzone form-group custom-file">
                                    <?php echo Form::label('images', 'Select 4 Images (800 X 475) (optional)', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="images" name="images[]" multiple
                                           data-url="/publisher/talent/upload-photos/images"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="images_loading"></div>
                                <div class="row py-2" id="images_list">
                                    <?php if($mainImages): ?>
                                        <?php $__currentLoopData = $mainImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mainImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-sm-4 my-2 text-center" id="img_<?php echo e($mainImage->id); ?>">
                                                <img
                                                    src="<?php echo e($talent->getStoredImage($mainImage->image, $mainImage->image_type)); ?>"
                                                    loading="lazy" class="img-fluid" width="200">
                                                <input type="text" name="alt_text_en[<?php echo e($mainImage->id); ?>]"
                                                       id="alt_text_en_<?php echo e($mainImage->id); ?>"
                                                       value="<?php echo e($mainImage->alt_texts['en'] ?? ''); ?>" class="form-control mt-2"
                                                       placeholder="Alt Text English">
                                                <a role="button" class="text-danger delete-image" data-type="images"
                                                   data-image-id="<?php echo e($mainImage->id); ?>"><i class="fa fa-trash"></i></a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="images_ids" id="images_ids"
                                       value="<?php echo e($mainImages ? implode(',', $mainImages->pluck('id', 'id')->toArray()) : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="stories-dropzone form-group custom-file">
                                    <?php echo Form::label('stories', 'Story Images (800 X 475) (optional)', ['class' => 'form-label']); ?>

                                    <input type="file" class="form-control" id="stories" name="stories[]" multiple
                                           data-url="/publisher/talent/upload-photos/stories"
                                           accept="image/jpg,image/jpeg,image/gif,image/png">
                                </div>
                                <div id="stories_loading"></div>
                                <div class="row py-2" id="stories_list">
                                    <?php if($storyImages): ?>
                                        <?php $__currentLoopData = $storyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storyImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-sm-4 my-2 text-center" id="img_<?php echo e($storyImage->id); ?>">
                                                <img
                                                    src="<?php echo e($talent->getStoredImage($storyImage->image, $storyImage->image_type)); ?>"
                                                    loading="lazy" class="img-fluid" width="200">
                                                <input type="text" name="alt_text_en[<?php echo e($storyImage->id); ?>]"
                                                       id="alt_text_en_<?php echo e($storyImage->id); ?>"
                                                       value="<?php echo e($storyImage->alt_texts['en'] ?? ''); ?>" class="form-control mt-2"
                                                       placeholder="Alt Text English">
                                                <a role="button" class="text-danger delete-image" data-type="stories"
                                                   data-image-id="<?php echo e($storyImage->id); ?>"><i class="fa fa-trash"></i></a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="stories_ids" id="stories_ids"
                                       value="<?php echo e($storyImages ? implode(',', $storyImages->pluck('id', 'id')->toArray()) : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="youtube_rows_append" id="talent-youtube-urls">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="youtube">Enter Youtube Url</label>
                                        <?php if(isset($youtube_urls)): ?>
                                        <input type="url" class="form-control"
                                                   value="<?php echo e($youtube_urls[0]['youtube_url'] ?? ''); ?>"
                                                   name="youtube_url_1" id="youtube_url_1">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php $urls_array_values = [];
                                 $url_count = 400; ?>
                                <?php $__currentLoopData = array_slice($youtube_urls,1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $youtube): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php $urls_array_values[] = $url_count;

                                ?>

                                <div class="row appended_row_youtube-<?php echo e($url_count); ?>">
                                    <div class="col-md-4 form-group">
                                        <label for="youtube">Enter Youtube Url</label>
                                        <input type="url" class="form-control" value="<?php echo e($youtube['youtube_url']); ?>"
                                               name="youtube_url_<?php echo e($url_count); ?>" id="youtube_url_<?php echo e($url_count); ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <button type="button" id="remove_youtube_btn-<?php echo e($url_count); ?>"
                                                class="btn btn-xs btn-danger  remove_youtube_btn_cls margin_top_cls_social">
                                            <i data-feather="minus"></i>
                                        </button>
                                    </div>
                                </div>


                                <?php $url_count = $url_count + 1; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if(count($youtube_urls)): ?>
                                <input type="hidden" id="youtube_name_array" name="youtube_name_array" value="1,<?php echo e(implode(',',$urls_array_values)); ?>">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="button" id="add_youtube_btn"
                                    class="btn btn-primary margin_top_cls">
                                Add More
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card card-outline card-pink">
                <div class="card-header">
                    <h4 class="card-title">Social Link Section</h4>
                </div>
                <div class="card-body">
                    <div class="social_rows_append" id="talent-social-links">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="company">Select Social Name</label>
                                <select name="social_name_1" class="form-control" id="social_name_1">
                                    <option value="" disabled selected>Please select option</option>
                                    <?php if(isset($social_links)): ?>
                                        <?php $__currentLoopData = $array_social_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"
                                                <?php echo e(($social_links[0]['social_name'] ?? ''==$key) ? 'selected' : ''); ?>><?php echo e($soc); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php $__currentLoopData = $array_social_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"><?php echo e($soc); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="company">Enter Social Link</label>
                                <?php if(isset($social_links)): ?>
                                    <input type="text" class="form-control"
                                           value="<?php echo e($social_links[0]['social_link'] ?? ''); ?>"
                                           name="social_link_1" id="social_link_1">
                                <?php endif; ?>
                            </div>

                        </div>
                        <?php $links_array_values = []; ?>
                        <?php $link_count = 400; ?>
                        <?php $__currentLoopData = array_slice($social_links,1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php $links_array_values[] = $link_count; ?>

                            <div class="row appended_row_social-<?php echo e($link_count); ?>">
                                <div class="col-md-4 form-group">
                                    <label for="company">Select Social Name</label>
                                    <select name="social_name_<?php echo e($link_count); ?>" class="form-control"
                                            id="social_name_<?php echo e($link_count); ?>">
                                        <option value="" disabled selected>Please select option</option>
                                        <?php $__currentLoopData = $array_social_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($key); ?>" <?php echo e(($social['social_name']==$key) ? 'selected' : ''); ?>>
                                                <?php echo e($soc); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="company">Enter Social Link</label>
                                    <input type="text" class="form-control" value="<?php echo e($social['social_link']); ?>"
                                           name="social_link_<?php echo e($link_count); ?>" id="social_link_<?php echo e($link_count); ?>">
                                </div>
                                <div class="col-md-2 form-group">
                                    <button type="button" id="remove_social_btn-<?php echo e($link_count); ?>"
                                            class="btn btn-xs btn-danger  remove_social_btn_cls margin_top_cls_social">
                                        <i data-feather="minus"></i>
                                    </button>
                                </div>
                            </div>


                            <?php $link_count = $link_count + 1; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(count($social_links)>1): ?>
                            <input type="hidden" id="social_name_array" name="social_name_array"
                                   value="1,<?php echo e(implode(',',$links_array_values)); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <button type="button" id="add_social_btn"
                                    class="btn btn-primary margin_top_cls">
                                Add More
                            </button>
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
                            <?php echo Form::label('meta_keywords', 'Meta Tags'); ?>

                            <?php echo Form::text('meta_keywords', null, array('placeholder' => 'Meta Keywords', 'id' => 'meta_keywords', 'data-role'=>'tagsinput', 'class' => 'form-control', 'required')); ?>

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
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary" id="submit_request">Submit</button>
                    <button type="button" class="btn btn-outline-secondary ms-5"
                            onclick="window.location.href='<?php echo e(route('publisher.talents.index')); ?>'">Cancel
                    </button>
                </div>
            </div>
        </section>

        <?php echo Form::close(); ?>

    </div>
    <!-- talents add ends -->
    <div class="overlay"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php echo $__env->make('admin.common-scripts', ['section' => 'publisher', 'module_name' => 'talent', 'amenties' => [], 'landmarks' => []], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    $(function () {
        const summernoteDescription = $('#description');
        const summernoteStatus = $('#status_text');
        //$('#slug').slugify('#title');
        const talentValidator = $("#form_edit_talent").validate({
            ignore: [],
            rules: {
                title: "required",
                main_category: "required",
                sub_category_id: "required",
                location: "required",

                email: {email: true},
                whatsapp: {intlTelNumber: true},
                contact: {intlTelNumber: true},
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
                            window.location.href = '/publisher/talents';
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
                    talentValidator.element(summernoteDescription);
                },
            }
        });
        summernoteStatus.summernote({
            height: 300,
            callbacks: {
                onChange: function (contents, $editable) {
                    summernoteStatus.val(summernoteStatus.summernote('isEmpty') ? "" : contents);
                    talentValidator.element(summernoteStatus);
                },
            }
        });
        $('#main_category, #sub_category_id, #meta_keywords, #slug, #popular_type, select.select2').on('change', function () {
            $(this).valid();
        });

        var counter_1 = 10;
            $("#add_youtube_btn").click(function () {
                counter_1 = parseInt(counter_1) + 1
                var id_now_1 = counter_1;
                var html_now_1 = create_youtube_row(id_now_1);

                $(".youtube_rows_append").append(html_now_1);

                $("#youtube_url_" + id_now_1).prop('required', true);

                feather.replace();

            });

            $(document).on('click', '.remove_youtube_btn_cls', function () {
                var ids_1 = $(this).attr('id');
                var now_1 = ids_1.split("-");
                $(".appended_row_youtube-" + now_1[1]).remove();
            });

            function create_youtube_row(id_now_1) {
                var html_1 = '<div class="row appended_row_youtube-' + id_now_1 + '"><div class="col-md-4 form-group"><label for="company">Enter Youtube Url</label><input type="url" class="form-control" name="youtube_url_' + id_now_1 + '" id="youtube_url_' + id_now_1 + '" ></div><div class="col-md-4 form-group"><button type="button" id="remove_youtube_btn-' + id_now_1 + '" class="btn btn-sm btn-danger  remove_youtube_btn_cls margin_top_cls_social"><i data-feather="minus">-</i></button></div></div>';

                var value_current_1 = $("#youtube_name_array").val();
                var now_value_1 = value_current_1 + "," + id_now_1;

                $("#youtube_name_array").val(now_value_1);

                return html_1;
            }

        var counter = 10;
        $("#add_social_btn").click(function () {
            counter = parseInt(counter) + 1
            var id_now = counter;
            var html_now = create_social_row(id_now);

            $(".social_rows_append").append(html_now);
            $('#talent-social-links').find('select').select2();
            $("#social_link_" + id_now).prop('required', true);
            $("#social_name_" + id_now).prop('required', true);
            feather.replace();

        });

        $(document).on('click', '.remove_social_btn_cls', function () {
            var ids = $(this).attr('id');
            var now = ids.split("-");
            $(".appended_row_social-" + now[1]).remove();
        });

        function create_social_row(id_now) {
            var html = '<div class="row appended_row_social-' + id_now + '"><div class="col-md-4 form-group"><label for="company">Select Social Name</label><select name="social_name_' + id_now + '"  class="form-control" id="social_name_' + id_now + '" ><option value="" disabled selected>Please select option</option><?php $__currentLoopData = $array_social_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $soc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>"><?php echo e($soc); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="col-md-4 form-group"><label for="company">Enter Social Link</label><input type="text" class="form-control" name="social_link_' + id_now + '" id="social_link_' + id_now + '" ></div><div class="col-md-4 form-group"><button type="button" id="remove_social_btn-' + id_now + '" class="btn btn-sm btn-danger  remove_social_btn_cls margin_top_cls_social"><i data-feather="minus">-</i></button></div></div>';

            var value_current = $("#social_name_array").val();
            var now_value = value_current + "," + id_now;
            $("#social_name_array").val(now_value);

            return html;
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('publisher.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/publisher/talents/edit.blade.php ENDPATH**/ ?>