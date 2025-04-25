<style>
    .profile-sidebar {
        position: fixed;
        top: 0;
        right: -450px;
        width: 450px;
        height: 100%;
        background: #2c8182;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.15);
        transition: right 0.4s ease;
        z-index: 9999;
        overflow-y: auto;
        padding: 40px 30px;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
    }

    .profile-sidebar h2 {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 15px;
    }

    .profile-sidebar.active {
        right: 0;
    }

    .profile-sidebar .close-btn {
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 26px;
        color: #999;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-header img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-header h3 {
        margin-top: 12px;
        font-size: 20px;
        font-weight: 700;
        color: white;
    }

    .profile-header p {
        font-size: 16px;
        color: white;
        margin-top: 5px;
    }

    .profile-menu {
        list-style: none;
        padding: 0;
        margin-bottom: 0;
    }

    .profile-menu li {
        margin-bottom: 10px;
    }

    .profile-menu a {
        display: flex;
        align-items: center;
        padding: 12px 18px;
        font-size: 16px;
        color: #333;
        background: #f8f9fa;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .profile-menu a:hover,
    .profile-menu a.active {
        background-color: #48a6a7;
        color: #fff;
    }

    .profile-menu a i {
        margin-right: 10px;
        font-size: 18px;
        min-width: 24px;
    }

    .profile-divider {
        border: none;
        height: 1px;
        background: white;
        margin: 20px 0;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        z-index: 9998;
        display: none;
    }
</style>

@auth
    <aside id="profile-sidebar" class="profile-sidebar">
        <span class="close-btn">&times;</span>
        <div class="profile-header">
            <img src="{{ Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/avatar2.jpeg') }}"
                alt="Foto Profil">
            <h3>{{ Auth::user()->name }}</h3>
        </div>
        <hr class="profile-divider">
        <h2>Menu</h2>
        <ul class="profile-menu">
            <li>
                <a href="{{ route('dashboard-siswa.index') }}"
                    class="{{ Request::routeIs('dashboard-siswa.index') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('profile-siswa.edit') }}"
                    class="{{ Request::routeIs('profile-siswa.edit') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Data Diri
                </a>
            </li>
            <li>
                <a href="{{ route('mata-pelajaran.index') }}"
                    class="{{ Request::routeIs('mata-pelajaran.index') ? 'active' : '' }}">
                    <i class="fas fa-book"></i> Mata Pelajaran
                </a>
            </li>
            <li>
                <a href="{{ route('list-tugas.index') }}" class="{{ Request::routeIs('list-tugas.index') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i> Tugas
                </a>
            </li>
            <li>
                <a href="{{ route('list-kuis.index') }}" class="{{ Request::routeIs('list-kuis.index') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i> Kuis
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
@endauth
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
