<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @include('includes.frontend.style')
    @include('includes.frontend.css-main')

</head>

<body>
    @include('components.frontend.header')

    <div class="overlay" id="overlay"></div>

    <main class="main">
        @include('components.frontend.sidebar')

        <section class="content">
            <!-- Profile Section -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png') }}"
                        alt="Profile Picture" class="rounded-circle me-3" width="60" height="60">
                    <div class="text">
                        <p class="mb-1 text-muted">Selamat Datang,</p>
                        <h5 class="mb-0 fw-bold">{{ Auth::user()->name }}</h5>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('profile-siswa.edit') }}"
                       class="btn btn-outline-success d-flex align-items-center gap-1">
                        <i class="bi bi-person-lines-fill"></i> Data Diri
                    </a>
                
                    <a href="{{ route('setifikat-siswa.index') }}"
                       class="btn btn-outline-primary d-flex align-items-center gap-1">
                        <i class="bi bi-person-lines-fill"></i> Sertifikat
                    </a>
                </div>
                
            </div>


            <!-- Garis Abu-abu -->
            <hr class="profile-divider-2 mb-5">

            @yield('content')

        </section>
    </main>

    @include('components.frontend.footer')
    @include('includes.frontend.script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    @stack('scripts')

</body>

</html>
