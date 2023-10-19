
<div class="modal fade" id="add-your-module" aria-hidden="true" aria-labelledby="add-your-module" tabindex="-1">

    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                    class="fa-solid fa-xmark"></i></button>
            <div class="modal-body">
                <h3>Add Your <?php echo e($popup_title ?? 'Listing'); ?> for FREE</h3>
                <form action="">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="location_field" placeholder="Location" required>
                    <input type="text" name="category_field" placeholder="Category" required>
                    <textarea name="message_field" placeholder="Message"></textarea>
                    <?php echo RecaptchaV3::field('fld_recaptcha'); ?>

                    <input type="submit" value="Add <?php echo e($popup_title ?? 'Listing'); ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="how-it-works" aria-hidden="true" aria-labelledby="how-it-works"
     tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            <div class="modal-body">
                <iframe src="https://www.youtube.com/embed/<?php echo e(Helper::get_youtube_id_from_url($major_category->video)); ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/user/common/pop_modal.blade.php ENDPATH**/ ?>