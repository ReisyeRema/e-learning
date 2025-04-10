<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
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
            <div class="profile-header">
                <img src="{{ Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png') }}" alt="Profile Picture">
                <div class="text">
                    <p>Selamat Datang,</p>
                    <h2>{{ Auth::user()->name }}</h2>
                </div>
            </div>

            <!-- Garis Abu-abu -->
            <hr class="profile-divider mb-5">

            @yield('content')
            
        </section>
    </main>
    
    @include('components.frontend.footer')
    @include('includes.frontend.script')

</body>

</html>
