@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <h4 class="text-center">Selamat Datang, Admin!</h4>
    <h6 class="font-weight-light text-center mb-4">Masuk ke akun Anda untuk melanjutkan.</h6>

    <form class="pt-3" method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email & Username --}}
        <div class="form-group">
            <label for="id_user" class="form-label">Email or Username</label>
            <input name="id_user" :value="old('id_user')" required autofocus autocomplete="username" id="id_user"
                type="text" class="form-control form-control-lg" placeholder="Masukkan email Anda">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <input id="password" type="password" class="form-control form-control-lg"
                    placeholder="Masukkan kata sandi Anda" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                    <button type="button" class="btn btn-inverse-info toggle-password" data-target="password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input">
                    Keep me signed in
                </label>
            </div>
            <a href="{{ route('password.request') }}" class="auth-link text-blue">Forgot password?</a>
        </div>

        <div class="mt-3">
            <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                Masuk
            </button>
        </div>


    </form>
@endsection
