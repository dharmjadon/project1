<div class="row col-12">

    <style type="text/css">
        .tab-modulemorep {
            border-style: solid;
            border-width: 1px;
            border-image: linear-gradient(to right, #702786, #feb967);
            border-image-slice: 1;
            margin: 0 0 50px 0;
        }

        .tab-modulemorep .tab-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: start;
            align-items: center;
            margin: 0;
            background-image: linear-gradient(40deg, #7a2185, #e20082, #ff5580, #feae6b);
            padding: 8px 15px 1px 15px;
        }

        .tab-modulemorep .tab-menu li {
            display: flex;
            flex-wrap: wrap;
            justify-content: start;
            align-items: center;
            font-family: 'Montserrat-Bold';
            font-size: 13px !important;
            color: #fff !important;
            text-transform: capitalize !important;
            background: none !important;
            border-radius: 50px !important;
            cursor: pointer;
            transition: 0.5s;
            padding: 7px 14px !important;
            margin: 18px 0 !important;
        }

        .tab-modulemorep .tab-menu div {
            width: 22%;
            margin: 0 0.2% !important;
        }

        .tab-modulemorep .tab-menu li.active, .tab-modulemorep .tab-menu li:hover {
            background: #fff !important;
            color: #000 !important;
        }

        .tab-modulemorep .tab-menu li.active img, .tab-modulemorep .tab-menu li:hover img {
            filter: brightness(0.1);
        }

        .tab-modulemorep .tab-menu li.tabactive {
            font-size: inherit !important;
            color: inherit !important;
            background: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        .tab-modulemorep .tab-menu li.tabactive a {
            font-family: 'Montserrat-Bold';
            font-size: 13px !important;
            color: #fff !important;
            text-transform: capitalize !important;
            border-radius: 50px !important;
            padding: 7px 14px !important;
            margin: 0 !important;
        }

        .tab-modulemorep .tab-menu li.tabactive:hover a {
            background: #fff !important;
            color: #000 !important;
        }

        .tab-modulemorep .tab-menu li:hover a {
            color: #000 !important;
        }

        .tab-modulemorep .tab-menu li:focus a {
            color: #000 !important;
        }

        .tab-modulemorep .tab-menu li a:focus {
            color: #000 !important;
        }

        .tab-modulemorep .tab-menu li img {
            width: 18px;
            height: 18px;
            margin-right: 7px;
        }

        .tab-modulemorep .tab-menu i {
            font-size: 16px;
            margin-right: 8px;
        }

        .tab-modulemorep .tab-content {
            display: none;
            padding: 40px;
        }

        .tab-modulemorep .tab-content.active {
            display: inherit !important;
        }

        .tab-modulemorep .tab-content h3 {
            font-size: 25px;
            text-align: center;
        }

        .tab-gallery-photo-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .tab-gallery-photo-item .imgeffect {
            width: 16%;
            margin: 0.2%;
        }

        .tabs-nav-gallery a {
            width: 50%;
            padding: 8px 10px;
            font-size: 18px;
        }

        .tab-gallery-photo-item .imgeffect {
            width: 24%;
        }

        .tab-gallery-video-item iframe {
            width: 49%;
            height: auto;
        }

        .imgeffect {
            position: relative;
            overflow: hidden;
            width: 100%;
            background: #000;
            text-align: center
        }

        .imgeffect img {
            position: relative;
            display: block;
            min-height: 100%;
            max-width: 100%;
            width: 100%;
            opacity: 1;
            max-width: none;
            -webkit-transition: opacity 1s, -webkit-transform 1s;
            transition: opacity 1s, transform 1s;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden
        }

        .imgeffect:hover img {
            opacity: 0.8;
            -webkit-transform: scale3d(1.1, 1.1, 1);
            transform: scale3d(1.1, 1.1, 1)
        }

    </style>

    <script src="<?php echo e(asset('user-asset/js/jquery-3.6.0.min.js')); ?>"></script>


    <div class="col-12">
        <div class="tab-modulemorep clearfix">
            <input type="hidden" name="delitems[]" id="delitems">
            <ul class="tab-menu">
                <li data-tab="download-tab" class="active">
                    <img src="<?php echo e(asset('user-asset/')); ?>/images/landing/download.svg" alt="download"
                         class="tab-download-i"> Download
                </li>
                <li data-tab="web-tab">
                    <img src="<?php echo e(asset('user-asset/')); ?>/images/landing/website.svg" alt="website"> Website
                </li>
                <li data-tab="services-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/services.svg"
                                                 alt="services"> Services
                </li>
                <li data-tab="download-app-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/download-app.svg"
                                                     alt="download-app" class="tab-app-i"> Download App
                </li>
                <li data-tab="store-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/store.svg" alt="store">
                    Store
                </li>
                <li data-tab="gallery-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/gallery.svg"
                                                alt="gallery"> Gallery
                </li>
                <li data-tab="offers-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/offers.svg"
                                               alt="offers"> Offers
                </li>
                <li data-tab="follow-us-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/follow-us.svg"
                                                  alt="follow-us"> Follow Us
                </li>
                <li data-tab="career-tab"><img src="<?php echo e(asset('user-asset/')); ?>/images/landing/career.svg"
                                               alt="follow-us">Career
                </li>
            </ul>
            <?php $cemail = $career_heading = $career_summary = ''; ?>
            <?php if(isset($more_info) && !empty($more_info)): ?>
                <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($row->section_name=='career' && $row->file_type=='email'): ?>
                        <?php
                            $cemail = $row->file_name;
                            $career_heading = $row->section_heading;
                            $career_summary = $row->section_summary;
                        ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <div id="download-tab" class="tab-content active">
                <div>
                    <table id="dpdf_section">


                        <tbody>
                        <tr>
                            <td class="col-6">
                                <div class="form-group mt-1">
                                    <label for="basicInput">Select Files</label>
                                    <input type="file" class="form-control" id="dPdf" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, image/jpg, image/png, image/jpeg,
text/plain, application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.presentationml.slideshow"
                                           name="dPdf[]" placeholder="Enter Category"/>
                                </div>
                            </td>
                            <td class="col-6">
                                <div class="">
                                    <label for="">Title </label>
                                    <select type="text" name="dPdf_name[]" id="dPdf_name" class="form-control">
                                        <option value="">Select File Type</option>
                                        <option value="Brochure">Brochure</option>
                                        <option value="Company Profile">Company Profile</option>
                                        <option value="Catalogue">Catalogue</option>
                                        <option value="Floor Plan">Floor Plan</option>
                                        <option value="Menu">Menu</option>
                                        <option value="Flyer">Flyer</option>
                                        <option value="Poster">Poster</option>
                                        <option value="Leaflet">Leaflet</option>
                                        <option value="Location">Location</option>
                                    </select>
                                </div>
                            </td>
                            <td class="col-3">
                                <div class="col-md-2">
                                    <button type="button" id=""
                                            class="add-dpdf btn btn-xs btn-primary margin_top_cls d-none">
                                        <i data-feather="plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php if(isset($more_info) && !empty($more_info)): ?>
                            <?php $image_counter = 1; ?>
                            <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($row->section_name=='download'): ?>
                                    <tr>
                                        <td class="col-6">
                                            <div class="form-group mt-1">
                                                <label for="basicInput">View File</label>
                                                <a href="<?php echo e($row->storedOtherImage($row->file_path)); ?>"
                                                   target="_blank">File <?php echo e($image_counter); ?></a>
                                            </div>
                                        </td>
                                        <td class="col-6">
                                            <div class="">
                                                <label class=""><?php echo e($row->file_name); ?></label>
                                            </div>
                                        </td>
                                        <td class="col-3">
                                            <div class="col-md-2">
                                                <button type="button" data-id="<?php echo e($row->id); ?>"
                                                        class="rem-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i
                                                        data-feather="minus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $image_counter = $image_counter + 1; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="col-12">
                                <button type="button" id=""
                                        class="add-dpdf btn btn-sm btn-primary margin_top_cls mt-2">
                                    Add More
                                </button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="dpdf_list"></div>
            </div>
            <div id="web-tab" class="tab-content">
                <div class="">
                    <label for="">Website URL</label>
                    <?php $webVal=''; ?>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='website'): ?>
                                <?php $webVal=$row->file_name; ?>

                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <input type="text" value="<?php echo e($webVal); ?>" name="web_url" id="web_url" class="form-control"
                           placeholder="Enter Website URL">
                </div>
            </div>
            <div id="services-tab" class="tab-content">
                <table id="services_table">
                    <tbody>
                    <tr>
                        <td class="col-6">
                            <div class="form-group mt-1">
                                <label for="basicInput">Select Image Files</label>
                                <input type="file" class="form-control" accept="image/*" id="serv_images"
                                       name="serv_images[]" placeholder="Enter Category"/>
                            </div>
                        </td>
                        <td class="col-6">
                            <div class="">
                                <label for="">Title </label>
                                <input type="text" name="serv_img_name[]" id="serv_img_name" value=""
                                       class="form-control" placeholder="Enter File Title">
                            </div>
                        </td>
                        <td class="col-3">
                            <div class="col-md-2">
                                <button type="button"
                                        class="add-services btn btn-xs btn-primary margin_top_cls mt-2 waves-effect waves-float waves-light d-none">
                                    <i data-feather="plus"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $image_counter = 1; ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='services'): ?>
                                <tr>
                                    <td class="col-6">
                                        <div class="form-group mt-1">
                                            <label for="basicInput">Select Image File</label>
                                            <a href="<?php echo e($row->storedOtherImage($row->file_path)); ?>" target="_blank"
                                               data-toggle="lightbox"
                                               data-title="Image <?php echo e($image_counter); ?>"><img src="<?php echo e($row->storedOtherImage($row->file_path)); ?>" class="img-fluid" alt="Image <?php echo e($image_counter); ?>"></a>
                                        </div>
                                    </td>
                                    <td class="col-6">
                                        <div class="">

                                            <label class=""><?php echo e($row->file_name); ?></label>
                                        </div>
                                    </td>
                                    <td class="col-3">
                                        <div class="col-md-2">
                                            <button type="button" data-id="<?php echo e($row->id); ?>"
                                                    class="rem-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i
                                                    data-feather="minus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php $image_counter = $image_counter + 1; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" class="col-12">
                            <button type="button" id=""
                                    class="add-services btn btn-sm btn-primary margin_top_cls mt-2 waves-effect waves-float waves-light">
                                Add More
                            </button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div id="download-app-tab" class="tab-content">
                <table id="app_table">
                    <tbody>
                    <tr>
                        <td class="col-6">
                            <div class="form-group mt-1">
                                <label for="basicInput">Select Platform</label>
                                <select id="app_platform_list" name="app_platform_list[]" class="form-control">
                                    <option value="">Select</option>
                                    <option value="android">GooglePlay</option>
                                    <option value="ios">Apple Store</option>
                                    <option value="huawei">Huawei</option>
                                </select>
                            </div>
                        </td>
                        <td class="col-6">
                            <div class="form-group mt-1">
                                <label for="basicInput">Select QR Code Image</label>
                                <input type="file" class="form-control" accept="image/*" id="qrcode_img"
                                       name="qrcode_img[]"/>
                            </div>
                        </td>
                        <td class="col-3">
                            <div class="col-md-2">
                                <button type="button" id="" class="add-app btn btn-xs btn-primary margin_top_cls mt-2 d-none">
                                    <i data-feather="plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $image_counter = 1; ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='download_app'): ?>
                                <tr>
                                    <td class="col-6">
                                        <div class="form-group mt-1">
                                            <label for="basicInput">Select Platform</label>
                                            <select id="app_platform_list" name="app_platform_list[]"
                                                    class="form-control">
                                                <option value="">Select</option>
                                                <option <?php echo e(($row->file_name=='android')?'selected':''); ?> value="android">
                                                    GooglePlay
                                                </option>
                                                <option <?php echo e(($row->file_name=='ios')?'selected':''); ?> value="ios">Apple
                                                    Store
                                                </option>
                                                <option <?php echo e(($row->file_name=='huawei')?'selected':''); ?> value="huawei">
                                                    Huawei
                                                </option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="col-6">
                                        <div class="form-group mt-1">
                                            <label for="basicInput">Select QR Code Image</label>
                                            <input type="file" class="form-control" accept="image/*" id="qrcode_img"
                                                   name="qrcode_img[]"/>
                                            <a href="<?php echo e($row->storedOtherImage($row->file_path)); ?>" target="_blank"
                                               data-toggle="lightbox"
                                               data-title="Image <?php echo e($image_counter); ?>">
                                                <img src="<?php echo e($row->storedOtherImage($row->file_path)); ?>" class="img-fluid" alt="Image <?php echo e($image_counter); ?>"></a>
                                        </div>
                                    </td>
                                    <td class="col-3">
                                        <div class="col-md-2">
                                            <div class="col-md-2">
                                                <button type="button" data-id="<?php echo e($row->id); ?>"
                                                        class="rem-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i
                                                        data-feather="minus"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php $image_counter = $image_counter + 1; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" class="col-12">
                            <button type="button" id=""
                                    class="add-app btn btn-sm btn-primary margin_top_cls mt-2">
                                Add More
                            </button>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
            <div id="store-tab" class="tab-content">
                <div class="form-group mt-1">
                    <label for="basicInput">Select Multiple Store Images</label>
                    <input type="file" class="form-control" id="storeImage" name="storeImage[]"
                           placeholder="Enter Category" multiple/>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <div class="row">
                        <?php $image_counter = 1; ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='store'): ?>
                                <div class="col-md-3 my-2 text-center">
                                <a href="<?php echo e($row->storedOtherImage($row->file_path)); ?>" data-toggle="lightbox"
                                   data-title="Image <?php echo e($image_counter); ?>" id="img_tag_<?php echo e($row->id); ?>"
                                   target="_blank"><img src="<?php echo e($row->storedOtherImage($row->file_path)); ?>" class="img-fluid" alt="Image <?php echo e($image_counter); ?>"></a>
                                <a href="javascript:void(0)" class="ml-1 mr-1 rem-btn-dpdf" data-id="<?php echo e($row->id); ?>">
                                    <span class="iconify" data-icon="emojione-v1:cross-mark"></span>
                                </a>
                                </div>
                                <?php $image_counter = $image_counter + 1; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div id="gallery-tab" class="tab-content">
                <div class="tabs-nav-gallery"><a class="tabslink" onclick="openTab(event, 'photos-gallery-tab')"
                                                 id="tab-cus-active">Photos</a> <a class="tabslink"
                                                                                   onclick="openTab(event, 'videos-gallery-tab')">Videos</a>
                </div>
                <!-- Tab content -->
                <div id="photos-gallery-tab" class="tabscontent-gallery">
                    <div class="form-group mt-1">
                        <label for="basicInput">Select Multiple Images</label>
                        <input type="file" class="form-control" id="gallary_img" accept="image/*" name="gallary_img[]"
                               placeholder="Enter Category" multiple/>
                    </div>

                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $image_counter = 1; ?>
                    <div class="row">
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='gallary' && $row->file_type=='image'): ?>
                                <div class="col-md-3 my-2 text-center">
                                <a href="<?php echo e($row->storedOtherImage($row->file_path)); ?>" id="img_tag_<?php echo e($row->id); ?>"
                                   target="_blank" data-toggle="lightbox"
                                   data-title="Image <?php echo e($image_counter); ?>"><img src="<?php echo e($row->storedOtherImage($row->file_path)); ?>" class="img-fluid" alt="Image <?php echo e($image_counter); ?>"></a>
                                <a href="javascript:void(0)" class="ml-1 mr-1 rem-btn-dpdf" data-id="<?php echo e($row->id); ?>">
                                    <span class="iconify" data-icon="emojione-v1:cross-mark"></span>
                                </a>
                                </div>
                                <?php $image_counter = $image_counter + 1; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div id="videos-gallery-tab" class="tabscontent-gallery ">

                    <table id="video_gallary_section">
                        <tbody>
                        <tr>
                            <td class="col-12">
                                <div class="form-group mt-1">
                                    <label for="basicInput">Enter Video Link</label>
                                    <input type="text" class="form-control" id="videoLink" name="videoLink[]"
                                           placeholder="Accept only Youtube Links"/>

                                </div>
                            </td>
                            <td class="col-3">
                                <div class="col-md-2">
                                    <button type="button" id=""
                                            class="add-videos btn btn-xs btn-primary margin_top_cls mt-2 d-none">
                                        <i data-feather="plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php if(isset($more_info) && !empty($more_info)): ?>
                            <?php $image_counter = 1; ?>
                            <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($row->section_name=='gallary' && $row->file_type=='videos'): ?>
                                    <tr>
                                        <td class="col-6">
                                            <div class="">
                                                <label class=""><?php echo e($row->file_path); ?></label>
                                            </div>
                                        </td>
                                        <td class="col-3">
                                            <div class="col-md-2">
                                                <button type="button" data-id="<?php echo e($row->id); ?>"
                                                        class="rem-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i
                                                        data-feather="minus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $image_counter = $image_counter + 1; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2" class="col-12">
                                <button type="button" id=""
                                        class="add-videos btn btn-sm btn-primary margin_top_cls mt-2">
                                    Add More
                                </button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div id="offers-tab" class="tab-content">
                <div class="form-group mt-1">
                    <label for="basicInput">Select Multiple Offer Images</label>
                    <input type="file" class="form-control" id="offer_img" accept="image/*" name="offer_img[]"
                           multiple/>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $image_counter = 1; ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='offer'): ?>
                                <a href="<?php echo e($row->storedOtherImage($row->file_path)); ?>" id="img_tag_<?php echo e($row->id); ?>"
                                   target="_blank" data-toggle="lightbox"
                                   data-title="Image <?php echo e($image_counter); ?>"><img src="<?php echo e($row->storedOtherImage($row->file_path)); ?>" class="img-fluid" alt="Image <?php echo e($image_counter); ?>"></a>
                                <a href="javascript:void(0)" class="ml-1 mr-1 rem-btn-dpdf" data-id="<?php echo e($row->id); ?>">
                                    <span class="iconify" data-icon="emojione-v1:cross-mark"></span>
                                </a> |
                                <?php $image_counter = $image_counter + 1; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div id="follow-us-tab" class="tab-content">
                <table id="followus_table">
                    <tbody>
                    <tr>
                        <td class="col-6">
                            <div class="form-group mt-1">
                                <label for="basicInput">Select Social Media</label>
                                <select id="social_media_list" name="social_media_list[]" class="form-control">
                                    <option value="">Select</option>
                                    <option value="tiktok">TikTok</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="twitter">Twitter</option>
                                </select>
                            </div>
                        </td>
                        <td class="col-6">
                            <div class="">
                                <label for="">Enter Link</label>
                                <input type="text" name="social_media_url[]" id="social_media_url" class="form-control"
                                       placeholder="Enter URL">
                            </div>
                        </td>
                        <td class="col-3">
                            <div class="col-md-2">
                                <button type="button" id=""
                                        class="add-social-media btn btn-xs btn-primary margin_top_cls mt-2 d-none">
                                    <i data-feather="plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $image_counter = 1; ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='followus'): ?>
                                <tr>
                                    <td class="col-6">
                                        <div class="form-group mt-1">
                                            <?php echo e(($row->file_name=='tiktok')?'Tiktok':''); ?>

                                            <?php echo e(($row->file_name=='youtube')?'Youtube':''); ?>

                                            <?php echo e(($row->file_name=='facebook')?'Facebook':''); ?>

                                            <?php echo e(($row->file_name=='instagram')?'Instagram':''); ?>

                                            <?php echo e(($row->file_name=='linkedin')?'LinkedIn':''); ?>

                                            <?php echo e(($row->file_name=='twitter')?'Twitter':''); ?>

                                        </div>
                                    </td>
                                    <td class="col-6">
                                        <div class="">
                                            <label class=""><?php echo e($row->file_path); ?></label>
                                        </div>
                                    </td>
                                    <td class="col-3">
                                        <div class="col-md-2">
                                            <button type="button" data-id="<?php echo e($row->id); ?>"
                                                    class="rem-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i
                                                    data-feather="minus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" class="col-12">
                            <button type="button" id=""
                                    class="add-social-media btn btn-sm btn-primary margin_top_cls mt-2">
                                Add More
                            </button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div id="career-tab" class="tab-content">
                <h3>Career</h3>
                <div class="row">
                    <div class="col-6">
                        <label for="section_heading">Section Heading</label>
                        <input type="text" class="form-control" id="career_section_heading" name="career_section_heading"
                               value="<?php echo e($career_heading); ?>" placeholder="Section Heading"/>
                    </div>
                    <div class="col-6">
                        <label for="section_summary">Section Summary</label>
                        <textarea rows="4" class="form-control" id="career_section_summary" name="career_section_summary"
                               placeholder="Section Summary"><?php echo e($career_summary); ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mx-1">
                        <label for="basicInput">Email</label>
                        <input type="email" class="form-control" id="career_email" name="career_email"
                               value="<?php echo e($cemail); ?>" placeholder="Enter Email"/>
                    </div>
                </div>
                <table id="position_section">
                    <tbody>
                    <tr>
                        <td class="col">
                            <div class="form-group mt-1">
                                <label for="basicInput">List of Positions</label>
                                <input type="text" class="form-control" id="positionlist" name="positionlist[]"
                                       placeholder="Enter Position Name"/>
                            </div>
                        </td>
                        <td class="col">
                            <button type="button" id=""
                                    class="add-position btn btn-xs btn-primary margin_top_cls d-none">
                                <i data-feather="plus"></i>
                            </button>
                        </td>
                    </tr>
                    <?php if(isset($more_info) && !empty($more_info)): ?>
                        <?php $image_counter = 1; ?>
                        <?php $__currentLoopData = $more_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($row->section_name=='career' && $row->file_type=='text'): ?>
                                <tr>
                                    <td class="col-6">
                                        <div class="">
                                            <label class=""><?php echo e($row->file_name); ?></label>
                                        </div>
                                    </td>
                                    <td class="col-3">
                                        <div class="col-md-2">
                                            <button type="button" data-id="<?php echo e($row->id); ?>"
                                                    class="rem-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i
                                                    data-feather="minus"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php $image_counter = $image_counter + 1; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2" class="col-12">
                            <button type="button" id=""
                                    class="add-position btn btn-sm btn-primary margin_top_cls mt-2">
                                Add More
                            </button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <script src="<?php echo e(asset('user-asset/')); ?>/js/gallery-slideshow.js"></script>

            <script type="text/javascript">
                $(document).ready(function () {
                    $("a.scroll-down").on('click', function (event) {
                        if (this.hash !== "") {
                            event.preventDefault();
                            var hash = this.hash;
                            $('html, body').animate({scrollTop: $(hash).offset().top - 300}, 800,
                                function () {
                                    window.location.hash = hash;
                                });
                        } // End if
                    });
                });
            </script>

            <script>
                $(document).ready(function () {
                    $('.tab-modulemorep .tab-menu li').click(function () {
                        var tab_id = $(this).attr('data-tab');
                        $('.tab-modulemorep .tab-menu li').removeClass('active');
                        $('.tab-modulemorep .tab-content').removeClass('active');
                        $(this).addClass('active');
                        $("#" + tab_id).addClass('active');
                    })
                })
            </script>
            <script>
                function openTab(evt, cityName) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabscontent-gallery");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tabslink");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(cityName).style.display = "block";
                    evt.currentTarget.className += " active";
                }

                document.getElementById("tab-cus-active").click();
            </script>
            <script type="text/javascript">
                $('.add-dpdf').click(function () {
                    var _form = "dpdf_section";
                    var _tr = $("#" + _form).find('tbody > tr:first').clone().find("input").val("").end();
                    _tr.find('select').val('');
                    _tr.find('button').replaceWith(`<button type="button" class="dlt-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i data-feather="minus"></i></button>`);
                    $("#" + _form + " > tbody").append(_tr);
                    feather.replace();
                });
                $('.add-position').click(function () {
                    var _form = "position_section";
                    var _tr = $("#" + _form).find('tbody > tr:first').clone().find("input").val("").end();
                    _tr.find('button').replaceWith(`<button type="button" class="dlt-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i data-feather="delete"></i></button>`);
                    $("#" + _form + " > tbody").append(_tr);
                    feather.replace();
                });
                $('.add-videos').click(function () {

                    var _form = "video_gallary_section";
                    var _tr = $("#" + _form).find('tbody > tr:first').clone().find("input").val("").end();
                    _tr.find('button').replaceWith(`<button type="button" class="dlt-btn-dpdf btn btn-xs btn-danger margin_top_cls"><i data-feather="minus"></i></button>`);
                    $("#" + _form + " > tbody").append(_tr);
                    feather.replace();
                });
                $('.add-services').click(function () {
                    var _form = "services_table";
                    var _tr = $("#" + _form).find('tbody > tr:first').clone().find("input").val("").end();
                    _tr.find('button').replaceWith(`<button type="button" class="dlt-serv btn btn-xs btn-danger margin_top_cls"><i data-feather="minus"></i></button>`);
                    $("#" + _form + " > tbody").append(_tr);
                    feather.replace();
                });
                $('.add-social-media').click(function () {
                    var _form = "followus_table";
                    var _tr = $("#" + _form).find('tbody > tr:first').clone().find("input").val("").end();
                    _tr.find('button').replaceWith(`<button type="button" class="dlt-follous btn btn-xs btn-danger margin_top_cls"><i data-feather="minus"></i></button>`);
                    $("#" + _form + " > tbody").append(_tr);
                    feather.replace();
                });
                $('.add-app').click(function () {
                    var _form = "app_table";
                    var _tr = $("#" + _form).find('tbody > tr').last().clone().find("input").val("").end();
                    _tr.find('button').replaceWith(`<button type="button" class="dlt-app btn btn-xs btn-danger margin_top_cls"><i data-feather="minus"></i></button>`);
                    $("#" + _form + " > tbody").append(_tr);
                    feather.replace();
                });


                $(document).on('click', '.dlt-app', function () {
                    $(this).parents('tr').remove();
                });
                $(document).on('click', '.dlt-follous', function () {
                    $(this).parents('tr').remove();
                });
                $(document).on('click', '.dlt-serv', function () {
                    $(this).parents('tr').remove();
                });
                $(document).on('click', '.dlt-btn-dpdf', function () {
                    $(this).parents('tr').remove();
                });
                $(document).on('click', '.rem-btn-dpdf', function () {
                    const id = $(this).data('id');
                    $('#delitems').val(function () {

                        if (this.value != '') {
                            return this.value + ',' + id
                        }
                        return id;
                    });
                    $(this).parents('tr').remove();
                    if ($('#img_tag_' + id).length > 0) {
                        $('#img_tag_' + id).remove();
                        $(this).remove();
                    }
                });

            </script>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/common/common_component.blade.php ENDPATH**/ ?>