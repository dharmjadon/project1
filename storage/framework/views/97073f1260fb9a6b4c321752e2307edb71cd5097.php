<table class="table" id="datatable1">
   <thead>
     <tr>
        <th>Module</th>
        <th>Post</th>
        <th>Name</th>
        <th>Email</th>
        <th>Position</th>
        <th>Document</th>
        <th>Date Time</th>
     </tr>
   </thead>
   <tbody>
      <?php if(!empty($data)): ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $module_name='';
            $mtype='';
            if($type=='space')
            {
               $module_name=$row->spaceModule->title;
               $mtype=$row->spaceModule->getAccommodationTypeNameAttribute($row->spaceModule->accommodation_type);
            }
            elseif($type=='motor')
            {
               $module_name=$row->motorsModule->title;
               $mtype=$row->motorsModule->getAccommodationTypeNameAttribute($row->motorsModule->accommodation_type);
            }
			elseif($type=='event')
            {
               $module_name=$row->eventModule->title;
               $mtype=$row->eventModule->getAccommodationTypeNameAttribute($row->id);
            }
			elseif($type=='buysell')
            {
               $module_name=$row->buysellModule->title;
               $mtype=$row->buysellModule->getAccommodationTypeNameAttribute($row->id);
            }
			elseif($type=='directory')
            {
               $module_name=$row->directoryModule->title;
               $mtype=$row->directoryModule->getAccommodationTypeNameAttribute($row->id);
            }
			elseif($type=='conciger')
            {
               $module_name=$row->conciergeModule->title;
               $mtype=$row->conciergeModule->getAccommodationTypeNameAttribute($row->id);
            }
			elseif($type=='venue')
            {
               $module_name=$row->venueModule->title;
               $mtype=$row->venueModule->getAccommodationTypeNameAttribute($row->id);
            }
            elseif($type=='crypto')
            {
               $module_name=$row->cryptoModule->title;
               $mtype=$row->cryptoModule->getAccommodationTypeNameAttribute($row->id);
            }

          ?>
         <tr>
            <td><?php echo e($mtype); ?></td>
            <td><?php echo e($module_name); ?></td>
            <td><?php echo e($row->name); ?></td>
            <td><?php echo e($row->email); ?></td>
            <td><?php echo e($row->position_name); ?></td>
            <td>
               <a target="_blank" <?php if(isset($row->cv_path)): ?> href="<?php echo e(otherImage($row->cv_path)); ?>" <?php endif; ?>>View</a>
            </td>
            <td>
              <?php if($row->created_at!=''): ?>
               <?php echo e(date('Y-m-d H:i',strtotime($row->created_at))); ?>

              <?php endif; ?>
            </td>
         </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
   </tbody>
</table>
<?php /**PATH C:\xampp\htdocs\partyfinder\resources\views/admin/career/career-table/career_table.blade.php ENDPATH**/ ?>