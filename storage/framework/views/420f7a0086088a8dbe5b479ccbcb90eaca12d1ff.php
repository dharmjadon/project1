<script src="<?php echo e(asset('app-assets/vendors/js/forms/select/select2.full.min.js')); ?>"></script>

<script src="<?php echo e(asset('app-assets/js/scripts/forms/form-select2.js')); ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js" integrity="sha512-6rE6Bx6fCBpRXG/FWpQmvguMWDLWMQjPycXMr35Zx/HRD9nwySZswkkLksgyQcvrpYMx0FELLJVBvWFtubZhDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google.maps_key')); ?>&libraries=places"></script>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>

<link rel="stylesheet" href="/v2/admin/plugins/jquery-ui/jquery-ui.min.css">

<script src="/v2/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/v2/js/speakingurl.js"></script>
<script src="/v2/js/slugify.min.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.ui.widget.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.fileupload-process.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.fileupload-image.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
<script src="/v2/admin/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
<script src="/intl-tel-input/js/intlTelInput-jquery.min.js"></script>
<script>
    $(function () {
        var start = moment();
        var end = moment().add(30, 'days');
        function cb(start, end) {
            $('#datetimefilter').val(start.format('DD/MM/YYYY hh:mm A') + ' - ' + end.format('DD/MM/YYYY hh:mm A'));
            $('#start_date_time').val(start.format('YYYY-MM-DD HH:mm:ss'));
            $('#end_date_time').val(end.format('YYYY-MM-DD HH:mm:ss'));
        }

        $('#datetimefilter').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'DD/MM/YYYY hh:mm A'
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm A') + ' - ' + picker.endDate.format(
                'DD/MM/YYYY hh:mm A'));
            $('#start_date_time').val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            $('#end_date_time').val(picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            $('#start_date_time').val('');
            $('#end_date_time').val('');
        });
        <?php if(!isset($datetimefilter)): ?>
            $('#datetimefilter').val('');
        <?php endif; ?>

        $("input[type=tel]").css('padding-left', '88px');
        $("#whatsapp").intlTelInput({
            hiddenInput: "full_phone_whatsapp",
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            allowDropdown: true,
            formatOnDisplay: true,
            autoPlaceholder: "polite",
            onlyCountries: ['ae','sa', 'in', 'qa', 'cn','uk'],
            placeholderNumberType: "MOBILE",
            preferredCountries: ['ae'],
            separateDialCode: true,
            utilsScript: "/intl-tel-input/js/utils.js?1562189064761"
        });
        $("#contact").intlTelInput({
            hiddenInput: "full_phone_contact",
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            allowDropdown: true,
            formatOnDisplay: true,
            autoPlaceholder: "polite",
            onlyCountries: ['ae','sa', 'in', 'qa', 'cn','uk'],
            placeholderNumberType: "MOBILE",
            preferredCountries: ['ae'],
            separateDialCode: true,
            utilsScript: "/intl-tel-input/js/utils.js?1562189064761"
        });
        $('#feature_image, #floor_plan, #main_image, #images, #stories, #menu, #logo, #qr_code').each(function (event) {
            var field_id = $(this).attr('id');
            var max_files = 1;
            if(field_id === 'images' || field_id === 'stories' ) {
                max_files = 4;
            }
            $(this).fileupload({
                maxFileSize: 1000000,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                dataType: 'json',
                limitMultiFileUploads: max_files,
                add: function (e, data) {
                    $('#' + $(this).attr('id') + '_loading').html('<div class="col-sm-4 my-2"><div class="alert alert-info">Uploading...</div></div>');
                    data.submit();
                },
                done: function (e, data) {
                    var id = $(this).attr('id');
                    var imgDiv;
                    $.each(data.result.files, function (index, file) {
                        if(file.errorMessage) {
                            /*imgDiv = $('<div class="col-sm-4 my-2"/>')
                                .html('<div class="alert alert-danger">'+file.errorMessage+'.</div>');*/
                            alert(file.errorMessage);
                            $('#' + id + '_loading').html('');
                        }
                        if (file.fileID) {
                            var existingVal = $('#' + id + '_ids').val();
                            imgDiv = $('<div id="img_' + file.fileID + '" class="col-sm-3 my-2 text-center"/>')
                                .html('<img src="' + file.path + '" alt="' + file.name + '" width="150" class="img-fluid">' +
                                    '<input type="text" name="alt_text_en[' + file.fileID + ']" id="alt_text_en_' + file.fileID + '" class="form-control mt-2" placeholder="Alt Text">' +
                                    '<!--input type="text"  name="alt_text_ar[' + file.fileID + ']" id="alt_text_ar_' + file.fileID + '" class="form-control mt-2" placeholder="Alt Text Arabic">' +
                                    '<input type="text"  name="alt_text_zh[' + file.fileID + ']" id="alt_text_zh_' + file.fileID + '" class="form-control mt-2" placeholder="Alt Text Chinese"-->' +
                                    '<a role="button" class="text-danger delete-image" data-image-id="' + file.fileID + '" data-image-type="' + id + '"><i class="fa fa-trash"></i></a>' +
                                    '');
                            if(id === 'feature_image' || id === 'logo' || id === 'menu' || id === 'floor_plan' || id === 'image'){
                                if(id === 'feature_image') {
                                    $('#featured_image').val(file.fileID);
                                }
                                $('#' + id + '_ids').val(file.fileID);
                            } else {
                                if ( existingVal !== '') {
                                    $('#' + id + '_ids').val( existingVal + ',' + file.fileID);
                                } else {
                                    $('#' + id + '_ids').val(file.fileID);
                                }
                            }
                        }
                        imgDiv.appendTo($('#' + id + '_list'));
                    });
                    $('#' + id + '_loading').html('');
                }
            });
        });

        $('body').on('click', '.delete-image', function (e) {
            if (confirm('Are you sure to delete this image? This cannot be undone.')) {
                $('#prepage').show();
                var imgId = $(this).data('image-id');
                var imgType = $(this).data('image-type');
                var that = $('#img_' + imgId);
                $.ajax({
                    url: '/<?php echo e($section); ?>/<?php echo e($module_name); ?>/delete-photo/' + imgId,
                    type: 'delete',
                    dataType: 'json',
                    success: function (res) {
                        $('#prepage').hide();
                        if (!res.error) {
                            toastr.success(res.msg);
                            if(imgType === 'feature_image' || imgType === 'logo' || imgType === 'menu' ||  imgType === 'main_image') {
                                if(imgType === 'feature_image') {
                                    $('#feature_image').val('');
                                }
                                $('#'+imgType+'_ids').val('');
                            }
                            that.remove();
                        } else {
                            toastr.error(res.msg);
                        }
                    },
                    error: function (res) {
                        $('#prepage').hide();
                        toastr.error('There was a problem while deleting image. Please try later.');
                    },
                    fail: function (res) {
                        $('#prepage').hide();
                        toastr.error('There was a problem while deleting image. Please try later.');
                    },
                });
            }
        });
        $.validator.setDefaults({
            errorElement: "span",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            ignore: ':hidden:not(.editor),.note-editable.card-block',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                } else if (element.hasClass("editor") || element.hasClass("summernote")) {
                    error.insertAfter(element.siblings(".note-editor"));
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.siblings('.select2'));
                } else if (element.hasClass('intl-tel') || element.prop("type") === "tel") {
                    error.insertAfter($(element).closest('.iti--allow-dropdown'));
                } else if (element.hasClass('selectpicker')) {
                    error.insertAfter($(element).closest('.selectric-wrapper'));
                } else {
                    error.insertAfter(element);
                }
                //element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
        $.validator.addMethod("regxNoSplChar", function (value, element, regexpr) {
            if (value !== '')
                return regexpr.test(value);
            else
                return true;
        }, "Special characters not allowed.");
        $.validator.addMethod('mediaFileSize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, "Image size should not exceed 1 MB");
        $.validator.addMethod("intlTelNumber", function(value, element) {
            return this.optional(element) || $(element).intlTelInput("isValidNumber");
        }, "Please enter a valid International Phone Number");
        $.validator.addMethod("youtubeUrl", function(value, element) {
            var p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
            return this.optional(element) || !!(value.match(p));
        }, "Enter valid YouTube URL");

            $('button#add-amenity').on('click', function () {
                const addDiv = $('#module-amenities');
                const i = $('#module-amenities div.row').length;
                const id_now = (i + 1);
                const html = '<div class="row" id="row_amenity_icon_'+id_now+'"><div class="col-md-10"><div class="form-group"><label for="status">Select Amenity</label><select class="form-control select2" name="amenities[]" id="amentie_icon_' + id_now + '" required><option value="" disabled selected>please select option</option><?php $__currentLoopData = $amenties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity_id => $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($amenity_id); ?>"><?php echo e($amenity); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div></div><div class="col-md-2"><button type="button" id="amentieremove_' + id_now + '" data-id="' + id_now + '" class="btn btn-sm btn-danger margin_top_cls amentie_remove_btn_cls"><i data-feather="minus"></i></button></div></div>';
                $(html).appendTo(addDiv);
                $(addDiv).find('select').select2();
                $("#amentie_icon_" + id_now).valid();
                feather.replace();
            });
            $(document).on('click', '.amentie_remove_btn_cls', function () {
                const row_id = $(this).data('id');
                $('#row_amenity_icon_'+row_id).remove();
                //this will be used in edit mode
                const i = $('#module-amenities div.row').length;
                if(i === 0) {
                    const addDiv = $('#module-amenities');
                    const id_now = (i + 1);
                    const html = '<div class="row" id="row_amenity_icon_'+id_now+'"><div class="col-md-10"><div class="form-group"><label for="status">Select Amenity</label><select class="form-control select2" name="amenities[]" id="amentie_icon_' + id_now + '" required><option value="" disabled selected>please select option</option><?php $__currentLoopData = $amenties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity_id => $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($amenity_id); ?>"><?php echo e($amenity); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div></div></div>';
                    $(html).appendTo(addDiv);
                    $(addDiv).find('select').select2();
                }
            });

        $('button#add-landmark').on('click', function () {
            const addDiv = $('#module-landmarks');
            const i = $('#module-landmarks div.row').length;
            const id_now = (i + 1);
            const html = ' <div class="row" id="row_landmark_' + id_now + '"><div class="col-md-5"><div class="form-group"><label for="status">Select Icon</label><select class="form-control select2" name="landmark[' + id_now + '][name]" id="landmark_' + id_now + '_name" required><option value="" disabled selected>please select option</option><?php $__currentLoopData = $landmarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $landmark_id => $landmark_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($landmark_id); ?>"><?php echo e($landmark_name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div></div><div class="col-md-5"><div class="form-group"><label for="status">Enter Short Description</label><input type="text" class="form-control" name="landmark[' + id_now + '][description]" id="landmark_' + id_now + '_description" required></div></div><div class="col-md-2"><button type="button" id="landmark-remove-btn_' + id_now + '" data-id="' + id_now + '" class="btn btn-sm btn-danger margin_top_cls remove_btn_landmark_cls"><i data-feather="minus"></i></button></div></div>';
            $(html).appendTo(addDiv);
            $(addDiv).find('select').select2();
            $('#landmark_' + id_now + '_name').valid();
            $('#landmark_' + id_now + '_description').valid();
            feather.replace();
        });
        $(document).on('click', '.remove_btn_landmark_cls', function () {
            const row_id = $(this).data('id');
            $('#row_landmark_' + row_id).remove();

            //this will be used in edit mode
            const i = $('#module-landmarks div.row').length;
            if (i === 0) {
                const addDiv = $('#module-landmarks');
                const id_now = (i + 1);
                const html = ' <div class="row" id="row_landmark_' + id_now + '"><div class="col-md-5"><div class="form-group"><label for="status">Select Icon</label><select class="form-control select2" name="landmark[' + id_now + '][name]" id="landmark_' + id_now + '_name" required><option value="" disabled selected>please select option</option><?php $__currentLoopData = $landmarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $landmark_id => $landmark_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($landmark_id); ?>"><?php echo e($landmark_name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div></div><div class="col-md-5"><div class="form-group"><label for="status">Enter Short Description</label><input type="text" class="form-control" name="landmark[' + id_now + '][description]" id="landmark_' + id_now + '_description" required></div></div></div>';
                $(html).appendTo(addDiv);
                $(addDiv).find('select').select2();
            }
        });

    });
</script>

<script>
    let searchBox;

    function init() {
        var current_lat = parseFloat($('#cityLat').val());
        var current_lang = parseFloat($('#cityLng').val());
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: {
                lat: current_lat ? current_lat : 25.2048493,
                lng: current_lang ? current_lang : 55.2707828
            },
            zoom: 13
        });
        if(current_lat && current_lang) {
            var geocoder;
            geocoder = new google.maps.Geocoder();
            var location_name = new google.maps.LatLng(current_lat, current_lang);
            geocoder.geocode(
                {'latLng': location_name},
                function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var add= results[0].formatted_address ;
                            var  value=add.split("-");
                            document.getElementById('pac-input').value = value;
                        }
                    }
                }
            );
        }

        searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
        google.maps.event.addListener(searchBox, 'places_changed', function () {

            searchBox.set('map', null);
            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            var i, place;
            for (i = 0; place = places[i]; i++) {
                document.getElementById('cityLat').value = place.geometry.location.lat();
                document.getElementById('cityLng').value = place.geometry.location.lng();
                fillInAddress(place.address_components);
                if (typeof place.user_ratings_total !== 'undefined') {
                    document.getElementById('map_review').value = place.user_ratings_total;
                }
                if (typeof place.rating !== 'undefined') {
                    document.getElementById('map_rating').value = place.rating;
                }
                (function (place) {
                    var marker = new google.maps.Marker({

                        position: place.geometry.location,
                        draggable: true
                    });
                    marker.bindTo('map', searchBox, 'map');
                    google.maps.event.addListener(marker, 'map_changed', function () {

                        if (!this.getMap()) {
                            this.unbindAll();
                        }
                    });
                    google.maps.event.addListener(marker, 'dragend', function (evt) {
                        document.getElementById('cityLat').value = evt.latLng.lat().toFixed(7);
                        document.getElementById('cityLng').value = evt.latLng.lng().toFixed(7);

                        var geocoder;
                        geocoder = new google.maps.Geocoder();
                        var latlng = new google.maps.LatLng(evt.latLng.lat().toFixed(7), evt.latLng.lng().toFixed(7));

                        geocoder.geocode({
                                'latLng': latlng
                            },
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        fillInAddress(results[0].address_components);
                                        var add = results[0].formatted_address;
                                        var value = add.split("-");
                                        document.getElementById('pac-input').value = value;
                                    }
                                }
                            }
                        );
                    });
                    map.setCenter(marker.position);
                    marker.setMap(map);
                    bounds.extend(place.geometry.location);

                }(place));
            }
            map.fitBounds(bounds);
            searchBox.set('map', map);
            map.setZoom(Math.min(map.getZoom(), 18));
        });
    }

    google.maps.event.addDomListener(window, 'load', init);

    function fillInAddress(address_components) {
        // Get the place details from the autocomplete object.
        for (const component of address_components) {
            // @ts-ignore remove once typings fixed
            const componentType = component.types[0];

            switch (componentType) {
                case "sublocality":
                case "sublocality_level_1":
                case "neighborhood":
                    document.querySelector("#area").value = component.long_name;
                    break;
                case "administrative_area_level_1":
                case "administrative_area_level_2":
                    document.querySelector("#city").value = component.short_name;
                    break;

                case "country":
                    document.querySelector("#country").value = component.long_name;
                    break;
            }
        }
    }
</script>
<script>
    $(document).on('change', '#main_category', function () {
        var selcted_v = $(this).val();
        var token = $("input[name='_token']").val();

        $("body").addClass("loading");

        if (!selcted_v) {

        } else {

            $.ajax({
                url: "<?php echo e(route('admin.ajax_render_subcategory')); ?>",
                method: 'POST',
                dataType: 'json',
                data: {
                    select_v: selcted_v,
                    _token: token
                },
                success: function (response) {

                    $('#sub_category_id').empty().trigger("change");

                    var len = 0;
                    if (response != null) {
                        len = response.length;
                    }
                    if (len > 0) {

                        var html_option = '<option value="" selected  disabled>please select option</option> ';
                        $('#sub_category_id').append(html_option);

                        for (var i = 0; i < len; i++) {
                            var newOption = new Option(response[i].name, response[i].id, true, true);
                            $('#sub_category_id').append(newOption);
                        }
                        $('#sub_category_id').val(null).trigger('change');
                    } else {

                    }

                    $("body").removeClass("loading");
                }
            });
        }
    });
</script>

<script>
    function youtube_parser(url) {
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        var match = url.match(regExp);
        return (match && match[7].length == 11) ? match[7] : false;
    }
</script>
<script type="text/javascript">
    $(function () {
        $("input[name='youtube_img']").click(function () {
            var id = $(this).val();
            if (id == 1) {
                $('.youtube').show();
                $('.img_you').hide();
            } else if (id == 2) {
                $('.youtube').hide();
                $('.img_you').show();
            }
        });
        $('#images').change(function () {
            //get the input and the file list
            var input = document.getElementById('images');
            if (input.files.length > 4) {
                $('.validation').css('display', 'block');
            } else {
                $('.validation').css('display', 'none');
            }
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
                <?php if (Session::has('primary_id')){ ?>
            $("#more-info-modal-button").click();
            <?php } ?>
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
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/common-scripts.blade.php ENDPATH**/ ?>