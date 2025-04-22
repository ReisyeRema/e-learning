<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | Learning Management System</title>
    @include('includes.backend.css.login-style')
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5" style="border-radius: 15px;">
                            <div class="brand-logo text-center mb-4">
                                <img src="{{ url('storage/logo_sekolah/' . $profilSekolah->foto) }}" alt="logo">
                            </div>

                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes.backend.js.login-script')

</body>

</html>
