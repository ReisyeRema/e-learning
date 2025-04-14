<aside class="sidebar">
    <h2>MENU</h2>
    <ul>
        <li class="<?php echo e(Request::routeIs('dashboard-siswa.index') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('dashboard-siswa.index')); ?>"><i class="fas fa-home"></i> Dashboard</a>
        </li>                
        <li class="<?php echo e(Request::routeIs('profile-siswa.edit') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('profile-siswa.edit')); ?>"><i class="fas fa-user"></i> Data Diri</a>
        </li>
        <li class="<?php echo e(Request::routeIs('mata-pelajaran.index') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('mata-pelajaran.index')); ?>"><i class="fas fa-book"></i> Mata Pelajaran</a>
        </li>
        <li class="<?php echo e(Request::routeIs('list-tugas.index') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('list-tugas.index')); ?>"><i class="fas fa-tasks"></i> Tugas</a>
        </li>
        <li class="<?php echo e(Request::routeIs('list-kuis.index') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('list-kuis.index')); ?>"><i class="fas fa-question-circle"></i> Kuis</a>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?>
            </form>
        </li>
    </ul>
</aside>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/frontend/sidebar.blade.php ENDPATH**/ ?>