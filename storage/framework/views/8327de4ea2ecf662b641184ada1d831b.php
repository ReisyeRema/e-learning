<style>
    .profile-sidebar {
        position: fixed;
        top: 0;
        right: -450px;
        width: 450px;
        height: 100%;
        background: white;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        transition: right 0.3s ease-in-out;
        z-index: 9999;
        overflow-y: auto;
        padding: 45px;
    }

    .profile-sidebar h2 {
        font-size: 22px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        font-weight: bold;
    }

    .profile-sidebar.active {
        right: 0;
    }

    .profile-sidebar .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 10px;
    }

    .profile-header img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }

    .profile-header h3 {
        margin-top: 5px;
        font-size: 18px;
        font-weight: bold;
    }

    .profile-header p {
        font-size: 16px;
        color: #555;
        margin-top: 5px;
    }

    .profile-menu {
        list-style: none;
        padding: 0;
        margin-bottom: 0;
    }

    .profile-menu li {
        padding: 15px 20px;
    }

    .profile-menu a {
        text-decoration: none;
        color: black;
        font-size: 16px;
        display: flex;
        align-items: center;
        transition: color 0.2s;
    }

    .profile-menu a:hover,
    .profile-menu a.active {
        color: #10bc69;
    }

    .profile-menu a i {
        margin-right: 10px;
        font-size: 18px;
    }

    .profile-divider {
        border: none;
        height: 1px;
        background: #adacac;
        margin: 15px 0;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        z-index: 9998;
        display: none;
    }
</style>

<?php if(auth()->guard()->check()): ?>
    <aside id="profile-sidebar" class="profile-sidebar">
        <span class="close-btn">&times;</span>
        <div class="profile-header">
            <img src="<?php echo e(Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png')); ?>"
                alt="Foto Profil">
            <h3><?php echo e(Auth::user()->name); ?></h3>
        </div>
        <hr class="profile-divider">
        <h2>Menu</h2>
        <ul class="profile-menu">
            <li>
                <a href="<?php echo e(route('dashboard-siswa.index')); ?>"
                    class="<?php echo e(Request::routeIs('dashboard-siswa.index') ? 'active' : ''); ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('profile-siswa.edit')); ?>"
                    class="<?php echo e(Request::routeIs('profile-siswa.edit') ? 'active' : ''); ?>">
                    <i class="fas fa-user"></i> Data Diri
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('mata-pelajaran.index')); ?>"
                    class="<?php echo e(Request::routeIs('mata-pelajaran.index') ? 'active' : ''); ?>">
                    <i class="fas fa-book"></i> Mata Pelajaran
                </a>
            </li>
            <li>
                <a href="" class="">
                    <i class="fas fa-tasks"></i> Tugas
                </a>
            </li>
            <li>
                <a href="" class="">
                    <i class="fas fa-question-circle"></i> Kuis
                </a>
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
<?php endif; ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const profileToggle = document.querySelector("#profile-toggle");
        const profileSidebar = document.querySelector("#profile-sidebar");
        const closeBtn = document.querySelector(".close-btn");
        const navLinks = document.querySelectorAll(".nav-link");
        const overlay = document.querySelector("#overlay");

        if (profileToggle) {
            profileToggle.addEventListener("click", function(event) {
                event.preventDefault();
                profileSidebar.classList.add("active");
                overlay.style.display = "block";
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener("click", function() {
                profileSidebar.classList.remove("active");
                overlay.style.display = "none";
            });
        }

        document.addEventListener("click", function(event) {
            if (!profileSidebar.contains(event.target) && !profileToggle.contains(event.target)) {
                profileSidebar.classList.remove("active");
                overlay.style.display = "none";
            }
        });

        navLinks.forEach(link => {
            link.addEventListener("click", function() {
                navLinks.forEach(nav => nav.classList.remove("active"));
                this.classList.add("active");
            });
        });
    });
</script>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/frontend/toggle-sidebar.blade.php ENDPATH**/ ?>