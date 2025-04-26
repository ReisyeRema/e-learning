<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html">
            <img src="<?php echo e(url('storage/logo_sekolah/' . $profilSekolah->foto)); ?>" 
                 alt="logo" 
                 style="height: 100px;" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="index.html">
            <img src="<?php echo e(url('storage/logo_sekolah/' . $profilSekolah->foto)); ?>" 
                 alt="logo" 
                 style="height: 90px;" />
        </a>
    </div>
    
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    
                    <img src="<?php echo e(Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png')); ?>"
                        alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">

                    <?php if(Auth::user()->hasRole('Super Admin')): ?>
                        <a class="dropdown-item" href="<?php echo e(route('profile-super-admin.edit')); ?>">
                            <i class="ti-settings text-primary"></i>
                            <?php echo e(__('Superadmin Profile')); ?>

                        </a>
                    <?php elseif(Auth::user()->hasRole('Admin')): ?>
                        <a class="dropdown-item" href="<?php echo e(route('profile-admin.edit')); ?>">
                            <i class="ti-settings text-primary"></i>
                            <?php echo e(__('Admin Profile')); ?>

                        </a>
                    <?php elseif(Auth::user()->hasRole('Guru')): ?>
                        <a class="dropdown-item" href="<?php echo e(route('profile-guru.edit')); ?>">
                            <i class="ti-settings text-primary"></i>
                            <?php echo e(__('Guru Profile')); ?>

                        </a>
                    <?php endif; ?>

                    

                    <form class="dropdown-item" method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <i class="ti-power-off text-primary"></i>
                        <a :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <?php echo e(__('Log Out')); ?>

                        </a>
                    </form>

                </div>
            </li>
            <li class="nav-item nav-settings d-none d-lg-flex">
                <a class="nav-link" href="#">
                    <i class="icon-ellipsis"></i>
                </a>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>