
<?php $__env->startSection('meta'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="registerp-container">
        <div class="container">
            <div class="row">
                <div class="col-sm-7">
                    <div class="how-register">
                        <iframe src="https://www.youtube.com/embed/GmOM9Y8os9E" title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="imgeffect"><a href="#faq-modal" data-bs-toggle="modal" role="button"> <img
                                            class="lazyload" src="/v2/images/faqs-register.jpg" alt="faqs">
                                        <h3>FAQs</h3>
                                    </a></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="imgeffect benefits-how-register"><a href="#benefit-modal"
                                                                                data-bs-toggle="modal" role="button">
                                        <img  class="lazyload" src="<?php echo e(asset('/v2/images/benefit-register.jpg')); ?>" alt="benefit">
                                        <h3>Benefits</h3>
                                    </a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade faq-modal" id="faq-modal" aria-hidden="true" aria-labelledby="story-modal"
                     tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fa-solid fa-xmark"></i></button>
                            <div class="modal-body">
                                <div class="accordion" id="faqs-accordion">
                                    <h2>Ask A Question</h2>
                                    <div class="accordion-item">
                                        <h3 class="accordion-header" id="heading-1">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse-1"
                                                    aria-expanded="true" aria-controls="collapse-1"> Lorem ipsum dolor
                                                sit ametsectetuer adipiscingsimply dummy text of the elitd diam nonummy
                                                safs esd
                                            </button>
                                        </h3>
                                        <div id="collapse-1" class="accordion-collapse collapse"
                                             aria-labelledby="heading-1" data-bs-parent="#faqs-accordion">
                                            <div class="accordion-body">
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum is simply dummy text of the printing and
                                                    typesetting industry. Lorem Ipsum is simply dummy text of the
                                                    printing and typesetting industry. Lorem Ipsum is simply dummy text
                                                    of the printing and typesetting industry.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h3 class="accordion-header" id="heading-2">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse-2"
                                                    aria-expanded="true" aria-controls="collapse-2"> Lorem ipsum dolor
                                                sit ametsectetuer adipiscingsimply dummy text of the elitd diam nonummy
                                                safs esd
                                            </button>
                                        </h3>
                                        <div id="collapse-2" class="accordion-collapse collapse"
                                             aria-labelledby="heading-2" data-bs-parent="#faqs-accordion">
                                            <div class="accordion-body">
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum is simply dummy text of the printing and
                                                    typesetting industry. Lorem Ipsum is simply dummy text of the
                                                    printing and typesetting industry. Lorem Ipsum is simply dummy text
                                                    of the printing and typesetting industry.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h3 class="accordion-header" id="heading-3">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse-3"
                                                    aria-expanded="true" aria-controls="collapse-3"> Lorem ipsum dolor
                                                sit ametsectetuer adipiscingsimply dummy text of the elitd diam nonummy
                                                safs esd
                                            </button>
                                        </h3>
                                        <div id="collapse-3" class="accordion-collapse collapse"
                                             aria-labelledby="heading-3" data-bs-parent="#faqs-accordion">
                                            <div class="accordion-body">
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                    industry. Lorem Ipsum is simply dummy text of the printing and
                                                    typesetting industry. Lorem Ipsum is simply dummy text of the
                                                    printing and typesetting industry. Lorem Ipsum is simply dummy text
                                                    of the printing and typesetting industry.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade benefit-modal" id="benefit-modal" aria-hidden="true"
                     aria-labelledby="story-modal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fa-solid fa-xmark"></i></button>
                            <div class="modal-body">
                                <h2>Ask A Benefits</h2>
                                <ul>
                                    <li>Lorem ipsum dolor sit ametse ctetuer adipis cingsimply dummy text of the elitd
                                        dummy text of
                                    </li>
                                    <li>Ingsimply ctetuer adipis ctetuer adipisci ngsimply dummy text of the elitd</li>
                                    <li>Amets ectetuer adipisc dummy ipsum dolor sit text of the elitd dummy text of the
                                        elitd
                                    </li>
                                    <li>Lorem ipsum dolor sit amet sectetuer adipis cingsi mply dummy text of the elitd
                                        dummy text of the elitd dummy text of the elitd
                                    </li>
                                    <li>Simply dummy text of the elitd ipsum dolor sit ametse ctetuer adipiscing simply
                                        dummy text of the elitd
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 offset-1">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="tab-register">
                        <ul class="tab-menu">
                            <li data-tab="login-tab" class="<?php echo e(request()->is('publisher/login') ? 'active' : ''); ?>">Login</li>
                        <li data-tab="register-tab" class="<?php echo e(!request()->is('publisher/login') ? 'active' : ''); ?>">Register</li>
                        </ul>
                        <div id="login-tab" class="tab-content <?php echo e(request()->is('publisher/login') ? 'active' : ''); ?>">
                            <form class="auth-login-form mt-2" action="<?php echo e(route('login')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo RecaptchaV3::field('fld_recaptcha'); ?>

                                <div class="field-register"><i class="party-icon icon-email"></i>
                                    <input type="text" name="email" value="<?php echo e(old('email')); ?>"
                                           placeholder="Email" required>
                                </div>
                                <div class="field-register"><i class="party-icon icon-lock"></i>
                                    <input type="password" name="password" value="<?php echo e(old('password')); ?>"
                                           id="login_password" placeholder="Password" required>
                                    <i id="login_toggler" class="party-icon icon-eye"></i></div>
                                <a href="<?php echo e(route('forgot-password')); ?>" class="forget-password">Forget Password</a>
                                <input type="submit" value="Login">
                            </form>
                        </div>
                        <div id="register-tab" class="tab-content <?php echo e(!request()->is('publisher/login') ? 'active' : ''); ?>">
                            <?php if(Session::has('message')): ?>
                                <div class="alert alert-<?php echo e(Session::get('alert-type', 'info')); ?> p-2"
                                     role="alert">
                                    <?php echo e(Session::get('message')); ?>

                                </div>
                            <?php endif; ?>
                            <form id="form_register" class="needs-validation" method="post" action="<?php echo e(route('register')); ?>" novalidate>
                                <?php echo csrf_field(); ?>
                                <div class="radio-field-register form-group">
                                    <p>
                                        <input type="radio" id="user_type_business" value="4" <?php echo e((isset($user_type) && $user_type === 'business' ? 'checked' : '')); ?> name="user_type">
                                        <label for="business">Business</label>
                                    </p>
                                    <p>
                                        <input type="radio" id="user_type_profile" value="3" <?php echo e((!isset($user_type) || (isset($user_type) && $user_type === 'profile') ? 'checked' : '')); ?> name="user_type">
                                        <label for="profile">Profile</label>
                                    </p>
                                </div>
                                <div class="social-login"><a href="<?php echo e(url('/auth/google')); ?>?utype=<?php echo e($user_type ?? 'profile'); ?>"><i><img  class="lazyload"
                                                src="<?php echo e(asset('user-asset/images/icons/google.svg')); ?>"
                                                alt="google"></i> Continue with Google </a> <a
                                        href="<?php echo e(url('/facebook-redirect')); ?>?utype=<?php echo e($user_type ?? 'profile'); ?>"><i class="party-icon icon-facebook"></i>
                                        Continue with Facebook </a></div>
                                <div class="field-break">Or</div>
                                <div class="field-register form-group"><i class="party-icon icon-user"></i>
                                    <input type="text" name="username" value="<?php echo e(old('username')); ?>"
                                           placeholder="Username" class="form-control" required>
                                </div>
                                <div class="field-register"><i class="party-icon icon-phone"></i>
                                    <input type="tel" name="phone" id="phone" placeholder="Mobile">
                                </div>
                                <span id="valid-msg" class="hide"></span>
                                <span id="error-msg" class="hide"></span>
                                <div class="field-register"><i class="party-icon icon-email"></i>
                                    <input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>"
                                           placeholder="info@company.com" required>
                                </div>
                                <div class="field-register"><i class="party-icon icon-lock"></i>
                                    <input type="password" class="form-control" name="password" value="<?php echo e(old('password')); ?>"
                                           id="password" placeholder="Password" required>
                                    <i id="toggler" class="party-icon icon-eye"></i></div>
                                <div class="field-register"><i class="party-icon icon-lock"></i>
                                    <input type="password" class="form-control" name="password_confirmation" value="<?php echo e(old('password_confirmation')); ?>"
                                           id="password_confirmation" placeholder="Confirm Password" required>
                                    <i id="toggler" class="party-icon icon-eye"></i></div>
                                <div class="policy-field-register">
                                    <input type="checkbox" id="policy" value="Policy" checked>
                                    <label for="policy">I accept the <a href="#">Privacy Policy</a> & <a
                                            href="#">T&Cs </a> </label>
                                </div>
                                <?php echo RecaptchaV3::field('fld_recaptcha'); ?>

                                <input type="submit" name="submit" id="submit_button" value="Register">
                            </form>
                            <script>
                                var password = document.getElementById('password');
                                var login_password = document.getElementById('login_password');
                                var toggler = document.getElementById('toggler');
                                var loginToggler = document.getElementById('login_toggler');
                                showHidePassword = () => {
                                    if (password.type === 'password' || login_password.type === 'password') {
                                        password.setAttribute('type', 'text');
                                        login_password.setAttribute('type', 'text');
                                        toggler.classList.add('fa-eye-slash');
                                        loginToggler.classList.add('fa-eye-slash');
                                    } else {
                                        toggler.classList.remove('fa-eye-slash');
                                        loginToggler.classList.remove('fa-eye-slash');
                                        password.setAttribute('type', 'password');
                                        login_password.setAttribute('type', 'password');
                                    }
                                };

                                toggler.addEventListener('click', showHidePassword);
                                loginToggler.addEventListener('click', showHidePassword);
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img data-src="/v2/images/register.jpg" alt="register" class="register-pic lazyload"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script defer
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script src="/intl-tel-input/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="/intl-tel-input/css/intlTelInput.min.css" />
    <script src="<?php echo e(asset('assets/js/toastr.min.js')); ?>"></script>
    <script type="text/javascript">
        $(function () {
            $('.tab-register .tab-menu li').click(function () {
                var tab_id = $(this).attr('data-tab');
                $('.tab-register .tab-menu li').removeClass('active');
                $('.tab-register .tab-content').removeClass('active');
                $(this).addClass('active');
                $("#" + tab_id).addClass('active');
            });
            $("#phone").css('padding-left', '88px');
            var inputTelReg = document.querySelector("#form_register input[type=tel]");
            if(inputTelReg != null && inputTelReg != 'undefined'){
                var itiReg = window.intlTelInput(inputTelReg, {
                    hiddenInput: "full_phone",
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
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
                <?php if(!empty(old('full_phone'))): ?>
                itiReg.setNumber("<?php echo e(old('full_phone')); ?>");
                <?php endif; ?>
                var errorMsg = document.querySelector("#error-msg"),
                    validMsg = document.querySelector("#valid-msg");

                // here, the index maps to the error code returned from getValidationError - see readme
                var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

                var reset = function() {
                    inputTelReg.classList.remove("error");
                    errorMsg.innerHTML = "";
                    errorMsg.classList.add("hide");
                    validMsg.classList.add("hide");
                };

                // on blur: validate
                inputTelReg.addEventListener('blur', function() {
                    //reset();
                    if (inputTelReg.value.trim()) {
                        if (itiReg.isValidNumber()) {
                            validMsg.classList.remove("hide");
                            $('#form_register input[name="full_phone"]').val($.trim(itiReg.getNumber()));
                        } else {
                            inputTelReg.classList.add("error");
                            var errorCode = itiReg.getValidationError();
                            errorMsg.innerHTML = errorMap[errorCode];
                            errorMsg.classList.remove("hide");
                        }
                    }
                });

                // on keyup / change flag: reset
                inputTelReg.addEventListener('change', reset);
                inputTelReg.addEventListener('keyup', reset);
                itiReg.promise.then(function() {
                    $('#form_register input[name="phone"]').val($('#phone').val().replace(/[^\d]/g, ''));
                });
            }
            $.validator.addMethod("regxNoSplChar", function(value, element, regexpr) {
                if(value !== '')
                    return regexpr.test(value);
                else
                    return true;
            }, "Special characters are not allowed");
            $.validator.methods.email = function (value, element) {
                return this.optional(element) || /^[\w+\-.]+@[a-z\d\-.]+\.[a-z]+$/.test(value);
            };
            $.validator.setDefaults({
                errorElement: "span",
                errorClass: 'is-invalid',
                validClass: 'is-valid',
                ignore: ':hidden:not(.summernote),.note-editable.card-block',
                errorPlacement: function (error, element) {
                    var data = element.data('selectric');

                    if (data) {
                        error.appendTo(element.closest('.' + data.classes.wrapper).parent());
                    } else {
                        if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                            error.insertAfter(element.parents('.form-group'));
                        } else if (element.hasClass('selectpicker')) {
                            error.insertAfter(element.parents('.bootstrap-select'));
                        } else if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.siblings('.select2'));
                        } else if (element.hasClass('rating-input')) {
                            error.insertAfter(element.parents('.rating-container'));
                        } else if (element.parent(".field-register").length || element.parent(".iti").length) {
                            error.insertAfter(element.parents(".field-register"));
                        } else {
                            error.insertAfter(element);
                        }
                    }

                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });

            const registrationValidator = $("#form_register").validate({
                ignore: [],
                rules: {
                    user_type: {
                        required: true,
                        normalizer: function (value) {
                            return $.trim(value);
                        }
                    },
                    username: {
                        required: true, normalizer: function (value) {
                            return $.trim(value);
                        }, maxlength: 15,
                        regxNoSplChar: /^[^`\!@#\$\%\^&\*\(\)\+\[\]\{\}\:;\"\?\<\>\=\',\/\\_~]+$/i
                    },
                    email: {
                        required: true, normalizer: function (value) {
                            return $.trim(value);
                        }, email: true
                    },
                    phone: {digits: true, maxlength: 12},
                    password: {required:true, normalizer: function(value) {return $.trim(value);}, minlength: 8},
                    password_confirmation: {required:true, normalizer: function(value) {return $.trim(value);}, equalTo: "#password"},
                },
                messages: {
                    //'slug': {remote: 'Slug already in use. Please use another slug.'},
                },
                submitHandler: function (form) {
                    var formData = new FormData(form);
                    $('#submit_button').attr('disabled', 'disabled');
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
                            if(form.user_type.value === 4) {
                                window.location.href = '<?php echo e(route('publisher.publisher_dashboard')); ?>';
                            } else {
                                window.location.href = '<?php echo e(route('client_registor')); ?>';
                            }
                        },
                        error: function (jqXhr, json, errorThrown) {
                            toastr.clear();
                            //toastr.error("Something went wrong. Please try later. error");
                            var errors = jqXhr.responseJSON;
                            var errorsHtml = '';
                            $.each(errors.errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            //toastr.error(errorsHtml, "Error " + jqXhr.status + ': ' + errorThrown);
                            toastr.error(errorsHtml, "Please review the following errors.");
                            $('#prepage').hide();
                            $('#submit_button').removeAttr('disabled');
                        },
                        fail: function () {
                            toastr.clear();
                            toastr.error("Something went wrong. Please try later.");
                            $('#prepage').hide();
                            $('#submit_button').removeAttr('disabled');
                        }
                    });
                    return false;
                }
            });

            $(window).scroll(function () {
                $(this).scrollTop() > 599 ? $("#backtop").fadeIn() : $("#backtop").fadeOut()
            }),
                $("#backtop").click(function () {
                    return $("#backtop").tooltip("hide"),
                        $("body,html").animate({scrollTop: 0}, 800), !1
                }),
                $("#backtop").tooltip("show")
        });
    </script>
    <a id="backtop" href="" data-placement="left"><i class="fa-solid fa-arrow-right"></i></a></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/auth/register.blade.php ENDPATH**/ ?>