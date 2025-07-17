@extends('layouts.guest')

@section('title', 'Pilih Role')

@section('content')
    <h4 class="text-center">Pilih Role Anda</h4>
    <form method="POST" action="{{ route('choose-role.submit') }}">
        @csrf
        <div class="form-group">
            <label for="role">Silakan pilih role untuk sesi ini:</label>
            <select name="role" id="role" class="form-control" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary mt-3 btn-block">Lanjutkan</button>
    </form>
@endsection
