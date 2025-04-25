<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <i class="mobile-nav-toggle d-xl-none bi bi-list text-white"></i>

        <a href="index.html" class="logo d-flex align-items-center me-auto">
            <h4 class="sitename text-white">{{ $profileSekolah->nama_sekolah }}</h4>
        </a>

        <nav id="navmenu" class="navmenu ml-5">
            <ul>
                <li><a href="{{ route('landing-page.index') }}#hero">Beranda</a></li>
                <li><a href="{{ route('landing-page.index') }}#about">Tentang Kami</a></li>
                <li>
                    <a href="{{ route('landing-page.index') }}#class"
                        class="{{ request()->routeIs('kelas.show') ? 'active' : '' }}">
                        Kelas
                    </a>
                </li>
                <li><a href="{{ route('landing-page.index') }}#contact">Kontak</a></li>
            </ul>
        </nav>

        @auth
            @if (Auth::user()->hasRole('Siswa'))
                <div id="profile-toggle" class="profile-link" style="cursor: pointer;">
                    <img src="{{ Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/avatar2.jpeg') }}"
                        alt="Foto Profil" class="rounded-circle" style="width: 50px; height: 50px; margin-left:30px;">
                </div>
            @endif
        @else
            <div class="dropdown">
                <button class="btn-getstarted dropdown-toggle custom-dropdown-btn" type="button" id="loginDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" style="background: white; color:black;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="loginDropdown">
                    <li>
                        <a class="dropdown-item custom-dropdown-item" href="{{ route('login-siswa') }}">
                            <i class="fas fa-user-graduate"></i> Login sebagai Siswa
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item custom-dropdown-item" href="{{ route('login') }}">
                            <i class="fas fa-chalkboard-teacher"></i> Login sebagai Guru
                        </a>
                    </li>
                </ul>
            </div>
        @endauth

    </div>
</header>

<style>
    .custom-dropdown-btn {
        color: black;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 30px;
        transition: 0.3s;
    }

    .custom-dropdown-btn:hover {
        background-color: #48a6a7;
    }

    /* Dropdown */
    .custom-dropdown-menu {
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        padding: 10px;
    }

    /* Item Dropdown */
    .custom-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        padding: 10px;
        transition: 0.3s;
        border-radius: 8px;
    }

    .custom-dropdown-item i {
        font-size: 16px;
        color: #48a6a7;
    }

    .custom-dropdown-item:hover {
        background-color: #f8f9fa;
        color: #48a6a7;
    }


    @media (max-width: 768px) {
        .dropdown {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }

    @media (max-width: 1280px) {
        .dropdown {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }

    @media (max-width: 1024px) {
        .dropdown {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }
    @media (max-width: 480px) {
        .dropdown {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }


    @media (max-width: 768px) {
        .profile-link {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }

    @media (max-width: 1280px) {
        .profile-link {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }

    @media (max-width: 1024px) {
        .profile-link {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }
    @media (max-width: 480px) {
        .profile-link {
            position: absolute;
            right: 15px;
            z-index: 1050;
        }
    }
</style>

@include('components.frontend.toggle-sidebar')
