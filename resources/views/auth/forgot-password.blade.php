@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Lupa kata sandi? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan untuk menyetel ulang kata sandi yang akan memungkinkan Anda memilih kata sandi baru.') }}
    </div>

    <form class="pt-3" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input name="email" :value="old('email')" required autofocus autocomplete="email" id="email" type="email"
                class="form-control form-control-lg" placeholder="Masukkan email Anda">
        </div>

        <div class="mt-3">
            <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                Tautan Reset Kata Sandi Email
            </button>
        </div>

    </form>
@endsection
