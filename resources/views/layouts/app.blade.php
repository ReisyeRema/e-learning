<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | Learning Manajement Syste</title>

    @include('includes.backend.css.style')
</head>

<body>
    <div class="container-scroller">

        @include('layouts.navigation')

        <div class="container-fluid page-body-wrapper">

            @include('components.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>

        @include('components.footer')

    </div>

    @include('includes.backend.js.script')

    @stack('scripts')

</body>

</html>
