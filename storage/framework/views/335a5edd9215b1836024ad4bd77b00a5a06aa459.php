<?php $sl_no = 1; ?>
<?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($sl_no); ?></td>
    <td><?php echo e($data->name); ?></td>
    <td><?php echo e($data->mainCategory->name); ?></td>
    <td><?php echo e($data->mainCategory->MajorCategory->name); ?></td>
    <td><img src="<?php echo e(otherImage($data->icon)); ?>" width="30"></td>
    <td>
        <a class="btn btn-sm btn-success" href="<?php echo e(route('admin.sub-category.edit', $data->id)); ?>">
            <i data-feather='pen-tool'></i>
        </a>
        <a data-id="<?php echo e($data->id); ?>" data-target="#danger" data-toggle="modal" type="button" href="javascript:void(0)" class="btn btn-sm btn-danger modal-btn">
            <i data-feather='trash-2'></i>
        </a>
    </td>
</tr>
<?php $sl_no++; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/sub-category/sub-category-ajax-tab.blade.php ENDPATH**/ ?>