<div class="modal fade modal-danger text-left" id="remove" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel120" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel120">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete?
            </div>
            <div class="modal-footer">
                <form method="post" action="">
                    <?php echo csrf_field(); ?>
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="submit" class="btn btn-danger">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/partials/modals/remove.blade.php ENDPATH**/ ?>