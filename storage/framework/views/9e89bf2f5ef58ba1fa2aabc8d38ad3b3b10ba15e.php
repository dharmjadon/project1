
<?php $__env->startSection('og_image', config('app.url').'/v2/images/logo.svg' ); ?>
<?php $__env->startSection('page_title', 'The Party Finder' ); ?>
<?php $__env->startSection('meta_description', ''); ?>
<?php $__env->startSection('meta_keywords', ''); ?>
<?php $__env->startSection('canonical_url', config('app.url')); ?>
<?php $__env->startSection('meta'); ?>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="slider">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 offset-1">
                    <h2>From anything to everything easy to find venue</h2>
                    <div class="searchbar">
                        <form action="<?php echo e(route('search-result')); ?>" method="GET">
                            <div class="select-search"><i class="party-icon icon-category"></i>
                                <select>
                                    <option selected>Category</option>
                                    <option value="Buy & Sell">Buy & Sell</option>
                                    <option value="Motors">Motors</option>
                                    <option value="Property">Property</option>
                                    <option>Directory</option>
                                    <option>Venues</option>
                                    <option>Events</option>
                                    <option>Education</option>
                                </select>
                            </div>
                            <div class="field-searchbar looking-field">
                                <input type="text" name="name" placeholder="I am looking for...">
                                <img class="lazyload" data-src="/v2/images/icons/mic.svg" alt="mic" ondragstart="return false"></div>
                            <div class="field-searchbar near-field"><i class="party-icon icon-location"></i>
                                <input type="text" name="location" id="autocomplete" placeholder="Near Me">
                                <a href="javascript:void(0)" onClick="locate()"
                                   id="current_location_button"><i class="party-icon icon-location-mark"></i></a></div>
                            <div class="field-searchbar">
                                <button><i class="party-icon icon-search"></i> Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="searchbar-nav">
                        <ul>
                            <li><a href="#"><i class="party-icon icon-search"></i> Restaurants</a></li>
                            <li><a href="#"><i class="party-icon icon-search"></i> Schools</a></li>
                            <li><a href="#"><i class="party-icon icon-search"></i> Influencers</a></li>
                            <li><a href="#"><i class="party-icon icon-search"></i> Villas</a></li>
                            <li><a href="#"><i class="party-icon icon-search"></i> Cars</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="whyus">
                    <h2 class="heading"><small>Why Choose Us?</small>We are the best classified, listing platform</h2>
                    <div class="whyus-item">
                        <ul>
                            <li><span>120 <small>k</small></span> Listing added</li>
                            <li><span>2.7 <small>m</small></span> Daily seaches</li>
                            <li><span>20K <small>+</small></span> Registered users</li>
                            <li><span>120 <small>k</small></span> Listing added</li>
                            <li><span>50 <small>+</small></span> Publisher ads</li>
                        </ul>
                    </div>
                </div>

                <div class="aboutus">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2 class="heading"><small>About Us</small>We are here to help you achieve your goals</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. ipsum dolor sit
                                amet, consectetur adipiscing elit.</p>
                            <a href="#" class="btn-arrow">Submit form <i class="fa-solid fa-arrow-right"></i></a></div>
                        <div class="col-sm-6 offset-1"><img class="lazyload" data-src="/v2/images/about-us.jpg" alt="about"></div>
                    </div>
                </div>

                <div class="classified">
                    <h2 class="heading"><small>Your free classifieds site</small>The most powerful global search
                        engine</h2>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(url('motors/buy')); ?>"><img
                                        class="lazyload" data-src="/v2/images/motors.jpg" alt="motors">
                                </a>
                                <h4><a href="<?php echo e(url('motors/buy')); ?>">Motors</a></h4>
                                <p>14K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(url('property/buy')); ?>"><img
                                        class="lazyload" data-src="/v2/images/property.jpg"
                                        alt="property"> </a>
                                <h4><a href="<?php echo e(url('property/buy')); ?>">Property</a></h4>
                                <p>20K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('buy-and-sells')); ?>"><img
                                        class="lazyload" data-src="/v2/images/buy-and-sell.jpg"
                                        alt="buy-and-sell"> </a>
                                <h4><a href="<?php echo e(route('buy-and-sells')); ?>">Buy & Sell</a></h4>
                                <p>26K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('directories')); ?>"><img
                                        class="lazyload" data-src="/v2/images/directory.jpg"
                                        alt="directory"> </a>
                                <h4><a href="<?php echo e(route('directories')); ?>">Directory</a></h4>
                                <p>3M</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('jobs')); ?>"><img class="lazyload" data-src="/v2/images/jobs.jpg" alt="jobs"> </a>
                                <h4><a href="<?php echo e(route('jobs')); ?>">Jobs</a></h4>
                                <p>5K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('educations')); ?>"><img class="lazyload" data-src="/v2/images/education.jpg"
                                                                          alt="education"> </a>
                                <h4><a href="<?php echo e(route('educations')); ?>">Education</a></h4>
                                <p>1K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="#"><img class="lazyload" data-src="/v2/images/book-a-table.jpg"
                                                                          alt="book-a-table"> </a>
                                <h4><a href="#">Book a Table</a></h4>
                                <p>762</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('venues')); ?>"><img class="lazyload" data-src="/v2/images/venues.jpg" alt="venues">
                                </a>
                                <h4><a href="<?php echo e(route('venues')); ?>">Venues</a></h4>
                                <p>6K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('events')); ?>"><img class="lazyload" data-src="/v2/images/events.jpg" alt="events">
                                </a>
                                <h4><a href="<?php echo e(route('events')); ?>">Events</a></h4>
                                <p>2K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('it')); ?>"><img class="lazyload" data-src="/v2/images/it.jpg" alt="it"> </a>
                                <h4><a href="<?php echo e(route('it')); ?>">IT</a></h4>
                                <p>43K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('tickets')); ?>"><img class="lazyload" data-src="/v2/images/tickets.jpg" alt="tickets">
                                </a>
                                <h4><a href="<?php echo e(route('tickets')); ?>">Tickets</a></h4>
                                <p>90K</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="classified-item"><a href="<?php echo e(route('cryptocoin')); ?>"><img class="lazyload" data-src="/v2/images/coins.jpg" alt="coins">
                                </a>
                                <h4><a href="<?php echo e(route('cryptocoin')); ?>">Coins</a></h4>
                                <p>10K</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="register-business">
                    <h2 class="heading"><small>Free</small>Register your business / profile with Us!</h2>
                    <div class="register-business-wraper">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod
                                    tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
                                <img class="lazyload" data-src="/v2/images/register-business.jpg" alt="register-business"></div>
                            <div class="col-sm-5 offset-1">
                                <form>
                                    <label for="name_field">Company Name *</label>
                                    <input type="text" id="name_field" placeholder="Company Name" required>
                                    <label for="email_field">Email *</label>
                                    <input type="email" id="email_field" placeholder="Enter Email" required>
                                    <label for="message_field">Message</label>
                                    <textarea id="message_field" placeholder="Leave your message"></textarea>
                                    <button class="btn-arrow">Submit Form <i class="fa-solid fa-arrow-right"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="faqs">
                    <h2 class="heading"><small>Confusion?</small>Frequently Asked Questions?</h2>
                    <div class="accordion" id="faqs-accordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1"> Lorem
                                    ipsum dolor sit ametsectetuer adipiscingsimply dummy text of the elitd diam nonummy safs
                                    esd
                                </button>
                            </h3>
                            <div id="collapse-1" class="accordion-collapse collapse" aria-labelledby="heading-1"
                                 data-bs-parent="#faqs-accordion">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                                    Simply dummy text of the printing and typesettinsimply dummy text of theg industry
                                </button>
                            </h3>
                            <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="heading-2"
                                 data-bs-parent="#faqs-accordion">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                    Sdummy text of the printing and dummy text of theg industry. Lorem Ipsum is simply dummy
                                    text
                                </button>
                            </h3>
                            <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="heading-3"
                                 data-bs-parent="#faqs-accordion">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4"> Ipsum
                                    is simply dummy text of the printsimply dummy text of theing and typesetting industry
                                </button>
                            </h3>
                            <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="heading-4"
                                 data-bs-parent="#faqs-accordion">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5"> Lorem
                                    Ipsum is simply dummy text of thesimply dummy text of the printing and typesetting
                                </button>
                            </h3>
                            <div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="heading-5"
                                 data-bs-parent="#faqs-accordion">
                                <div class="accordion-body">
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                        Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="download-app"><img data-src="/v2/images/download-our-app.png" alt="download-our-app" class="pic-app lazyload">
                    <div class="row">
                        <div class="col-sm-4 offset-3">
                            <h2>Download Our App</h2>
                            <p>Lorem ipsum dolor sit amet conse elitsed diam nonummy nibh euismod</p>
                        </div>
                        <div class="col-sm-5"><a href="#"><img class="lazyload" data-src="/v2/images/icons/android.svg" alt="android"></a> <a
                                href="#"><img class="lazyload" data-src="/v2/images/icons/ios.svg" alt="ios"></a></div>
                    </div>
                </div>

                <div class="subscribe-newsletter">
                    <h2 class="heading"><small>Subscribe Now</small> Sign up to receive the latest news & Update</h2>
                    <p>All you need to know about everything that matters</p>
                    <form action="#">
                        <div class="field-subscribe"><i class="party-icon icon-email"></i>
                            <input type="text" name="name" placeholder="Email" required>
                        </div>
                        <button class="btn-arrow">Subscribe <i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

                <div class="support">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="item-support email-support">
                                <h3>Can't find what you are looking for?</h3>
                                <ul>
                                    <li><a href="#"><i class="party-icon icon-chat-2"></i> Chat with us</a></li>
                                    <li><a href="#"><i class="party-icon icon-headphone"></i> Call us</a></li>
                                    <li><a href="#"><i class="party-icon icon-email"></i> Email us</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="item-support smo-support">
                                <h3>Connect With Us</h3>
                                <ul>
                                    <li><a href="https://www.instagram.com/thepartyfinderdubai/" target="_blank"><img src="/v2/images/icons/instagram.svg"
                                                                                                                      alt="instagram" ondragstart="return false"></a></li>
                                    <li><a href="https://www.tiktok.com/@thepartyfinder_?lang=en" target="_blank"><img src="/v2/images/icons/tiktok.svg"
                                                                                                                       alt="register-tiktok" ondragstart="return false"></a></li>
                                    <li><a href="https://www.facebook.com/The-Party-Finder-106485635427613" target="_blank"><img src="/v2/images/icons/facebook.svg" alt="facebook"
                                                                                                                                 ondragstart="return false"></a></li>
                                    <li><a href="https://www.youtube.com/channel/UClTTso_EWgK6Q2w7Tw8DAlA" target="_blank"><img src="/v2/images/icons/youtube.svg" alt="youtube"
                                                                                                                                ondragstart="return false"></a></li>
                                    <li><a href="https://twitter.com/ThePartyFinder_" target="_blank" title="Follow us on Twitter"><img src="/v2/images/icons/twitter.svg" alt="twitter"
                                                                                                                                        ondragstart="return false"></a></li>
                                    <li><a href="#"><img src="/v2/images/icons/snapchat.svg" alt="snapchat"
                                                         ondragstart="return false"></a></li>
                                    <li><a href="#"><img src="/v2/images/icons/linkedin.svg" alt="linkedin"
                                                         ondragstart="return false"></a></li>
                                    <li><a href="#"><img src="/v2/images/icons/telegram.svg" alt="telegram"
                                                         ondragstart="return false"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="item-support chat-support">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h3>Qestions?</h3>
                                        <p>Chat with our experts for answers.</p>
                                        <h4><a href="#">Not now</a> <a href="#" class="btn btn-primary">Get started</a></h4>
                                    </div>
                                    <div class="col-sm-4"><img class="lazyload" data-src="/v2/images/chat-expert.jpg" alt="chat-expert"> <a
                                            href="#"><i class="party-icon icon-chat"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.landing-page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/new-landing-page.blade.php ENDPATH**/ ?>