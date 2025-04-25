<h3 style="text-align: center; margin-bottom: 20px;"><?php echo e($title); ?></h3>

<table id="myTable" class="display expandable-table" style="width:100%; border-collapse: collapse;">
    <thead style="background-color: #d3d3d3;">
        <tr>
            <th style="text-align: center; border: 1px solid black;">No</th>
            <th style="border: 1px solid black;">Nama</th>
            <th style="border: 1px solid black;">Username</th>
            <th style="border: 1px solid black;">Email</th>
            <th style="border: 1px solid black;">Password</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="text-align: center; border: 1px solid black;"><?php echo e($loop->iteration); ?></td>
                <td style="border: 1px solid black;"><?php echo e($user->name); ?></td>
                <td style="border: 1px solid black;"><?php echo e($user->username); ?></td>
                <td style="border: 1px solid black;"><?php echo e($user->email); ?></td>
                <td style="border: 1px solid black;"><?php echo e($user->password_plain ?? 'N/A'); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/guru/table.blade.php ENDPATH**/ ?>